<?php
// app/Http/Controllers/SubscriptionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use App\Models\PaymentHistory;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        
        if (!$tenant) {
            abort(404, 'Aucun abonnement trouvé');
        }
        
        // Informations générales
        $subscriptionStatus = $tenant->payment_status;
        $currentPlan = ucfirst($tenant->billing_cycle ?? 'Mensuel');
        $currentPrice = $tenant->subscription_price ?? 0;
        $startDate = $tenant->subscription_start_date ? Carbon::parse($tenant->subscription_start_date) : null;
        $endDate = $tenant->subscription_end_date ? Carbon::parse($tenant->subscription_end_date) : null;
        $trialEndDate = $tenant->trial_ends_at ? Carbon::parse($tenant->trial_ends_at) : null;
        
        // Calcul des jours restants
        $daysRemaining = 0;
        if ($subscriptionStatus === 'paid' && $endDate) {
            $daysRemaining = Carbon::now()->diffInDays($endDate, false);
        } elseif ($subscriptionStatus === 'trial' && $trialEndDate) {
            $daysRemaining = Carbon::now()->diffInDays($trialEndDate, false);
        }
        
        // Historique des paiements
        $paymentHistory = Subscription::where('tenant_id', $tenant->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Prochain renouvellement
        $nextRenewal = $endDate ? $endDate->copy() : null;
        
        // Options de formules disponibles
        $availablePlans = [
            ['type' => 'monthly', 'name' => 'Mensuel', 'price' => 10000, 'duration' => '1 mois'],
            ['type' => 'quarterly', 'name' => 'Trimestriel', 'price' => 28500, 'duration' => '3 mois', 'saving' => 'Économisez 1 500 FCFA'],
            ['type' => 'semester', 'name' => 'Semestriel', 'price' => 54000, 'duration' => '6 mois', 'saving' => 'Économisez 6 000 FCFA', 'popular' => true],
            ['type' => 'yearly', 'name' => 'Annuel', 'price' => 102000, 'duration' => '12 mois', 'saving' => 'Économisez 18 000 FCFA'],
        ];
        
        return view('subscription.show', compact(
            'tenant',
            'subscriptionStatus',
            'currentPlan',
            'currentPrice',
            'startDate',
            'endDate',
            'trialEndDate',
            'daysRemaining',
            'paymentHistory',
            'nextRenewal',
            'availablePlans'
        ));
    }
    
    public function cancel(Request $request)
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        
        if (!$tenant) {
            return redirect()->route('dashboard')->with('error', 'Aucun abonnement trouvé');
        }
        
        // Logique d'annulation (à adapter selon votre besoin)
        // $tenant->update(['payment_status' => 'cancelled']);
        
        return redirect()->route('subscription.show')->with('info', 'Votre demande d\'annulation a été enregistrée. Notre support vous contactera.');
    }
}