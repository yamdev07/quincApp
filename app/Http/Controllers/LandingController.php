<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing.index');
    }
    
    public function demo()
    {
        // Soit une démo interactive, soit une vidéo
        return view('landing.demo');
    }
    
    public function pricing()
    {
        $plans = [
            'monthly' => [
                'name' => 'Mensuel',
                'price' => 29900,
                'formatted' => '299 €',
                'savings' => 0,
                'popular' => false
            ],
            'quarterly' => [
                'name' => 'Trimestriel',
                'price' => 85200,
                'formatted' => '852 €',
                'savings' => '5%',
                'popular' => false
            ],
            'semester' => [
                'name' => 'Semestriel',
                'price' => 161400,
                'formatted' => '1 614 €',
                'savings' => '10%',
                'popular' => true
            ],
            'yearly' => [
                'name' => 'Annuel',
                'price' => 304800,
                'formatted' => '3 048 €',
                'savings' => '15%',
                'popular' => false
            ],
        ];
        
        return view('landing.pricing', compact('plans'));
    }
    
    public function register(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'subdomain' => 'required|string|alpha_dash|unique:tenants,subdomain',
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'plan' => 'required|in:monthly,quarterly,semester,yearly',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Prix selon le plan
            $prices = [
                'monthly' => 29900,
                'quarterly' => 85200,
                'semester' => 161400,
                'yearly' => 304800,
            ];
            
            // 1. Créer le tenant
            $tenant = Tenant::create([
                'company_name' => $validated['company_name'],
                'subdomain' => $validated['subdomain'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'is_active' => true,
                'has_trial' => true,
                'trial_days' => 14,
                'trial_ends_at' => now()->addDays(14),
                'subscription_price' => $prices[$validated['plan']],
                'billing_cycle' => $validated['plan'],
                'payment_status' => 'trial',
            ]);
            
            // 2. Créer l'utilisateur admin
            $password = Str::random(12);
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
            
            DB::commit();
            
            // 4. Envoyer l'email avec les identifiants
            // Mail::to($user->email)->send(new WelcomeMail($user, $password, $tenant));
            
            // 5. Connecter l'utilisateur automatiquement
            auth()->login($user);
            
            return redirect()->route('dashboard')
                ->with('success', 'Bienvenue ! Votre quincaillerie est prête. Vos identifiants vous ont été envoyés par email.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
}