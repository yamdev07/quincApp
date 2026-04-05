<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StockManagerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            Log::warning('StockManagerMiddleware: Utilisateur non connecté');
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // LOGGING - Afficher les informations de l'utilisateur
        Log::info('StockManagerMiddleware - Infos utilisateur:', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'is_storekeeper' => $user->isStorekeeper(),
            'is_admin' => $user->isAdmin(),
            'is_super_admin' => $user->isSuperAdmin(),
            'route' => $request->route()->getName(),
            'method' => $request->method(),
            'url' => $request->fullUrl()
        ]);
        
        // Super Admin Global, Super Admin et Admin ont tous les droits
        if ($user->isSuperAdminGlobal() || $user->isSuperAdmin() || $user->isAdmin()) {
            Log::info('StockManagerMiddleware: Accès autorisé (Super Admin/Admin)');
            return $next($request);
        }
        
        // MAGASINIER - Peut gérer le stock (créer, modifier, fusionner)
        if ($user->isStorekeeper()) {
            Log::info('StockManagerMiddleware: Utilisateur est storekeeper');
            
            // Interdire la suppression
            if ($request->isMethod('delete') || str_contains($request->route()->getName(), 'destroy')) {
                Log::warning('StockManagerMiddleware: Tentative de suppression par magasinier bloquée');
                abort(403, 'Les magasiniers ne peuvent pas supprimer de produits.');
            }
            
            Log::info('StockManagerMiddleware: Accès autorisé pour magasinier');
            return $next($request);
        }

        // GÉRANT - Peut voir mais pas modifier
        if ($user->isManager()) {
            Log::info('StockManagerMiddleware: Utilisateur est manager');
            // N'autoriser que les méthodes GET
            if ($request->isMethod('get')) {
                Log::info('StockManagerMiddleware: Accès GET autorisé pour manager');
                return $next($request);
            }
            Log::warning('StockManagerMiddleware: Tentative de modification par manager bloquée');
        }

        Log::warning('StockManagerMiddleware: Accès refusé - rôle non autorisé', ['role' => $user->role]);
        abort(403, 'Accès refusé - Vous devez être administrateur, magasinier ou gérant.');
    }
}