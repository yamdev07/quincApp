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

        // ✅ CAS 1 : Période d'essai valide
        if ($tenant->payment_status === 'trial' && $tenant->trial_ends_at > now()) {
            // Calculer les jours restants
            $daysLeft = now()->diffInDays($tenant->trial_ends_at);
            
            // Ajouter un message de notification (optionnel)
            if ($daysLeft <= 3) {
                session()->flash('warning', "⚠️ Il ne vous reste que {$daysLeft} jour(s) d'essai gratuit. Pensez à souscrire un abonnement !");
            } else {
                session()->flash('info', "🎁 Période d'essai : {$daysLeft} jour(s) restants");
            }
            
            return $next($request);
        }

        // ❌ CAS 2 : Essai expiré
        if ($tenant->payment_status === 'trial' && $tenant->trial_ends_at <= now()) {
            // Désactiver le compte
            $tenant->update(['is_active' => false]);
            
            // Rediriger vers la page d'expiration
            return redirect()->route('trial.expired')
                ->with('error', '⏰ Votre période d\'essai de 14 jours est terminée. Veuillez souscrire un abonnement pour continuer.');
        }

        // ✅ CAS 3 : Abonnement payant actif
        if ($tenant->payment_status === 'paid' && $tenant->subscription_end_date > now()) {
            return $next($request);
        }

        // ❌ CAS 4 : Abonnement expiré
        if ($tenant->payment_status === 'paid' && $tenant->subscription_end_date <= now()) {
            $tenant->update([
                'payment_status' => 'expired',
                'is_active' => false
            ]);
            
            return redirect()->route('subscription.expired')
                ->with('error', '💰 Votre abonnement a expiré. Veuillez le renouveler.');
        }

        // ❌ CAS 5 : Statut inconnu
        return redirect()->route('dashboard')
            ->with('error', 'Une erreur est survenue avec votre abonnement. Contactez le support.');
    }
}