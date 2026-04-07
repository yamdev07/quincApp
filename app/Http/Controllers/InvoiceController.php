<?php
// app/Http/Controllers/InvoiceController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sale;
use App\Models\Subscription;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la dernière facture
     */
    public function showLastInvoice()
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        
        // Récupérer le dernier paiement (abonnement)
        $lastPayment = Subscription::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->latest()
            ->first();
        
        if (!$lastPayment) {
            return redirect()->route('subscription.show')->with('info', 'Aucune facture disponible pour le moment.');
        }
        
        return view('reports.invoice', compact('lastPayment', 'tenant'));
    }
    
    /**
     * Télécharger la facture en PDF
     */
    public function downloadInvoice($id)
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        
        $payment = Subscription::where('tenant_id', $tenant->id)
            ->where('id', $id)
            ->firstOrFail();
        
        $pdf = Pdf::loadView('invoices.pdf', compact('payment', 'tenant'));
        
        return $pdf->download('facture_' . $payment->id . '_' . date('Y-m-d') . '.pdf');
    }
    
    /**
     * Liste des factures
     */
    public function index()
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        
        $payments = Subscription::where('tenant_id', $tenant->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('reports.show', compact('payments', 'tenant'));
    }
}