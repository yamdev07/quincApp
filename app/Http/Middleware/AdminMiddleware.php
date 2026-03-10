<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        // ✅ Vérifie si l'utilisateur peut gérer les utilisateurs
        // super_admin_global, super_admin et admin ont tous ce droit
        if (!$user->isSuperAdminGlobal() && !$user->isSuperAdmin() && !$user->isAdmin()) {
            abort(403, 'Accès refusé - Vous devez être administrateur.');
        }

        return $next($request);
    }
}