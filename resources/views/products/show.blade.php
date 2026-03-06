@extends('layouts.app')

@section('title', 'Produit : ' . $product->name . ' — QuincaApp')

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
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(-30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Page */
    .spd-page {
        max-width: 1400px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .spd-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        animation: fadeUp 0.35s ease both;
    }

    .spd-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .spd-hex {
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
    .spd-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .spd-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .spd-title span {
        color: var(--orange);
        font-weight: 800;
    }
    .spd-sub {
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
        box-shadow: 0 12px 28px rgba(249,115,22,0.4);
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

    /* Stats Grid */
    .spd-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1100px) { .spd-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 580px)  { .spd-stats { grid-template-columns: 1fr; } }

    .spd-stat {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px 22px;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
        transition: all 0.2s ease;
        animation: fadeUp 0.35s ease both;
    }
    .spd-stat:nth-child(2) { animation-delay:0.07s; }
    .spd-stat:nth-child(3) { animation-delay:0.14s; }
    .spd-stat:nth-child(4) { animation-delay:0.21s; }
    .spd-stat:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--orange-soft);
    }

    .spd-stat::before {
        content: '';
        position: absolute;
        top: 14px;
        bottom: 14px;
        left: 0;
        width: 4px;
        border-radius: 0 4px 4px 0;
    }
    .spd-stat.c-a::before { background: var(--info); }
    .spd-stat.c-b::before { background: var(--success); }
    .spd-stat.c-c::before { background: var(--purple); }
    .spd-stat.c-d::before { background: var(--orange); }

    .spd-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
    }
    .spd-stat-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-3);
    }
    .spd-stat-ico {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .c-a .spd-stat-ico { background: #eff6ff; color: var(--info); }
    .c-b .spd-stat-ico { background: #f0fdf4; color: var(--success); }
    .c-c .spd-stat-ico { background: #f5f3ff; color: var(--purple); }
    .c-d .spd-stat-ico { background: var(--orange-pale); color: var(--orange); }
    .spd-stat:hover .spd-stat-ico {
        background: var(--orange-pale);
        color: var(--orange);
    }
    .spd-stat-ico svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
    }
    .spd-stat-val {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.5px;
        line-height: 1;
        margin-bottom: 2px;
        color: var(--text);
    }
    .spd-stat-unit {
        font-size: 12px;
        color: var(--text-3);
    }
    .spd-stat-foot {
        font-size: 11px;
        color: var(--text-3);
        margin-top: 4px;
    }

    /* Stock status */
    .spd-stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 40px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 8px;
    }
    .stock-excellent {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .stock-attention {
        background: #fef9c3;
        color: #854d0e;
        border: 1px solid #fde047;
    }
    .stock-critique {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* Card */
    .spd-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
        overflow: hidden;
        transition: border-color 0.2s;
    }
    .spd-card:hover {
        border-color: var(--orange-soft);
    }

    .spd-card-header {
        padding: 18px 24px;
        background: #fafbfd;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .spd-card-header-l {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .spd-card-ico {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: var(--orange-pale);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .spd-card-ico svg {
        width: 16px;
        height: 16px;
        stroke: var(--orange);
        fill: none;
    }
    .spd-card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
    }
    .spd-card-badge {
        font-size: 12px;
        font-weight: 500;
        color: var(--text-2);
        background: #f1f5f9;
        border-radius: 100px;
        padding: 4px 12px;
    }

    .spd-card-body {
        padding: 24px;
    }

    /* Info Grid */
    .spd-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 24px;
    }
    @media (max-width: 700px) {
        .spd-info-grid { grid-template-columns: 1fr; }
    }

    .spd-info-item {
        background: #fafbfd;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 16px;
    }
    .spd-info-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-3);
        margin-bottom: 4px;
    }
    .spd-info-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--text);
    }

    /* Description */
    .spd-description {
        background: #fafbfd;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 20px;
        margin-top: 20px;
    }
    .spd-description p {
        font-size: 14px;
        color: var(--text-2);
        line-height: 1.6;
    }

    /* Table */
    .spd-table-wrap {
        overflow-x: auto;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-light);
    }
    .spd-table {
        width: 100%;
        border-collapse: collapse;
    }
    .spd-table thead th {
        padding: 14px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-2);
        background: #f8fafc;
        border-bottom: 1px solid var(--border);
    }
    .spd-table tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: var(--text-2);
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }
    .spd-table tbody tr:last-child td {
        border-bottom: none;
    }
    .spd-table tbody tr {
        transition: background 0.15s;
    }
    .spd-table tbody tr:hover td {
        background: var(--orange-pale);
    }

    .spd-table tfoot td {
        padding: 14px 16px;
        background: #f8fafc;
        border-top: 2px solid var(--border);
        font-weight: 600;
    }

    /* Batch items */
    .spd-batch-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .spd-batch-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--orange);
    }

    /* Value colors */
    .text-profit {
        color: var(--success);
        font-weight: 600;
    }
    .text-loss {
        color: var(--danger);
        font-weight: 600;
    }

    /* Summary stats */
    .spd-summary-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-top: 20px;
    }
    .spd-summary-item {
        background: #fafbfd;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 16px;
        text-align: center;
    }
    .spd-summary-label {
        font-size: 11px;
        color: var(--text-3);
        margin-bottom: 4px;
    }
    .spd-summary-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
    }

    /* Actions side */
    .spd-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .spd-action-btn {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 14px 20px;
        background: var(--card);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 14px;
        font-weight: 600;
        color: var(--text-2);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .spd-action-btn svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
    }
    .spd-action-btn:hover {
        border-color: var(--orange);
        color: var(--orange);
        background: var(--orange-pale);
        transform: translateY(-2px);
    }
    .spd-action-btn.danger:hover {
        border-color: var(--danger);
        color: var(--danger);
        background: #fef2f2;
    }

    /* Meta info */
    .spd-meta {
        background: #fafbfd;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 20px;
    }
    .spd-meta-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-light);
    }
    .spd-meta-item:last-child {
        border-bottom: none;
    }
    .spd-meta-label {
        font-size: 12px;
        color: var(--text-3);
    }
    .spd-meta-value {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }

    /* Consistency alert */
    .spd-alert {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: var(--radius-sm);
        padding: 20px;
        margin-bottom: 24px;
    }
    .spd-alert-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }
    .spd-alert-header svg {
        width: 24px;
        height: 24px;
        stroke: var(--danger);
    }
    .spd-alert-header h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--danger);
    }
    .spd-alert-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    .spd-alert-item {
        background: rgba(220,38,38,0.05);
        border-radius: var(--radius-sm);
        padding: 12px;
        text-align: center;
    }
    .spd-alert-item .label {
        font-size: 11px;
        color: var(--text-2);
        margin-bottom: 4px;
    }
    .spd-alert-item .value {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
    }
    .spd-alert-item .diff {
        font-size: 14px;
        font-weight: 600;
        color: var(--danger);
    }

    /* ===== MODALES CORRIGÉES - PARFAITEMENT CENTRÉES ===== */
    #restockModal,
    #adjustmentModal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        backdrop-filter: blur(4px);
    }

    #restockModal.show,
    #adjustmentModal.show {
        display: flex !important;
    }

    #restockModal .modal-content,
    #adjustmentModal .modal-content {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        width: 100%;
        max-width: 28rem;
        max-height: 90vh;
        overflow-y: auto;
        animation: modalFadeIn 0.3s ease-out;
    }

    /* En-tête du modal */
    .modal-header {
        background: linear-gradient(135deg, #f97316, #ea580c);
        padding: 1.25rem;
        color: white;
    }

    .modal-header h3 {
        font-size: 1.25rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-header svg {
        stroke: white;
        fill: none;
    }

    .modal-header .close-btn {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .modal-header .close-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Corps du modal */
    .modal-body {
        padding: 1.5rem;
    }

    /* Formulaire */
    .modal-form-group {
        margin-bottom: 1rem;
    }

    .modal-form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.25rem;
    }

    .modal-form-input,
    .modal-form-select {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .modal-form-input:focus,
    .modal-form-select:focus {
        outline: none;
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }

    /* Pied du modal */
    .modal-footer {
        padding: 1rem 1.5rem 1.5rem;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        border-top: 1px solid #e5e7eb;
    }

    .modal-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .modal-btn-secondary {
        background: white;
        border: 1px solid #d1d5db;
        color: #374151;
    }

    .modal-btn-secondary:hover {
        background: #f9fafb;
    }

    .modal-btn-primary {
        background: linear-gradient(135deg, #f97316, #ea580c);
        border: none;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(249, 115, 22, 0.2);
    }

    .modal-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 12px -2px rgba(249, 115, 22, 0.3);
    }

    /* Empêcher le scroll quand modal ouvert */
    body.modal-open {
        overflow: hidden !important;
    }
</style>
@endsection

@section('content')
<div class="spd-page">

    {{-- HEADER --}}
    <div class="spd-header">
        <div class="spd-header-l">
            <div class="spd-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <div>
                <div class="spd-title">
                    {{ $product->name }} <span>#{{ $product->id }}</span>
                </div>
                <div class="spd-sub">Détails complets du produit</div>
            </div>
        </div>
        <div style="display: flex; gap: 8px;">
            @if(Auth::user() && Auth::user()->role === 'admin')
                <a href="{{ route('products.edit', $product->id) }}" class="btn-outline">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
            @endif
            <a href="{{ route('products.index') }}" class="btn-secondary">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="spd-stats">
        {{-- Stock Card --}}
        <div class="spd-stat c-a">
            <div class="spd-stat-top">
                <span class="spd-stat-label">Stock disponible</span>
                <div class="spd-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <div class="spd-stat-val">{{ number_format($product->stock, 0) }}</div>
            <div class="spd-stat-unit">unités</div>
            @php 
                $stock = $product->stock;
                $stockStatus = $stock > 10 ? 'excellent' : ($stock > 0 ? 'attention' : 'critique');
                $statusText = $stockStatus === 'excellent' ? 'Stock excellent' : ($stockStatus === 'attention' ? 'Stock faible' : 'Rupture de stock');
            @endphp
            <div class="spd-stock-badge stock-{{ $stockStatus }}">
                <span class="w-1.5 h-1.5 rounded-full" style="background:currentColor;"></span>
                {{ $statusText }}
            </div>
        </div>

        {{-- Sale Price Card --}}
        <div class="spd-stat c-b">
            <div class="spd-stat-top">
                <span class="spd-stat-label">Prix de vente</span>
                <div class="spd-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="spd-stat-val">{{ number_format($product->sale_price, 0, ',', ' ') }}</div>
            <div class="spd-stat-unit">CFA/unité</div>
            @if(Auth::user() && Auth::user()->role === 'admin' && $product->purchase_price > 0)
                <div class="spd-stat-foot">Achat: {{ number_format($product->purchase_price, 0, ',', ' ') }} CFA</div>
            @endif
        </div>

        {{-- Total Value Card --}}
        <div class="spd-stat c-c">
            <div class="spd-stat-top">
                <span class="spd-stat-label">Valeur totale</span>
                <div class="spd-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="spd-stat-val">{{ number_format($product->stock * $product->sale_price, 0, ',', ' ') }}</div>
            <div class="spd-stat-unit">CFA</div>
            @if($product->purchase_price > 0)
                <div class="spd-stat-foot">
                    Bénéfice: +{{ number_format($product->stock * ($product->sale_price - $product->purchase_price), 0, ',', ' ') }} CFA
                </div>
            @endif
        </div>

        {{-- Batches Card --}}
        <div class="spd-stat c-d">
            <div class="spd-stat-top">
                <span class="spd-stat-label">Lots/Stocks</span>
                <div class="spd-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16h8m-8-4h8m-4 8h8M8 8h8" />
                    </svg>
                </div>
            </div>
            <div class="spd-stat-val">{{ isset($stockSummary['batches_count']) ? $stockSummary['batches_count'] : 1 }}</div>
            <div class="spd-stat-unit">
                @if(isset($stockSummary['has_multiple_batches']) && $stockSummary['has_multiple_batches'])
                    Multiples lots
                @else
                    Lot unique
                @endif
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT GRID --}}
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        {{-- LEFT COLUMN --}}
        <div>
            {{-- PRODUCT DETAILS CARD --}}
            <div class="spd-card">
                <div class="spd-card-header">
                    <div class="spd-card-header-l">
                        <div class="spd-card-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="spd-card-title">Informations détaillées</span>
                    </div>
                </div>
                <div class="spd-card-body">
                    <div class="spd-info-grid">
                        <div class="spd-info-item">
                            <div class="spd-info-label">Nom du produit</div>
                            <div class="spd-info-value">{{ $product->name }}</div>
                        </div>
                        <div class="spd-info-item">
                            <div class="spd-info-label">Catégorie</div>
                            <div class="spd-info-value">{{ $product->category->name ?? 'Non catégorisé' }}</div>
                        </div>
                        <div class="spd-info-item">
                            <div class="spd-info-label">Fournisseur</div>
                            <div class="spd-info-value">{{ $product->supplier->name ?? 'Non spécifié' }}</div>
                        </div>
                        @if(Auth::user() && Auth::user()->role === 'admin')
                            <div class="spd-info-item">
                                <div class="spd-info-label">Prix d'achat</div>
                                <div class="spd-info-value">{{ number_format($product->purchase_price, 0, ',', ' ') }} CFA</div>
                            </div>
                        @endif
                        <div class="spd-info-item">
                            <div class="spd-info-label">Prix de vente</div>
                            <div class="spd-info-value text-profit">{{ number_format($product->sale_price, 0, ',', ' ') }} CFA</div>
                        </div>
                        @if($product->purchase_price > 0)
                            <div class="spd-info-item">
                                <div class="spd-info-label">Marge unitaire</div>
                                <div class="spd-info-value text-profit">
                                    +{{ number_format($product->sale_price - $product->purchase_price, 0, ',', ' ') }} CFA
                                    <span style="font-size:11px; color:var(--text-3);">
                                        ({{ number_format(($product->sale_price - $product->purchase_price) / $product->purchase_price * 100, 1, ',', ' ') }}%)
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Description --}}
                    <div class="spd-description">
                        <h4 style="font-size:14px; font-weight:600; color:var(--text); margin-bottom:12px;">Description</h4>
                        @if($product->description)
                            <p>{{ $product->description }}</p>
                        @else
                            <div style="text-align:center; padding:20px; color:var(--text-3);">
                                <svg viewBox="0 0 24 24" stroke-width="1.5" style="width:32px;height:32px; margin:0 auto 8px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                                <p>Aucune description</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- BATCHES TABLE --}}
            @if(isset($stockSummary['has_multiple_batches']) && $stockSummary['has_multiple_batches'])
                <div class="spd-card">
                    <div class="spd-card-header">
                        <div class="spd-card-header-l">
                            <div class="spd-card-ico">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16h8m-8-4h8m-4 8h8M8 8h8" />
                                </svg>
                            </div>
                            <span class="spd-card-title">Stocks par lot/prix</span>
                        </div>
                        <span class="spd-card-badge">{{ $stockSummary['batches_count'] }} lot(s)</span>
                    </div>
                    <div class="spd-card-body">
                        @if(isset($stockByPrice) && count($stockByPrice) > 0)
                            <div class="spd-table-wrap">
                                <table class="spd-table">
                                    <thead>
                                        <tr>
                                            <th>Lot/Référence</th>
                                            <th style="text-align:center;">Quantité</th>
                                            <th style="text-align:right;">Prix d'achat</th>
                                            <th style="text-align:right;">Valeur achat</th>
                                            <th style="text-align:right;">Valeur actuelle</th>
                                            <th style="text-align:right;">Bénéfice</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stockByPrice as $batch)
                                            @php
                                                $valeur_achat = ($batch->total_quantity ?? 0) * ($batch->purchase_price ?? 0);
                                                $valeur_actuelle = ($batch->total_quantity ?? 0) * ($product->sale_price ?? 0);
                                                $benefice = $valeur_actuelle - $valeur_achat;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="spd-batch-indicator">
                                                        <span class="spd-batch-dot"></span>
                                                        <span style="font-weight:600;">{{ $batch->reference_document ?? 'Lot ' . $loop->iteration }}</span>
                                                    </div>
                                                    @if(isset($batch->last_update) && $batch->last_update)
                                                        <div style="font-size:11px; color:var(--text-3); margin-top:2px;">
                                                            {{ \Carbon\Carbon::parse($batch->last_update)->format('d/m/Y') }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td style="text-align:center;">
                                                    <span style="font-weight:600;">{{ number_format($batch->total_quantity ?? 0, 0) }}</span>
                                                </td>
                                                <td style="text-align:right;">
                                                    {{ number_format($batch->purchase_price ?? 0, 0, ',', ' ') }} CFA
                                                </td>
                                                <td style="text-align:right;">
                                                    {{ number_format($valeur_achat, 0, ',', ' ') }} CFA
                                                </td>
                                                <td style="text-align:right;">
                                                    <span style="color:var(--info); font-weight:600;">
                                                        {{ number_format($valeur_actuelle, 0, ',', ' ') }} CFA
                                                    </span>
                                                </td>
                                                <td style="text-align:right;">
                                                    <span class="{{ $benefice >= 0 ? 'text-profit' : 'text-loss' }}">
                                                        {{ $benefice >= 0 ? '+' : '' }}{{ number_format($benefice, 0, ',', ' ') }} CFA
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        @php
                                            $totalValuePurchase = isset($stockTotals['total_value_purchase']) ? $stockTotals['total_value_purchase'] : 0;
                                            $profitPotential = isset($stockTotals['profit_potential']) ? $stockTotals['profit_potential'] : 0;
                                        @endphp
                                        <tr>
                                            <td colspan="6">
                                                <div class="spd-summary-stats">
                                                    <div class="spd-summary-item">
                                                        <div class="spd-summary-label">Prix achat moyen</div>
                                                        <div class="spd-summary-value">{{ number_format($stockSummary['average_purchase_price'] ?? 0, 0, ',', ' ') }} CFA</div>
                                                    </div>
                                                    <div class="spd-summary-item">
                                                        <div class="spd-summary-label">Valeur totale</div>
                                                        <div class="spd-summary-value">{{ number_format($stockSummary['total_value'] ?? 0, 0, ',', ' ') }} CFA</div>
                                                    </div>
                                                    <div class="spd-summary-item">
                                                        <div class="spd-summary-label">Bénéfice potentiel</div>
                                                        <div class="spd-summary-value text-profit">+{{ number_format($profitPotential, 0, ',', ' ') }} CFA</div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div style="margin-top:20px; text-align:center;">
                                <a href="{{ route('reports.grouped-stocks') }}" class="btn-outline">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Voir le rapport complet
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT COLUMN --}}
        <div>
            {{-- QUICK ACTIONS --}}
            <div class="spd-card">
                <div class="spd-card-header">
                    <div class="spd-card-header-l">
                        <div class="spd-card-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="spd-card-title">Actions rapides</span>
                    </div>
                </div>
                <div class="spd-card-body">
                    <div class="spd-actions">
                        @if(Auth::user() && Auth::user()->role === 'admin')
                            <button onclick="openRestockModal()" class="spd-action-btn">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Réapprovisionner
                            </button>

                            <button onclick="openAdjustmentModal()" class="spd-action-btn">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Ajuster le stock
                            </button>

                            <a href="{{ route('products.edit', $product->id) }}" class="spd-action-btn">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Modifier le produit
                            </a>

                            <a href="{{ route('products.history', $product->id) }}" class="spd-action-btn">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Voir l'historique
                            </a>

                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.')" style="width:100%;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="spd-action-btn danger">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Supprimer le produit
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- PRODUCT META --}}
            <div class="spd-card">
                <div class="spd-card-header">
                    <div class="spd-card-header-l">
                        <div class="spd-card-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="spd-card-title">Métadonnées</span>
                    </div>
                </div>
                <div class="spd-card-body">
                    <div class="spd-meta">
                        <div class="spd-meta-item">
                            <span class="spd-meta-label">ID du produit</span>
                            <span class="spd-meta-value">#{{ $product->id }}</span>
                        </div>
                        <div class="spd-meta-item">
                            <span class="spd-meta-label">Créé le</span>
                            <span class="spd-meta-value">{{ $product->created_at?->format('d/m/Y à H:i') ?? 'N/A' }}</span>
                        </div>
                        <div class="spd-meta-item">
                            <span class="spd-meta-label">Dernière modification</span>
                            <span class="spd-meta-value">{{ $product->updated_at?->format('d/m/Y à H:i') ?? 'N/A' }}</span>
                        </div>
                        @if($product->category)
                            <div class="spd-meta-item">
                                <span class="spd-meta-label">Catégorie</span>
                                <span class="spd-meta-value">{{ $product->category->name }}</span>
                            </div>
                        @endif
                        @if($product->supplier)
                            <div class="spd-meta-item">
                                <span class="spd-meta-label">Fournisseur</span>
                                <span class="spd-meta-value">{{ $product->supplier->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- STOCK CONSISTENCY ALERT --}}
            @if(isset($stockConsistency) && $stockConsistency['is_consistent'] === false)
                <div class="spd-alert">
                    <div class="spd-alert-header">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3>Incohérence détectée</h3>
                    </div>
                    <div class="spd-alert-grid">
                        <div class="spd-alert-item">
                            <div class="label">Stock actuel</div>
                            <div class="value">{{ $stockConsistency['current_stock'] }}</div>
                        </div>
                        <div class="spd-alert-item">
                            <div class="label">Stock calculé</div>
                            <div class="value">{{ $stockConsistency['calculated_stock'] }}</div>
                        </div>
                        <div class="spd-alert-item">
                            <div class="label">Différence</div>
                            <div class="diff">{{ $stockConsistency['difference'] >= 0 ? '+' : '' }}{{ $stockConsistency['difference'] }}</div>
                        </div>
                    </div>
                    <div style="margin-top:16px; text-align:center;">
                        <a href="{{ route('products.history', $product->id) }}" class="btn-outline">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Vérifier l'historique
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- RECENT MOVEMENTS --}}
    <div class="spd-card" style="margin-top:24px;">
        <div class="spd-card-header">
            <div class="spd-card-header-l">
                <div class="spd-card-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="spd-card-title">Derniers mouvements</span>
            </div>
            <a href="{{ route('products.history', $product->id) }}" class="btn-outline">
                <span>Voir tout</span>
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
        <div class="spd-card-body">
            @php
                $recentMovements = $product->stockMovements()->limit(5)->get();
            @endphp
            
            @if($recentMovements->count() > 0)
                <div class="spd-table-wrap">
                    <table class="spd-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Quantité</th>
                                <th>Motif</th>
                                <th>Stock après</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentMovements as $movement)
                                <tr>
                                    <td>
                                        <div style="font-weight:600;">{{ $movement->created_at->format('d/m/Y') }}</div>
                                        <div style="font-size:11px; color:var(--text-3);">{{ $movement->created_at->format('H:i') }}</div>
                                    </td>
                                    <td>
                                        @if($movement->type == 'entree')
                                            <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 10px; background:#dcfce7; color:#166534; border-radius:40px; font-size:11px; font-weight:600;">
                                                <svg viewBox="0 0 24 24" stroke-width="2" style="width:12px;height:12px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                </svg>
                                                Entrée
                                            </span>
                                        @else
                                            <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 10px; background:#fee2e2; color:#991b1b; border-radius:40px; font-size:11px; font-weight:600;">
                                                <svg viewBox="0 0 24 24" stroke-width="2" style="width:12px;height:12px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                                </svg>
                                                Sortie
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="font-weight:600; {{ $movement->type == 'entree' ? 'color:#16a34a;' : 'color:#dc2626;' }}">
                                            {{ $movement->type == 'entree' ? '+' : '-' }}{{ $movement->quantity }}
                                        </span>
                                    </td>
                                    <td>{{ $movement->motif }}</td>
                                    <td><span style="font-weight:600;">{{ $movement->stock_after }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align:center; padding:40px;">
                    <div style="width:56px; height:56px; background:#f1f5f9; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                        <svg viewBox="0 0 24 24" stroke-width="1.5" style="width:28px;height:28px; stroke:var(--text-3);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 style="font-size:15px; font-weight:600; color:var(--text); margin-bottom:4px;">Aucun mouvement récent</h4>
                    <p style="font-size:13px; color:var(--text-2);">Les mouvements de stock apparaîtront ici.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL RÉAPPROVISIONNEMENT - PARFAITEMENT CENTRÉE --}}
<div id="restockModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden modal-content">
        <!-- En-tête orange -->
        <div class="bg-gradient-to-r from-[#f97316] to-[#ea580c] p-5 text-white">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold flex items-center gap-3">
                    <svg viewBox="0 0 24 24" stroke-width="2" class="w-6 h-6 stroke-white fill-none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Réapprovisionner
                </h3>
                <button type="button" onclick="closeRestockModal()" class="text-white hover:text-gray-200 transition-colors">
                    <svg viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5 stroke-white fill-none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-orange-100 mt-1 text-sm">Ajouter du stock à ce produit</p>
        </div>
        
        <form action="{{ route('products.restock', $product->id) }}" method="POST" class="p-5">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantité à ajouter</label>
                    <input type="number" name="amount" min="1" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="Ex: 10">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix d'achat (CFA)</label>
                    <input type="number" name="purchase_price" min="0" step="1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           value="{{ $product->purchase_price }}" placeholder="Ex: 5000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix de vente (CFA)</label>
                    <input type="number" name="sale_price" min="0" step="1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           value="{{ $product->sale_price }}" placeholder="Ex: 6500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Référence / Lot</label>
                    <input type="text" name="reference_document" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="Ex: LOT-2024-001">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motif</label>
                    <input type="text" name="motif" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           value="Réapprovisionnement" placeholder="Motif du mouvement">
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeRestockModal()" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 font-medium shadow-md hover:shadow-lg transition-all">
                    Confirmer
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL AJUSTEMENT - PARFAITEMENT CENTRÉE --}}
<div id="adjustmentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden modal-content">
        <!-- En-tête orange -->
        <div class="bg-gradient-to-r from-[#f97316] to-[#ea580c] p-5 text-white">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold flex items-center gap-3">
                    <svg viewBox="0 0 24 24" stroke-width="2" class="w-6 h-6 stroke-white fill-none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Ajuster le stock
                </h3>
                <button type="button" onclick="closeAdjustmentModal()" class="text-white hover:text-gray-200 transition-colors">
                    <svg viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5 stroke-white fill-none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-orange-100 mt-1 text-sm">Ajuster manuellement le stock</p>
        </div>
        
        <form action="{{ route('products.adjust-stock', $product->id) }}" method="POST" class="p-5">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type d'ajustement</label>
                    <select name="adjustment_type" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="add">Entrée (augmentation)</option>
                        <option value="remove">Sortie (diminution)</option>
                        <option value="set">Définir à une valeur exacte</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
                    <input type="number" name="amount" min="0" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="Ex: 5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau prix d'achat (optionnel)</label>
                    <input type="number" name="purchase_price" min="0" step="1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="Laissez vide pour conserver">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau prix de vente (optionnel)</label>
                    <input type="number" name="sale_price" min="0" step="1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="Laissez vide pour conserver">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motif</label>
                    <input type="text" name="reason" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="Ex: Inventaire, casse, perte...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Référence</label>
                    <input type="text" name="reference_document" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="Ex: ADJ-2024-001">
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeAdjustmentModal()" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 font-medium shadow-md hover:shadow-lg transition-all">
                    Confirmer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Gestion des modaux - Version corrigée avec centrage parfait
function openRestockModal() {
    const modal = document.getElementById('restockModal');
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    // Empêcher le scroll du body
    document.body.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
}

function closeRestockModal() {
    const modal = document.getElementById('restockModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    // Réactiver le scroll
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
}

function openAdjustmentModal() {
    const modal = document.getElementById('adjustmentModal');
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
}

function closeAdjustmentModal() {
    const modal = document.getElementById('adjustmentModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
}

// Fermer avec Échap
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeRestockModal();
        closeAdjustmentModal();
    }
});

// Fermer en cliquant à l'extérieur
document.addEventListener('DOMContentLoaded', function() {
    const modals = ['restockModal', 'adjustmentModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    if (modalId === 'restockModal') closeRestockModal();
                    else closeAdjustmentModal();
                }
            });
        }
    });
});

// S'assurer que les modales sont bien cachées au chargement
window.addEventListener('load', function() {
    closeRestockModal();
    closeAdjustmentModal();
});
</script>
@endsection