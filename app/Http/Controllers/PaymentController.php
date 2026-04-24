<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmationMail;
use App\Models\Subscription;

class PaymentController extends Controller
{
    /**
     * Afficher le formulaire de paiement
     */
    public function showPaymentForm(Request $request)
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        
        // Vérifier si c'est un renouvellement
        $isRenewal = $request->get('renewal', false);
        $currentAmount = null;
        $currentPlanType = null;
        $currentPlanName = null;
        
        if ($isRenewal && $tenant) {
            // Récupérer l'abonnement actif
            $currentSubscription = Subscription::where('tenant_id', $tenant->id)
                ->where('status', 'active')
                ->latest()
                ->first();
            
            if ($currentSubscription) {
                $currentAmount = $currentSubscription->amount;
                $currentPlanType = $currentSubscription->plan_type;
                $currentPlanName = match($currentSubscription->plan_type) {
                    'starter'  => 'Starter',
                    'monthly'  => 'Mensuel',
                    'quarterly' => 'Trimestriel',
                    'semester' => 'Semestriel',
                    'yearly'   => 'Annuel',
                    'lifetime' => 'Licence à vie',
                    default    => 'Mensuel'
                };
            }
        }
        
        // Récupérer le plan sélectionné depuis l'URL
        $selectedPlan = $request->get('plan', $currentPlanType ?? 'monthly');
        $amount = $request->get('amount', $currentAmount ?? 15000);
        $currency = 'XOF';
        
        $plans = [
            'starter'   => ['name' => 'Starter',           'price' => 10000,  'duration' => '1 mois'],
            'monthly'   => ['name' => 'Business Mensuel',  'price' => 15000,  'duration' => '1 mois'],
            'quarterly' => ['name' => 'Pro Trimestriel',   'price' => 39900,  'duration' => '3 mois', 'saving' => 'Économisez 5 100 FCFA'],
            'semester'  => ['name' => 'Pro Semestriel',    'price' => 79900,  'duration' => '6 mois', 'saving' => 'Économisez 10 100 FCFA', 'popular' => true],
            'yearly'    => ['name' => 'Annuel',            'price' => 105000, 'duration' => '12 mois', 'saving' => 'Économisez 75 000 FCFA'],
            'lifetime'  => ['name' => 'Licence à vie',     'price' => 300000, 'duration' => 'À vie',   'saving' => 'Paiement unique'],
        ];
        
        $currentPlan = $plans[$selectedPlan] ?? $plans['monthly'];
        
        // Passage des variables à la vue
        return view('payment.form', compact(
            'user', 'tenant', 'currentPlan', 'selectedPlan', 'amount', 'currency',
            'isRenewal', 'currentAmount', 'currentPlanName'
        ));
    }

    public function paymentCallback(Request $request)
    {
        // =============================================
        // LOGS POUR DEBUG - ÉCRITURE DANS FICHIER
        // =============================================
        $logFile = storage_path('logs/payment_debug.log');
        
        $logData = [
            'time' => date('Y-m-d H:i:s'),
            'method' => $request->method(),
            'full_url' => $request->fullUrl(),
            'all_data' => $request->all(),
            'headers' => [
                'user-agent' => $request->header('user-agent'),
                'referer' => $request->header('referer'),
            ]
        ];
        
        file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);
        
        // =============================================
        // TRAITEMENT NORMAL
        // =============================================
        
        $amount = $request->input('amount');
        $planType = $request->input('plan_type');
        $isRenewal = $request->input('is_renewal', false);
        
        $user = Auth::user();
        
        if (!$user) {
            file_put_contents($logFile, "ERREUR: Utilisateur non connecté\n\n", FILE_APPEND);
            return redirect()->route('login')->with('error', 'Veuillez vous connecter');
        }
        
        $tenant = $user->tenant;
        
        file_put_contents($logFile, "User ID: " . $user->id . "\n", FILE_APPEND);
        file_put_contents($logFile, "Tenant ID: " . ($tenant ? $tenant->id : 'null') . "\n", FILE_APPEND);
        file_put_contents($logFile, "Amount: $amount, Plan: $planType, Renewal: " . ($isRenewal ? 'Yes' : 'No') . "\n", FILE_APPEND);
        
        if ($planType === 'lifetime') {
            $endDate = now()->addYears(99);
        } else {
            $duration = match($planType) {
                'starter'   => 1,
                'monthly'   => 1,
                'quarterly' => 3,
                'semester'  => 6,
                'yearly'    => 12,
                default     => 1
            };
            $endDate = now()->addMonths($duration);
        }

        $planName = match($planType) {
            'starter'   => 'Starter',
            'monthly'   => 'Business Mensuel',
            'quarterly' => 'Pro Trimestriel',
            'semester'  => 'Pro Semestriel',
            'yearly'    => 'Annuel',
            'lifetime'  => 'Licence à vie',
            default     => 'Business Mensuel'
        };
        
        try {
            if ($tenant) {
                // Récupérer l'ancien abonnement avant de le désactiver
                $oldSubscription = Subscription::where('tenant_id', $tenant->id)
                    ->where('status', 'active')
                    ->first();
                
                $oldAmount = null;
                if ($oldSubscription) {
                    $oldAmount = $oldSubscription->amount;
                    $oldSubscription->update(['status' => 'expired']);
                    file_put_contents($logFile, "Ancien abonnement désactivé - Montant: " . $oldAmount . " FCFA\n", FILE_APPEND);
                }
                
                // Générer un ID de transaction
                $transactionId = 'fedapay_' . time();
                
                // Créer nouvel abonnement avec DB direct
                $insertId = DB::table('subscriptions')->insertGetId([
                    'tenant_id' => $tenant->id,
                    'plan_type' => $planType,
                    'amount' => $amount,
                    'start_date' => now()->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'status' => 'active',
                    'payment_method' => 'fedapay',
                    'transaction_id' => $transactionId,
                    'metadata' => json_encode([
                        'paid_at' => now()->toDateTimeString(),
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'plan_name' => $planName,
                        'is_renewal' => $isRenewal,
                        'previous_amount' => $oldAmount
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                file_put_contents($logFile, "Nouvel abonnement créé: ID $insertId - Montant: $amount FCFA\n", FILE_APPEND);
                
                // MISE À JOUR COMPLÈTE DU TENANT - TOUTES LES COLONNES
                $tenant->update([
                    'subscription_status' => 'active',
                    'payment_status' => 'paid',
                    'subscription_ends_at' => $endDate,
                    'subscription_end_date' => $endDate->toDateString(),
                    'subscription_price' => $amount,
                    'billing_cycle' => $planType,
                    'is_active' => 1,
                    'last_payment_at' => now(),
                    'last_payment_amount' => $amount,
                ]);
                
                file_put_contents($logFile, "Tenant mis à jour - Nouveau prix: $amount FCFA\n", FILE_APPEND);
            }
            
            // Mettre à jour l'utilisateur
            $user->update([
                'subscription_status' => 'active',
                'subscription_ends_at' => $endDate,
                'last_payment_at' => now(),
            ]);
            
            file_put_contents($logFile, "Utilisateur mis à jour - Statut: active\n", FILE_APPEND);
            
            // =============================================
            // ENVOI DE L'EMAIL DE CONFIRMATION
            // =============================================
            try {
                // Créer un objet transaction pour l'email
                $transaction = (object) [
                    'id' => $transactionId,
                    'amount' => $amount,
                    'status' => 'approved',
                    'is_renewal' => $isRenewal
                ];
                
                Mail::to($user->email)->send(new PaymentConfirmationMail($user, $transaction));
                file_put_contents($logFile, "✅ Email de confirmation envoyé à: " . $user->email . "\n", FILE_APPEND);
            } catch (\Exception $mailError) {
                file_put_contents($logFile, "❌ ERREUR ENVOI EMAIL: " . $mailError->getMessage() . "\n", FILE_APPEND);
            }
            
            file_put_contents($logFile, "=== SUCCÈS ===\n\n", FILE_APPEND);
            
            // Message personnalisé selon que c'est un renouvellement ou non
            $message = $isRenewal 
                ? '🎉 Abonnement renouvelé avec succès ! Votre abonnement ' . $planName . ' est actif jusqu\'au ' . $endDate->format('d/m/Y')
                : '🎉 Abonnement activé avec succès ! Votre abonnement ' . $planName . ' est actif jusqu\'au ' . $endDate->format('d/m/Y');
            
            return redirect()->route('dashboard')->with('success', $message);
                
        } catch (\Exception $e) {
            file_put_contents($logFile, "ERREUR: " . $e->getMessage() . "\n", FILE_APPEND);
            file_put_contents($logFile, "STACK: " . $e->getTraceAsString() . "\n\n", FILE_APPEND);
            
            return redirect()->route('trial.expired')
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}