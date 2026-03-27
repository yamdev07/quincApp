<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use App\Mail\NewTenantWelcomeMail;

class LandingController extends Controller
{
    /**
     * Affiche la page d'accueil (vitrine)
     */
    public function index()
    {
        return view('landing.index');
    }
    
    /**
     * Affiche la page de démo interactive
     */
    public function demo()
    {
        return view('landing.demo');
    }
    
    /**
     * Affiche la page des tarifs
     */
    public function pricing()
    {
        $plans = [
            'monthly' => [
                'name' => 'Mensuel',
                'price' => 10000,
                'formatted' => '10 000 FCFA',
                'period' => '/mois',
                'savings' => 0,
                'popular' => false
            ],
            'quarterly' => [
                'name' => 'Trimestriel',
                'price' => 28500,
                'formatted' => '28 500 FCFA',
                'period' => '/3 mois',
                'savings' => '5%',
                'popular' => false
            ],
            'semester' => [
                'name' => 'Semestriel',
                'price' => 54000,
                'formatted' => '54 000 FCFA',
                'period' => '/6 mois',
                'savings' => '10%',
                'popular' => true
            ],
            'yearly' => [
                'name' => 'Annuel',
                'price' => 102000,
                'formatted' => '102 000 FCFA',
                'period' => '/an',
                'savings' => '15%',
                'popular' => false
            ],
        ];
        
        return view('landing.pricing', compact('plans'));
    }
    
    /**
     * Affiche la page FAQ
     */
    public function faq()
    {
        return view('landing.faq');
    }
    
    /**
     * Affiche le formulaire d'inscription avec le plan choisi
     */
    public function registerForm(Request $request)
    {
        $plan = $request->get('plan', 'monthly');
        
        $validPlans = ['monthly', 'quarterly', 'semester', 'yearly'];
        if (!in_array($plan, $validPlans)) {
            $plan = 'monthly';
        }
        
        // Récupérer les infos du plan pour les afficher
        $plans = [
            'monthly' => [
                'name' => 'Mensuel',
                'price' => 10000,
                'formatted' => '10 000 FCFA',
                'period' => '/mois'
            ],
            'quarterly' => [
                'name' => 'Trimestriel',
                'price' => 28500,
                'formatted' => '28 500 FCFA',
                'period' => '/3 mois'
            ],
            'semester' => [
                'name' => 'Semestriel',
                'price' => 54000,
                'formatted' => '54 000 FCFA',
                'period' => '/6 mois'
            ],
            'yearly' => [
                'name' => 'Annuel',
                'price' => 102000,
                'formatted' => '102 000 FCFA',
                'period' => '/an'
            ],
        ];
        
        $planName = $plans[$plan]['name'];
        $planPrice = $plans[$plan]['formatted'];
        
        return view('landing.register', compact('plan', 'planName', 'planPrice'));
    }
    
    /**
     * Traite l'inscription et envoie l'email avec le mot de passe
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'subdomain' => 'required|string|alpha_dash|unique:tenants,subdomain',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'plan' => 'required|in:monthly,quarterly,semester,yearly',
        ]);
        
        try {
            DB::beginTransaction();
            
            $prices = [
                'monthly' => 10000,
                'quarterly' => 28500,
                'semester' => 54000,
                'yearly' => 102000,
            ];
            
            // GÉNÉRER LE MOT DE PASSE (on le garde en clair pour l'email)
            $plainPassword = Str::random(12);
            
            // Créer le tenant
            $tenant = Tenant::create([
                'name' => $request->company_name,
                'company_name' => $request->company_name,
                'subdomain' => $request->subdomain,
                'domain' => $request->subdomain . '.quincaapp.com',
                'email' => $request->email,
                'phone' => $request->phone ?? null,
                'address' => $request->address ?? null,
                'logo' => null,
                'is_active' => true,
                'database_name' => 'tenant_' . $request->subdomain,
                'db_username' => 'user_' . $request->subdomain,
                'db_password' => Str::random(16),
                'subscription_price' => $prices[$request->plan],
                'billing_cycle' => $request->plan,
                'subscription_start_date' => now(),
                'subscription_end_date' => now()->addDays(14),
                'has_trial' => true,
                'trial_days' => 14,
                'trial_ends_at' => now()->addDays(14),
                'payment_status' => 'trial',
                'settings' => json_encode(['theme' => 'default', 'created_from' => 'website']),
                'subscription_metadata' => json_encode(['source' => 'website', 'plan' => $request->plan]),
            ]);
            
            // Créer l'utilisateur avec le mot de passe hashé
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($plainPassword),
                'role' => 'super_admin',
                'tenant_id' => $tenant->id,
                'can_manage_users' => true,
            ]);
            
            // Associer le propriétaire
            $tenant->owner_id = $user->id;
            $tenant->save();
            
            // Créer l'abonnement
            if (method_exists($tenant, 'subscriptions')) {
                $tenant->subscriptions()->create([
                    'plan_type' => $request->plan,
                    'amount' => $prices[$request->plan],
                    'start_date' => now(),
                    'end_date' => now()->addDays(14),
                    'status' => 'trial',
                ]);
            }
            
            // ENVOYER L'EMAIL AVEC LE MOT DE PASSE EN CLAIR
            try {
                Mail::to($user->email)->send(new NewTenantWelcomeMail($user, $plainPassword));
                \Log::info('✅ Email envoyé avec succès à ' . $user->email);
            } catch (\Exception $e) {
                // Log l'erreur mais ne bloque pas l'inscription
                \Log::error('❌ Erreur envoi email inscription: ' . $e->getMessage());
            }
            
            DB::commit();
            
            // Connecter l'utilisateur automatiquement
            auth()->login($user);
            
            // Rediriger avec le mot de passe en session (optionnel, pour affichage)
            return redirect()->route('dashboard')
                ->with('success', 'Bienvenue sur QuincaApp ! Votre période d\'essai de 14 jours commence maintenant.')
                ->with('temp_password', $plainPassword);
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('❌ Erreur inscription: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'inscription : ' . $e->getMessage());
        }
    }
    
    /**
     * Affiche la page de succès après inscription
     */
    public function registerSuccess()
    {
        return view('landing.success');
    }

    /**
     * Affiche la page des fonctionnalités
     */
    public function features()
    {
        return view('landing.features');
    }
}