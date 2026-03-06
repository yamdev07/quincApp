@extends('layouts.app')

@section('title', 'Facture #' . $sale->id . ' — QuincaApp')

@section('styles')
<style>
    :root {
        --orange:        #f97316;
        --orange-dark:   #ea580c;
        --orange-pale:   #fff7ed;
        --orange-soft:   #fed7aa;
        --bg:            #f1f5f9;
        --card:          #ffffff;
        --border:        #e2e8f0;
        --border-light:  #f1f5f9;
        --text:          #0f172a;
        --text-2:        #475569;
        --text-3:        #94a3b8;
        --success:       #16a34a;
        --danger:        #dc2626;
        --info:          #2563eb;
        --purple:        #7c3aed;
        --shadow-sm:     0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
        --shadow-md:     0 4px 16px rgba(15,23,42,.08);
        --shadow-orange: 0 8px 24px rgba(249,115,22,.25);
        --radius:        20px;
        --radius-sm:     12px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Inter', system-ui, sans-serif;
        background: var(--bg);
        color: var(--text);
        -webkit-font-smoothing: antialiased;
    }

    /* Animations */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Page */
    .sf-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .sf-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        animation: fadeUp 0.35s ease both;
    }

    .sf-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .sf-hex {
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-orange);
    }
    .sf-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .sf-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .sf-title span {
        color: var(--orange);
        font-weight: 800;
    }
    .sf-sub {
        font-size: 13px;
        color: var(--text-3);
        margin-top: 4px;
    }

    /* Boutons */
    .btn-primary, .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }
    .btn-primary {
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        color: #fff;
        box-shadow: var(--shadow-orange);
    }
    .btn-primary::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        transform: translateX(-100%);
        transition: transform 0.5s;
    }
    .btn-primary:hover::after { transform: translateX(100%); }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(249,115,22,0.4);
    }
    .btn-secondary {
        background: var(--card);
        border: 1.5px solid var(--border);
        color: var(--text-2);
    }
    .btn-secondary:hover {
        border-color: var(--orange);
        color: var(--orange);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }
    .btn-primary svg, .btn-secondary svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
    }

    /* Facture Card */
    .sf-invoice {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        padding: 48px;
        margin-bottom: 24px;
        animation: fadeUp 0.35s 0.07s ease both;
        position: relative;
    }

    /* En-tête facture */
    .sf-invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 48px;
        padding-bottom: 24px;
        border-bottom: 2px dashed var(--border);
    }

    .sf-invoice-ref h2 {
        font-size: 42px;
        font-weight: 800;
        color: var(--text);
        letter-spacing: -1px;
        line-height: 1;
        margin-bottom: 12px;
    }
    .sf-invoice-ref h2 span {
        color: var(--orange);
    }
    .sf-invoice-ref p {
        font-size: 14px;
        color: var(--text-2);
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 5px;
    }
    .sf-invoice-ref p svg {
        width: 16px;
        height: 16px;
        stroke: var(--text-3);
        fill: none;
    }

    .sf-invoice-logo {
        text-align: right;
    }
    .sf-logo {
        width: 72px;
        height: 72px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 0 12px auto;
        box-shadow: var(--shadow-orange);
    }
    .sf-logo svg {
        width: 32px;
        height: 32px;
        stroke: #fff;
        fill: none;
    }
    .sf-invoice-logo h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 3px;
    }
    .sf-invoice-logo p {
        font-size: 12px;
        color: var(--text-3);
    }

    /* Infos client/vendeur */
    .sf-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 40px;
    }
    @media (max-width: 700px) {
        .sf-info-grid { grid-template-columns: 1fr; }
    }

    .sf-info-box {
        background: #fafbfd;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 22px;
        transition: border-color 0.2s;
    }
    .sf-info-box:hover {
        border-color: var(--orange-soft);
    }

    .sf-info-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 18px;
    }
    .sf-info-title svg {
        width: 18px;
        height: 18px;
        stroke: var(--orange);
        fill: none;
    }

    .sf-info-content {
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }
    .sf-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }
    .av-client { background: linear-gradient(135deg, #60a5fa, #2563eb); }
    .av-vendor { background: linear-gradient(135deg, #a78bfa, #7c3aed); }

    .sf-info-details {
        flex: 1;
    }
    .sf-info-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }
    .sf-info-line {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        color: var(--text-2);
        margin-bottom: 4px;
    }
    .sf-info-line svg {
        width: 14px;
        height: 14px;
        stroke: var(--text-3);
        fill: none;
    }

    .sf-empty {
        color: var(--text-3);
        font-style: italic;
        font-size: 14px;
    }

    /* Tableau produits */
    .sf-products {
        margin-bottom: 40px;
    }
    .sf-products-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 18px;
    }
    .sf-products-title svg {
        width: 20px;
        height: 20px;
        stroke: var(--orange);
        fill: none;
    }

    .sf-table-wrap {
        overflow-x: auto;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-light);
    }
    .sf-table {
        width: 100%;
        border-collapse: collapse;
    }
    .sf-table thead th {
        padding: 14px 20px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-2);
        background: #f8fafc;
        border-bottom: 1px solid var(--border);
    }
    .sf-table tbody td {
        padding: 16px 20px;
        font-size: 13px;
        color: var(--text-2);
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }
    .sf-table tbody tr:last-child td {
        border-bottom: none;
    }
    .sf-table tbody tr {
        transition: background 0.15s;
    }
    .sf-table tbody tr:hover td {
        background: var(--orange-pale);
    }

    .sf-product {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sf-product-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f97316, #ea580c);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 16px;
    }
    .sf-product-info {
        flex: 1;
    }
    .sf-product-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 2px;
    }
    .sf-product-ref {
        font-size: 11px;
        color: var(--text-3);
    }

    .sf-qty-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #f1f5f9;
        color: var(--text-2);
        font-weight: 700;
        font-size: 14px;
        transition: all 0.15s;
    }
    tr:hover .sf-qty-badge {
        background: var(--orange-pale);
        color: var(--orange);
    }

    .sf-price {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
    }
    .sf-total {
        font-size: 16px;
        font-weight: 700;
        color: var(--orange);
    }

    /* Totaux */
    .sf-totals {
        background: #fafbfd;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 24px;
        margin-bottom: 40px;
    }
    .sf-totals-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
    @media (max-width: 700px) {
        .sf-totals-grid { grid-template-columns: 1fr; }
    }

    .sf-recap h4 {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 16px;
    }
    .sf-recap-line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 13px;
        color: var(--text-2);
    }
    .sf-recap-line strong {
        color: var(--text);
        font-weight: 700;
    }

    .sf-total-box {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 18px;
    }
    .sf-total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 13px;
        color: var(--text-2);
    }
    .sf-total-row.grand {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 2px dashed var(--border);
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
    }
    .sf-total-row.grand span:last-child {
        color: var(--orange);
    }

    /* Conditions */
    .sf-terms {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 48px;
        padding-top: 24px;
        border-top: 1px dashed var(--border);
    }
    @media (max-width: 700px) {
        .sf-terms { grid-template-columns: 1fr; }
    }

    .sf-term-block h4 {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 12px;
    }
    .sf-term-block p {
        font-size: 12px;
        color: var(--text-2);
        line-height: 1.6;
    }
    .sf-term-block ul {
        list-style: none;
        padding: 0;
    }
    .sf-term-block li {
        font-size: 12px;
        color: var(--text-2);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .sf-term-block li::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--orange);
    }

    /* Signatures */
    .sf-signatures {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 48px;
        padding-top: 32px;
        border-top: 1px solid var(--border);
    }
    .sf-signature {
        text-align: center;
    }
    .sf-signature-line {
        width: 200px;
        height: 40px;
        border-bottom: 2px solid var(--border);
        margin: 0 auto 12px;
    }
    .sf-signature p {
        font-size: 12px;
        color: var(--text-3);
    }

    /* Footer facture */
    .sf-footer {
        margin-top: 48px;
        padding-top: 24px;
        border-top: 1px dashed var(--border);
        text-align: center;
        font-size: 11px;
        color: var(--text-3);
    }
    .sf-footer strong {
        color: var(--orange);
        font-weight: 600;
    }

    /* Actions */
    .sf-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 16px;
        margin-top: 24px;
    }

    /* Print styles */
    @media print {
        body {
            background: #fff;
            padding: 0;
        }
        .sf-page {
            padding: 0;
            max-width: 100%;
        }
        .sf-header, .sf-actions, .btn-primary, .btn-secondary {
            display: none !important;
        }
        .sf-invoice {
            box-shadow: none;
            border: none;
            padding: 20px;
            margin: 0;
            animation: none;
        }
        .sf-logo, .sf-hex {
            box-shadow: none;
        }
        .sf-info-box, .sf-totals, .sf-table-wrap {
            break-inside: avoid;
        }
    }
</style>
@endsection

@section('content')
<div class="sf-page">

    {{-- HEADER --}}
    <div class="sf-header">
        <div class="sf-header-l">
            <div class="sf-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <div class="sf-title">
                    Facture <span>#{{ $sale->id }}</span>
                </div>
                <div class="sf-sub">Document officiel de vente</div>
            </div>
        </div>
    </div>

    {{-- FACTURE --}}
    <div id="invoice" class="sf-invoice">

        {{-- En-tête facture --}}
        <div class="sf-invoice-header">
            <div class="sf-invoice-ref">
                <h2>FACTURE <span>#{{ $sale->id }}</span></h2>
                <p>
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Date: {{ $sale->created_at->format('d/m/Y') }}
                </p>
                <p>
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Heure: {{ $sale->created_at->format('H:i:s') }}
                </p>
            </div>
            <div class="sf-invoice-logo">
                <div class="sf-logo">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3>QuincaApp</h3>
                <p>Votre partenaire de confiance</p>
            </div>
        </div>

        {{-- Infos client & vendeur --}}
        <div class="sf-info-grid">
            {{-- Client --}}
            <div class="sf-info-box">
                <div class="sf-info-title">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    CLIENT
                </div>
                @if($sale->client)
                    <div class="sf-info-content">
                        <div class="sf-avatar av-client">{{ substr($sale->client->name, 0, 1) }}</div>
                        <div class="sf-info-details">
                            <div class="sf-info-name">{{ $sale->client->name }}</div>
                            @if($sale->client->email)
                                <div class="sf-info-line">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $sale->client->email }}</span>
                                </div>
                            @endif
                            @if($sale->client->phone)
                                <div class="sf-info-line">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span>{{ $sale->client->phone }}</span>
                                </div>
                            @endif
                            @if($sale->client->address)
                                <div class="sf-info-line">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $sale->client->address }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="sf-empty">Client non spécifié</div>
                @endif
            </div>

            {{-- Vendeur --}}
            <div class="sf-info-box">
                <div class="sf-info-title">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    VENDEUR
                </div>
                <div class="sf-info-content">
                    <div class="sf-avatar av-vendor">{{ substr($sale->user->name, 0, 1) }}</div>
                    <div class="sf-info-details">
                        <div class="sf-info-name">{{ $sale->user->name }}</div>
                        <div class="sf-info-line">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $sale->user->email }}</span>
                        </div>
                        <div class="sf-info-line">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="capitalize">{{ $sale->user->role }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Produits --}}
        <div class="sf-products">
            <div class="sf-products-title">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                DÉTAIL DES PRODUITS
            </div>
            <div class="sf-table-wrap">
                <table class="sf-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produit</th>
                            <th>Prix unitaire</th>
                            <th>Quantité</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="sf-product">
                                        <div class="sf-product-avatar">
                                            {{ substr($item->product->name ?? '?', 0, 1) }}
                                        </div>
                                        <div class="sf-product-info">
                                            <div class="sf-product-name">{{ $item->product->name ?? 'Produit supprimé' }}</div>
                                            @if($item->product->reference ?? false)
                                                <div class="sf-product-ref">Réf: {{ $item->product->reference }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="sf-price">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</span>
                                </td>
                                <td>
                                    <span class="sf-qty-badge">{{ $item->quantity }}</span>
                                </td>
                                <td>
                                    <span class="sf-total">{{ number_format($item->total_price, 0, ',', ' ') }} FCFA</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Totaux --}}
        <div class="sf-totals">
            <div class="sf-totals-grid">
                <div class="sf-recap">
                    <h4>Récapitulatif</h4>
                    <div class="sf-recap-line">
                        <span>Nombre d'articles:</span>
                        <strong>{{ $totalQuantity }}</strong>
                    </div>
                    <div class="sf-recap-line">
                        <span>Articles différents:</span>
                        <strong>{{ $sale->items->count() }}</strong>
                    </div>
                </div>
                <div class="sf-total-box">
                    <div class="sf-total-row">
                        <span>Sous-total:</span>
                        <span>{{ number_format($sale->total_price, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="sf-total-row">
                        <span>TVA (0%):</span>
                        <span>0 FCFA</span>
                    </div>
                    <div class="sf-total-row grand">
                        <span>TOTAL:</span>
                        <span>{{ number_format($sale->total_price, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Conditions --}}
        <div class="sf-terms">
            <div class="sf-term-block">
                <h4>Conditions de paiement</h4>
                <ul>
                    <li>Paiement comptant</li>
                    <li>La facture est payable immédiatement</li>
                    <li>Aucun escompte pour paiement anticipé</li>
                </ul>
            </div>
            <div class="sf-term-block">
                <h4>Remarques</h4>
                <p>Merci pour votre confiance. Les produits vendus ne sont ni repris ni échangés.</p>
            </div>
        </div>

        {{-- Signatures --}}
        <div class="sf-signatures">
            <div class="sf-signature">
                <div class="sf-signature-line"></div>
                <p>Signature du client</p>
            </div>
            <div class="sf-signature">
                <div class="sf-signature-line"></div>
                <p>Signature du vendeur</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="sf-footer">
            <strong>QuincaApp</strong> • 123 Rue Principale, Ville • +225 XX XX XX XX • contact@quincaapp.com<br>
            Facture générée le {{ now()->format('d/m/Y à H:i') }}
        </div>
    </div>

    {{-- ACTIONS --}}
    <div class="sf-actions">
        <button onclick="window.print()" class="btn-primary">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Imprimer la facture
        </button>
        <a href="{{ route('sales.show', $sale->id) }}" class="btn-secondary">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour aux détails
        </a>
        <button onclick="downloadAsPDF()" class="btn-primary">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Télécharger PDF
        </button>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadAsPDF() {
    const button = event.currentTarget;
    const originalHTML = button.innerHTML;
    
    button.innerHTML = `
        <svg class="animate-spin" viewBox="0 0 24 24" stroke-width="2" style="width:16px;height:16px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        Génération PDF...
    `;
    button.disabled = true;
    
    const element = document.getElementById('invoice');
    const opt = {
        margin:       10,
        filename:     'facture-{{ $sale->id }}-{{ date("Y-m-d") }}.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, letterRendering: true },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };
    
    html2pdf().set(opt).from(element).save().then(() => {
        button.innerHTML = originalHTML;
        button.disabled = false;
    });
}
</script>

{{-- Styles d'impression supplémentaires --}}
<style>
@media print {
    .animate-spin, .btn-primary, .btn-secondary {
        display: none !important;
    }
    body {
        background: white;
    }
}
</style>
@endsection