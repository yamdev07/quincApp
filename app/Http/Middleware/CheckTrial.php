<?php
// app/Http/Middleware/CheckTrial.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTrial
{
    public function handle(Request $request, Closure $next)
    {
        // Si l'utilisateur n'est pas connecté, on laisse passer (sera géré par auth middleware)
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        
        // Super Admin Global passe toujours
        if ($user->isSuperAdminGlobal()) {
            return $next($request);
        }

        // Vérifier que l'utilisateur a un tenant
        if (!$user->tenant) {
            return $next($request);
        }

        $tenant = $user->tenant;

        // ✅ CAS 1 : Abonnement payant actif (utilisation des colonnes qui existent)
        if ($tenant->payment_status === 'paid' && $tenant->subscription_end_date > now()->toDateString()) {
            return $next($request);
        }

        // ✅ CAS 2 : Période d'essai valide
        if ($tenant->payment_status === 'trial' && $tenant->trial_ends_at > now()) {
            $daysLeft = now()->diffInDays($tenant->trial_ends_at);
            if ($daysLeft <= 3) {
                session()->flash('warning', "⚠️ Il ne vous reste que {$daysLeft} jour(s) d'essai gratuit.");
            }
            return $next($request);
        }

        // ❌ CAS 3 : Essai expiré
        if ($tenant->payment_status === 'trial' && $tenant->trial_ends_at <= now()) {
            return redirect()->route('trial.expired')
                ->with('error', '⏰ Votre période d\'essai est terminée. Veuillez souscrire un abonnement.');
        }

        // ❌ CAS 4 : Abonnement expiré
        if ($tenant->payment_status === 'paid' && $tenant->subscription_end_date <= now()->toDateString()) {
            return redirect()->route('subscription.expired')
                ->with('error', '💰 Votre abonnement a expiré. Veuillez le renouveler.');
        }

        // ❌ CAS 5 : Par défaut
        return redirect()->route('trial.expired')
            ->with('error', '⏰ Votre période d\'essai est terminée. Veuillez souscrire un abonnement.');
    }
}