@extends('layouts.app')

@section('title', 'Facture - Inventix')

@section('styles')
<style>
    :root {
        --primary: #f97316;
        --primary-dark: #ea580c;
        --primary-light: #fff7ed;
        --secondary: #0f172a;
        --secondary-light: #1e293b;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --success: #10b981;
        --success-light: #d1fae5;
    }

    .invoice-wrapper {
        max-width: 1100px;
        margin: 0 auto;
        padding: 40px 24px;
    }

    .invoice {
        background: white;
        border-radius: 32px;
        box-shadow: 0 20px 35px -10px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    /* Header Section */
    .invoice-header {
        background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-light) 100%);
        padding: 48px;
        position: relative;
        overflow: hidden;
    }

    .invoice-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 80%;
        height: 200%;
        background: radial-gradient(circle, rgba(249,115,22,0.1) 0%, transparent 70%);
        transform: rotate(25deg);
    }

    .header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 30px;
    }

    .logo-section h1 {
        color: white;
        font-size: 32px;
        font-weight: 800;
        margin: 0 0 8px;
        letter-spacing: -0.5px;
    }

    .logo-section p {
        color: rgba(255,255,255,0.7);
        margin: 0;
        font-size: 14px;
    }

    .invoice-title {
        text-align: right;
    }

    .invoice-title .title {
        font-size: 48px;
        font-weight: 800;
        color: white;
        margin: 0;
        line-height: 1;
        letter-spacing: -1px;
    }

    .invoice-title .subtitle {
        color: var(--primary);
        font-size: 14px;
        margin-top: 8px;
        letter-spacing: 2px;
    }

    /* Body Section */
    .invoice-body {
        padding: 48px;
    }

    /* Company & Client Info */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 48px;
        padding-bottom: 32px;
        border-bottom: 2px solid var(--gray-100);
    }

    .info-section {
        background: var(--gray-50);
        padding: 20px;
        border-radius: 16px;
    }

    .info-section h3 {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--gray-600);
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-section h3 i {
        color: var(--primary);
        font-size: 16px;
    }

    .company-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 8px;
    }

    .company-details, .client-details {
        color: var(--gray-600);
        font-size: 13px;
        line-height: 1.6;
    }

    /* Invoice Details Table */
    .details-table {
        margin-bottom: 48px;
    }

    .details-row {
        display: flex;
        justify-content: space-between;
        padding: 16px 0;
        border-bottom: 1px solid var(--gray-100);
    }

    .details-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-size: 14px;
        color: var(--gray-600);
        font-weight: 500;
    }

    .detail-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--gray-800);
    }

    .detail-value.accent {
        color: var(--primary);
        font-size: 18px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: var(--success-light);
        color: var(--success);
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
    }

    /* Amount Section */
    .amount-section {
        background: linear-gradient(135deg, var(--primary-light) 0%, white 100%);
        border-radius: 20px;
        padding: 32px;
        margin-bottom: 32px;
        text-align: center;
        border: 1px solid rgba(249,115,22,0.1);
    }

    .amount-label {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--gray-600);
        margin-bottom: 12px;
    }

    .amount-value {
        font-size: 56px;
        font-weight: 800;
        color: var(--primary);
        line-height: 1;
        margin-bottom: 8px;
    }

    .amount-tax {
        font-size: 13px;
        color: var(--gray-600);
    }

    /* Payment Details */
    .payment-details {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 32px;
    }

    .payment-details h4 {
        font-size: 14px;
        font-weight: 700;
        color: var(--gray-800);
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .payment-details h4 i {
        color: var(--primary);
    }

    .payment-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .payment-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
    }

    .payment-label {
        font-size: 13px;
        color: var(--gray-600);
    }

    .payment-value {
        font-size: 13px;
        font-weight: 600;
        color: var(--gray-800);
    }

    /* Footer Actions */
    .invoice-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding-top: 32px;
        border-top: 1px solid var(--gray-200);
        margin-top: 16px;
    }

    .btn {
        padding: 12px 28px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        box-shadow: 0 8px 20px rgba(249,115,22,0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(249,115,22,0.4);
        color: white;
    }

    .btn-secondary {
        background: white;
        color: var(--gray-700);
        border: 1px solid var(--gray-300);
    }

    .btn-secondary:hover {
        background: var(--gray-50);
        border-color: var(--primary);
        color: var(--primary);
    }

    .btn-outline {
        background: transparent;
        color: var(--gray-700);
        border: 1px solid var(--gray-300);
    }

    .btn-outline:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    /* Thank You Section */
    .thankyou-section {
        text-align: center;
        padding: 32px;
        background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
        border-radius: 16px;
        margin-top: 32px;
    }

    .thankyou-section i {
        font-size: 48px;
        color: var(--primary);
        margin-bottom: 16px;
    }

    .thankyou-section h3 {
        font-size: 20px;
        font-weight: 700;
        color: var(--gray-800);
        margin: 0 0 8px 0;
    }

    .thankyou-section p {
        color: var(--gray-600);
        font-size: 13px;
        margin: 0;
    }

    @media print {
        .btn, .actions {
            display: none;
        }
        .invoice-wrapper {
            padding: 0;
        }
        .invoice {
            box-shadow: none;
        }
    }
</style>
@endsection

@section('content')
<div class="invoice-wrapper">
    <div class="invoice">
        
        {{-- Header --}}
        <div class="invoice-header">
            <div class="header-content">
                <div class="logo-section">
                    <h1>Inventix</h1>
                    <p>Solution de gestion professionnelle</p>
                </div>
                <div class="invoice-title">
                    <div class="title">FACTURE</div>
                    <div class="subtitle">N° {{ str_pad($lastPayment->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="invoice-body">
            
            {{-- Company & Client Info --}}
            <div class="info-grid">
                <div class="info-section">
                    <h3><i class="bi bi-building"></i> Émetteur</h3>
                    <div class="company-name">{{ $tenant->company_name ?? 'Inventix' }}</div>
                    <div class="company-details">
                        {{ $tenant->address ?? '123 Avenue du Commerce' }}<br>
                        Tél: {{ $tenant->phone ?? '+225 XX XX XX XX' }}<br>
                        Email: {{ $tenant->email ?? 'contact@quincapro.com' }}<br>
                        N° RCCM: {{ $tenant->rccm ?? 'CI-ABC-2024-001' }}
                    </div>
                </div>
                
                <div class="info-section">
                    <h3><i class="bi bi-person-badge"></i> Client</h3>
                    <div class="company-name">{{ $tenant->company_name ?? 'Client' }}</div>
                    <div class="company-details">
                        {{ $tenant->address ?? '' }}<br>
                        Tél: {{ $tenant->phone ?? '' }}<br>
                        Email: {{ $tenant->email ?? '' }}
                    </div>
                </div>
            </div>

            {{-- Invoice Details --}}
            <div class="details-table">
                <div class="details-row">
                    <span class="detail-label">Date d'émission</span>
                    <span class="detail-value">{{ $lastPayment->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="details-row">
                    <span class="detail-label">Période d'abonnement</span>
                    <span class="detail-value">
                        Du {{ \Carbon\Carbon::parse($lastPayment->start_date)->format('d/m/Y') }}
                        au {{ \Carbon\Carbon::parse($lastPayment->end_date)->format('d/m/Y') }}
                    </span>
                </div>
                <div class="details-row">
                    <span class="detail-label">Type d'abonnement</span>
                    <span class="detail-value accent">{{ ucfirst($lastPayment->plan_type) }}</span>
                </div>
                <div class="details-row">
                    <span class="detail-label">Statut</span>
                    <span class="detail-value">
                        <span class="status-badge">
                            <i class="bi bi-check-circle-fill"></i> Payé
                        </span>
                    </span>
                </div>
            </div>

            {{-- Amount Section --}}
            <div class="amount-section">
                <div class="amount-label">Montant total à payer</div>
                <div class="amount-value">{{ number_format($lastPayment->amount, 0, ',', ' ') }} FCFA</div>
                <div class="amount-tax">TVA 0% (exonéré)</div>
            </div>

            {{-- Payment Details --}}
            <div class="payment-details">
                <h4><i class="bi bi-credit-card"></i> Détails du paiement</h4>
                <div class="payment-grid">
                    <div class="payment-item">
                        <span class="payment-label">Méthode de paiement</span>
                        <span class="payment-value">Carte bancaire / Mobile Money</span>
                    </div>
                    <div class="payment-item">
                        <span class="payment-label">Date de transaction</span>
                        <span class="payment-value">{{ $lastPayment->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="payment-item">
                        <span class="payment-label">Référence transaction</span>
                        <span class="payment-value">{{ $lastPayment->transaction_id ?? 'N/A' }}</span>
                    </div>
                    <div class="payment-item">
                        <span class="payment-label">Prochaine échéance</span>
                        <span class="payment-value">{{ \Carbon\Carbon::parse($lastPayment->end_date)->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Thank You Section --}}
            <div class="thankyou-section">
                <i class="bi bi-emoji-smile"></i>
                <h3>Merci pour votre confiance !</h3>
                <p>Cet abonnement vous donne accès à toutes les fonctionnalités premium de Inventix.</p>
            </div>

            {{-- Actions --}}
            <div class="invoice-footer">
                <a href="{{ route('subscription.show') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
                <div class="actions" style="display: flex; gap: 12px;">
                    <button onclick="window.print()" class="btn btn-outline">
                        <i class="bi bi-printer"></i> Imprimer
                    </button>
                    <a href="{{ route('invoices.download', $lastPayment->id) }}" class="btn btn-primary">
                        <i class="bi bi-download"></i> Télécharger PDF
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection