@extends('layouts.app')

@section('title', $tenant->company_name . ' — Super Admin')

@section('styles')
<style>
    :root {
        /* Orange */
        --orange:        #f97316;
        --orange-dark:   #ea580c;
        --orange-pale:   #fff7ed;
        --orange-soft:   #fed7aa;
        
        /* Blanc et gris */
        --white:         #ffffff;
        --gray-50:       #f9fafb;
        --gray-100:      #f3f4f6;
        --gray-200:      #e5e7eb;
        --gray-300:      #d1d5db;
        --gray-400:      #9ca3af;
        --gray-500:      #6b7280;
        --gray-600:      #4b5563;
        --gray-700:      #374151;
        --gray-800:      #1f2937;
        --gray-900:      #111827;
        
        /* Fond et bordures */
        --bg:            var(--gray-50);
        --card:          var(--white);
        --border:        var(--gray-200);
        --border-light:  var(--gray-100);
        
        /* Textes */
        --text:          var(--gray-900);
        --text-2:        var(--gray-600);
        --text-3:        var(--gray-400);
        
        /* États (uniquement gris et orange) */
        --success:       var(--orange);
        --danger:        var(--gray-600);
        --info:          var(--gray-500);
        
        /* Ombres */
        --shadow-sm:     0 1px 3px rgba(0,0,0,0.05);
        --shadow-md:     0 4px 16px rgba(0,0,0,0.05);
        --shadow-orange: 0 8px 24px rgba(249,115,22,0.15);
        
        /* Arrondis */
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
    .st-page {
        max-width: 1280px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .st-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        animation: fadeUp 0.35s ease both;
    }

    .st-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .st-hex {
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
    .st-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .st-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .st-title span {
        color: var(--orange);
        font-weight: 800;
    }
    .st-sub {
        font-size: 13px;
        color: var(--text-3);
        margin-top: 4px;
    }

    /* Boutons */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        border: none;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        text-decoration: none;
        cursor: pointer;
        box-shadow: var(--shadow-orange);
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    .btn-primary svg {
        width: 16px;
        height: 16px;
        stroke: #fff;
        fill: none;
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
        box-shadow: 0 12px 28px rgba(249,115,22,0.3);
    }

    .btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: transparent;
        border: 1.5px solid var(--border);
        border-radius: 40px;
        font-size: 12px;
        font-weight: 500;
        color: var(--text-2);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-outline svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        fill: none;
    }
    .btn-outline:hover {
        border-color: var(--orange);
        color: var(--orange);
        background: var(--orange-pale);
    }

    /* Alert */
    .st-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: var(--radius-sm);
        margin-bottom: 24px;
        animation: fadeUp 0.35s 0.07s ease both;
        border-left: 4px solid;
    }
    .st-alert svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
        flex-shrink: 0;
    }
    .st-alert-success {
        background: var(--orange-pale);
        border-color: var(--orange);
        color: var(--orange-dark);
    }
    .st-alert-error {
        background: var(--gray-100);
        border-color: var(--gray-400);
        color: var(--gray-600);
    }

    /* Stats Cards */
    .st-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1100px) { .st-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 580px)  { .st-stats { grid-template-columns: 1fr; } }

    .st-stat {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px 20px;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
        animation: fadeUp 0.35s ease both;
    }
    .st-stat:nth-child(2) { animation-delay:0.07s; }
    .st-stat:nth-child(3) { animation-delay:0.14s; }
    .st-stat:nth-child(4) { animation-delay:0.21s; }
    .st-stat:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--orange);
    }

    .st-stat-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .st-stat-ico {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .st-stat-ico svg {
        width: 22px;
        height: 22px;
        stroke: currentColor;
        fill: none;
    }
    .st-stat-info {
        flex: 1;
    }
    .st-stat-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .st-stat-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--text);
        line-height: 1.2;
    }

    /* Card */
    .st-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: border-color 0.2s;
        margin-bottom: 24px;
    }
    .st-card:hover {
        border-color: var(--orange);
    }

    .st-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        background: var(--gray-50);
    }
    .st-card-header svg {
        width: 22px;
        height: 22px;
        stroke: var(--orange);
        fill: none;
    }
    .st-card-header h2 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }
    .st-card-header .st-badge {
        margin-left: auto;
    }

    .st-card-body {
        padding: 24px;
    }

    /* Badge */
    .st-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .st-badge-success {
        background: var(--orange-pale);
        color: var(--orange-dark);
    }
    .st-badge-danger {
        background: var(--gray-100);
        color: var(--gray-600);
    }
    .st-badge-warning {
        background: #fef3c7;
        color: #92400e;
    }
    .st-badge-info {
        background: #dbeafe;
        color: #1e40af;
    }

    /* Info Grid */
    .st-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    @media (max-width: 768px) {
        .st-info-grid {
            grid-template-columns: 1fr;
        }
    }

    .st-info-item {
        display: flex;
        flex-direction: column;
    }
    .st-info-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .st-info-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--text);
        background: var(--gray-50);
        padding: 12px 16px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-light);
    }
    .st-info-value code {
        font-family: 'Monaco', 'Menlo', monospace;
        background: var(--white);
        padding: 2px 6px;
        border-radius: 4px;
        color: var(--orange);
    }

    /* Deux colonnes */
    .st-two-cols {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    @media (max-width: 900px) {
        .st-two-cols {
            grid-template-columns: 1fr;
        }
    }

    /* Table mini */
    .st-table {
        width: 100%;
        border-collapse: collapse;
    }
    .st-table th {
        padding: 8px 0;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-3);
    }
    .st-table td {
        padding: 12px 0;
        font-size: 14px;
        color: var(--text-2);
        border-bottom: 1px solid var(--border-light);
    }
    .st-table tr:last-child td {
        border-bottom: none;
    }

    /* Code */
    .st-code {
        font-family: 'Monaco', 'Menlo', monospace;
        font-size: 12px;
        background: var(--gray-100);
        padding: 4px 8px;
        border-radius: 6px;
        color: var(--orange);
    }

    /* Contact */
    .st-contact {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--text-2);
        margin-bottom: 4px;
    }
    .st-contact svg {
        width: 14px;
        height: 14px;
        stroke: var(--text-3);
        fill: none;
    }

    /* Avatar */
    .st-avatar {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 18px;
        box-shadow: var(--shadow-sm);
    }

    /* Product list */
    .st-product-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .st-product-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-light);
    }
    .st-product-item:last-child {
        border-bottom: none;
    }
    .st-product-name {
        font-weight: 600;
        color: var(--text);
    }
    .st-product-stock {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .st-product-stock-warning {
        background: #fef3c7;
        color: #92400e;
    }

    /* Sale item */
    .st-sale-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-light);
    }
    .st-sale-item:last-child {
        border-bottom: none;
    }
    .st-sale-info small {
        display: block;
        font-size: 11px;
        color: var(--text-3);
        margin-top: 2px;
    }
    .st-sale-amount {
        font-weight: 700;
        color: var(--orange);
    }

    /* Empty state */
    .st-empty {
        padding: 32px 24px;
        text-align: center;
    }
    .st-empty-ico {
        width: 56px;
        height: 56px;
        background: var(--gray-100);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
    }
    .st-empty-ico svg {
        width: 24px;
        height: 24px;
        stroke: var(--text-3);
        fill: none;
    }
    .st-empty p {
        font-size: 14px;
        color: var(--text-2);
    }

    /* Actions */
    .st-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 16px;
    }
    .st-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 40px;
        font-size: 13px;
        font-weight: 500;
        border: 1.5px solid var(--border);
        background: var(--card);
        color: var(--text-2);
        cursor: pointer;
        transition: all 0.15s;
    }
    .st-btn svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        fill: none;
    }
    .st-btn:hover {
        border-color: var(--orange);
        color: var(--orange);
        background: var(--orange-pale);
    }

    /* Back link */
    .st-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 16px;
        font-size: 13px;
        color: var(--text-2);
        text-decoration: none;
    }
    .st-back svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
    }
    .st-back:hover {
        color: var(--orange);
    }

    /* Security note */
    .st-security {
        margin-top: 40px;
        text-align: center;
        font-size: 12px;
        color: var(--text-3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 16px 0;
        border-top: 1px solid var(--border);
    }
    .st-security svg {
        width: 14px;
        height: 14px;
        stroke: var(--orange);
    }
</style>
@endsection

@section('content')
<div class="st-page">

    {{-- BACK LINK --}}
    <a href="{{ route('super-admin.tenants') }}" class="st-back">
        <svg viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Retour aux quincailleries
    </a>

    {{-- HEADER --}}
    <div class="st-header">
        <div class="st-header-l">
            <div class="st-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <div class="st-title">
                    {{ $tenant->company_name }}
                </div>
                <div class="st-sub">ID: {{ $tenant->id }} · Créé le {{ $tenant->created_at->format('d/m/Y') }}</div>
            </div>
        </div>
        <div class="st-tenant-status">
            @if($tenant->hasActiveSubscription())
                <span class="st-badge st-badge-success">Actif</span>
            @elseif($tenant->isExpired())
                <span class="st-badge st-badge-danger">Expiré</span>
            @elseif($tenant->isOverdue())
                <span class="st-badge st-badge-warning">En retard</span>
            @else
                <span class="st-badge st-badge-info">Essai</span>
            @endif
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="st-alert st-alert-success">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="st-alert st-alert-error">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- STATS CARDS --}}
    <div class="st-stats">
        <div class="st-stat">
            <div class="st-stat-content">
                <div class="st-stat-ico" style="background: var(--orange-pale); color: var(--orange);">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="st-stat-info">
                    <div class="st-stat-label">Utilisateurs</div>
                    <div class="st-stat-value">{{ $tenant->users_count ?? $tenant->users->count() ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="st-stat">
            <div class="st-stat-content">
                <div class="st-stat-ico" style="background: var(--orange-pale); color: var(--orange);">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="st-stat-info">
                    <div class="st-stat-label">Produits</div>
                    <div class="st-stat-value">{{ $stats['total_products'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="st-stat">
            <div class="st-stat-content">
                <div class="st-stat-ico" style="background: var(--orange-pale); color: var(--orange);">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="st-stat-info">
                    <div class="st-stat-label">Ventes</div>
                    <div class="st-stat-value">{{ $stats['total_sales'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="st-stat">
            <div class="st-stat-content">
                <div class="st-stat-ico" style="background: var(--orange-pale); color: var(--orange);">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="st-stat-info">
                    <div class="st-stat-label">CA Total</div>
                    <div class="st-stat-value">{{ number_format($stats['total_revenue'] ?? 0, 0, ',', ' ') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- DEUX COLONNES --}}
    <div class="st-two-cols">
        {{-- COLONNE GAUCHE --}}
        <div>
            {{-- INFORMATIONS ABONNEMENT --}}
            <div class="st-card">
                <div class="st-card-header">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <h2>Abonnement</h2>
                </div>
                <div class="st-card-body">
                    <div class="st-info-grid">
                        <div class="st-info-item">
                            <div class="st-info-label">Formule</div>
                            <div class="st-info-value">{{ $tenant->billing_cycle_label ?? 'Standard' }}</div>
                        </div>
                        <div class="st-info-item">
                            <div class="st-info-label">Prix</div>
                            <div class="st-info-value"><span style="color: var(--orange); font-weight: 700;">{{ $tenant->formatted_price ?? '0 FCFA' }}</span></div>
                        </div>
                        <div class="st-info-item">
                            <div class="st-info-label">Début</div>
                            <div class="st-info-value">{{ $tenant->subscription_start_date ? $tenant->subscription_start_date->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="st-info-item">
                            <div class="st-info-label">Fin</div>
                            <div class="st-info-value">
                                {{ $tenant->subscription_end_date ? $tenant->subscription_end_date->format('d/m/Y') : '-' }}
                                @if(method_exists($tenant, 'daysRemaining') && $tenant->daysRemaining() > 0)
                                    <span class="st-badge st-badge-warning" style="margin-left: 8px;">J-{{ $tenant->daysRemaining() }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="st-info-item">
                            <div class="st-info-label">Statut paiement</div>
                            <div class="st-info-value">
                                @switch($tenant->payment_status)
                                    @case('paid')
                                        <span class="st-badge st-badge-success">Payé</span>
                                        @break
                                    @case('pending')
                                        <span class="st-badge st-badge-warning">En attente</span>
                                        @break
                                    @case('overdue')
                                        <span class="st-badge st-badge-danger">En retard</span>
                                        @break
                                    @case('trial')
                                        <span class="st-badge st-badge-info">Essai</span>
                                        @break
                                    @default
                                        <span class="st-badge" style="background: var(--gray-100); color: var(--gray-600);">{{ $tenant->payment_status ?? '-' }}</span>
                                @endswitch
                            </div>
                        </div>
                    </div>

                    {{-- ACTIONS ABONNEMENT --}}
                    <div class="st-actions">
                        <button class="st-btn" onclick="markPayment()">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Marquer payé
                        </button>
                        <button class="st-btn" onclick="extendSubscription()">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Prolonger
                        </button>
                        <button class="st-btn" onclick="sendReminder()">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Envoyer rappel
                        </button>
                    </div>
                </div>
            </div>

            {{-- PROPRIÉTAIRE --}}
            <div class="st-card" style="margin-top: 24px;">
                <div class="st-card-header">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <h2>Équipe & Employés</h2>
                    <span class="st-badge" style="margin-left: auto; background: var(--orange-pale); color: var(--orange);">
                        {{ $tenant->users_count ?? $tenant->users->count() ?? 0 }} employés
                    </span>
                </div>
                <div class="st-card-body">
                    @if($tenant->owner)
                        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                            <div class="st-avatar">
                                {{ strtoupper(substr($tenant->owner->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 700; font-size: 18px; color: var(--text); margin-bottom: 4px;">
                                    {{ $tenant->owner->name }}
                                </div>
                                <div class="st-contact">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $tenant->owner->email }}
                                </div>
                                @if($tenant->owner->role)
                                    <span class="st-badge" style="background: var(--gray-100); color: var(--gray-600); margin-top: 4px; display: inline-block;">
                                        {{ $tenant->owner->role }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    {{-- BOUTONS DE GESTION DES EMPLOYÉS --}}
                    <div class="st-actions" style="margin-top: 16px;">
                        <a href="{{ route('super-admin.tenants.users', $tenant) }}" class="btn-primary" style="background: linear-gradient(135deg, var(--orange), var(--orange-dark));">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="width: 18px; height: 18px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Gérer les employés
                        </a>
                        <a href="{{ route('super-admin.tenants.users.create', $tenant) }}" class="btn-outline">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            + Ajouter un employé
                        </a>
                    </div>
                    
                    {{-- LISTE DES EMPLOYÉS RÉCENTS --}}
                    @if(isset($tenant->users) && $tenant->users->count() > 0)
                        <div style="margin-top: 24px;">
                            <div style="font-size: 13px; font-weight: 600; color: var(--text-2); margin-bottom: 12px;">
                                Derniers employés ajoutés
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                @foreach($tenant->users->take(3) as $user)
                                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-light);">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 32px; height: 32px; background: var(--gray-100); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--orange); font-weight: 600;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div style="font-weight: 500; font-size: 14px;">{{ $user->name }}</div>
                                                <div style="font-size: 11px; color: var(--text-3);">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="st-badge" style="background: var(--gray-100); color: var(--gray-600); font-size: 10px;">
                                                {{ $user->role }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if(($tenant->users->count() - 3) > 0)
                                <div style="margin-top: 12px; text-align: center;">
                                    <span class="st-badge" style="background: var(--gray-100); color: var(--text-2);">
                                        + {{ $tenant->users->count() - 3 }} autres employés
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- COLONNE DROITE --}}
        <div>
            {{-- STOCK FAIBLE --}}
            <div class="st-card">
                <div class="st-card-header">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h2>Stock faible</h2>
                    @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
                        <span class="st-badge st-badge-warning" style="margin-left: auto;">{{ $lowStockProducts->count() }}</span>
                    @endif
                </div>
                <div class="st-card-body">
                    @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
                        <div class="st-product-list">
                            @foreach($lowStockProducts as $product)
                                <div class="st-product-item">
                                    <div>
                                        <span class="st-product-name">{{ $product->name }}</span>
                                        <div style="font-size: 11px; color: var(--text-3); margin-top: 2px;">Ref: {{ $product->reference ?? '-' }}</div>
                                    </div>
                                    <span class="st-product-stock st-product-stock-warning">{{ $product->stock }} unités</span>
                                </div>
                            @endforeach
                        </div>
                        <div style="margin-top: 16px;">
                            <a href="{{ route('products.index') }}?stock=low" class="btn-outline">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Voir tous les produits
                            </a>
                        </div>
                    @else
                        <div class="st-empty">
                            <div class="st-empty-ico">
                                <svg viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p>Aucun produit en stock faible</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- DERNIÈRES VENTES --}}
            <div class="st-card" style="margin-top: 24px;">
                <div class="st-card-header">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h2>Dernières ventes</h2>
                    @if(isset($recentSales) && $recentSales->count() > 0)
                        <span class="st-badge" style="background: var(--gray-100); color: var(--gray-600); margin-left: auto;">{{ $recentSales->count() }}</span>
                    @endif
                </div>
                <div class="st-card-body">
                    @if(isset($recentSales) && $recentSales->count() > 0)
                        <div class="st-product-list">
                            @foreach($recentSales as $sale)
                                <div class="st-sale-item">
                                    <div class="st-sale-info">
                                        <strong>{{ $sale->client->name ?? 'Client inconnu' }}</strong>
                                        <small><i class="bi bi-clock"></i> {{ $sale->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <span class="st-sale-amount">{{ number_format($sale->total_price, 0, ',', ' ') }} FCFA</span>
                                </div>
                            @endforeach
                        </div>
                        <div style="margin-top: 16px;">
                            <a href="{{ route('sales.index') }}" class="btn-outline">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Voir toutes les ventes
                            </a>
                        </div>
                    @else
                        <div class="st-empty">
                            <div class="st-empty-ico">
                                <svg viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <p>Aucune vente enregistrée</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- INFORMATIONS SYSTÈME --}}
            <div class="st-card" style="margin-top: 24px;">
                <div class="st-card-header">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h2>Informations système</h2>
                </div>
                <div class="st-card-body">
                    <table class="st-table">
                        <tr>
                            <th>Base de données</th>
                            <td><code class="st-code">{{ $tenant->database ?? 'principale' }}</code></td>
                        </tr>
                        <tr>
                            <th>Sous-domaine</th>
                            <td><code class="st-code">{{ $tenant->subdomain }}.{{ config('app.domain') }}</code></td>
                        </tr>
                        <tr>
                            <th>Statut</th>
                            <td>
                                @if($tenant->is_active)
                                    <span class="st-badge st-badge-success">Actif</span>
                                @else
                                    <span class="st-badge st-badge-danger">Inactif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Créé le</th>
                            <td>{{ $tenant->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Dernière modif.</th>
                            <td>{{ $tenant->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    {{-- NOTE DE SÉCURITÉ --}}
    <div class="st-security">
        <svg viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        Informations confidentielles · Accès réservé aux super administrateurs
    </div>
</div>

<script>
function markPayment() {
    if(confirm('Marquer cet abonnement comme payé ?')) {
        alert('Fonction à implémenter : Marquer comme payé');
        // Ici vous appelleriez votre API
    }
}

function extendSubscription() {
    const days = prompt('Nombre de jours à ajouter ?', '30');
    if(days && !isNaN(days) && days > 0) {
        alert('Fonction à implémenter : Prolonger de ' + days + ' jours');
        // Ici vous appelleriez votre API
    }
}

function sendReminder() {
    if(confirm('Envoyer un rappel de paiement au propriétaire ?')) {
        alert('Fonction à implémenter : Envoyer rappel');
        // Ici vous appelleriez votre API
    }
}
</script>
@endsection