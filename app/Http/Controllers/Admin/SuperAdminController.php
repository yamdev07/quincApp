<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    /**
     * Affiche la liste des tenants (quincailleries)
     */
    public function tenants()
    {
        $tenants = Tenant::with('owner')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.super-admin.tenants', compact('tenants'));
    }
    
    /**
     * Affiche le formulaire de création d'un tenant
     */
    public function createTenant()
    {
        return view('admin.super-admin.create-tenant');
    }
    
    /**
     * Enregistre un nouveau tenant
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
        ]);
        
        try {
            DB::beginTransaction();
            
            // 1. Créer le tenant
            $tenant = Tenant::create([
                'company_name' => $validated['company_name'],
                'subdomain' => $validated['subdomain'],
                'address' => $validated['address'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'],
                'is_active' => true,
                'settings' => [],
            ]);
            
            // 2. Créer l'utilisateur admin du tenant
            $password = Str::random(12); // À envoyer par email dans un vrai projet
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role' => 'super_admin', // Admin de la quincaillerie
                'tenant_id' => $tenant->id,
            ]);
            
            // 3. Associer l'utilisateur comme propriétaire du tenant
            $tenant->owner_id = $user->id;
            $tenant->save();
            
            DB::commit();
            
            return redirect()
                ->route('super-admin.tenants')
                ->with('success', "Quincaillerie créée avec succès. Mot de passe généré : {$password}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', "Erreur lors de la création : " . $e->getMessage());
        }
    }
    
    /**
     * Affiche les détails d'un tenant
     */
    public function showTenant(Tenant $tenant)
    {
        $tenant->load('owner', 'users');
        return view('admin.super-admin.show-tenant', compact('tenant'));
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
}