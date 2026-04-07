@extends('layouts.app')

@section('title', 'Dashboard - Pilotage Quincaillerie')

@section('styles')
<style>
    /* -----------------------------------------------------
       VARIABLES - NOIR & ORANGE (COULEURS PRINCIPALES)
    ----------------------------------------------------- */
    :root {
        --bg-page: #f8fafc;
        --bg-card: #ffffff;
        --bg-side: #f1f5f9;
        --border-light: #e2e8f0;
        --border-soft: #cbd5e1;
        
        --text-primary: #0f172a;
        --text-secondary: #334155;
        --text-tertiary: #64748b;
        
        --accent: #f97316;
        --accent-dark: #ea580c;
        --accent-light: #ffedd5;
        --accent-soft: #fed7aa;
        --accent-gradient: linear-gradient(135deg, #f97316, #ea580c);
        
        --gradient-dark: linear-gradient(145deg, #0f172a, #1e293b);
        
        --badge-low: #b91c1c;
        --badge-bg-low: #fee2e2;
        --badge-warning: #f97316;
        --badge-bg-warning: #fff3cd;
        
        --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        --shadow-hover: 0 10px 15px -3px rgba(249, 115, 22, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    * { box-sizing: border-box; }

    body {
        background: var(--bg-page);
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--text-primary);
    }

    .dashboard {
        max-width: 1440px;
        margin: 0 auto;
        padding: 32px 24px;
    }

    .dash-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .dash-header-left h1 {
        font-size: 32px;
        font-weight: 700;
        background: linear-gradient(135deg, #0f172a 0%, #f97316 80%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.5px;
        margin: 0 0 6px;
    }

    .dash-header-left .greeting {
        color: var(--text-tertiary);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .greeting-role {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: white;
        padding: 4px 14px;
        border-radius: 30px;
        font-weight: 500;
        font-size: 13px;
        border: 1px solid rgba(249, 115, 22, 0.3);
    }

    .greeting-date {
        background: var(--accent-light);
        color: var(--accent-dark);
        padding: 4px 14px;
        border-radius: 30px;
        font-weight: 500;
        font-size: 13px;
    }

    .dash-header-right {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: var(--accent-gradient);
        color: white;
        border: none;
        padding: 0 24px;
        height: 46px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 8px 16px rgba(249, 115, 22, 0.25);
    }
    .btn-primary i { font-size: 18px; }
    .btn-primary:hover {
        background: linear-gradient(135deg, #ea580c, #c2410c);
        transform: translateY(-2px);
    }

    .btn-outline {
        background: transparent;
        border: 1.5px solid var(--border-soft);
        color: var(--text-primary);
        height: 46px;
        padding: 0 22px;
        border-radius: 40px;
        font-weight: 500;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-outline i { color: var(--accent); }
    .btn-outline:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent-dark);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 22px;
        margin-bottom: 32px;
    }
    @media (min-width: 580px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .stats-grid { grid-template-columns: repeat(4, 1fr); } }

    .stat-card {
        background: var(--bg-card);
        border-radius: 24px;
        padding: 24px 22px;
        box-shadow: var(--shadow-card);
        border: 1px solid var(--border-light);
        transition: all 0.25s;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        box-shadow: var(--shadow-hover);
        border-color: var(--accent);
        transform: translateY(-2px);
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--accent-gradient);
        opacity: 0;
        transition: opacity 0.2s;
    }
    .stat-card:hover::before { opacity: 1; }

    .stat-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        background: var(--accent-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 26px;
        transition: all 0.2s;
    }
    .stat-card:hover .stat-icon {
        background: var(--accent);
        color: white;
    }

    .stat-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-tertiary);
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.1;
        margin-bottom: 6px;
    }

    .stat-desc {
        font-size: 13px;
        color: var(--text-tertiary);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .trend-up { color: var(--accent); font-weight: 600; }

    .stat-card.stock-warning {
        background: linear-gradient(145deg, #fff7ed, #ffedd5);
        border-left: 5px solid var(--accent);
    }

    .main-panel {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }
    @media (min-width: 1024px) {
        .main-panel { grid-template-columns: 1.6fr 1fr; }
    }

    .chart-card {
        background: var(--bg-card);
        border-radius: 24px;
        border: 1px solid var(--border-light);
        padding: 22px 22px 18px;
        box-shadow: var(--shadow-card);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .chart-header h3 {
        font-weight: 700;
        font-size: 18px;
        color: var(--text-primary);
        margin: 0;
        position: relative;
        padding-left: 12px;
    }
    .chart-header h3::before {
        content: '';
        position: absolute;
        left: 0;
        top: 2px;
        bottom: 2px;
        width: 4px;
        background: var(--accent-gradient);
        border-radius: 4px;
    }

    .chart-actions select {
        background: var(--bg-side);
        border: 1px solid var(--border-soft);
        border-radius: 40px;
        padding: 8px 30px 8px 16px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-primary);
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23f97316' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
    }

    .chart-container {
        position: relative;
        height: 280px;
        width: 100%;
    }
    #salesChart { width: 100%; height: 100%; }

    .chart-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 8px;
        z-index: 5;
        border-radius: 16px;
        backdrop-filter: blur(2px);
    }
    .spinner {
        width: 40px; height: 40px;
        border: 3px solid #dde2e9;
        border-top-color: var(--accent);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .info-sidebar {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .info-card {
        background: var(--bg-card);
        border-radius: 24px;
        border: 1px solid var(--border-light);
        padding: 24px 22px;
        box-shadow: var(--shadow-card);
    }
    .info-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .info-card-header h4 {
        font-size: 17px;
        font-weight: 700;
        margin: 0;
        color: var(--text-primary);
    }
    .info-badge {
        background: var(--accent-gradient);
        color: white;
        padding: 4px 14px;
        border-radius: 40px;
        font-size: 12px;
        font-weight: 600;
    }

    .avg-sale-large {
        font-size: 48px;
        font-weight: 800;
        background: linear-gradient(135deg, #0f172a, #f97316);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.1;
        margin: 10px 0 5px;
    }
    .avg-sale-desc { 
        color: var(--text-tertiary); 
        font-size: 14px;
        font-weight: 500;
    }

    .stock-status-block {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }
    .stock-count-big {
        font-size: 56px;
        font-weight: 800;
        color: var(--accent);
        line-height: 1;
    }
    .stock-message {
        font-weight: 600;
        color: var(--text-primary);
    }

    .quick-links-list {
        list-style: none;
        padding: 0;
        margin: 10px 0 0;
    }
    .quick-links-list li { margin-bottom: 8px; }
    .quick-links-list a {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: 18px;
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
        border: 1px solid var(--border-light);
    }
    .quick-links-list a:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent-dark);
        transform: translateX(6px);
        box-shadow: var(--shadow-hover);
    }
    .quick-links-list i { 
        font-size: 22px; 
        width: 30px; 
        color: var(--accent); 
    }

    .tabs-minimal {
        display: flex;
        gap: 32px;
        border-bottom: 2px solid var(--border-light);
        margin: 0 0 24px 0;
    }
    .tab-minimal {
        padding: 14px 2px 12px;
        font-weight: 700;
        color: var(--text-tertiary);
        border-bottom: 3px solid transparent;
        cursor: pointer;
        transition: 0.2s;
        font-size: 15px;
    }
    .tab-minimal.active {
        color: var(--accent);
        border-bottom-color: var(--accent);
    }

    .twin-tables {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
        margin-top: 16px;
    }
    @media (min-width: 1024px) {
        .twin-tables { grid-template-columns: 1fr 1fr; }
    }

    .table-card-mini {
        background: var(--bg-card);
        border-radius: 24px;
        border: 1px solid var(--border-light);
        overflow: hidden;
        box-shadow: var(--shadow-card);
    }

    .table-header-mini {
        padding: 18px 24px;
        border-bottom: 1px solid var(--border-light);
        background: linear-gradient(145deg, #ffffff, #f8fafc);
    }
    .table-header-mini h3 {
        font-weight: 700;
        font-size: 17px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-primary);
    }
    .badge-count {
        background: var(--accent-gradient);
        color: white;
        border-radius: 40px;
        padding: 3px 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .table-responsive { overflow-x: auto; }
    .table-mini {
        width: 100%;
        border-collapse: collapse;
    }
    .table-mini th {
        text-align: left;
        padding: 16px 24px 10px;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border-bottom: 1px solid var(--border-light);
        background: #f9fafb;
    }
    .table-mini td {
        padding: 16px 24px;
        border-bottom: 1px solid #f1f3f5;
        color: var(--text-primary);
        font-size: 14px;
        font-weight: 500;
    }
    .table-mini tr:hover td { background: var(--accent-light); }
    .table-mini tr:last-child td { border-bottom: none; }

    .badge-stock {
        display: inline-block;
        padding: 5px 16px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 12px;
    }
    .badge-critical {
        background: var(--badge-bg-low);
        color: var(--badge-low);
    }
    .badge-warning {
        background: var(--badge-bg-warning);
        color: var(--accent-dark);
        border: 1px solid var(--accent);
    }

    .text-muted-small {
        color: var(--text-tertiary);
        font-style: italic;
    }

    .security-note {
        margin-top: 40px;
        text-align: center;
        color: var(--text-tertiary);
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 16px 0;
        border-top: 1px solid var(--border-light);
    }
    .security-note i { color: var(--accent); }

    .text-accent { color: var(--accent); }
    .bg-accent-light { background: var(--accent-light); }
    .border-accent { border-color: var(--accent); }

    .user-company-info {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f1f5f9;
        padding: 4px 12px;
        border-radius: 40px;
        font-size: 13px;
    }
    .user-company-info i { color: var(--accent); }
</style>
@endsection

@section('content')
<div class="dashboard">

    {{-- HEADER AVEC INFOS UTILISATEUR --}}
    <div class="dash-header">
        <div class="dash-header-left">
            <h1>Tableau de bord</h1>
            <div class="greeting">
                <span>👋 {{ auth()->user()->name }}, content de vous revoir</span>
                @if(auth()->user()->tenant)
                    <span class="user-company-info">
                        <i class="bi bi-building"></i> {{ auth()->user()->tenant->company_name ?? 'Ma Quincaillerie' }}
                    </span>
                @endif
                <span class="greeting-role">
                    @if(auth()->user()->isSuperAdmin())
                        <i class="bi bi-crown"></i> Super Admin
                    @elseif(auth()->user()->isAdmin())
                        <i class="bi bi-shield"></i> Administrateur
                    @elseif(auth()->user()->isManager())
                        <i class="bi bi-person-workspace"></i> Gérant
                    @elseif(auth()->user()->isCashier())
                        <i class="bi bi-cart"></i> Caissier
                    @elseif(auth()->user()->isStorekeeper())
                        <i class="bi bi-boxes"></i> Magasinier
                    @endif
                </span>
                <span class="greeting-date">{{ now()->locale('fr')->isoFormat('dddd D MMMM') }}</span>
            </div>
        </div>
        <div class="dash-header-right">
            @if(auth()->user()->canManageSales())
                <a href="{{ route('sales.create') }}" class="btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouvelle vente
                </a>
            @endif
            @if(auth()->user()->canManageUsers())
                <a href="{{ route('users.index') }}" class="btn-outline">
                    <i class="bi bi-people"></i> Équipe
                </a>
            @endif
            @if(auth()->user()->isSuperAdminGlobal())
                <a href="{{ route('super-admin.tenants') }}" class="btn-outline" title="Toutes les quincailleries">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </a>
            @endif
        </div>
    </div>

    {{-- ===================================================== --}}
    {{-- CARTE ABONNEMENT STYLE DASHBOARD --}}
    {{-- ===================================================== --}}
    @php
        $user = Auth::user();
        $tenant = $user->tenant;
        
        if ($tenant) {
            $subscriptionStatus = $tenant->payment_status;
            $subscriptionEndDate = $tenant->subscription_end_date ? \Carbon\Carbon::parse($tenant->subscription_end_date) : null;
            $trialEndDate = $tenant->trial_ends_at ? \Carbon\Carbon::parse($tenant->trial_ends_at) : null;
            $subscriptionStartDate = $tenant->subscription_start_date ? \Carbon\Carbon::parse($tenant->subscription_start_date) : null;
            
            $daysRemaining = 0;
            $isExpiringSoon = false;
            $expiryDate = null;
            
            if ($subscriptionStatus === 'paid' && $subscriptionEndDate) {
                $daysRemaining = now()->diffInDays($subscriptionEndDate, false);
                $isExpiringSoon = $daysRemaining <= 7 && $daysRemaining > 0;
                $expiryDate = $subscriptionEndDate;
            } elseif ($subscriptionStatus === 'trial' && $trialEndDate) {
                $daysRemaining = now()->diffInDays($trialEndDate, false);
                $isExpiringSoon = $daysRemaining <= 3 && $daysRemaining > 0;
                $expiryDate = $trialEndDate;
            }
            
            $totalDays = $subscriptionStartDate && $expiryDate 
                ? $subscriptionStartDate->diffInDays($expiryDate) 
                : 90;
            $elapsed = $subscriptionStartDate 
                ? $subscriptionStartDate->diffInDays(now()) 
                : 0;
            $pct = $totalDays > 0 ? min(100, round(($elapsed / $totalDays) * 100)) : 0;
            $fillColor = $pct > 80 ? '#ef4444' : '#f97316';
        }
    @endphp

    @if($tenant)
    <div style="background: linear-gradient(145deg, #0f172a 0%, #1a2540 100%); border-radius: 20px; border: 1px solid rgba(249,115,22,0.18); overflow: hidden; margin-bottom: 24px;">

        {{-- Top --}}
        <div style="display:flex; justify-content:space-between; align-items:center; padding:18px 22px 14px; border-bottom:1px solid rgba(255,255,255,0.06);">
            <span style="font-size:11px; font-weight:700; letter-spacing:1.2px; color:rgba(255,255,255,0.4); text-transform:uppercase;">
                <i class="bi bi-calendar-check" style="opacity:0.5"></i> Mon abonnement
            </span>
            <span style="display:flex; align-items:center; gap:5px; background:rgba(34,197,94,0.12); border:1px solid rgba(34,197,94,0.25); color:#4ade80; padding:4px 12px; border-radius:30px; font-size:11px; font-weight:700;">
                @if($subscriptionStatus === 'paid')
                    <span style="width:6px;height:6px;background:#22c55e;border-radius:50%;display:inline-block;"></span> ACTIF
                @else
                    <span style="width:6px;height:6px;background:#f97316;border-radius:50%;display:inline-block;"></span> ESSAI
                @endif
            </span>
        </div>

        {{-- Body : 2 colonnes --}}
        <div style="display:grid; grid-template-columns:1fr 1px 1fr;">
            <div style="padding:18px 22px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                    <span style="font-size:12px;color:rgba(255,255,255,0.38);font-weight:500;">Formule</span>
                    <span style="font-size:13px;color:rgba(255,255,255,0.88);font-weight:600;">{{ ucfirst($tenant->billing_cycle ?? 'Mensuel') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                    <span style="font-size:12px;color:rgba(255,255,255,0.38);font-weight:500;">Prix</span>
                    <span style="font-size:13px;color:#f97316;font-weight:600;">{{ number_format($tenant->subscription_price ?? 0, 0, ',', ' ') }} FCFA</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                    <span style="font-size:12px;color:rgba(255,255,255,0.38);font-weight:500;">Cycle</span>
                    <span style="font-size:13px;color:rgba(255,255,255,0.88);font-weight:600;">{{ ucfirst($tenant->billing_cycle ?? 'Mensuel') }}</span>
                </div>
            </div>
            <div style="background:rgba(255,255,255,0.06);"></div>
            <div style="padding:18px 22px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                    <span style="font-size:12px;color:rgba(255,255,255,0.38);font-weight:500;">Expiration</span>
                    <span style="font-size:13px;color:#f97316;font-weight:600;">{{ $expiryDate ? $expiryDate->format('d/m/Y') : 'N/A' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                    <span style="font-size:12px;color:rgba(255,255,255,0.38);font-weight:500;">Prochain paiement</span>
                    <span style="font-size:13px;color:#f97316;font-weight:600;">{{ $expiryDate ? $expiryDate->format('d/m/Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>

        {{-- Barre de progression --}}
        <div style="padding:0 22px 18px;">
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="font-size:11px;color:rgba(255,255,255,0.35);">Validité de la période en cours</span>
                <span style="font-size:12px;color:rgba(255,255,255,0.55);font-weight:600;">
                    <span style="color:#f97316;">{{ max(0, $daysRemaining) }}</span> jours restants
                </span>
            </div>
            <div style="height:5px;background:rgba(255,255,255,0.07);border-radius:10px;overflow:hidden;">
                <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,{{ $fillColor }},{{ $fillColor }}cc);border-radius:10px;"></div>
            </div>
        </div>

        {{-- Footer --}}
        <div style="background:rgba(249,115,22,0.06);border-top:1px solid rgba(249,115,22,0.12);padding:13px 22px;display:flex;align-items:center;justify-content:space-between;gap:12px;">
            <span style="font-size:12px;color:rgba(255,255,255,0.45);">
                <i class="bi bi-info-circle" style="color:#f97316;margin-right:5px;"></i>
                Expire le <strong style="color:rgba(255,255,255,0.7);">{{ $expiryDate ? $expiryDate->format('d/m/Y') : 'N/A' }}</strong> — Renouvelez avant expiration pour éviter toute interruption
            </span>
            @if($isExpiringSoon)
            <a href="{{ route('payment.form') }}" style="background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;border:none;padding:8px 18px;border-radius:30px;font-size:12px;font-weight:700;white-space:nowrap;text-decoration:none;transition:all 0.2s;">
                Renouveler
            </a>
            @endif
        </div>

    </div>
    @endif

    {{-- STATS CARTES --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-row">
                <span class="stat-title">Ventes aujourd'hui</span>
                <span class="stat-icon"><i class="bi bi-cart3"></i></span>
            </div>
            <div class="stat-value" id="salesToday">{{ $salesToday ?? 0 }}</div>
            <div class="stat-desc">
                <i class="bi bi-arrow-up-short trend-up"></i>
                <span>{{ $salesYesterday ?? 0 }} hier</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-row">
                <span class="stat-title">Chiffre d'affaires</span>
                <span class="stat-icon"><i class="bi bi-currency-dollar"></i></span>
            </div>
            <div class="stat-value" id="totalRevenue">{{ number_format($totalRevenue ?? 0, 0, ',', ' ') }} FCFA</div>
            <div class="stat-desc">Cumulé</div>
        </div>

        <div class="stat-card {{ ($lowStockCount ?? 0) > 0 ? 'stock-warning' : '' }}">
            <div class="stat-row">
                <span class="stat-title">Alertes stock</span>
                <span class="stat-icon"><i class="bi bi-exclamation-triangle"></i></span>
            </div>
            <div class="stat-value" id="lowStockCount">{{ $lowStockCount ?? 0 }}</div>
            <div class="stat-desc">produits à réapprovisionner</div>
        </div>

        <div class="stat-card">
            <div class="stat-row">
                <span class="stat-title">Clients actifs</span>
                <span class="stat-icon"><i class="bi bi-person-badge"></i></span>
            </div>
            <div class="stat-value" id="activeClients">{{ $activeClients ?? 0 }}</div>
            <div class="stat-desc">ce mois-ci</div>
        </div>
    </div>

    {{-- PANEL PRINCIPAL : GRAPHE + INFOS DROITE --}}
    <div class="main-panel">
        <div class="chart-card">
            <div class="chart-header">
                <h3>Évolution des ventes</h3>
                <div class="chart-actions">
                    <select id="chartPeriod">
                        <option value="7">7 derniers jours</option>
                        <option value="30">30 derniers jours</option>
                        <option value="90">3 mois</option>
                    </select>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="salesChart"></canvas>
                <div class="chart-overlay" id="chartLoading" style="display: none;">
                    <div class="spinner"></div>
                    <span>Chargement...</span>
                </div>
                <div class="chart-overlay" id="chartError" style="display: none;">
                    <i class="bi bi-exclamation-triangle" style="font-size: 32px;color:#f97316;"></i>
                    <span>Erreur de chargement</span>
                    <button onclick="window.location.reload()" style="background:var(--accent-gradient);color:#fff;border:none;border-radius:40px;padding:8px 20px;margin-top:8px;font-weight:600;cursor:pointer;">Actualiser</button>
                </div>
            </div>
        </div>

        <div class="info-sidebar">
            <div class="info-card">
                <div class="info-card-header">
                    <h4>Vente moyenne</h4>
                    <span class="info-badge">aujourd'hui</span>
                </div>
                <div class="avg-sale-large" id="averageSale">
                    {{ $totalRevenue && $salesToday ? number_format($totalRevenue / max($salesToday,1), 0, ',', ' ') : 0 }} FCFA
                </div>
                <div class="avg-sale-desc">par transaction</div>
            </div>

            <div class="info-card">
                <div class="info-card-header">
                    <h4>État du stock</h4>
                </div>
                <div class="stock-status-block">
                    @if(($lowStockCount ?? 0) === 0)
                        <span style="font-size:48px;color:#10b981;">✓</span>
                        <span class="stock-message">Aucune alerte, stock sain</span>
                    @else
                        <span class="stock-count-big">{{ $lowStockCount }}</span>
                        <span class="stock-message">produit(s) en dessous du seuil</span>
                    @endif
                </div>
            </div>

            <div class="info-card">
                <div class="info-card-header">
                    <h4>Accès rapide</h4>
                </div>
                <ul class="quick-links-list">
                    @if(auth()->user()->canManageSales())
                        <li><a href="{{ route('clients.index') }}"><i class="bi bi-people-fill"></i>Clients</a></li>
                    @endif
                    @if(auth()->user()->canManageStock())
                        <li><a href="{{ route('suppliers.index') }}"><i class="bi bi-truck"></i>Fournisseurs</a></li>
                        <li><a href="{{ route('products.index') }}"><i class="bi bi-box-seam"></i>Produits</a></li>
                        <li><a href="{{ route('categories.index') }}"><i class="bi bi-tags-fill"></i>Catégories</a></li>
                    @endif
                    @if(auth()->user()->canViewReports())
                        <li><a href="{{ route('reports.index') }}"><i class="bi bi-graph-up"></i>Rapports</a></li>
                    @endif
                </ul>
            </div>
            
            @if(auth()->user()->canManageUsers())
                <div class="info-card">
                    <div class="info-card-header">
                        <h4>Équipe</h4>
                    </div>
                    <div class="d-flex justify-between align-center">
                        <div>
                            <span class="stat-value" style="font-size: 28px;">{{ $employeesCount ?? 0 }}</span>
                            <span class="stat-desc">employés</span>
                        </div>
                        <a href="{{ route('users.index') }}" class="btn-icon">
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ONGLETS SOUS LE GRAPHE --}}
    <div class="tabs-minimal">
        <span class="tab-minimal active" data-tab="recent">Ventes récentes</span>
        <span class="tab-minimal" data-tab="lowstock">Stock faible</span>
    </div>

    {{-- TABLES --}}
    <div class="twin-tables">
        <div class="table-card-mini" id="recentSalesCard">
            <div class="table-header-mini">
                <h3><i class="bi bi-clock-history text-accent"></i> Dernières transactions <span class="badge-count">{{ count($recentSales ?? []) }}</span></h3>
            </div>
            <div class="table-responsive">
                <table class="table-mini">
                    <thead><tr><th>Produit</th><th>Client</th><th>Montant</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($recentSales ?? [] as $sale)
                        <tr>
                            <td>
                                @foreach($sale->items->take(2) as $item)
                                    <strong>{{ $item->product->name ?? '...' }}</strong>@if(!$loop->last), @endif
                                @endforeach
                                @if($sale->items->count() > 2) ... @endif
                            </td>
                            <td>{{ $sale->client->name ?? 'Particulier' }}</td>
                            <td><strong class="text-accent">{{ number_format($sale->total_price,0,',',' ') }} FCFA</strong></td>
                            <td>{{ $sale->created_at->format('d/m H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-muted-small" style="text-align:center; padding:22px;">Aucune vente récente</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-card-mini" id="lowStockCard" style="display:none;">
            <div class="table-header-mini">
                <h3><i class="bi bi-exclamation-triangle-fill text-accent"></i> Réapprovisionnement <span class="badge-count">{{ count($lowStockProducts ?? []) }}</span></h3>
            </div>
            <div class="table-responsive">
                <table class="table-mini">
                    <thead><tr><th>Produit</th><th>Stock</th><th>Prix vente</th></tr></thead>
                    <tbody>
                        @forelse($lowStockProducts ?? [] as $product)
                        <tr>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td><span class="badge-stock {{ $product->stock <= 2 ? 'badge-critical' : 'badge-warning' }}">{{ $product->stock }}</span></td>
                            <td><strong class="text-accent">{{ number_format($product->sale_price,0,',',' ') }} FCFA</strong></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-muted-small" style="text-align:center;">Aucun produit en alerte</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="security-note">
        <i class="bi bi-shield-check"></i> Toutes les données sont chiffrées et synchronisées en temps réel
        <span class="badge-warning" style="margin-left: 10px; padding: 2px 10px;">v2.0</span>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
    if (window.__dashInit) return;
    window.__dashInit = true;

    const state = {
        chart: null,
        period: 7,
        loading: false
    };

    const getHeaders = () => ({
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        'Accept': 'application/json'
    });

    const formatMoney = v => Number(v||0).toLocaleString('fr-FR') + ' FCFA';

    async function refreshStats() {
        try {
            const res = await fetch('/ajax/dashboard/stats', { 
                headers: getHeaders(), 
                credentials: 'same-origin' 
            });
            if (!res.ok) return;
            const d = await res.json();
            document.getElementById('salesToday') && (document.getElementById('salesToday').innerText = d.salesToday ?? 0);
            document.getElementById('totalRevenue') && (document.getElementById('totalRevenue').innerText = formatMoney(d.totalRevenue));
            document.getElementById('lowStockCount') && (document.getElementById('lowStockCount').innerText = d.lowStockCount ?? 0);
            document.getElementById('activeClients') && (document.getElementById('activeClients').innerText = d.activeClients ?? 0);
            const avgEl = document.getElementById('averageSale');
            if (avgEl) avgEl.innerText = formatMoney(d.averageSale || 0);
        } catch (e) {}
    }

    async function loadChart(period = state.period) {
        if (state.loading) return;
        state.loading = true;
        state.period = period;

        const loading = document.getElementById('chartLoading');
        const error = document.getElementById('chartError');
        const canvas = document.getElementById('salesChart');

        if (loading) loading.style.display = 'flex';
        if (error) error.style.display = 'none';
        if (canvas) canvas.style.opacity = '0.4';

        try {
            const res = await fetch(`/ajax/dashboard/chart-data?period=${period}`, { 
                headers: getHeaders(), 
                credentials: 'same-origin' 
            });
            if (res.status === 401) throw new Error('AUTH');
            if (!res.ok) throw new Error('NETWORK');

            const data = await res.json();
            if (loading) loading.style.display = 'none';
            if (canvas) canvas.style.opacity = '1';

            if (state.chart) state.chart.destroy();

            state.chart = new Chart(canvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: data.dates || [],
                    datasets: [{
                        label: 'Ventes (FCFA)',
                        data: data.totals || [],
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249,115,22,0.08)',
                        borderWidth: 3,
                        pointBackgroundColor: '#f97316',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: { backgroundColor: '#0f172a' }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            ticks: { 
                                callback: v => v >= 1000 ? (v/1000)+'k' : v,
                                color: '#334155'
                            },
                            grid: { color: '#e2e8f0' }
                        },
                        x: {
                            ticks: { color: '#334155' },
                            grid: { display: false }
                        }
                    }
                }
            });

        } catch (e) {
            console.warn(e);
            if (loading) loading.style.display = 'none';
            if (error) error.style.display = 'flex';
            if (canvas) canvas.style.opacity = '1';
        } finally {
            state.loading = false;
        }
    }

    async function loadRecent() {
        try {
            const res = await fetch('/ajax/dashboard/recent-sales', { headers: getHeaders() });
            if (!res.ok) return;
            const data = await res.json();
            const tbody = document.querySelector('#recentSalesCard tbody');
            if (!tbody) return;
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-muted-small" style="text-align:center; padding:22px;">Aucune vente récente</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(s => `
                <tr>
                    <td><strong>${s.product_name || 'Vente'}</strong></td>
                    <td>${s.client_name || 'Client'}</td>
                    <td><strong class="text-accent">${formatMoney(s.total_price)}</strong></td>
                    <td>${s.formatted_date || (s.created_at ? new Date(s.created_at).toLocaleDateString('fr-FR',{day:'2-digit',month:'2-digit',hour:'2-digit',minute:'2-digit'}) : '')}</td>
                </tr>
            `).join('');
        } catch (e) {}
    }

    async function loadLowStock() {
        try {
            const res = await fetch('/ajax/dashboard/low-stock', { headers: getHeaders() });
            if (!res.ok) return;
            const data = await res.json();
            const tbody = document.querySelector('#lowStockCard tbody');
            if (!tbody) return;
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-muted-small" style="text-align:center;">Aucun produit en alerte</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(p => `
                <tr>
                    <td><strong>${p.name}</strong></td>
                    <td><span class="badge-stock ${p.stock<=2?'badge-critical':'badge-warning'}">${p.stock}</span></td>
                    <td><strong class="text-accent">${formatMoney(p.sale_price)}</strong></td>
                </tr>
            `).join('');
        } catch (e) {}
    }

    document.querySelectorAll('.tab-minimal').forEach(tab => {
        tab.addEventListener('click', e => {
            document.querySelectorAll('.tab-minimal').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            const which = tab.dataset.tab;
            document.getElementById('recentSalesCard').style.display = which === 'recent' ? 'block' : 'none';
            document.getElementById('lowStockCard').style.display = which === 'lowstock' ? 'block' : 'none';
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        loadChart(7);
        refreshStats();
        loadRecent();
        loadLowStock();

        document.getElementById('chartPeriod')?.addEventListener('change', e => loadChart(e.target.value));

        setInterval(() => {
            if (document.visibilityState === 'visible') {
                refreshStats(); 
                loadRecent(); 
                loadLowStock();
            }
        }, 30000);
    });

})();
</script>
@endsection