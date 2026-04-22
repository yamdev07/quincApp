{{-- resources/views/subscription/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Mon abonnement - Sellvantix')

@section('styles')
<style>
    /* -----------------------------------------------------
       VARIABLES - identiques au dashboard
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

        --shadow-card: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
        --shadow-hover: 0 10px 15px -3px rgba(249,115,22,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
    }

    * { box-sizing: border-box; }

    body {
        background: var(--bg-page);
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--text-primary);
    }

    /* =====================================================
       LAYOUT
    ===================================================== */
    .sub-container {
        max-width: 1440px;
        margin: 0 auto;
        padding: 32px 24px;
    }

    /* =====================================================
       HEADER - repris du dashboard
    ===================================================== */
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

    .dash-header-left p {
        color: var(--text-tertiary);
        font-size: 14px;
        margin: 0;
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
        box-shadow: 0 8px 16px rgba(249,115,22,0.25);
        cursor: pointer;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #ea580c, #c2410c);
        transform: translateY(-2px);
        box-shadow: 0 12px 20px rgba(249,115,22,0.35);
        color: white;
    }
    .btn-primary i { font-size: 16px; }

    .btn-secondary {
        background: transparent;
        border: 1.5px solid var(--border-soft);
        color: var(--text-primary);
        height: 44px;
        padding: 0 20px;
        border-radius: 40px;
        font-weight: 500;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
    }
    .btn-secondary i { color: var(--accent); }
    .btn-secondary:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent-dark);
    }

    .btn-danger {
        background: transparent;
        border: 1.5px solid #fca5a5;
        color: #ef4444;
        height: 44px;
        padding: 0 20px;
        border-radius: 40px;
        font-weight: 500;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
    }
    .btn-danger:hover {
        background: #fee2e2;
        border-color: #ef4444;
    }

    /* =====================================================
       ALERTE EXPIRATION
    ===================================================== */
    .warning-box {
        background: linear-gradient(145deg, #fff7ed, #ffedd5);
        border-left: 5px solid var(--accent);
        border-radius: 20px;
        border: 1px solid rgba(249,115,22,0.25);
        border-left: 4px solid var(--accent);
        padding: 18px 24px;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }
    .warning-box-text {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .warning-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: rgba(249,115,22,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 20px;
        flex-shrink: 0;
    }
    .warning-title {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 15px;
        margin-bottom: 3px;
    }
    .warning-sub {
        font-size: 13px;
        color: var(--text-tertiary);
    }

    /* =====================================================
       GRILLE PRINCIPALE
    ===================================================== */
    .sub-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }
    @media (min-width: 1024px) {
        .sub-grid { grid-template-columns: 1.5fr 1fr; }
    }

    .sub-col-left, .sub-col-right {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* =====================================================
       CARTE ABONNEMENT PRINCIPALE (style dashboard dark)
    ===================================================== */
    .sub-dark-card {
        background: linear-gradient(145deg, #0f172a 0%, #1a2540 100%);
        border-radius: 24px;
        border: 1px solid rgba(249,115,22,0.18);
        overflow: hidden;
    }

    .sub-dark-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 24px 14px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }

    .sub-dark-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.2px;
        color: rgba(255,255,255,0.4);
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .status-pill {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
    }
    .status-pill.active {
        background: rgba(34,197,94,0.12);
        border: 1px solid rgba(34,197,94,0.25);
        color: #4ade80;
    }
    .status-pill.trial {
        background: rgba(249,115,22,0.15);
        border: 1px solid rgba(249,115,22,0.3);
        color: #fb923c;
    }
    .status-pill.expired {
        background: rgba(239,68,68,0.12);
        border: 1px solid rgba(239,68,68,0.25);
        color: #f87171;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    .dot-green { background: #22c55e; }
    .dot-orange { background: #f97316; }
    .dot-red { background: #ef4444; }

    .sub-dark-body {
        display: grid;
        grid-template-columns: 1fr 1px 1fr;
    }

    .sub-dark-col { padding: 20px 24px; }

    .sub-dark-divider { background: rgba(255,255,255,0.06); }

    .sub-dark-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
    }
    .sub-dark-row:last-child { margin-bottom: 0; }

    .sub-dark-key {
        font-size: 12px;
        color: rgba(255,255,255,0.38);
        font-weight: 500;
    }
    .sub-dark-val {
        font-size: 13px;
        color: rgba(255,255,255,0.88);
        font-weight: 600;
    }
    .sub-dark-val.accent { color: #f97316; }

    .sub-progress-wrap { padding: 0 24px 20px; }

    .sub-progress-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .sub-progress-label {
        font-size: 11px;
        color: rgba(255,255,255,0.35);
        font-weight: 500;
    }
    .sub-progress-days {
        font-size: 12px;
        color: rgba(255,255,255,0.5);
        font-weight: 600;
    }
    .sub-progress-days span { color: #f97316; }

    .progress-track {
        height: 5px;
        background: rgba(255,255,255,0.07);
        border-radius: 10px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.4s ease;
    }

    .sub-dark-footer {
        background: rgba(249,115,22,0.06);
        border-top: 1px solid rgba(249,115,22,0.12);
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .sub-dark-footer-text {
        font-size: 12px;
        color: rgba(255,255,255,0.45);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .sub-dark-footer-text i { color: #f97316; }
    .sub-dark-footer-text strong { color: rgba(255,255,255,0.7); }

    /* =====================================================
       CARTES BLANCHES
    ===================================================== */
    .card {
        background: var(--bg-card);
        border-radius: 24px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-card);
        overflow: hidden;
        transition: all 0.25s;
    }
    .card:hover {
        box-shadow: var(--shadow-hover);
    }

    .card-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--border-light);
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-header h3 {
        font-size: 17px;
        font-weight: 700;
        margin: 0;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
        padding-left: 12px;
    }
    .card-header h3::before {
        content: '';
        position: absolute;
        left: 0;
        top: 2px;
        bottom: 2px;
        width: 4px;
        background: var(--accent-gradient);
        border-radius: 4px;
    }
    .card-header h3 i { color: var(--accent); font-size: 18px; }

    .card-body { padding: 24px; }

    /* =====================================================
       LIGNES D'INFO
    ===================================================== */
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 13px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .info-row:last-child { border-bottom: none; }

    .info-label {
        color: var(--text-tertiary);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .info-label i { color: var(--accent); font-size: 15px; }

    .info-value {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 15px;
    }
    .info-value.accent { color: var(--accent); }

    /* =====================================================
       HISTORIQUE PAIEMENTS
    ===================================================== */
    .table-payments {
        width: 100%;
        border-collapse: collapse;
    }
    .table-payments thead th {
        text-align: left;
        padding: 13px 16px 10px;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border-bottom: 1px solid var(--border-light);
        background: #f9fafb;
    }
    .table-payments tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f3f5;
        color: var(--text-primary);
        font-size: 14px;
        font-weight: 500;
    }
    .table-payments tbody tr:hover td { background: var(--accent-light); }
    .table-payments tbody tr:last-child td { border-bottom: none; }

    .badge-paid {
        background: rgba(34,197,94,0.1);
        color: #16a34a;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid rgba(34,197,94,0.2);
    }

    /* =====================================================
       FORMULES / PLANS - AVEC INDICATEUR ACTUEL
    ===================================================== */
    .plan-grid {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .plan-card {
        background: var(--bg-card);
        border-radius: 18px;
        border: 1.5px solid var(--border-light);
        padding: 18px 20px;
        transition: all 0.2s;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .plan-card:hover {
        border-color: var(--accent);
        box-shadow: var(--shadow-hover);
        transform: translateY(-2px);
    }

    .plan-card.popular {
        border: 2px solid var(--accent);
        background: linear-gradient(145deg, #fff7ed, #ffffff);
    }

    /* Style pour la formule actuelle */
    .plan-card.current-plan {
        border: 2px solid #f97316;
        background: linear-gradient(145deg, #ffedd5, #fff7ed);
        box-shadow: 0 4px 15px rgba(249,115,22,0.15);
    }

    .current-plan-badge {
        position: absolute;
        top: -12px;
        left: 20px;
        background: #f97316;
        color: white;
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(249,115,22,0.3);
    }

    .current-plan-badge i {
        font-size: 10px;
        margin-right: 4px;
    }

    .plan-badge-pop {
        position: absolute;
        top: -11px;
        right: 18px;
        background: var(--accent-gradient);
        color: white;
        padding: 3px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .plan-info .plan-name {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 3px;
    }

    .plan-info .plan-saving {
        font-size: 12px;
        color: #16a34a;
        font-weight: 600;
    }

    .plan-right {
        text-align: right;
    }

    .plan-right .plan-price {
        font-size: 22px;
        font-weight: 800;
        color: var(--accent);
        line-height: 1.1;
    }

    .plan-right .plan-price small {
        font-size: 11px;
        font-weight: 500;
        color: var(--text-tertiary);
    }

    .plan-current-badge {
        display: inline-block;
        background: rgba(249,115,22,0.15);
        border: 1px solid rgba(249,115,22,0.3);
        color: #f97316;
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 8px;
    }

    .plan-current-badge i {
        margin-right: 5px;
    }

    .plan-btn {
        display: block;
        text-align: center;
        background: transparent;
        border: 1.5px solid var(--accent);
        color: var(--accent);
        padding: 6px 16px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 13px;
        margin-top: 8px;
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
        width: 100%;
    }

    .plan-btn:hover {
        background: var(--accent);
        color: white;
    }

    /* =====================================================
       ACTIONS
    ===================================================== */
    .actions-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* =====================================================
       AIDE
    ===================================================== */
    .help-block {
        text-align: center;
        padding: 8px 0 4px;
    }
    .help-icon {
        width: 56px;
        height: 56px;
        border-radius: 20px;
        background: var(--accent-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 26px;
        margin: 0 auto 12px;
    }
    .help-block p { color: var(--text-tertiary); font-size: 14px; margin-bottom: 16px; }

    /* =====================================================
       FOOTER NOTE
    ===================================================== */
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
</style>
@endsection

@section('content')
<div class="sub-container">

    {{-- HEADER --}}
    <div class="dash-header">
        <div class="dash-header-left">
            <h1>Mon abonnement</h1>
            <p>Gérez votre formule et suivez vos paiements</p>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="btn-outline">
                <i class="bi bi-arrow-left"></i> Retour au dashboard
            </a>
        </div>
    </div>

    {{-- ALERTE EXPIRATION --}}
    @if($daysRemaining > 0 && $daysRemaining <= 7)
    <div class="warning-box">
        <div class="warning-box-text">
            <div class="warning-icon">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <div class="warning-title">Votre abonnement expire dans {{ $daysRemaining }} jour(s)</div>
                <div class="warning-sub">Renouvelez avant le {{ $endDate ? $endDate->format('d/m/Y') : 'N/A' }} pour éviter toute interruption de service</div>
            </div>
        </div>
        <a href="{{ route('payment.form', ['renewal' => true, 'amount' => $currentPrice, 'plan' => strtolower($currentPlan)]) }}" class="btn-primary">
            <i class="bi bi-credit-card"></i> Renouveler maintenant
        </a>
    </div>
    @endif

    {{-- GRILLE PRINCIPALE --}}
    @php
        $pct = 0;
        $fillColor = '#f97316';
        if ($startDate && $endDate) {
            $totalDays = $startDate->diffInDays($endDate);
            $elapsed   = $startDate->diffInDays(now());
            $pct       = $totalDays > 0 ? min(100, round(($elapsed / $totalDays) * 100)) : 0;
            $fillColor = $pct > 80 ? '#ef4444' : ($pct > 60 ? '#f97316' : '#f97316');
        }
    @endphp

    <div class="sub-grid">

        {{-- COLONNE GAUCHE --}}
        <div class="sub-col-left">

            {{-- CARTE ABONNEMENT SOMBRE --}}
            <div class="sub-dark-card">

                <div class="sub-dark-top">
                    <span class="sub-dark-label">
                        <i class="bi bi-calendar-check"></i> Abonnement actuel
                    </span>
                    @if($subscriptionStatus === 'paid')
                        <span class="status-pill active">
                            <span class="status-dot dot-green"></span> ACTIF
                        </span>
                    @elseif($subscriptionStatus === 'trial')
                        <span class="status-pill trial">
                            <span class="status-dot dot-orange"></span> ESSAI GRATUIT
                        </span>
                    @else
                        <span class="status-pill expired">
                            <span class="status-dot dot-red"></span> EXPIRÉ
                        </span>
                    @endif
                </div>

                <div class="sub-dark-body">
                    <div class="sub-dark-col">
                        <div class="sub-dark-row">
                            <span class="sub-dark-key">Formule</span>
                            <span class="sub-dark-val">{{ $currentPlan }}</span>
                        </div>
                        <div class="sub-dark-row">
                            <span class="sub-dark-key">Prix</span>
                            <span class="sub-dark-val accent">{{ number_format($currentPrice, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="sub-dark-row">
                            <span class="sub-dark-key">Cycle</span>
                            <span class="sub-dark-val">{{ ucfirst($currentPlan) }}</span>
                        </div>
                    </div>

                    <div class="sub-dark-divider"></div>

                    <div class="sub-dark-col">
                        <div class="sub-dark-row">
                            <span class="sub-dark-key">Début</span>
                            <span class="sub-dark-val">{{ $startDate ? $startDate->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="sub-dark-row">
                            <span class="sub-dark-key">Expiration</span>
                            <span class="sub-dark-val accent">
                                {{ $endDate ? $endDate->format('d/m/Y') : ($trialEndDate ? $trialEndDate->format('d/m/Y') : 'N/A') }}
                            </span>
                        </div>
                        <div class="sub-dark-row">
                            <span class="sub-dark-key">Prochain paiement</span>
                            <span class="sub-dark-val accent">
                                {{ $endDate ? $endDate->format('d/m/Y') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="sub-progress-wrap">
                    <div class="sub-progress-head">
                        <span class="sub-progress-label">Validité de la période en cours</span>
                        <span class="sub-progress-days">
                            <span>{{ max(0, $daysRemaining) }}</span> jours restants &bull; {{ $pct }}%
                        </span>
                    </div>
                    <div class="progress-track">
                        <div class="progress-fill" style="width: {{ $pct }}%; background: linear-gradient(90deg, {{ $fillColor }}, {{ $fillColor }}cc);"></div>
                    </div>
                </div>

                <div class="sub-dark-footer">
                    <span class="sub-dark-footer-text">
                        <i class="bi bi-info-circle"></i>
                        Expire le <strong>{{ $endDate ? $endDate->format('d/m/Y') : 'N/A' }}</strong> — Renouvelez avant expiration pour éviter toute interruption
                    </span>
                    @if($daysRemaining > 0 && $daysRemaining <= 7)
                    <a href="{{ route('payment.form', ['renewal' => true, 'amount' => $currentPrice, 'plan' => strtolower($currentPlan)]) }}" style="background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;padding:8px 20px;border-radius:30px;font-size:12px;font-weight:700;text-decoration:none;white-space:nowrap;">
                        Renouveler
                    </a>
                    @endif
                </div>
            </div>

            {{-- DÉTAILS DE L'ABONNEMENT --}}
            <div class="card">
                <div class="card-header">
                    <h3><i class="bi bi-receipt"></i> Détails</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label"><i class="bi bi-tag"></i> Formule</span>
                        <span class="info-value accent">{{ $currentPlan }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="bi bi-currency-dollar"></i> Prix</span>
                        <span class="info-value">{{ number_format($currentPrice, 0, ',', ' ') }} FCFA / {{ strtolower($currentPlan) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="bi bi-calendar"></i> Date de début</span>
                        <span class="info-value">{{ $startDate ? $startDate->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="bi bi-calendar-x"></i> Date d'expiration</span>
                        <span class="info-value">{{ $endDate ? $endDate->format('d/m/Y') : ($trialEndDate ? $trialEndDate->format('d/m/Y') : 'N/A') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="bi bi-hourglass-split"></i> Jours restants</span>
                        <span class="info-value {{ $daysRemaining > 7 ? 'accent' : '' }}" style="{{ $daysRemaining <= 0 ? 'color:#ef4444' : ($daysRemaining <= 7 ? 'color:#f97316' : '') }}">
                            @if($daysRemaining > 0)
                                {{ $daysRemaining }} jour(s)
                            @elseif($daysRemaining === 0)
                                Expire aujourd'hui
                            @else
                                Expiré
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- HISTORIQUE DES PAIEMENTS --}}
            <div class="card">
                <div class="card-header">
                    <h3><i class="bi bi-clock-history"></i> Historique des paiements</h3>
                    @if($paymentHistory->count() > 0)
                        <span style="background:var(--accent-gradient);color:white;padding:3px 12px;border-radius:30px;font-size:12px;font-weight:600;">
                            {{ $paymentHistory->count() }}
                        </span>
                    @endif
                </div>
                <div class="card-body" style="padding: 0;">
                    @if($paymentHistory->count() > 0)
                        <div style="overflow-x: auto;">
                            <table class="table-payments">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Formule</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paymentHistory as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                        <td><strong style="color: var(--accent);">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></td>
                                        <td>{{ ucfirst($payment->plan_type) }}</td>
                                        <td><span class="badge-paid">Payé</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="text-align:center; padding: 40px 24px;">
                            <i class="bi bi-receipt" style="font-size: 48px; color: var(--border-soft);"></i>
                            <p style="color: var(--text-tertiary); margin-top: 12px; margin-bottom: 0;">Aucun paiement enregistré</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>{{-- /col-left --}}

        {{-- COLONNE DROITE --}}
        <div class="sub-col-right">

            {{-- ACTIONS --}}
            <div class="card">
                <div class="card-header">
                    <h3><i class="bi bi-gear"></i> Actions rapides</h3>
                </div>
                <div class="card-body">
                    <div class="actions-list">
                        <a href="{{ route('payment.form', ['renewal' => true, 'amount' => $currentPrice, 'plan' => strtolower($currentPlan)]) }}" class="btn-primary" style="justify-content:center;">
                            <i class="bi bi-arrow-repeat"></i> Renouveler l'abonnement ({{ number_format($currentPrice, 0, ',', ' ') }} FCFA)
                        </a>
                        <a href="{{ route('invoices.last') }}" class="btn-secondary" style="justify-content:center;">
                            <i class="bi bi-file-pdf"></i> Télécharger ma dernière facture
                        </a>
                        <button class="btn-danger" style="justify-content:center; width:100%;" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i class="bi bi-x-circle"></i> Résilier mon abonnement
                        </button>
                    </div>
                </div>
            </div>

            {{-- CHANGER DE FORMULE AVEC INDICATEUR ACTUEL --}}
            <div class="card">
                <div class="card-header">
                    <h3><i class="bi bi-arrow-repeat"></i> Changer de formule</h3>
                </div>
                <div class="card-body">
                    <div class="plan-grid">
                        @foreach($availablePlans as $plan)
                            @php
                                $isCurrentPlan = ($plan['type'] === strtolower($currentPlan));
                            @endphp
                            <div class="plan-card {{ isset($plan['popular']) ? 'popular' : '' }} {{ $isCurrentPlan ? 'current-plan' : '' }}">
                                @if(isset($plan['popular']))
                                    <div class="plan-badge-pop">⭐ Le plus populaire</div>
                                @endif
                                
                                @if($isCurrentPlan)
                                    <div class="current-plan-badge">
                                        <i class="bi bi-check-circle-fill"></i> Votre formule actuelle
                                    </div>
                                @endif
                                
                                <div class="plan-info">
                                    <div class="plan-name">{{ $plan['name'] }}</div>
                                    @if(isset($plan['saving']))
                                        <div class="plan-saving">{{ $plan['saving'] }}</div>
                                    @endif
                                </div>
                                <div class="plan-right">
                                    <div class="plan-price">
                                        {{ number_format($plan['price'], 0, ',', ' ') }} FCFA
                                        <small>/ {{ $plan['duration'] }}</small>
                                    </div>
                                    @if($isCurrentPlan)
                                        <span class="plan-current-badge">
                                            <i class="bi bi-calendar-check"></i> En cours
                                        </span>
                                    @else
                                        <form action="{{ route('payment.form') }}" method="GET">
                                            <input type="hidden" name="plan" value="{{ $plan['type'] }}">
                                            <input type="hidden" name="amount" value="{{ $plan['price'] }}">
                                            <input type="hidden" name="renewal" value="1">
                                            <button type="submit" class="plan-btn">Choisir cette formule</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- BESOIN D'AIDE --}}
            <div class="card">
                <div class="card-header">
                    <h3><i class="bi bi-question-circle"></i> Besoin d'aide ?</h3>
                </div>
                <div class="card-body">
                    <div class="help-block">
                        <div class="help-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <p>Une question sur votre abonnement ?<br>Notre équipe vous répond sous 24h.</p>
                        <a href="mailto:contact@yyamd.com" class="btn-secondary" style="justify-content:center; display:inline-flex;">
                            <i class="bi bi-send"></i> Contacter le support
                        </a>
                    </div>
                </div>
            </div>

        </div>{{-- /col-right --}}

    </div>{{-- /sub-grid --}}

    <div class="security-note">
        <i class="bi bi-shield-check"></i> Toutes les données sont chiffrées et synchronisées en temps réel
        <span style="background:var(--accent-light);color:var(--accent-dark);padding:2px 10px;border-radius:30px;font-size:11px;font-weight:600;margin-left:10px;">v2.0</span>
    </div>

</div>{{-- /sub-container --}}

{{-- MODAL DE RÉSILIATION --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 24px; border: 1px solid var(--border-light); overflow: hidden;">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-light); padding: 20px 24px;">
                <h5 class="modal-title" style="font-weight: 700; color: var(--text-primary);">
                    <i class="bi bi-exclamation-triangle" style="color: #ef4444; margin-right: 8px;"></i>
                    Résilier mon abonnement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 28px 24px;">
                <div style="background: #fee2e2; border-radius: 16px; padding: 20px; margin-bottom: 20px; text-align: center;">
                    <i class="bi bi-x-circle" style="font-size: 40px; color: #ef4444;"></i>
                    <p style="font-weight: 700; color: #b91c1c; margin: 8px 0 4px; font-size: 16px;">Cette action est irréversible</p>
                    <p style="color: #dc2626; font-size: 13px; margin: 0;">Vous perdrez l'accès à toutes les fonctionnalités à la fin de votre période en cours.</p>
                </div>
                <p style="color: var(--text-secondary); font-size: 14px;">Êtes-vous sûr de vouloir résilier votre abonnement Sellvantix ?</p>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border-light); padding: 16px 24px; gap: 10px;">
                <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left"></i> Annuler
                </button>
                <form action="{{ route('subscription.cancel') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-danger" style="height: 46px; padding: 0 24px;">
                        <i class="bi bi-x-circle"></i> Confirmer la résiliation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection