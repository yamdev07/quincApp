<?php

namespace App\Services;

use App\Mail\PaymentConfirmationMail;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriptionService
{
    private const PLAN_MONTHS = [
        'starter'   => 1,
        'monthly'   => 1,
        'quarterly' => 3,
        'semester'  => 6,
        'yearly'    => 12,
        'lifetime'  => null, // handled separately
    ];

    private const PLAN_NAMES = [
        'starter'   => 'Starter',
        'monthly'   => 'Business Mensuel',
        'quarterly' => 'Pro Trimestriel',
        'semester'  => 'Pro Semestriel',
        'yearly'    => 'Annuel',
        'lifetime'  => 'Licence à vie',
    ];

    public function activate(User $user, string $planType, float $amount, bool $isRenewal = false): array
    {
        $tenant = $user->tenant;

        Log::channel('payment')->info('Activation abonnement', [
            'user_id'   => $user->id,
            'tenant_id' => $tenant?->id,
            'plan'      => $planType,
            'amount'    => $amount,
            'renewal'   => $isRenewal,
        ]);

        $endDate   = $this->computeEndDate($planType);
        $planName  = self::PLAN_NAMES[$planType] ?? 'Mensuel';

        DB::transaction(function () use ($user, $tenant, $planType, $amount, $endDate, $isRenewal) {
            if ($tenant) {
                // Expirer l'abonnement précédent
                $old = Subscription::where('tenant_id', $tenant->id)
                    ->where('status', 'active')
                    ->first();

                if ($old) {
                    $old->update(['status' => 'expired']);
                    Log::channel('payment')->info('Ancien abonnement expiré', ['subscription_id' => $old->id]);
                }

                $transactionId = 'fedapay_' . time();

                $sub = Subscription::create([
                    'tenant_id'      => $tenant->id,
                    'plan_type'      => $planType,
                    'amount'         => $amount,
                    'start_date'     => now()->toDateString(),
                    'end_date'       => $endDate->toDateString(),
                    'status'         => 'active',
                    'payment_method' => 'fedapay',
                    'transaction_id' => $transactionId,
                    'metadata'       => [
                        'paid_at'    => now()->toDateTimeString(),
                        'user_id'    => $user->id,
                        'user_email' => $user->email,
                        'is_renewal' => $isRenewal,
                    ],
                ]);

                Log::channel('payment')->info('Abonnement créé', ['subscription_id' => $sub->id]);

                $tenant->update([
                    'subscription_status'   => 'active',
                    'payment_status'        => 'paid',
                    'subscription_ends_at'  => $endDate,
                    'subscription_end_date' => $endDate->toDateString(),
                    'subscription_price'    => $amount,
                    'billing_cycle'         => $planType,
                    'is_active'             => true,
                    'last_payment_at'       => now(),
                    'last_payment_amount'   => $amount,
                ]);
            }

            $user->update([
                'subscription_status'  => 'active',
                'subscription_ends_at' => $endDate,
                'last_payment_at'      => now(),
            ]);
        });

        $this->sendConfirmationEmail($user, $planType, $amount, $isRenewal);

        $verb    = $isRenewal ? 'renouvelé' : 'activé';
        $message = "Abonnement {$verb} avec succès ! Votre plan {$planName} est actif jusqu'au {$endDate->format('d/m/Y')}.";

        Log::channel('payment')->info('Activation réussie', ['user_id' => $user->id, 'end_date' => $endDate]);

        return ['message' => $message, 'end_date' => $endDate];
    }

    private function computeEndDate(string $planType): \Carbon\Carbon
    {
        if ($planType === 'lifetime') {
            return now()->addYears(99);
        }

        $months = self::PLAN_MONTHS[$planType] ?? 1;
        return now()->addMonths($months);
    }

    private function sendConfirmationEmail(User $user, string $planType, float $amount, bool $isRenewal): void
    {
        try {
            $transaction = (object) [
                'id'         => 'fedapay_' . time(),
                'amount'     => $amount,
                'status'     => 'approved',
                'is_renewal' => $isRenewal,
            ];

            Mail::to($user->email)->send(new PaymentConfirmationMail($user, $transaction));
            Log::channel('payment')->info('Email confirmation envoyé', ['to' => $user->email]);

        } catch (\Exception $e) {
            // Ne pas bloquer le flux principal si l'email échoue
            Log::channel('payment')->error('Échec envoi email', [
                'to'    => $user->email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
