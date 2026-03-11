<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    /**
     * Affiche la liste des tenants (quincailleries) avec stats d'abonnement
     */
    public function tenants()
    {
        // Récupérer les tenants avec leurs propriétaires et stats
        $tenants = Tenant::with('owner')
            ->withCount('users')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Statistiques globales des abonnements pour le dashboard
        $stats = [
            'total' => Tenant::count(),
            'actifs' => Tenant::active()->count(),
            'en_retard' => Tenant::overdue()->count(),
            'expirant_bientot' => Tenant::expiringSoon(7)->count(),
            'essais' => Tenant::where('payment_status', 'trial')->count(),
            'revenu_mensuel' => Tenant::where('payment_status', 'paid')
                ->sum('subscription_price') / 100,
        ];
            
        return view('admin.super-admin.tenants', compact('tenants', 'stats'));
    }
    
    /**
     * Affiche le formulaire de création d'un tenant avec choix d'abonnement
     */
    public function createTenant()
    {
        // Options d'abonnement disponibles
        $plans = [
            'monthly' => ['name' => 'Mensuel', 'price' => 29900, 'savings' => 0],
            'quarterly' => ['name' => 'Trimestriel', 'price' => 85200, 'savings' => 5],
            'semester' => ['name' => 'Semestriel', 'price' => 161400, 'savings' => 10],
            'yearly' => ['name' => 'Annuel', 'price' => 304800, 'savings' => 15],
        ];
        
        return view('admin.super-admin.create-tenant', compact('plans'));
    }
    
    /**
     * Enregistre un nouveau tenant avec son abonnement
     */
    public function storeTenant(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'subdomain' => 'required|string|alpha_dash|unique:tenants,subdomain',
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            
            // 👇 NOUVEAUX CHAMPS D'ABONNEMENT
            'billing_cycle' => 'required|in:monthly,quarterly,semester,yearly',
            'has_trial' => 'boolean',
            'trial_days' => 'required_if:has_trial,true|integer|min:1|max:90',
            'subscription_price' => 'required|integer|min:0',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Calculer les dates d'abonnement
            $now = Carbon::now();
            $trialEndsAt = null;
            $subscriptionStartDate = $now;
            $subscriptionEndDate = null;
            
            // Gérer la période d'essai
            if ($request->has('has_trial') && $request->has_trial) {
                $trialEndsAt = $now->copy()->addDays($request->trial_days ?? 14);
                $subscriptionStartDate = $trialEndsAt->copy()->addDay();
            }
            
            // Calculer la date de fin selon le cycle
            switch ($request->billing_cycle) {
                case 'monthly':
                    $subscriptionEndDate = $subscriptionStartDate->copy()->addMonth();
                    break;
                case 'quarterly':
                    $subscriptionEndDate = $subscriptionStartDate->copy()->addMonths(3);
                    break;
                case 'semester':
                    $subscriptionEndDate = $subscriptionStartDate->copy()->addMonths(6);
                    break;
                case 'yearly':
                    $subscriptionEndDate = $subscriptionStartDate->copy()->addYear();
                    break;
            }
            
            // 1. Créer le tenant avec toutes les infos d'abonnement
            $tenant = Tenant::create([
                'company_name' => $validated['company_name'],
                'subdomain' => $validated['subdomain'],
                'address' => $validated['address'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'],
                'is_active' => true,
                'settings' => [],
                
                // 👇 INFOS D'ABONNEMENT
                'subscription_price' => $validated['subscription_price'],
                'billing_cycle' => $validated['billing_cycle'],
                'subscription_start_date' => $subscriptionStartDate,
                'subscription_end_date' => $subscriptionEndDate,
                'has_trial' => $request->has_trial ?? true,
                'trial_days' => $request->trial_days ?? 14,
                'trial_ends_at' => $trialEndsAt,
                'payment_status' => $request->has_trial ? 'trial' : 'pending',
            ]);
            
            // 2. Créer l'utilisateur admin du tenant
            $password = Str::random(12);
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role' => 'super_admin',
                'tenant_id' => $tenant->id,
            ]);
            
            // 3. Associer l'utilisateur comme propriétaire du tenant
            $tenant->owner_id = $user->id;
            $tenant->save();
            
            // 4. Créer l'entrée dans l'historique des abonnements
            $tenant->subscriptions()->create([
                'plan_type' => $validated['billing_cycle'],
                'amount' => $validated['subscription_price'] / 100,
                'start_date' => $subscriptionStartDate,
                'end_date' => $subscriptionEndDate,
                'status' => $request->has_trial ? 'trial' : 'pending',
            ]);
            
            DB::commit();
            
            $message = "Quincaillerie créée avec succès. ";
            $message .= "Mot de passe : {$password}. ";
            if ($request->has_trial) {
                $message .= "Période d'essai de {$request->trial_days} jours.";
            }
            
            return redirect()
                ->route('super-admin.tenants')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', "Erreur lors de la création : " . $e->getMessage());
        }
    }
    
    /**
     * Affiche les détails d'un tenant avec ses statistiques
     */
    public function showTenant(Tenant $tenant)
    {
        // Charger les relations
        $tenant->load(['owner', 'users', 'subscriptions' => function($q) {
            $q->latest();
        }]);
        
        // Statistiques du tenant
        $stats = [
            'total_products' => $tenant->products()->count(),
            'total_sales' => $tenant->sales()->count(),
            'total_revenue' => $tenant->sales()->sum('total_price'),
            'total_clients' => $tenant->clients()->count(),
            'total_suppliers' => $tenant->suppliers()->count(),
            'low_stock' => $tenant->products()->where('stock', '<=', 5)->count(),
            'out_of_stock' => $tenant->products()->where('stock', 0)->count(),
        ];
        
        // Dernières ventes
        $recentSales = $tenant->latestSales(10);
        
        // Produits en stock faible
        $lowStockProducts = $tenant->lowStockProducts(5);
        
        return view('admin.super-admin.show-tenant', compact(
            'tenant', 
            'stats', 
            'recentSales', 
            'lowStockProducts'
        ));
    }
    
    /**
     * Active/désactive un tenant
     */
    public function toggleTenant(Tenant $tenant)
    {
        $tenant->is_active = !$tenant->is_active;
        $tenant->save();
        
        $status = $tenant->is_active ? 'activée' : 'désactivée';
        
        return back()->with('success', "Quincaillerie {$status} avec succès.");
    }
    
    /**
     * Supprime un tenant
     */
    public function deleteTenant(Tenant $tenant)
    {
        try {
            DB::beginTransaction();
            
            // Supprimer tous les utilisateurs du tenant
            User::where('tenant_id', $tenant->id)->delete();
            
            // Supprimer les abonnements
            $tenant->subscriptions()->delete();
            
            // Supprimer le tenant
            $tenant->delete();
            
            DB::commit();
            
            return redirect()
                ->route('super-admin.tenants')
                ->with('success', 'Quincaillerie supprimée avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', "Erreur lors de la suppression : " . $e->getMessage());
        }
    }
    
    /**
     * Gérer les paiements d'un tenant
     */
    public function managePayments(Tenant $tenant)
    {
        $payments = $tenant->subscriptions()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.super-admin.payments', compact('tenant', 'payments'));
    }
    
    /**
     * Marquer un paiement comme reçu
     */
    public function markPaymentReceived(Request $request, Tenant $tenant)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Mettre à jour le statut du tenant
            $tenant->payment_status = 'paid';
            $tenant->last_payment_date = Carbon::now();
            $tenant->last_payment_amount = $request->amount;
            
            // Recalculer la date de fin selon le cycle
            if ($tenant->subscription_end_date && $tenant->subscription_end_date->isPast()) {
                // Si expiré, nouvelle période
                $tenant->subscription_start_date = Carbon::now();
                switch ($tenant->billing_cycle) {
                    case 'monthly': $tenant->subscription_end_date = Carbon::now()->addMonth(); break;
                    case 'quarterly': $tenant->subscription_end_date = Carbon::now()->addMonths(3); break;
                    case 'semester': $tenant->subscription_end_date = Carbon::now()->addMonths(6); break;
                    case 'yearly': $tenant->subscription_end_date = Carbon::now()->addYear(); break;
                }
            } else if ($tenant->subscription_end_date) {
                // Si encore actif, prolonger
                switch ($tenant->billing_cycle) {
                    case 'monthly': $tenant->subscription_end_date->addMonth(); break;
                    case 'quarterly': $tenant->subscription_end_date->addMonths(3); break;
                    case 'semester': $tenant->subscription_end_date->addMonths(6); break;
                    case 'yearly': $tenant->subscription_end_date->addYear(); break;
                }
            }
            
            $tenant->save();
            
            // Ajouter à l'historique
            $tenant->subscriptions()->create([
                'plan_type' => $tenant->billing_cycle,
                'amount' => $request->amount,
                'start_date' => $tenant->subscription_start_date,
                'end_date' => $tenant->subscription_end_date,
                'status' => 'active',
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
            ]);
            
            DB::commit();
            
            return back()->with('success', 'Paiement enregistré avec succès.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
    
    /**
     * Prolonger l'abonnement d'un tenant
     */
    public function extendSubscription(Request $request, Tenant $tenant)
    {
        $request->validate([
            'months' => 'required|integer|min:1|max:12',
        ]);
        
        if ($tenant->subscription_end_date) {
            $tenant->subscription_end_date = $tenant->subscription_end_date->addMonths($request->months);
        } else {
            $tenant->subscription_end_date = Carbon::now()->addMonths($request->months);
        }
        
        $tenant->payment_status = 'paid';
        $tenant->save();
        
        return back()->with('success', "Abonnement prolongé de {$request->months} mois.");
    }
    
    /**
     * Dashboard du super admin avec statistiques globales
     */
    public function dashboard()
    {
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::active()->count(),
            'expired_tenants' => Tenant::expired()->count(),
            'overdue_tenants' => Tenant::overdue()->count(),
            'trial_tenants' => Tenant::where('payment_status', 'trial')->count(),
            
            'total_users' => User::count(),
            'total_revenue' => Tenant::where('payment_status', 'paid')->sum('subscription_price') / 100,
            
            'expiring_next_week' => Tenant::expiringSoon(7)->count(),
            'trial_expiring' => Tenant::trialExpiring(7)->count(),
            
            'recent_tenants' => Tenant::with('owner')
                ->latest()
                ->limit(5)
                ->get(),
        ];
        
        // Graphique des inscriptions
        $registrations = [];
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            $registrations[] = Tenant::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }
        
        return view('admin.super-admin.dashboard', compact('stats', 'months', 'registrations'));
    }
}