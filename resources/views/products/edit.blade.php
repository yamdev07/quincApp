@extends('layouts.app')

@section('title', 'Modifier le produit : ' . $product->name . ' — Sellvantix')

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
        --pink:          #db2777;
        --indigo:        #6366f1;
        --yellow:        #eab308;
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
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Page */
    .sp-edit-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .sp-edit-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        animation: fadeUp 0.35s ease both;
    }

    .sp-edit-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .sp-edit-hex {
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
    .sp-edit-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .sp-edit-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .sp-edit-title span {
        color: var(--orange);
        font-weight: 800;
    }
    .sp-edit-sub {
        font-size: 13px;
        color: var(--text-3);
        margin-top: 4px;
    }

    .sp-edit-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        background: var(--orange-pale);
        border: 1px solid var(--orange-soft);
        border-radius: 40px;
        font-size: 12px;
        color: var(--orange);
        margin-left: 8px;
    }

    /* Access denied */
    .sp-access-denied {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: var(--radius);
        padding: 32px;
        text-align: center;
        animation: fadeUp 0.35s ease both;
        margin-bottom: 24px;
    }
    .sp-access-denied svg {
        width: 48px;
        height: 48px;
        stroke: var(--danger);
        margin: 0 auto 16px;
    }
    .sp-access-denied h2 {
        font-size: 20px;
        font-weight: 700;
        color: var(--danger);
        margin-bottom: 8px;
    }
    .sp-access-denied p {
        font-size: 14px;
        color: var(--text-2);
        margin-bottom: 24px;
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
        box-shadow: 0 12px 28px rgba(249,115,22,0.4);
    }
    .btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        background: var(--card);
        border: 1.5px solid var(--border);
        border-radius: 40px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-2);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-secondary svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        fill: none;
    }
    .btn-secondary:hover {
        border-color: var(--orange);
        color: var(--orange);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
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

    .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        background: #fee2e2;
        border: 1.5px solid #fecaca;
        border-radius: 40px;
        font-size: 13px;
        font-weight: 600;
        color: var(--danger);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-danger svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        fill: none;
    }
    .btn-danger:hover {
        background: var(--danger);
        border-color: var(--danger);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220,38,38,0.3);
    }

    /* Stats Cards */
    .sp-edit-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 700px) {
        .sp-edit-stats { grid-template-columns: 1fr; }
    }

    .sp-edit-stat {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 18px 20px;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
        animation: fadeUp 0.35s ease both;
    }
    .sp-edit-stat:nth-child(2) { animation-delay:0.07s; }
    .sp-edit-stat:nth-child(3) { animation-delay:0.14s; }
    .sp-edit-stat:hover {
        border-color: var(--orange-soft);
        transform: translateY(-2px);
    }

    .sp-edit-stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .sp-edit-stat-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-3);
    }
    .sp-edit-stat-ico {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sp-edit-stat-ico svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
    }
    .stat-green .sp-edit-stat-ico { background: #f0fdf4; color: var(--success); }
    .stat-yellow .sp-edit-stat-ico { background: #fef3c7; color: #b45309; }
    .stat-red .sp-edit-stat-ico { background: #fee2e2; color: var(--danger); }
    .stat-purple .sp-edit-stat-ico { background: #f5f3ff; color: var(--purple); }

    .sp-edit-stat-value {
        font-size: 20px;
        font-weight: 700;
        color: var(--text);
        line-height: 1.2;
        margin-bottom: 4px;
    }
    .sp-edit-stat-unit {
        font-size: 11px;
        color: var(--text-3);
    }

    /* Card */
    .sp-edit-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        margin-bottom: 24px;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .sp-edit-card:hover {
        border-color: var(--orange-soft);
    }

    .sp-edit-card-header {
        padding: 18px 24px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .sp-edit-card-header-l {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sp-edit-card-ico {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sp-edit-card-ico svg {
        width: 18px;
        height: 18px;
        stroke: #fff;
        fill: none;
    }
    .sp-edit-card-header h2 {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
    }
    .sp-edit-card-header p {
        font-size: 12px;
        color: rgba(255,255,255,0.8);
    }

    .sp-edit-card-body {
        padding: 32px;
    }

    /* Form Grid */
    .sp-edit-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 24px;
    }
    @media (max-width: 700px) {
        .sp-edit-grid { grid-template-columns: 1fr; }
    }

    .sp-edit-form-group {
        margin-bottom: 20px;
    }
    .sp-edit-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-2);
        margin-bottom: 6px;
        letter-spacing: 0.3px;
    }
    .sp-edit-label svg {
        width: 14px;
        height: 14px;
        stroke: var(--orange);
        margin-right: 4px;
    }

    .sp-edit-field-wrapper {
        position: relative;
    }
    .sp-edit-ico {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-3);
        transition: color 0.2s;
    }
    .sp-edit-ico svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
    }
    .sp-edit-field-wrapper:focus-within .sp-edit-ico {
        color: var(--orange);
        animation: pulse 2s infinite;
    }

    .sp-edit-input, .sp-edit-select, .sp-edit-textarea {
        width: 100%;
        padding: 12px 16px 12px 44px;
        background: #fafbfd;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 14px;
        color: var(--text);
        font-family: inherit;
        transition: all 0.2s;
    }
    .sp-edit-input:focus, .sp-edit-select:focus, .sp-edit-textarea:focus {
        border-color: var(--orange);
        outline: none;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
        background: var(--card);
    }
    .sp-edit-input.error {
        border-color: var(--danger);
        background: #fef2f2;
    }

    .sp-edit-select {
        appearance: none;
        padding-right: 32px;
    }
    .sp-edit-select-arrow {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: var(--text-3);
    }
    .sp-edit-select-arrow svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
    }

    .sp-edit-textarea {
        padding: 12px 16px 12px 44px;
        resize: vertical;
        min-height: 100px;
    }
    .sp-edit-ico-textarea {
        position: absolute;
        left: 14px;
        top: 16px;
        color: var(--text-3);
    }

    .sp-edit-hint {
        display: flex;
        justify-content: space-between;
        margin-top: 6px;
        font-size: 11px;
        color: var(--text-3);
    }

    /* Margin Badge */
    .sp-edit-margin {
        margin-top: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 40px;
        font-size: 12px;
        font-weight: 600;
    }
    .margin-positive {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .margin-negative {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .margin-neutral {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde047;
    }

    /* Form Actions */
    .sp-edit-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
        margin-top: 24px;
    }
    .sp-edit-actions-left, .sp-edit-actions-right {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Preview Card */
    .sp-edit-preview {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        margin-top: 24px;
    }
    .sp-edit-preview-header {
        padding: 16px 24px;
        background: linear-gradient(135deg, var(--purple), #8b5cf6);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sp-edit-preview-header h3 {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
    }
    .sp-edit-preview-body {
        padding: 24px;
    }

    .sp-edit-preview-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 20px;
    }
    @media (max-width: 600px) {
        .sp-edit-preview-grid { grid-template-columns: 1fr; }
    }

    .sp-edit-preview-item {
        background: #fafbfd;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 16px;
    }
    .sp-edit-preview-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-3);
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .sp-edit-preview-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--text);
    }
    .sp-edit-preview-description {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }

    /* Error Alert */
    .sp-edit-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: var(--radius-sm);
        padding: 20px;
        margin-bottom: 24px;
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }
    .sp-edit-error svg {
        width: 24px;
        height: 24px;
        stroke: var(--danger);
        flex-shrink: 0;
    }
    .sp-edit-error h3 {
        font-size: 15px;
        font-weight: 700;
        color: var(--danger);
        margin-bottom: 8px;
    }
    .sp-edit-error ul {
        list-style: disc;
        padding-left: 20px;
        color: #991b1b;
        font-size: 13px;
    }

    /* Text colors */
    .text-success { color: var(--success); }
    .text-danger { color: var(--danger); }
    .text-yellow-600 { color: #b45309; }
    .text-green-600 { color: var(--success); }
    .text-red-600 { color: var(--danger); }
</style>
@endsection

@section('content')
<div class="sp-edit-page">

    {{-- ✅ VÉRIFICATION D'ACCÈS CORRIGÉE POUR LE MAGASINIER --}}
    @if(!auth()->user()->canManageStock())
        <div class="sp-access-denied">
            <svg viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h2>Accès refusé</h2>
            <p>Vous n'avez pas les droits pour modifier ce produit.</p>
            <a href="{{ route('products.index') }}" class="btn-secondary">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la liste
            </a>
        </div>
    @else

    {{-- HEADER --}}
    <div class="sp-edit-header">
        <div class="sp-edit-header-l">
            <div class="sp-edit-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <div>
                <div class="sp-edit-title">
                    Modifier <span>{{ $product->name }}</span>
                    <span class="sp-edit-badge">ID: {{ $product->id }}</span>
                </div>
                <div class="sp-edit-sub">Mettez à jour les informations du produit</div>
            </div>
        </div>
        <a href="{{ route('products.index') }}" class="btn-secondary">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>
    </div>

    {{-- STATS CARDS --}}
    <div class="sp-edit-stats">
        <div class="sp-edit-stat">
            <div class="sp-edit-stat-header">
                <span class="sp-edit-stat-label">Marge bénéficiaire</span>
                <div class="sp-edit-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            @php
                $margin = $product->sale_price - $product->purchase_price;
                $marginPercent = $product->purchase_price > 0 ? ($margin / $product->purchase_price) * 100 : 0;
            @endphp
            <div class="sp-edit-stat-value">{{ number_format($margin, 0, ',', ' ') }} CFA</div>
            <div class="sp-edit-stat-unit">
                @if($marginPercent > 0)
                    +{{ number_format($marginPercent, 1) }}%
                @endif
            </div>
        </div>

        <div class="sp-edit-stat">
            <div class="sp-edit-stat-header">
                <span class="sp-edit-stat-label">Stock disponible</span>
                <div class="sp-edit-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <div class="sp-edit-stat-value">{{ $product->stock }}</div>
            <div class="sp-edit-stat-unit">unités</div>
        </div>

        <div class="sp-edit-stat">
            <div class="sp-edit-stat-header">
                <span class="sp-edit-stat-label">Valeur du stock</span>
                <div class="sp-edit-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="sp-edit-stat-value">{{ number_format($product->sale_price * $product->stock, 0, ',', ' ') }}</div>
            <div class="sp-edit-stat-unit">CFA</div>
        </div>
    </div>

    {{-- ERROR ALERT --}}
    @if($errors->any())
        <div class="sp-edit-error">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <h3>Veuillez corriger les erreurs suivantes :</h3>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- FORM CARD --}}
    <div class="sp-edit-card">
        <div class="sp-edit-card-header">
            <div class="sp-edit-card-header-l">
                <div class="sp-edit-card-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h2>Modifier le produit</h2>
                    <p>Dernière mise à jour : {{ $product->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="sp-edit-card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" id="editProductForm">
                @csrf
                @method('PUT')

                <div class="sp-edit-grid">
                    {{-- Nom du produit --}}
                    <div class="sp-edit-form-group">
                        <label for="name" class="sp-edit-label">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Nom du produit
                        </label>
                        <div class="sp-edit-field-wrapper">
                            <span class="sp-edit-ico">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </span>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $product->name) }}" 
                                   class="sp-edit-input @error('name') error @enderror"
                                   placeholder="Ex: Marteau professionnel 500g"
                                   required>
                        </div>
                        <div class="sp-edit-hint">
                            <span>Nom complet et descriptif du produit</span>
                        </div>
                    </div>

                    {{-- Prix d'achat --}}
                    <div class="sp-edit-form-group">
                        <label for="purchase_price" class="sp-edit-label">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Prix d'achat (CFA)
                        </label>
                        <div class="sp-edit-field-wrapper">
                            <span class="sp-edit-ico">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <input type="number" 
                                   id="purchase_price" 
                                   name="purchase_price" 
                                   value="{{ old('purchase_price', $product->purchase_price) }}" 
                                   class="sp-edit-input calculate-margin @error('purchase_price') error @enderror"
                                   step="1"
                                   min="0"
                                   placeholder="0"
                                   required>
                        </div>
                    </div>

                    {{-- Prix de vente --}}
                    <div class="sp-edit-form-group">
                        <label for="sale_price" class="sp-edit-label">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Prix de vente (CFA)
                        </label>
                        <div class="sp-edit-field-wrapper">
                            <span class="sp-edit-ico">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <input type="number" 
                                   id="sale_price" 
                                   name="sale_price" 
                                   value="{{ old('sale_price', $product->sale_price) }}" 
                                   class="sp-edit-input calculate-margin @error('sale_price') error @enderror"
                                   step="1"
                                   min="0"
                                   placeholder="0"
                                   required>
                        </div>
                        <div id="marginDisplay" class="sp-edit-margin @if($margin > 0) margin-positive @elseif($margin < 0) margin-negative @else margin-neutral @endif">
                            Marge: {{ number_format($margin, 0, ',', ' ') }} CFA
                            @if($marginPercent > 0)
                                ({{ number_format($marginPercent, 1) }}%)
                            @endif
                        </div>
                    </div>

                    {{-- Stock disponible --}}
                    <div class="sp-edit-form-group">
                        <label for="stock" class="sp-edit-label">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Stock disponible
                        </label>
                        <div class="sp-edit-field-wrapper">
                            <span class="sp-edit-ico">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </span>
                            <input type="number" 
                                   id="stock" 
                                   name="stock" 
                                   value="{{ old('stock', $product->stock) }}" 
                                   class="sp-edit-input @error('stock') error @enderror"
                                   min="0"
                                   placeholder="0"
                                   required>
                        </div>
                        <div class="sp-edit-hint">
                            <span>Nombre d'unités disponibles pour la vente</span>
                        </div>
                    </div>

                    {{-- Seuil d'alerte stock --}}
                    <div class="sp-edit-form-group">
                        <label for="stock_alert" class="sp-edit-label">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Seuil d'alerte stock
                        </label>
                        <div class="sp-edit-field-wrapper focus-scale">
                            <span class="sp-edit-ico">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </span>
                            <input type="number"
                                   id="stock_alert"
                                   name="stock_alert"
                                   value="{{ old('stock_alert', $product->stock_alert ?? 10) }}"
                                   class="sp-edit-input @error('stock_alert') error @enderror"
                                   min="0"
                                   placeholder="10">
                        </div>
                        <div class="sp-edit-hint">
                            <span>Alerte déclenchée quand le stock atteint ce seuil (défaut : 10)</span>
                        </div>
                        @error('stock_alert')
                            <p class="sp-edit-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Catégorie --}}
                    <div class="sp-edit-form-group">
                        <label for="category_id" class="sp-edit-label">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Catégorie
                        </label>
                        <div class="sp-edit-field-wrapper">
                            <span class="sp-edit-ico">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </span>
                            <select id="category_id" name="category_id" class="sp-edit-select">
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="sp-edit-select-arrow">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Fournisseur --}}
                    <div class="sp-edit-form-group">
                        <label for="supplier_id" class="sp-edit-label">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Fournisseur
                        </label>
                        <div class="sp-edit-field-wrapper">
                            <span class="sp-edit-ico">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </span>
                            <select id="supplier_id" name="supplier_id" class="sp-edit-select">
                                <option value="">Sélectionner un fournisseur</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" 
                                            {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="sp-edit-select-arrow">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="sp-edit-form-group">
                    <label for="description" class="sp-edit-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        Description détaillée
                    </label>
                    <div class="sp-edit-field-wrapper">
                        <span class="sp-edit-ico-textarea">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="width:18px;height:18px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </span>
                        <textarea id="description" 
                                  name="description" 
                                  class="sp-edit-textarea"
                                  rows="4"
                                  placeholder="Décrivez le produit en détail...">{{ old('description', $product->description) }}</textarea>
                    </div>
                    <div class="sp-edit-hint">
                        <span>Informations supplémentaires sur le produit</span>
                        <span id="charCount">{{ strlen(old('description', $product->description)) }}/500 caractères</span>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="sp-edit-actions">
                    <div class="sp-edit-actions-left">
                        <a href="{{ route('products.show', $product->id) }}" class="btn-outline">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Voir le produit
                        </a>
                        
                        @if(auth()->user()->isSuperAdminGlobal() || auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                            <button type="button" 
                                    onclick="if(confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce produit ?')) document.getElementById('deleteForm').submit();"
                                    class="btn-danger">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Supprimer
                            </button>
                        @endif
                    </div>
                    
                    <div class="sp-edit-actions-right">
                        <button type="reset" class="btn-outline">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Réinitialiser
                        </button>
                        
                        <button type="submit" class="btn-primary">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Enregistrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- DELETE FORM --}}
    @if(auth()->user()->isSuperAdminGlobal() || auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
    <form id="deleteForm" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
    @endif

    {{-- PREVIEW CARD --}}
    <div class="sp-edit-preview">
        <div class="sp-edit-preview-header">
            <svg viewBox="0 0 24 24" stroke-width="2" style="width:22px; height:22px; stroke:#fff;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <h3>Aperçu en temps réel</h3>
        </div>
        <div class="sp-edit-preview-body">
            <div class="sp-edit-preview-grid">
                <div class="sp-edit-preview-item">
                    <div class="sp-edit-preview-label">Nom du produit</div>
                    <div class="sp-edit-preview-value" id="previewName">{{ $product->name }}</div>
                </div>
                <div class="sp-edit-preview-item">
                    <div class="sp-edit-preview-label">Stock</div>
                    <div class="sp-edit-preview-value" id="previewStock">
                        <span class="{{ $product->stock > 10 ? 'text-success' : ($product->stock > 0 ? 'text-yellow-600' : 'text-danger') }}">
                            {{ $product->stock }} unités
                        </span>
                    </div>
                </div>
                <div class="sp-edit-preview-item">
                    <div class="sp-edit-preview-label">Prix d'achat</div>
                    <div class="sp-edit-preview-value" id="previewPurchase">{{ number_format($product->purchase_price, 0, ',', ' ') }} CFA</div>
                </div>
                <div class="sp-edit-preview-item">
                    <div class="sp-edit-preview-label">Prix de vente</div>
                    <div class="sp-edit-preview-value" id="previewSale">{{ number_format($product->sale_price, 0, ',', ' ') }} CFA</div>
                </div>
                <div class="sp-edit-preview-item">
                    <div class="sp-edit-preview-label">Catégorie</div>
                    <div class="sp-edit-preview-value" id="previewCategory">{{ $product->category->name ?? 'Non définie' }}</div>
                </div>
                <div class="sp-edit-preview-item">
                    <div class="sp-edit-preview-label">Fournisseur</div>
                    <div class="sp-edit-preview-value" id="previewSupplier">{{ $product->supplier->name ?? 'Non défini' }}</div>
                </div>
            </div>
            <div class="sp-edit-preview-description">
                <div class="sp-edit-preview-label">Description</div>
                <div class="sp-edit-preview-value" id="previewDescription">{{ $product->description ?: 'Aucune description' }}</div>
            </div>
        </div>
    </div>
    
    @endif {{-- Fin de la condition d'autorisation --}}
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du formulaire
    const nameInput = document.getElementById('name');
    const purchasePriceInput = document.getElementById('purchase_price');
    const salePriceInput = document.getElementById('sale_price');
    const stockInput = document.getElementById('stock');
    const categorySelect = document.getElementById('category_id');
    const supplierSelect = document.getElementById('supplier_id');
    const descriptionInput = document.getElementById('description');
    const marginDisplay = document.getElementById('marginDisplay');

    // Éléments de prévisualisation
    const previewName = document.getElementById('previewName');
    const previewStock = document.getElementById('previewStock');
    const previewPurchase = document.getElementById('previewPurchase');
    const previewSale = document.getElementById('previewSale');
    const previewCategory = document.getElementById('previewCategory');
    const previewSupplier = document.getElementById('previewSupplier');
    const previewDescription = document.getElementById('previewDescription');
    const charCount = document.getElementById('charCount');

    // Fonction de mise à jour de l'aperçu
    function updatePreview() {
        // Nom
        if (previewName) previewName.textContent = nameInput.value || 'Non défini';
        
        // Stock
        if (previewStock && stockInput) {
            const stock = parseInt(stockInput.value) || 0;
            previewStock.innerHTML = `<span class="${stock > 10 ? 'text-success' : (stock > 0 ? 'text-yellow-600' : 'text-danger')}">${stock} unités</span>`;
        }
        
        // Prix
        if (previewPurchase && purchasePriceInput) {
            previewPurchase.textContent = (parseInt(purchasePriceInput.value) || 0).toLocaleString('fr-FR') + ' CFA';
        }
        if (previewSale && salePriceInput) {
            previewSale.textContent = (parseInt(salePriceInput.value) || 0).toLocaleString('fr-FR') + ' CFA';
        }
        
        // Catégorie
        if (previewCategory && categorySelect) {
            previewCategory.textContent = categorySelect.options[categorySelect.selectedIndex]?.text || 'Non définie';
        }
        
        // Fournisseur
        if (previewSupplier && supplierSelect) {
            previewSupplier.textContent = supplierSelect.options[supplierSelect.selectedIndex]?.text || 'Non défini';
        }
        
        // Description
        if (previewDescription && descriptionInput) {
            previewDescription.textContent = descriptionInput.value || 'Aucune description';
        }
        
        // Compteur de caractères
        if (charCount && descriptionInput) {
            charCount.textContent = descriptionInput.value.length + '/500 caractères';
        }
        
        // Calcul de la marge
        updateMargin();
    }

    // Calcul et affichage de la marge
    function updateMargin() {
        if (!purchasePriceInput || !salePriceInput || !marginDisplay) return;
        
        const purchasePrice = parseFloat(purchasePriceInput.value) || 0;
        const salePrice = parseFloat(salePriceInput.value) || 0;
        const margin = salePrice - purchasePrice;
        const marginPercent = purchasePrice > 0 ? (margin / purchasePrice) * 100 : 0;
        
        marginDisplay.className = 'sp-edit-margin';
        if (margin > 0) marginDisplay.classList.add('margin-positive');
        else if (margin < 0) marginDisplay.classList.add('margin-negative');
        else marginDisplay.classList.add('margin-neutral');
        
        let marginText = `Marge: ${margin.toLocaleString('fr-FR')} CFA`;
        if (marginPercent > 0) marginText += ` (${marginPercent.toFixed(1)}%)`;
        
        marginDisplay.textContent = marginText;
        
        // Alerte si prix de vente < prix d'achat
        if (salePrice < purchasePrice && purchasePrice > 0) {
            salePriceInput.style.borderColor = '#dc2626';
            salePriceInput.style.backgroundColor = '#fef2f2';
        } else {
            salePriceInput.style.borderColor = '#e2e8f0';
            salePriceInput.style.backgroundColor = '#fafbfd';
        }
    }

    // Écouteurs d'événements
    [nameInput, purchasePriceInput, salePriceInput, stockInput, descriptionInput].forEach(input => {
        if (input) input.addEventListener('input', updatePreview);
    });
    
    [categorySelect, supplierSelect].forEach(select => {
        if (select) select.addEventListener('change', updatePreview);
    });

    // Écouteur spécifique pour la marge
    if (salePriceInput) salePriceInput.addEventListener('input', updateMargin);
    if (purchasePriceInput) purchasePriceInput.addEventListener('input', updateMargin);

    // Initialisation
    updatePreview();

    // Validation du formulaire
    document.getElementById('editProductForm')?.addEventListener('submit', function(e) {
        const purchasePrice = parseFloat(purchasePriceInput?.value) || 0;
        const salePrice = parseFloat(salePriceInput?.value) || 0;
        const stock = parseInt(stockInput?.value) || 0;
        
        if (salePrice < purchasePrice && purchasePrice > 0) {
            if (!confirm('⚠️ Le prix de vente est inférieur au prix d\'achat. Voulez-vous continuer ?')) {
                e.preventDefault();
                return false;
            }
        }
        
        if (stock < 0) {
            alert('❌ Le stock disponible ne peut pas être négatif.');
            e.preventDefault();
            return false;
        }
        
        // Effet de chargement
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = `
                <svg viewBox="0 0 24 24" stroke-width="2" style="width:16px;height:16px; animation:spin 1s linear infinite;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Enregistrement...
            `;
            submitBtn.disabled = true;
        }
    });
});
</script>
@endsection