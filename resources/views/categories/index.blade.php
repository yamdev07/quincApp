@extends('layouts.app')

@section('title', 'Catégories — QuincaApp')

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
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to   { opacity: 0; transform: translateY(-10px); }
    }

    /* Page */
    .sc-page {
        max-width: 1280px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .sc-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        animation: fadeUp 0.35s ease both;
    }

    .sc-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .sc-hex {
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
    .sc-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .sc-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .sc-title span {
        color: var(--orange);
        font-weight: 800;
    }
    .sc-sub {
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
    .sc-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: var(--radius-sm);
        margin-bottom: 24px;
        animation: fadeIn 0.3s ease-out;
        border-left: 4px solid var(--success);
        background: #f0fdf4;
        color: #166534;
    }
    .sc-alert svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
        flex-shrink: 0;
    }

    /* Stats Cards */
    .sc-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 700px) {
        .sc-stats { grid-template-columns: 1fr; }
    }

    .sc-stat {
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
    .sc-stat:nth-child(2) { animation-delay:0.07s; }
    .sc-stat:nth-child(3) { animation-delay:0.14s; }
    .sc-stat:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--orange-soft);
    }

    .sc-stat::before {
        content: '';
        position: absolute;
        top: 14px;
        bottom: 14px;
        left: 0;
        width: 4px;
        border-radius: 0 4px 4px 0;
    }
    .sc-stat.c-a::before { background: var(--info); }
    .sc-stat.c-b::before { background: var(--purple); }
    .sc-stat.c-c::before { background: var(--success); }

    .sc-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
    }
    .sc-stat-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-3);
    }
    .sc-stat-ico {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .c-a .sc-stat-ico { background: #eff6ff; color: var(--info); }
    .c-b .sc-stat-ico { background: #f5f3ff; color: var(--purple); }
    .c-c .sc-stat-ico { background: #f0fdf4; color: var(--success); }
    .sc-stat:hover .sc-stat-ico {
        background: var(--orange-pale);
        color: var(--orange);
    }
    .sc-stat-ico svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
    }
    .sc-stat-val {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.5px;
        line-height: 1;
        margin-bottom: 2px;
        color: var(--text);
    }
    .sc-stat-unit {
        font-size: 12px;
        color: var(--text-3);
        margin-top: 2px;
    }

    /* Search Bar */
    .sc-search-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .sc-search-body {
        padding: 24px;
    }
    .sc-search-wrapper {
        position: relative;
        flex: 1;
    }
    .sc-search-ico {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-3);
    }
    .sc-search-ico svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
    }
    .sc-search-input {
        width: 100%;
        padding: 14px 18px 14px 48px;
        background: #fafbfd;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 15px;
        color: var(--text);
        transition: all 0.2s;
    }
    .sc-search-input:focus {
        border-color: var(--orange);
        outline: none;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
        background: var(--card);
    }
    .sc-search-clear {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-3);
        cursor: pointer;
        padding: 4px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .sc-search-clear:hover {
        color: var(--danger);
        background: #f1f5f9;
    }
    .sc-search-hint {
        margin-top: 12px;
        font-size: 12px;
        color: var(--text-3);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Card */
    .sc-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: border-color 0.2s;
    }
    .sc-card:hover {
        border-color: var(--orange-soft);
    }

    /* Table */
    .sc-table-wrap {
        overflow-x: auto;
    }
    .sc-table {
        width: 100%;
        border-collapse: collapse;
    }
    .sc-table thead th {
        padding: 16px 20px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #fff;
        background: #1e293b;
        border-bottom: 1px solid #334155;
    }
    .sc-table thead th:last-child {
        text-align: center;
    }
    .sc-table tbody td {
        padding: 16px 20px;
        font-size: 13px;
        color: var(--text-2);
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }
    .sc-table tbody td:last-child {
        text-align: center;
    }
    .sc-table tbody tr {
        transition: background 0.15s;
    }
    .sc-table tbody tr:hover td {
        background: var(--orange-pale);
    }

    /* ID Badge */
    .sc-id {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f1f5f9;
        color: var(--text-2);
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
    }
    tr:hover .sc-id {
        background: var(--orange-pale);
        color: var(--orange);
    }

    /* Category */
    .sc-category {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sc-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 16px;
        box-shadow: var(--shadow-sm);
    }
    .sc-category-info {
        flex: 1;
    }
    .sc-category-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 2px;
    }
    .sc-category-id {
        font-size: 11px;
        color: var(--text-3);
    }

    /* Subname */
    .sc-subname {
        font-size: 13px;
        color: var(--text-2);
    }

    /* Actions */
    .sc-actions {
        display: flex;
        justify-content: center;
        gap: 6px;
    }
    .sc-btn {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s;
        background: var(--card);
    }
    .sc-btn svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        fill: none;
    }
    .sc-btn:hover {
        transform: scale(1.08);
        box-shadow: var(--shadow-sm);
    }
    .sc-btn-view {
        border-color: #bfdbfe;
        color: var(--info);
    }
    .sc-btn-view:hover {
        background: var(--info);
        border-color: var(--info);
        color: #fff;
    }
    .sc-btn-edit {
        border-color: var(--orange-soft);
        color: var(--orange);
    }
    .sc-btn-edit:hover {
        background: var(--orange);
        border-color: var(--orange);
        color: #fff;
    }
    .sc-btn-delete {
        border-color: #fecaca;
        color: var(--danger);
    }
    .sc-btn-delete:hover {
        background: var(--danger);
        border-color: var(--danger);
        color: #fff;
    }
    .sc-badge-readonly {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: #f1f5f9;
        border: 1px solid var(--border);
        border-radius: 40px;
        font-size: 12px;
        font-weight: 500;
        color: var(--text-2);
    }

    /* Empty state */
    .sc-empty {
        padding: 64px 24px;
        text-align: center;
    }
    .sc-empty-ico {
        width: 72px;
        height: 72px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .sc-empty-ico svg {
        width: 32px;
        height: 32px;
        stroke: var(--text-3);
        fill: none;
    }
    .sc-empty h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }
    .sc-empty p {
        font-size: 14px;
        color: var(--text-2);
        margin-bottom: 20px;
    }

    /* No results */
    .sc-no-results {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        padding: 48px 24px;
        text-align: center;
        margin-top: 24px;
    }
    .sc-no-results svg {
        width: 48px;
        height: 48px;
        stroke: var(--text-3);
        margin-bottom: 16px;
    }
    .sc-no-results h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }
    .sc-no-results p {
        font-size: 14px;
        color: var(--text-2);
        margin-bottom: 20px;
    }

    /* Pagination */
    .sc-pagination {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px 24px;
        box-shadow: var(--shadow-sm);
        margin-top: 24px;
    }
    .sc-pagination nav { width: 100%; }
    .sc-pagination .pagination {
        display: flex;
        justify-content: center;
        gap: 6px;
        list-style: none;
        flex-wrap: wrap;
    }
    .sc-pagination .page-item .page-link {
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
    .sc-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        border-color: var(--orange);
        color: #fff;
        box-shadow: 0 4px 12px rgba(249,115,22,0.3);
    }
    .sc-pagination .page-item .page-link:hover {
        border-color: var(--orange);
        color: var(--orange);
        background: var(--orange-pale);
    }

    /* Animations */
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    .animate-fade-out {
        animation: fadeOut 0.3s ease-out;
    }
</style>
@endsection

@section('content')
<div class="sc-page">

    {{-- HEADER --}}
    <div class="sc-header">
        <div class="sc-header-l">
            <div class="sc-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <div class="sc-title">
                    Gestion des <span>catégories</span>
                </div>
                <div class="sc-sub">Organisez et gérez vos catégories de produits</div>
            </div>
        </div>
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('categories.create') }}" class="btn-primary">
                <svg viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle catégorie
            </a>
        @endif
    </div>

    {{-- SEARCH CARD --}}
    <div class="sc-search-card">
        <div class="sc-search-body">
            <div class="flex items-center gap-4 flex-wrap sm:flex-nowrap">
                <div class="sc-search-wrapper">
                    <span class="sc-search-ico">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Rechercher une catégorie par nom, sous-nom ou ID..." 
                           class="sc-search-input">
                    <button id="clearSearch" class="sc-search-clear hidden">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="width:16px;height:16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <button id="searchButton" class="btn-primary" style="white-space:nowrap;">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Rechercher
                </button>
            </div>
            <div class="sc-search-hint">
                <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Recherchez par : nom, sous-nom, ID</span>
            </div>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="sc-alert">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="font-semibold">Succès !</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
            <button class="sc-alert-close" onclick="this.parentElement.remove()">
                <svg viewBox="0 0 24 24" stroke-width="2" style="width:18px;height:18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- STATS CARDS --}}
    <div class="sc-stats">
        <div class="sc-stat c-a">
            <div class="sc-stat-top">
                <span class="sc-stat-label">Total catégories</span>
                <div class="sc-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="sc-stat-val" id="totalCategories">{{ $categories->count() }}</div>
        </div>

        <div class="sc-stat c-b">
            <div class="sc-stat-top">
                <span class="sc-stat-label">Catégories actives</span>
                <div class="sc-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="sc-stat-val" id="activeCategories">{{ $categories->count() }}</div>
        </div>

        <div class="sc-stat c-c">
            <div class="sc-stat-top">
                <span class="sc-stat-label">Organisation</span>
                <div class="sc-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="sc-stat-val" id="organizationStatus">
                @if($categories->count() == 0) Vide
                @elseif($categories->count() < 5) Bonne
                @else Optimale
                @endif
            </div>
            <div class="sc-stat-unit">
                @if($categories->count() == 0) Aucune catégorie
                @elseif($categories->count() < 5) À développer
                @else Organisation parfaite
                @endif
            </div>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="sc-card">
        <div class="sc-table-wrap">
            <table class="sc-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Sous-nom</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody">
                    @forelse($categories as $category)
                        <tr class="category-row" 
                            data-id="{{ $category->id }}"
                            data-name="{{ strtolower($category->name) }}"
                            data-subname="{{ strtolower($category->sub_name ?? '') }}">
                            <td>
                                <span class="sc-id">{{ $category->id }}</span>
                            </td>
                            <td>
                                <div class="sc-category">
                                    <div class="sc-avatar">
                                        {{ substr($category->name, 0, 1) }}
                                    </div>
                                    <div class="sc-category-info">
                                        <div class="sc-category-name category-name">{{ $category->name }}</div>
                                        <div class="sc-category-id">Catégorie #{{ $category->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="sc-subname category-subname">{{ $category->sub_name ?? '-' }}</span>
                            </td>
                            <td>
                                @if(auth()->user()->role === 'admin')
                                    <div class="sc-actions">
                                        <a href="{{ route('categories.show', $category->id) }}" class="sc-btn sc-btn-view" title="Voir">
                                            <svg viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('categories.edit', $category->id) }}" class="sc-btn sc-btn-edit" title="Modifier">
                                            <svg viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" 
                                              onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cette catégorie ?')" 
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sc-btn sc-btn-delete" title="Supprimer">
                                                <svg viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="sc-badge-readonly">
                                        <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lecture seule
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr id="noResultsRow">
                            <td colspan="4">
                                <div class="sc-empty">
                                    <div class="sc-empty-ico">
                                        <svg viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h3>Aucune catégorie trouvée</h3>
                                    <p>Commencez par créer votre première catégorie</p>
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('categories.create') }}" class="btn-primary">
                                            <svg viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Créer une catégorie
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- NO RESULTS MESSAGE --}}
    <div id="noResultsMessage" class="sc-no-results hidden">
        <svg viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <h3>Aucun résultat trouvé</h3>
        <p>Aucune catégorie ne correspond à votre recherche.</p>
        <button id="clearSearchBtn" class="btn-outline">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Effacer la recherche
        </button>
    </div>

    {{-- PAGINATION --}}
    @if(method_exists($categories, 'links'))
        <div id="paginationContainer" class="sc-pagination">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments DOM
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const clearSearch = document.getElementById('clearSearch');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const categoriesTableBody = document.getElementById('categoriesTableBody');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const noResultsRow = document.getElementById('noResultsRow');
    const paginationContainer = document.getElementById('paginationContainer');
    const totalCategoriesElement = document.getElementById('totalCategories');
    const activeCategoriesElement = document.getElementById('activeCategories');
    const organizationStatusElement = document.getElementById('organizationStatus');

    // Données
    let originalRows = Array.from(document.querySelectorAll('.category-row'));
    let currentRows = [...originalRows];

    // Fonction de recherche
    function performSearch() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        
        if (searchTerm === '') {
            clearSearch.classList.add('hidden');
            showAllRows();
            updateStats();
            return;
        }
        
        clearSearch.classList.remove('hidden');
        
        // Filtrer les lignes
        const filteredRows = originalRows.filter(row => {
            const id = row.dataset.id || '';
            const name = row.dataset.name || '';
            const subname = row.dataset.subname || '';
            
            return id.includes(searchTerm) || 
                   name.includes(searchTerm) || 
                   subname.includes(searchTerm);
        });
        
        currentRows = filteredRows;
        displayFilteredRows(filteredRows);
        updateStats();
    }
    
    // Afficher les lignes filtrées
    function displayFilteredRows(rows) {
        // Masquer toutes les lignes
        originalRows.forEach(row => {
            row.style.display = 'none';
        });
        
        // Masquer la ligne "aucun résultat" initiale si elle existe
        if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
        
        // Masquer la pagination
        if (paginationContainer) {
            paginationContainer.style.display = 'none';
        }
        
        // Afficher les lignes filtrées
        if (rows.length > 0) {
            rows.forEach(row => {
                row.style.display = '';
            });
            noResultsMessage.classList.add('hidden');
        } else {
            noResultsMessage.classList.remove('hidden');
        }
    }
    
    // Afficher toutes les lignes
    function showAllRows() {
        originalRows.forEach(row => {
            row.style.display = '';
        });
        
        // Réafficher la ligne "aucun résultat" initiale si nécessaire
        if (noResultsRow && originalRows.length === 0) {
            noResultsRow.style.display = '';
        }
        
        // Réafficher la pagination
        if (paginationContainer) {
            paginationContainer.style.display = 'block';
        }
        
        // Masquer le message "aucun résultat"
        noResultsMessage.classList.add('hidden');
    }
    
    // Mettre à jour les statistiques
    function updateStats() {
        let total = currentRows.length;
        
        totalCategoriesElement.textContent = total;
        activeCategoriesElement.textContent = total;
        
        // Mettre à jour le statut d'organisation
        if (total === 0) {
            organizationStatusElement.textContent = 'Vide';
            organizationStatusElement.className = 'sc-stat-val text-danger';
        } else if (total < 5) {
            organizationStatusElement.textContent = 'Bonne';
            organizationStatusElement.className = 'sc-stat-val text-warning';
        } else {
            organizationStatusElement.textContent = 'Optimale';
            organizationStatusElement.className = 'sc-stat-val text-success';
        }
    }
    
    // Événements
    searchButton.addEventListener('click', performSearch);
    
    searchInput.addEventListener('input', performSearch); // Recherche en temps réel
    
    searchInput.addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            performSearch();
        }
    });
    
    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        clearSearch.classList.add('hidden');
        showAllRows();
        updateStats();
        searchInput.focus();
    });
    
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            clearSearch.classList.add('hidden');
            showAllRows();
            updateStats();
            searchInput.focus();
        });
    }
    
    // Recherche initiale si l'input a déjà une valeur
    if (searchInput.value.trim() !== '') {
        performSearch();
    }
});
</script>
@endsection