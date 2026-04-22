<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\PlanService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Vérifier que l'utilisateur peut gérer les employés
     */
    private function authorizeUserManagement()
    {
        if (!Auth::user()->canManageUsers()) {
            abort(403, 'Vous n\'avez pas les droits pour gérer les employés.');
        }
    }

    /**
     * Vérifier que l'utilisateur cible peut être modifié
     */
    private function authorizeTargetUser(User $targetUser)
    {
        $currentUser = Auth::user();
        
        // Vérifier que l'utilisateur cible appartient à la même quincaillerie
        if (!$currentUser->hasAccessTo($targetUser)) {
            abort(403, 'Cet employé ne fait pas partie de votre équipe.');
        }
        
        // Un admin délégué ne peut pas modifier un super_admin
        if (!$currentUser->isSuperAdmin() && $targetUser->isSuperAdmin()) {
            abort(403, 'Vous ne pouvez pas modifier le super administrateur.');
        }
        
        // Un admin délégué ne peut pas modifier un autre admin délégué
        if (!$currentUser->isSuperAdmin() && $targetUser->isAdmin() && $targetUser->id !== $currentUser->id) {
            abort(403, 'Seul le super administrateur peut modifier les administrateurs.');
        }
    }

    /**
     * Vérifier les permissions pour créer un utilisateur avec un rôle spécifique
     */
    private function authorizeRoleCreation($role)
    {
        $currentUser = Auth::user();
        
        // Seul le super_admin peut créer des admins
        if ($role === 'admin' && !$currentUser->isSuperAdmin()) {
            abort(403, 'Seul le super administrateur peut créer des administrateurs.');
        }
        
        // Les admins délégués ne peuvent créer que des managers, caissiers, magasiniers
        if ($currentUser->isAdmin() && !in_array($role, ['manager', 'cashier', 'storekeeper'])) {
            abort(403, 'Vous ne pouvez créer que des managers, caissiers ou magasiniers.');
        }
    }

    // Affiche la liste des utilisateurs
    public function index()
    {
        $this->authorizeUserManagement();
        
        $currentUser = Auth::user();
        
        // Les super_admins voient tous leurs employés
        // Les admins délégués voient les employés (sauf les autres admins)
        $query = User::where('owner_id', $currentUser->isSuperAdmin() ? $currentUser->id : $currentUser->owner_id);
        
        // Les admins délégués ne voient pas les super_admins
        if (!$currentUser->isSuperAdmin()) {
            $query->where('role', '!=', 'super_admin');
        }
        
        $users = $query->paginate(10);
        
        return view('users.index', compact('users'));
    }

    // Affiche le formulaire de création
    public function create()
    {
        $this->authorizeUserManagement();
        
        // Déterminer les rôles disponibles selon l'utilisateur connecté
        $availableRoles = [];
        
        if (Auth::user()->isSuperAdmin()) {
            $availableRoles = [
                'admin' => 'Administrateur (peut gérer les utilisateurs)',
                'manager' => 'Gérant',
                'cashier' => 'Caissier',
                'storekeeper' => 'Magasinier',
            ];
        } else {
            $availableRoles = [
                'manager' => 'Gérant',
                'cashier' => 'Caissier',
                'storekeeper' => 'Magasinier',
            ];
        }
        
        return view('users.create', compact('availableRoles'));
    }

    // Stocke un nouvel utilisateur
    public function store(Request $request)
    {
        $this->authorizeUserManagement();

        $user   = Auth::user();
        $tenant = $user->tenant;
        if (!$user->isSuperAdminGlobal() && $tenant) {
            $plan = PlanService::for($tenant);
            $currentCount = User::where('tenant_id', $tenant->id)->count();
            if (!$plan->canAddUser($currentCount)) {
                $max = $plan->maxUsers();
                return back()->with('upgrade', "Limite atteinte : votre plan {$plan->planLabel()} est limité à {$max} utilisateurs. Passez à un plan supérieur pour en ajouter davantage.");
            }
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:admin,manager,cashier,storekeeper',
        ]);
        
        // Vérifier les permissions pour ce rôle
        $this->authorizeRoleCreation($request->role);
        
        $currentUser = Auth::user();
        
        // Créer l'utilisateur avec les bonnes relations
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'tenant_id' => $currentUser->tenant_id,
            'owner_id' => $currentUser->isSuperAdmin() ? $currentUser->id : $currentUser->owner_id,
            'can_manage_users' => $request->role === 'admin',
        ]);

        return redirect()->route('users.index')
                         ->with('success', 'Employé ajouté avec succès ✅');
    }

    // Affiche le formulaire d'édition
    public function edit(User $user)
    {
        $this->authorizeUserManagement();
        $this->authorizeTargetUser($user);
        
        // Déterminer les rôles disponibles selon l'utilisateur connecté
        $availableRoles = [];
        
        if (Auth::user()->isSuperAdmin()) {
            $availableRoles = [
                'admin' => 'Administrateur (peut gérer les utilisateurs)',
                'manager' => 'Gérant',
                'cashier' => 'Caissier',
                'storekeeper' => 'Magasinier',
            ];
            
            // Ne pas permettre de changer le rôle d'un super_admin
            if ($user->isSuperAdmin()) {
                $availableRoles = ['super_admin' => 'Super Administrateur (non modifiable)'];
            }
        } else {
            $availableRoles = [
                'manager' => 'Gérant',
                'cashier' => 'Caissier',
                'storekeeper' => 'Magasinier',
            ];
        }
        
        return view('users.edit', compact('user', 'availableRoles'));
    }

    // Met à jour un utilisateur
    public function update(Request $request, User $user)
    {
        $this->authorizeUserManagement();
        $this->authorizeTargetUser($user);
        
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,manager,cashier,storekeeper',
        ]);
        
        // Vérifier les permissions pour changer le rôle
        if ($user->role !== $request->role) {
            $this->authorizeRoleCreation($request->role);
        }
        
        $updateData = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
            'can_manage_users' => $request->role === 'admin',
        ];

        $user->update($updateData);

        // Mise à jour du mot de passe seulement si rempli
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:6|confirmed'
            ]);
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('users.index')
                         ->with('success', 'Employé mis à jour avec succès ✅');
    }

    // Supprime un utilisateur
    public function destroy(User $user)
    {
        $this->authorizeUserManagement();
        $this->authorizeTargetUser($user);
        
        $currentUser = Auth::user();
        
        // Empêcher de supprimer son propre compte
        if ($user->id === $currentUser->id) {
            return redirect()->route('users.index')
                             ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        
        // Empêcher de supprimer un super_admin
        if ($user->isSuperAdmin()) {
            return redirect()->route('users.index')
                             ->with('error', 'Impossible de supprimer le super administrateur.');
        }
        
        // Vérifier si l'utilisateur a des données associées
        if ($user->sales()->count() > 0) {
            return redirect()->route('users.index')
                ->with('warning', "Impossible de supprimer cet employé car il a {$user->sales()->count()} vente(s) associée(s).");
        }

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'Employé supprimé avec succès ✅');
    }

    // Afficher les statistiques des employés
    public function statistics()
    {
        $this->authorizeUserManagement();
        
        $currentUser = Auth::user();
        $ownerId = $currentUser->isSuperAdmin() ? $currentUser->id : $currentUser->owner_id;
        
        $employees = User::where('owner_id', $ownerId)
                        ->withCount('sales')
                        ->withSum('sales', 'total_price')
                        ->get();
        
        $stats = [
            'total_employees' => $employees->count(),
            'by_role' => $employees->groupBy('role')->map->count(),
            'total_sales' => $employees->sum('sales_count'),
            'total_revenue' => $employees->sum('sales_sum_total_price'),
            'best_cashier' => $employees->where('role', 'cashier')
                                       ->sortByDesc('sales_sum_total_price')
                                       ->first(),
            'best_manager' => $employees->where('role', 'manager')
                                       ->sortByDesc('sales_sum_total_price')
                                       ->first(),
            'average_per_employee' => $employees->count() > 0 
                ? round($employees->sum('sales_sum_total_price') / $employees->count(), 0)
                : 0,
        ];
        
        return view('users.statistics', compact('stats', 'employees'));
    }

    // API pour récupérer les employés (pour les select AJAX)
    public function getEmployees(Request $request)
    {
        $this->authorizeUserManagement();
        
        $search = $request->get('q');
        
        $currentUser = Auth::user();
        $ownerId = $currentUser->isSuperAdmin() ? $currentUser->id : $currentUser->owner_id;
        
        $employees = User::where('owner_id', $ownerId)
                        ->where('id', '!=', $currentUser->id)
                        ->when($search, function($query, $search) {
                            return $query->where('name', 'LIKE', "%{$search}%");
                        })
                        ->limit(20)
                        ->get(['id', 'name', 'role']);
        
        return response()->json($employees);
    }

    // Révoquer les droits d'admin d'un utilisateur
    public function revokeAdminRights(User $user)
    {
        $this->authorizeUserManagement();
        $this->authorizeTargetUser($user);
        
        $currentUser = Auth::user();
        
        // Seul le super_admin peut révoquer les droits admin
        if (!$currentUser->isSuperAdmin()) {
            abort(403);
        }
        
        // Ne pas révoquer ses propres droits
        if ($user->id === $currentUser->id) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas révoquer vos propres droits.');
        }
        
        $user->update([
            'role' => 'manager',
            'can_manage_users' => false,
        ]);
        
        return redirect()->route('users.index')
            ->with('success', "Les droits d'administrateur de {$user->name} ont été révoqués.");
    }

    // Promouvoir un utilisateur en admin
    public function promoteToAdmin(User $user)
    {
        $this->authorizeUserManagement();
        $this->authorizeTargetUser($user);
        
        $currentUser = Auth::user();
        
        // Seul le super_admin peut promouvoir
        if (!$currentUser->isSuperAdmin()) {
            abort(403);
        }
        
        $user->update([
            'role' => 'admin',
            'can_manage_users' => true,
        ]);
        
        return redirect()->route('users.index')
            ->with('success', "{$user->name} a été promu administrateur.");
    }
}