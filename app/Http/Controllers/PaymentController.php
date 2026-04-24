<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    private const PLANS = [
        'starter'   => ['name' => 'Starter',          'price' => 10000,  'duration' => '1 mois'],
        'monthly'   => ['name' => 'Business Mensuel', 'price' => 15000,  'duration' => '1 mois'],
        'quarterly' => ['name' => 'Pro Trimestriel',  'price' => 39900,  'duration' => '3 mois',  'saving' => 'Économisez 5 100 FCFA'],
        'semester'  => ['name' => 'Pro Semestriel',   'price' => 79900,  'duration' => '6 mois',  'saving' => 'Économisez 10 100 FCFA', 'popular' => true],
        'yearly'    => ['name' => 'Annuel',            'price' => 105000, 'duration' => '12 mois', 'saving' => 'Économisez 75 000 FCFA'],
        'lifetime'  => ['name' => 'Licence à vie',    'price' => 300000, 'duration' => 'À vie',   'saving' => 'Paiement unique'],
    ];

    public function __construct(private SubscriptionService $subscriptionService) {}

    public function showPaymentForm(Request $request)
    {
        $user   = Auth::user();
        $tenant = $user->tenant;

        $isRenewal       = $request->boolean('renewal');
        $currentPlanType = null;
        $currentAmount   = null;
        $currentPlanName = null;

        if ($isRenewal && $tenant) {
            $active = Subscription::where('tenant_id', $tenant->id)
                ->where('status', 'active')
                ->latest()
                ->first();

            if ($active) {
                $currentAmount   = $active->amount;
                $currentPlanType = $active->plan_type;
                $currentPlanName = self::PLANS[$active->plan_type]['name'] ?? 'Mensuel';
            }
        }

        $selectedPlan = $request->get('plan', $currentPlanType ?? 'monthly');
        $amount       = $request->get('amount', $currentAmount ?? 15000);
        $currentPlan  = self::PLANS[$selectedPlan] ?? self::PLANS['monthly'];

        return view('payment.form', compact(
            'user', 'tenant', 'currentPlan', 'selectedPlan', 'amount',
            'isRenewal', 'currentAmount', 'currentPlanName'
        ));
    }

    public function paymentCallback(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $validated = $request->validate([
            'plan_type'  => 'required|in:starter,monthly,quarterly,semester,yearly,lifetime',
            'amount'     => 'required|numeric|min:1',
            'is_renewal' => 'nullable|boolean',
        ]);

        try {
            $result = $this->subscriptionService->activate(
                user:      $user,
                planType:  $validated['plan_type'],
                amount:    (float) $validated['amount'],
                isRenewal: (bool) ($validated['is_renewal'] ?? false),
            );

            return redirect()->route('dashboard')->with('success', $result['message']);

        } catch (\Exception $e) {
            return redirect()->route('trial.expired')
                ->with('error', 'Erreur lors de l\'activation : ' . $e->getMessage());
        }
    }
}
