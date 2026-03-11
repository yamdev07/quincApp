<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
     * Traite l'inscription et crée la quincaillerie
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
            
            // Prix selon le plan (en FCFA)
            $prices = [
                'monthly' => 10000,
                'quarterly' => 28500,
                'semester' => 54000,
                'yearly' => 102000,
            ];
            
            // Générer un mot de passe aléatoire
            $password = Str::random(12);
            
            // 1. Créer le tenant (quincaillerie)
            $tenant = Tenant::create([
                'company_name' => $validated['company_name'],
                'subdomain' => $validated['subdomain'],
                'address' => $validated['address'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'],
                'is_active' => true,
                
                // Infos d'abonnement
                'subscription_price' => $prices[$validated['plan']],
                'billing_cycle' => $validated['plan'],
                'payment_status' => 'trial',
                'has_trial' => true,
                'trial_days' => 14,
                'trial_ends_at' => now()->addDays(14),
            ]);
            
            // 2. Créer l'utilisateur admin
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role' => 'super_admin',
                'tenant_id' => $tenant->id,
            ]);
            
            // 3. Associer le propriétaire
            $tenant->owner_id = $user->id;
            $tenant->save();
            
            // 4. Créer l'entrée dans l'historique des abonnements
            $tenant->subscriptions()->create([
                'plan_type' => $validated['plan'],
                'amount' => $prices[$validated['plan']],
                'start_date' => now(),
                'end_date' => now()->addDays(14),
                'status' => 'trial',
            ]);
            
            DB::commit();
            
            // 5. Envoyer l'email avec les identifiants (à décommenter quand vous aurez créé le Mailable)
            // Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user, $password, $tenant));
            
            // 6. Connecter l'utilisateur automatiquement
            auth()->login($user);
            
            // 7. Rediriger vers le dashboard avec un message de succès
            return redirect()->route('dashboard')
                ->with('success', 'Bienvenue sur QuincaApp ! Votre période d\'essai de 14 jours commence maintenant. Vos identifiants vous ont été envoyés par email.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de votre compte : ' . $e->getMessage());
        }
    }
    
    /**
     * Affiche la page de succès après inscription
     */
    public function registerSuccess()
    {
        return view('landing.success');
    }
}