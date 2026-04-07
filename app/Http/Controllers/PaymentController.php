<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmationMail;
use App\Models\Subscription;

class PaymentController extends Controller
{
    public function paymentCallback(Request $request)
    {
        // =============================================
        // LOGS POUR DEBUG - ÉCRITURE DANS FICHIER
        // =============================================
        $logFile = storage_path('logs/payment_debug.log');
        
        $logData = [
            'time' => date('Y-m-d H:i:s'),
            'method' => $request->method(),
            'full_url' => $request->fullUrl(),
            'all_data' => $request->all(),
            'headers' => [
                'user-agent' => $request->header('user-agent'),
                'referer' => $request->header('referer'),
            ]
        ];
        
        file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);
        
        // =============================================
        // TRAITEMENT NORMAL
        // =============================================
        
        $amount = $request->input('amount');
        $planType = $request->input('plan_type');
        
        $user = Auth::user();
        
        if (!$user) {
            file_put_contents($logFile, "ERREUR: Utilisateur non connecté\n\n", FILE_APPEND);
            return redirect()->route('login')->with('error', 'Veuillez vous connecter');
        }
        
        $tenant = $user->tenant;
        
        file_put_contents($logFile, "User ID: " . $user->id . "\n", FILE_APPEND);
        file_put_contents($logFile, "Tenant ID: " . ($tenant ? $tenant->id : 'null') . "\n", FILE_APPEND);
        file_put_contents($logFile, "Amount: $amount, Plan: $planType\n", FILE_APPEND);
        
        $duration = match($planType) {
            'monthly' => 1,
            'quarterly' => 3,
            'semester' => 6,
            'yearly' => 12,
            default => 1
        };
        
        $endDate = now()->addMonths($duration);
        
        $planName = match($planType) {
            'monthly' => 'Mensuel',
            'quarterly' => 'Trimestriel',
            'semester' => 'Semestriel',
            'yearly' => 'Annuel',
            default => 'Mensuel'
        };
        
        try {
            if ($tenant) {
                // Désactiver ancien abonnement
                Subscription::where('tenant_id', $tenant->id)
                    ->where('status', 'active')
                    ->update(['status' => 'expired']);
                
                file_put_contents($logFile, "Ancien abonnement désactivé\n", FILE_APPEND);
                
                // Générer un ID de transaction
                $transactionId = 'fedapay_' . time();
                
                // Créer nouvel abonnement avec DB direct
                $insertId = DB::table('subscriptions')->insertGetId([
                    'tenant_id' => $tenant->id,
                    'plan_type' => $planType,
                    'amount' => $amount,
                    'start_date' => now()->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'status' => 'active',
                    'payment_method' => 'fedapay',
                    'transaction_id' => $transactionId,
                    'metadata' => json_encode([
                        'paid_at' => now()->toDateTimeString(),
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'plan_name' => $planName
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                file_put_contents($logFile, "Nouvel abonnement créé: ID $insertId\n", FILE_APPEND);
                
                // MISE À JOUR COMPLÈTE DU TENANT - TOUTES LES COLONNES
                $tenant->update([
                    'subscription_status' => 'active',
                    'payment_status' => 'paid',
                    'subscription_ends_at' => $endDate,
                    'subscription_end_date' => $endDate->toDateString(),
                    'is_active' => 1,
                    'last_payment_at' => now(),
                    'last_payment_amount' => $amount,
                ]);
                
                file_put_contents($logFile, "Tenant mis à jour\n", FILE_APPEND);
            }
            
            // Mettre à jour l'utilisateur
            $user->update([
                'subscription_status' => 'active',
                'subscription_ends_at' => $endDate,
                'last_payment_at' => now(),
            ]);
            
            file_put_contents($logFile, "Utilisateur mis à jour - Statut: active\n", FILE_APPEND);
            
            // =============================================
            // ENVOI DE L'EMAIL DE CONFIRMATION
            // =============================================
            try {
                // Créer un objet transaction pour l'email (sans multiplier par 100)
                $transaction = (object) [
                    'id' => $transactionId,
                    'amount' => $amount,  // ✅ Montant correct (10000, 28500, etc.)
                    'status' => 'approved'
                ];
                
                Mail::to($user->email)->send(new PaymentConfirmationMail($user, $transaction));
                file_put_contents($logFile, "✅ Email de confirmation envoyé à: " . $user->email . "\n", FILE_APPEND);
            } catch (\Exception $mailError) {
                file_put_contents($logFile, "❌ ERREUR ENVOI EMAIL: " . $mailError->getMessage() . "\n", FILE_APPEND);
            }
            
            file_put_contents($logFile, "=== SUCCÈS ===\n\n", FILE_APPEND);
            
            return redirect()->route('dashboard')
                ->with('success', '🎉 Abonnement ' . $planName . ' activé jusqu\'au ' . $endDate->format('d/m/Y') . '. Un email de confirmation vous a été envoyé.');
                
        } catch (\Exception $e) {
            file_put_contents($logFile, "ERREUR: " . $e->getMessage() . "\n", FILE_APPEND);
            file_put_contents($logFile, "STACK: " . $e->getTraceAsString() . "\n\n", FILE_APPEND);
            
            return redirect()->route('trial.expired')
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}