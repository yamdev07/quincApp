@extends('layouts.app')

@section('title', 'Historique global des mouvements — Inventix')

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
    .sg-page {
        max-width: 1440px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .sg-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        animation: fadeUp 0.35s ease both;
    }

    .sg-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .sg-hex {
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
    .sg-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .sg-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .sg-title span {
        color: var(--orange);
        font-weight: 800;
    }
    .sg-sub {
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

    /* Stats Cards */
    .sg-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 700px) {
        .sg-stats { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 500px) {
        .sg-stats { grid-template-columns: 1fr; }
    }

    .sg-stat {
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
    .sg-stat:nth-child(2) { animation-delay:0.07s; }
    .sg-stat:nth-child(3) { animation-delay:0.14s; }
    .sg-stat:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--orange-soft);
    }

    .sg-stat::before {
        content: '';
        position: absolute;
        top: 14px;
        bottom: 14px;
        left: 0;
        width: 4px;
        border-radius: 0 4px 4px 0;
    }
    .sg-stat.c-a::before { background: var(--info); }
    .sg-stat.c-b::before { background: var(--success); }
    .sg-stat.c-c::before { background: var(--danger); }

    .sg-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
    }
    .sg-stat-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-3);
    }
    .sg-stat-ico {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .c-a .sg-stat-ico { background: #eff6ff; color: var(--info); }
    .c-b .sg-stat-ico { background: #f0fdf4; color: var(--success); }
    .c-c .sg-stat-ico { background: #fee2e2; color: var(--danger); }
    .sg-stat:hover .sg-stat-ico {
        background: var(--orange-pale);
        color: var(--orange);
    }
    .sg-stat-ico svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
    }
    .sg-stat-val {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.5px;
        line-height: 1;
        margin-bottom: 2px;
        color: var(--text);
    }
    .sg-stat-unit {
        font-size: 12px;
        color: var(--text-3);
    }

    /* Card */
    .sg-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
        overflow: hidden;
        transition: border-color 0.2s;
    }
    .sg-card:hover {
        border-color: var(--orange-soft);
    }

    .sg-card-header {
        padding: 18px 24px;
        background: #fafbfd;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .sg-card-header-l {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sg-card-ico {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: var(--orange-pale);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sg-card-ico svg {
        width: 16px;
        height: 16px;
        stroke: var(--orange);
        fill: none;
    }
    .sg-card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
    }

    .sg-card-body {
        padding: 24px;
    }

    /* Filter Form */
    .sg-filter-form {
        background: #fafbfd;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 24px;
        margin-bottom: 24px;
    }
    .sg-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }
    @media (max-width: 900px) {
        .sg-filter-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 500px) {
        .sg-filter-grid { grid-template-columns: 1fr; }
    }

    .sg-filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .sg-filter-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-3);
    }
    .sg-filter-select, .sg-filter-input {
        padding: 10px 12px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 13px;
        color: var(--text);
        background: var(--card);
        transition: all 0.2s;
    }
    .sg-filter-select:focus, .sg-filter-input:focus {
        border-color: var(--orange);
        outline: none;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
    }

    .sg-filter-search {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 16px;
        align-items: end;
    }
    @media (max-width: 700px) {
        .sg-filter-search { grid-template-columns: 1fr; }
    }

    .sg-filter-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    /* Table */
    .sg-table-wrap {
        overflow-x: auto;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-light);
    }
    .sg-table {
        width: 100%;
        border-collapse: collapse;
    }
    .sg-table thead th {
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
    .sg-table tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: var(--text-2);
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }
    .sg-table tbody tr:last-child td {
        border-bottom: none;
    }
    .sg-table tbody tr {
        transition: background 0.15s;
    }
    .sg-table tbody tr:hover td {
        background: var(--orange-pale);
    }

    /* Badges */
    .sg-badge {
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
    .sg-user {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sg-user-avatar {
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

    /* Action link */
    .sg-action-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        color: var(--info);
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        text-decoration: none;
        transition: all 0.2s;
    }
    .sg-action-link:hover {
        background: var(--info);
        color: #fff;
        border-color: var(--info);
    }
    .sg-action-link svg {
        width: 12px;
        height: 12px;
        stroke: currentColor;
        fill: none;
    }

    /* Empty state */
    .sg-empty {
        padding: 64px 24px;
        text-align: center;
    }
    .sg-empty-ico {
        width: 72px;
        height: 72px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .sg-empty-ico svg {
        width: 32px;
        height: 32px;
        stroke: var(--text-3);
        fill: none;
    }
    .sg-empty h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }
    .sg-empty p {
        font-size: 14px;
        color: var(--text-2);
        margin-bottom: 20px;
    }

    /* Pagination */
    .sg-pagination {
        margin-top: 24px;
    }
    .sg-pagination nav { width: 100%; }
    .sg-pagination .pagination {
        display: flex;
        justify-content: center;
        gap: 6px;
        list-style: none;
        flex-wrap: wrap;
    }
    .sg-pagination .page-item .page-link {
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
    .sg-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        border-color: var(--orange);
        color: #fff;
        box-shadow: 0 4px 12px rgba(249,115,22,0.3);
    }
    .sg-pagination .page-item .page-link:hover {
        border-color: var(--orange);
        color: var(--orange);
        background: var(--orange-pale);
    }
</style>
@endsection

@section('content')
<div class="sg-page">

    {{-- HEADER --}}
    <div class="sg-header">
        <div class="sg-header-l">
            <div class="sg-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div class="sg-title">
                    Historique <span>global</span>
                </div>
                <div class="sg-sub">Tous les mouvements de stock de l'application</div>
            </div>
        </div>
        <a href="{{ route('products.index') }}" class="btn-secondary">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour aux produits
        </a>
    </div>

    {{-- STATS CARDS --}}
    <div class="sg-stats">
        <div class="sg-stat c-a">
            <div class="sg-stat-top">
                <span class="sg-stat-label">Total mouvements</span>
                <div class="sg-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
            </div>
            <div class="sg-stat-val">{{ $stats->total_movements ?? 0 }}</div>
            <div class="sg-stat-unit">enregistrements</div>
        </div>

        <div class="sg-stat c-b">
            <div class="sg-stat-top">
                <span class="sg-stat-label">Total entrées</span>
                <div class="sg-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>
            </div>
            <div class="sg-stat-val">{{ $stats->total_entrees ?? 0 }}</div>
            <div class="sg-stat-unit">unités</div>
        </div>

        <div class="sg-stat c-c">
            <div class="sg-stat-top">
                <span class="sg-stat-label">Total sorties</span>
                <div class="sg-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                </div>
            </div>
            <div class="sg-stat-val">{{ $stats->total_sorties ?? 0 }}</div>
            <div class="sg-stat-unit">unités</div>
        </div>
    </div>

    {{-- FILTER FORM --}}
    <div class="sg-card">
        <div class="sg-card-header">
            <div class="sg-card-header-l">
                <div class="sg-card-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </div>
                <span class="sg-card-title">Filtres avancés</span>
            </div>
        </div>
        <div class="sg-card-body">
            <form method="GET" action="{{ route('products.global-history') }}">
                <div class="sg-filter-form">
                    <div class="sg-filter-grid">
                        <div class="sg-filter-group">
                            <label class="sg-filter-label">Produit</label>
                            <select name="product_id" class="sg-filter-select">
                                <option value="">Tous les produits</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sg-filter-group">
                            <label class="sg-filter-label">Type</label>
                            <select name="type" class="sg-filter-select">
                                <option value="">Tous les types</option>
                                <option value="entree" {{ request('type') == 'entree' ? 'selected' : '' }}>Entrées</option>
                                <option value="sortie" {{ request('type') == 'sortie' ? 'selected' : '' }}>Sorties</option>
                            </select>
                        </div>

                        <div class="sg-filter-group">
                            <label class="sg-filter-label">Date début</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="sg-filter-input">
                        </div>

                        <div class="sg-filter-group">
                            <label class="sg-filter-label">Date fin</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="sg-filter-input">
                        </div>
                    </div>

                    <div class="sg-filter-search">
                        <div class="sg-filter-group">
                            <label class="sg-filter-label">Recherche</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Rechercher par nom de produit..." 
                                   class="sg-filter-input">
                        </div>

                        <div class="sg-filter-actions">
                            <button type="submit" class="btn-primary" style="padding:8px 16px;">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filtrer
                            </button>
                            @if(request()->anyFilled(['product_id', 'type', 'start_date', 'end_date', 'search']))
                                <a href="{{ route('products.global-history') }}" class="btn-outline">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Effacer
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>

            {{-- MOVEMENTS TABLE --}}
            @if($movements->isEmpty())
                <div class="sg-empty">
                    <div class="sg-empty-ico">
                        <svg viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3>Aucun mouvement enregistré</h3>
                    <p>Aucun mouvement de stock n'a été enregistré dans le système.</p>
                </div>
            @else
                <div class="sg-table-wrap">
                    <table class="sg-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Produit</th>
                                <th>Type</th>
                                <th>Quantité</th>
                                <th>Stock après</th>
                                <th>Motif</th>
                                <th>Utilisateur</th>
                                <th>Actions</th>
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
                                        <div style="font-weight:600; color:var(--text);">{{ $movement->product->name ?? 'Produit inconnu' }}</div>
                                    </td>
                                    <td>
                                        @if($movement->type == 'entree')
                                            <span class="sg-badge badge-entree">
                                                <svg viewBox="0 0 24 24" stroke-width="2" style="width:12px;height:12px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                </svg>
                                                Entrée
                                            </span>
                                        @else
                                            <span class="sg-badge badge-sortie">
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
                                        <span class="sg-badge badge-stock">
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
                                        <div class="sg-user">
                                            <div class="sg-user-avatar">
                                                {{ substr($movement->user->name ?? 'S', 0, 1) }}
                                            </div>
                                            <span>{{ $movement->user->name ?? 'Système' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('products.history', $movement->product_id) }}" class="sg-action-link">
                                            <svg viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Voir
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($movements->hasPages())
                    <div class="sg-pagination">
                        {{ $movements->withQueryString()->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection