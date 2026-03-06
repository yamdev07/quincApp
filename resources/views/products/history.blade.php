@extends('layouts.app')

@section('title', 'Historique des mouvements — ' . $product->name . ' — QuincaApp')

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

    /* Page */
    .sh-page {
        max-width: 1280px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .sh-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        animation: fadeUp 0.35s ease both;
    }

    .sh-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .sh-hex {
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
    .sh-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .sh-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .sh-title span {
        color: var(--orange);
        font-weight: 800;
    }
    .sh-sub {
        font-size: 13px;
        color: var(--text-3);
        margin-top: 4px;
    }

    .sh-product-info {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-2);
        margin-top: 4px;
    }
    .sh-product-name {
        font-weight: 700;
        color: var(--text);
    }
    .sh-stock-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 40px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 8px;
    }
    .stock-low {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .stock-ok {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
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

    /* Stats Cards */
    .sh-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1100px) { .sh-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 580px)  { .sh-stats { grid-template-columns: 1fr; } }

    .sh-stat {
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
    .sh-stat:nth-child(2) { animation-delay:0.07s; }
    .sh-stat:nth-child(3) { animation-delay:0.14s; }
    .sh-stat:nth-child(4) { animation-delay:0.21s; }
    .sh-stat:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--orange-soft);
    }

    .sh-stat::before {
        content: '';
        position: absolute;
        top: 14px;
        bottom: 14px;
        left: 0;
        width: 4px;
        border-radius: 0 4px 4px 0;
    }
    .sh-stat.c-a::before { background: var(--info); }
    .sh-stat.c-b::before { background: var(--success); }
    .sh-stat.c-c::before { background: var(--danger); }
    .sh-stat.c-d::before { background: var(--purple); }

    .sh-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
    }
    .sh-stat-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-3);
    }
    .sh-stat-ico {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .c-a .sh-stat-ico { background: #eff6ff; color: var(--info); }
    .c-b .sh-stat-ico { background: #f0fdf4; color: var(--success); }
    .c-c .sh-stat-ico { background: #fee2e2; color: var(--danger); }
    .c-d .sh-stat-ico { background: #f5f3ff; color: var(--purple); }
    .sh-stat:hover .sh-stat-ico {
        background: var(--orange-pale);
        color: var(--orange);
    }
    .sh-stat-ico svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
    }
    .sh-stat-val {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.5px;
        line-height: 1;
        margin-bottom: 2px;
        color: var(--text);
    }
    .sh-stat-unit {
        font-size: 12px;
        color: var(--text-3);
    }

    /* Card */
    .sh-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
        overflow: hidden;
        transition: border-color 0.2s;
    }
    .sh-card:hover {
        border-color: var(--orange-soft);
    }

    .sh-card-header {
        padding: 18px 24px;
        background: #fafbfd;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .sh-card-header-l {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sh-card-ico {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: var(--orange-pale);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sh-card-ico svg {
        width: 16px;
        height: 16px;
        stroke: var(--orange);
        fill: none;
    }
    .sh-card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
    }

    .sh-card-body {
        padding: 24px;
    }

    /* Filter Form */
    .sh-filter-form {
        background: #fafbfd;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 24px;
        margin-bottom: 24px;
    }
    .sh-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr) auto;
        gap: 16px;
        align-items: end;
    }
    @media (max-width: 900px) {
        .sh-filter-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 580px) {
        .sh-filter-grid { grid-template-columns: 1fr; }
    }

    .sh-filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .sh-filter-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-3);
    }
    .sh-filter-select, .sh-filter-input {
        padding: 10px 12px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 13px;
        color: var(--text);
        background: var(--card);
        transition: all 0.2s;
    }
    .sh-filter-select:focus, .sh-filter-input:focus {
        border-color: var(--orange);
        outline: none;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
    }

    .sh-filter-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    /* Table */
    .sh-table-wrap {
        overflow-x: auto;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-light);
    }
    .sh-table {
        width: 100%;
        border-collapse: collapse;
    }
    .sh-table thead th {
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
    .sh-table tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: var(--text-2);
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }
    .sh-table tbody tr:last-child td {
        border-bottom: none;
    }
    .sh-table tbody tr {
        transition: background 0.15s;
    }
    .sh-table tbody tr:hover td {
        background: var(--orange-pale);
    }

    /* Badges */
    .sh-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 40px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-entree {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .badge-sortie {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .badge-quantity {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 600;
    }
    .quantity-in {
        background: #dcfce7;
        color: #166534;
    }
    .quantity-out {
        background: #fee2e2;
        color: #991b1b;
    }
    .badge-stock {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    /* User avatar */
    .sh-user {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sh-user-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--orange-pale);
        color: var(--orange);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
    }

    /* Empty state */
    .sh-empty {
        padding: 64px 24px;
        text-align: center;
    }
    .sh-empty-ico {
        width: 72px;
        height: 72px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .sh-empty-ico svg {
        width: 32px;
        height: 32px;
        stroke: var(--text-3);
        fill: none;
    }
    .sh-empty h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }
    .sh-empty p {
        font-size: 14px;
        color: var(--text-2);
        margin-bottom: 20px;
    }

    /* Pagination */
    .sh-pagination {
        margin-top: 24px;
    }
    .sh-pagination nav { width: 100%; }
    .sh-pagination .pagination {
        display: flex;
        justify-content: center;
        gap: 6px;
        list-style: none;
        flex-wrap: wrap;
    }
    .sh-pagination .page-item .page-link {
        padding: 7px 14px;
        border-radius: 9px;
        border: 1.5px solid var(--border);
        color: var(--text-2);
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.18s;
        display: block;
        background: var(--card);
    }
    .sh-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        border-color: var(--orange);
        color: #fff;
        box-shadow: 0 4px 12px rgba(249,115,22,0.3);
    }
    .sh-pagination .page-item .page-link:hover {
        border-color: var(--orange);
        color: var(--orange);
        background: var(--orange-pale);
    }
</style>
@endsection

@section('content')
<div class="sh-page">

    {{-- HEADER --}}
    <div class="sh-header">
        <div class="sh-header-l">
            <div class="sh-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div class="sh-title">
                    Historique des <span>mouvements</span>
                </div>
                <div class="sh-product-info">
                    Produit: <span class="sh-product-name">{{ $product->name }}</span>
                    <span class="sh-stock-badge {{ $product->stock <= 10 ? 'stock-low' : 'stock-ok' }}">
                        Stock actuel: {{ $product->stock }}
                    </span>
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('products.show', $product) }}" class="btn-secondary">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour au produit
            </a>
            <a href="{{ route('products.global-history') }}" class="btn-outline">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Historique global
            </a>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="sh-stats">
        <div class="sh-stat c-a">
            <div class="sh-stat-top">
                <span class="sh-stat-label">Stock actuel</span>
                <div class="sh-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <div class="sh-stat-val">{{ $product->stock }}</div>
            <div class="sh-stat-unit">unités</div>
        </div>

        <div class="sh-stat c-b">
            <div class="sh-stat-top">
                <span class="sh-stat-label">Total entrées</span>
                <div class="sh-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>
            </div>
            <div class="sh-stat-val">{{ $totals['entree']->total_quantity ?? 0 }}</div>
            <div class="sh-stat-unit">unités</div>
        </div>

        <div class="sh-stat c-c">
            <div class="sh-stat-top">
                <span class="sh-stat-label">Total sorties</span>
                <div class="sh-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                </div>
            </div>
            <div class="sh-stat-val">{{ $totals['sortie']->total_quantity ?? 0 }}</div>
            <div class="sh-stat-unit">unités</div>
        </div>

        <div class="sh-stat c-d">
            <div class="sh-stat-top">
                <span class="sh-stat-label">Total mouvements</span>
                <div class="sh-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
            </div>
            <div class="sh-stat-val">{{ $movements->total() }}</div>
            <div class="sh-stat-unit">enregistrements</div>
        </div>
    </div>

    {{-- FILTER FORM --}}
    <div class="sh-card">
        <div class="sh-card-header">
            <div class="sh-card-header-l">
                <div class="sh-card-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </div>
                <span class="sh-card-title">Filtrer les mouvements</span>
            </div>
        </div>
        <div class="sh-card-body">
            <div class="sh-filter-form">
                <form method="GET" action="{{ route('products.history', $product) }}" class="sh-filter-grid">
                    <div class="sh-filter-group">
                        <label class="sh-filter-label">Type de mouvement</label>
                        <select name="type" class="sh-filter-select">
                            <option value="">Tous les types</option>
                            <option value="entree" {{ request('type') == 'entree' ? 'selected' : '' }}>Entrées</option>
                            <option value="sortie" {{ request('type') == 'sortie' ? 'selected' : '' }}>Sorties</option>
                        </select>
                    </div>

                    <div class="sh-filter-group">
                        <label class="sh-filter-label">Date de début</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="sh-filter-input">
                    </div>

                    <div class="sh-filter-group">
                        <label class="sh-filter-label">Date de fin</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="sh-filter-input">
                    </div>

                    <div class="sh-filter-actions">
                        <button type="submit" class="btn-primary" style="padding:8px 16px;">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filtrer
                        </button>
                        @if(request()->anyFilled(['type', 'start_date', 'end_date']))
                            <a href="{{ route('products.history', $product) }}" class="btn-outline">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Effacer
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- MOVEMENTS TABLE --}}
            @if($movements->isEmpty())
                <div class="sh-empty">
                    <div class="sh-empty-ico">
                        <svg viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3>Aucun mouvement enregistré</h3>
                    <p>Aucun mouvement de stock n'a été enregistré pour ce produit.</p>
                </div>
            @else
                <div class="sh-table-wrap">
                    <table class="sh-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Quantité</th>
                                <th>Stock après</th>
                                <th>Motif</th>
                                <th>Utilisateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movements as $movement)
                                <tr>
                                    <td>
                                        <div style="font-weight:600;">{{ $movement->created_at->format('d/m/Y') }}</div>
                                        <div style="font-size:11px; color:var(--text-3);">{{ $movement->created_at->format('H:i') }}</div>
                                    </td>
                                    <td>
                                        @if($movement->type == 'entree')
                                            <span class="sh-badge badge-entree">
                                                <svg viewBox="0 0 24 24" stroke-width="2" style="width:12px;height:12px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                </svg>
                                                Entrée
                                            </span>
                                        @else
                                            <span class="sh-badge badge-sortie">
                                                <svg viewBox="0 0 24 24" stroke-width="2" style="width:12px;height:12px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                                </svg>
                                                Sortie
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-quantity {{ $movement->type == 'entree' ? 'quantity-in' : 'quantity-out' }}">
                                            {{ $movement->type == 'entree' ? '+' : '-' }}{{ $movement->quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="sh-badge badge-stock">
                                            {{ $movement->stock_after }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="font-weight:500;">{{ $movement->motif }}</div>
                                        @if($movement->reference_document)
                                            <div style="font-size:11px; color:var(--text-3); margin-top:2px;">
                                                Réf: {{ $movement->reference_document }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="sh-user">
                                            <div class="sh-user-avatar">
                                                {{ substr($movement->user->name ?? 'S', 0, 1) }}
                                            </div>
                                            <span>{{ $movement->user->name ?? 'Système' }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($movements->hasPages())
                    <div class="sh-pagination">
                        {{ $movements->withQueryString()->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection