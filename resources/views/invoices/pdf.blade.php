<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 2cm;
            footer: html_footer;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            color: #1e293b;
            line-height: 1.5;
            font-size: 12px;
        }
        
        .invoice-container {
            max-width: 100%;
        }
        
        /* Header */
        .header {
            background: #0f172a;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 8px;
            color: white;
            position: relative;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .logo h1 {
            margin: 0 0 5px;
            font-size: 24px;
        }
        
        .logo p {
            margin: 0;
            opacity: 0.7;
            font-size: 11px;
        }
        
        .invoice-number {
            text-align: right;
        }
        
        .invoice-number .title {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }
        
        .invoice-number .number {
            color: #f97316;
            font-size: 14px;
            margin-top: 5px;
        }
        
        /* Info Grid */
        .info-grid {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .info-box {
            flex: 1;
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
        }
        
        .info-box h3 {
            font-size: 11px;
            color: #64748b;
            margin: 0 0 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .details {
            margin-bottom: 30px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .detail-label {
            color: #64748b;
        }
        
        .detail-value {
            font-weight: 600;
        }
        
        .amount-box {
            background: linear-gradient(135deg, #fff7ed, #ffffff);
            border: 1px solid #fed7aa;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            margin: 30px 0;
        }
        
        .amount-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .amount {
            font-size: 42px;
            font-weight: bold;
            color: #f97316;
            margin: 10px 0;
        }
        
        .status {
            display: inline-block;
            background: #d1fae5;
            color: #10b981;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .thankyou {
            text-align: center;
            padding: 30px;
            background: #f8fafc;
            border-radius: 12px;
            margin-top: 30px;
        }
        
        .thankyou h3 {
            color: #0f172a;
            margin: 0 0 10px;
        }
        
        .footer {
            position: running(footer);
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        
        html_footer {
            position: running(footer);
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        
        {{-- Header --}}
        <div class="header">
            <div class="header-content">
                <div class="logo">
                    <h1>Sellvantix</h1>
                    <p>Solution de gestion professionnelle</p>
                </div>
                <div class="invoice-number">
                    <div class="title">FACTURE</div>
                    <div class="number">N° {{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>
        </div>
        
        {{-- Info Grid --}}
        <div class="info-grid">
            <div class="info-box">
                <h3>ÉMETTEUR</h3>
                <div class="company-name">{{ $tenant->company_name ?? 'Sellvantix' }}</div>
                <div>{{ $tenant->address ?? '123 Avenue du Commerce' }}</div>
                <div>Tél: {{ $tenant->phone ?? '+225 XX XX XX XX' }}</div>
                <div>Email: {{ $tenant->email ?? 'contact@quincapro.com' }}</div>
            </div>
            <div class="info-box">
                <h3>CLIENT</h3>
                <div class="company-name">{{ $tenant->company_name ?? 'Client' }}</div>
                <div>{{ $tenant->address ?? '' }}</div>
                <div>Tél: {{ $tenant->phone ?? '' }}</div>
                <div>Email: {{ $tenant->email ?? '' }}</div>
            </div>
        </div>
        
        {{-- Details --}}
        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Date d'émission</span>
                <span class="detail-value">{{ $payment->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Période d'abonnement</span>
                <span class="detail-value">
                    Du {{ Carbon\Carbon::parse($payment->start_date)->format('d/m/Y') }}
                    au {{ Carbon\Carbon::parse($payment->end_date)->format('d/m/Y') }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Type d'abonnement</span>
                <span class="detail-value">{{ ucfirst($payment->plan_type) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Statut</span>
                <span class="detail-value"><span class="status">✓ PAYÉ</span></span>
            </div>
        </div>
        
        {{-- Amount --}}
        <div class="amount-box">
            <div class="amount-label">Montant total</div>
            <div class="amount">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</div>
            <div style="font-size: 10px; color:#64748b;">TVA 0% (exonéré)</div>
        </div>
        
        {{-- Thank You --}}
        <div class="thankyou">
            <h3>Merci pour votre confiance !</h3>
            <p>Cet abonnement vous donne accès à toutes les fonctionnalités premium de Sellvantix.</p>
        </div>
        
        {{-- Footer --}}
        <div class="footer">
            Sellvantix - Solution de gestion de stock<br>
            {{ now()->year }} © Tous droits réservés
        </div>
        
    </div>
</body>
</html>