@extends('layouts.app')

@section('title', 'Ma facture - Sellvantix')

@section('styles')
<style>
    .invoice-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 24px;
    }

    .invoice-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }

    .invoice-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 32px;
        color: white;
        position: relative;
    }

    .invoice-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 8px;
    }

    .invoice-header p {
        color: rgba(255,255,255,0.7);
        margin: 0;
    }

    .invoice-body {
        padding: 32px;
    }

    .company-info {
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .company-name {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
    }

    .company-details {
        color: #64748b;
        font-size: 13px;
        margin-top: 8px;
    }

    .invoice-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .detail-label {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 4px;
    }

    .detail-value {
        font-size: 16px;
        font-weight: 600;
        color: #0f172a;
    }

    .amount-large {
        font-size: 42px;
        font-weight: 800;
        color: #f97316;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-paid {
        background: #dcfce7;
        color: #16a34a;
    }

    .info-box {
        background: #f8fafc;
        border-radius: 16px;
        padding: 24px;
        margin-top: 24px;
        text-align: center;
    }

    .btn-download {
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: white;
        padding: 12px 28px;
        border-radius: 40px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(249,115,22,0.3);
        color: white;
    }

    .btn-back {
        background: transparent;
        border: 1.5px solid #cbd5e1;
        color: #334155;
        padding: 12px 28px;
        border-radius: 40px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }

    .actions {
        display: flex;
        gap: 16px;
        justify-content: center;
        margin-top: 24px;
    }
</style>
@endsection

@section('content')
<div class="invoice-container">
    <div class="invoice-card">
        
        {{-- En-tête --}}
        <div class="invoice-header">
            <h1>Facture d'abonnement</h1>
            <p>Sellvantix - Gestion de entreprise</p>
        </div>

        {{-- Corps --}}
        <div class="invoice-body">
            
            {{-- Informations entreprise --}}
            <div class="company-info">
                <div class="company-name">{{ $tenant->company_name ?? 'Mon Entreprise' }}</div>
                <div class="company-details">
                    {{ $tenant->address ?? '' }}<br>
                    Tél: {{ $tenant->phone ?? '' }} | Email: {{ $tenant->email ?? '' }}
                </div>
            </div>

            {{-- Détails facture --}}
            <div class="invoice-details">
                <div>
                    <div class="detail-label">N° Facture</div>
                    <div class="detail-value">#{{ str_pad($lastPayment->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div>
                    <div class="detail-label">Date d'émission</div>
                    <div class="detail-value">{{ $lastPayment->created_at->format('d/m/Y') }}</div>
                </div>
                <div>
                    <div class="detail-label">Période concernée</div>
                    <div class="detail-value">
                        du {{ \Carbon\Carbon::parse($lastPayment->start_date)->format('d/m/Y') }}
                        au {{ \Carbon\Carbon::parse($lastPayment->end_date)->format('d/m/Y') }}
                    </div>
                </div>
                <div>
                    <div class="detail-label">Statut</div>
                    <div class="detail-value">
                        <span class="status-badge status-paid">
                            <i class="bi bi-check-circle-fill"></i> Payé
                        </span>
                    </div>
                </div>
            </div>

            {{-- Montant --}}
            <div style="text-align: center; padding: 24px 0;">
                <div class="detail-label">Montant total</div>
                <div class="amount-large">{{ number_format($lastPayment->amount, 0, ',', ' ') }} FCFA</div>
                <div style="font-size: 13px; color: #64748b; margin-top: 4px;">
                    TVA 0% incluse
                </div>
            </div>

            {{-- Détails abonnement --}}
            <div class="info-box">
                <i class="bi bi-calendar-check" style="font-size: 24px; color: #f97316;"></i>
                <h3 style="margin: 12px 0 8px; font-size: 18px;">Abonnement {{ ucfirst($lastPayment->plan_type) }}</h3>
                <p style="color: #64748b; margin: 0;">
                    Votre abonnement est actif jusqu'au {{ \Carbon\Carbon::parse($lastPayment->end_date)->format('d/m/Y') }}
                </p>
            </div>

            {{-- Actions --}}
            <div class="actions">
                <a href="{{ route('invoices.download', $lastPayment->id) }}" class="btn-download">
                    <i class="bi bi-download"></i> Télécharger PDF
                </a>
                <a href="{{ route('subscription.show') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

        </div>
    </div>
</div>
@endsection