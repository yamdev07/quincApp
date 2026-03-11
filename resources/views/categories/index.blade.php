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
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
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

    /* Alertes */
    .sc-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: var(--radius-sm);
        margin-bottom: 24px;
        animation: fadeUp 0.35s 0.07s ease both;
        border-left: 4px solid;
    }
    .sc-alert svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
        flex-shrink: 0;
    }
    .sc-alert-success {
        background: #f0fdf4;
        border-color: var(--success);
        color: #166534;
    }
    .sc-alert-error {
        background: #fef2f2;
        border-color: var(--danger);
        color: #991b1b;
    }

    /* Stats Cards */
    .sc-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1100px) { .sc-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 580px)  { .sc-stats { grid-template-columns: 1fr; } }

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
    }
    .sc-stat-foot {
        font-size: 11px;
        color: var(--text-3);
        margin-top: 4px;
    }

    /* Card */
    .sc-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
        overflow: hidden;
        transition: border-color 0.2s;
    }
    .sc-card:hover {
        border-color: var(--orange-soft);
    }

    .sc-card-header {
        padding: 18px 24px;
        background: #fafbfd;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .sc-card-header-l {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sc-card-ico {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: var(--orange-pale);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sc-card-ico svg {
        width: 16px;
        height: 16px;
        stroke: var(--orange);
        fill: none;
    }
    .sc-card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
    }

    .sc-card-body {
        padding: 24px;
    }

    /* Search Bar */
    .sc-search {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .sc-search-wrapper {
        flex: 1;
        position: relative;
        min-width: 250px;
    }
    .sc-search-ico {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }
    .sc-search-ico svg {
        width: 18px;
        height: 18px;
        stroke: var(--text-3);
        fill: none;
    }
    .sc-search-input {
        width: 100%;
        padding: 12px 16px 12px 44px;
        background: var(--card);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 14px;
        color: var(--text);
        font-family: inherit;
        outline: none;
        transition: all 0.2s;
    }
    .sc-search-input:focus {
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
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

    /* Filter chips */
    .sc-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 16px 0;
    }
    .sc-filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: #f1f5f9;
        border: 1px solid var(--border-light);
        border-radius: 40px;
        font-size: 12px;
        font-weight: 500;
        color: var(--text-2);
        text-decoration: none;
        transition: all 0.2s;
    }
    .sc-filter-chip svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        fill: none;
    }
    .sc-filter-chip:hover {
        background: var(--orange-pale);
        border-color: var(--orange-soft);
        color: var(--orange);
    }
    .sc-filter-chip.active {
        background: var(--orange-pale);
        border-color: var(--orange);
        color: var(--orange);
        font-weight: 600;
    }

    .sc-filter-info {
        margin-top: 12px;
        padding: 12px 16px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .sc-filter-info-left {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #1e40af;
    }
    .sc-filter-info-left svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
    }

    /* Table */
    .sc-table-wrap {
        overflow-x: auto;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-light);
    }
    .sc-table {
        width: 100%;
        border-collapse: collapse;
    }
    .sc-table thead th {
        padding: 14px 16px;
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
        padding: 14px 16px;
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
        border-radius: 10px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
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
        width: 34px;
        height: 34px;
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

    /* Pagination */
    .sc-pagination {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 14px 22px;
        box-shadow: var(--shadow-sm);
        margin-top: 20px;
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
</style>
@endsection

@section('content')
<div class="sc-page">

    {{-- Vérification d'accès --}}
    @if(!auth()->user()->canManageStock())
        <div class="sc-alert" style="background: #fef2f2; border-left-color: var(--danger); color: #991b1b; justify-content: center; text-align: center;">
            <svg viewBox="0 0 24 24" stroke-width="1.5" style="width:48px;height:48px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <div>
                <h3 style="font-size:18px; font-weight:700; margin-bottom:8px;">Accès refusé</h3>
                <p>Vous n'avez pas les droits pour gérer les catégories.</p>
                <a href="{{ route('dashboard') }}" class="btn-outline" style="margin-top:16px; display:inline-flex;">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour au tableau de bord
                </a>
            </div>
        </div>
    @else

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
        <div class="sc-header-actions" style="display: flex; gap: 8px;">
            @if(auth()->user() && auth()->user()->canManageStock() && (auth()->user()->isSuperAdminGlobal() || auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()))
                <a href="{{ route('categories.create') }}" class="btn-primary">
                    <svg viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouvelle catégorie
                </a>
            @endif
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="sc-alert sc-alert-success">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="sc-alert sc-alert-error">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            {{ session('error') }}
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
            <div class="sc-stat-val">{{ $categories->count() }}</div>
            <div class="sc-stat-foot">Total</div>
        </div>

        <div class="sc-stat c-b">
            <div class="sc-stat-top">
                <span class="sc-stat-label">Avec sous-catégories</span>
                <div class="sc-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="sc-stat-val">{{ $categories->filter(function($cat) { return $cat->children->count() > 0; })->count() }}</div>
            <div class="sc-stat-foot">Catégories</div>
        </div>

        <div class="sc-stat c-c">
            <div class="sc-stat-top">
                <span class="sc-stat-label">Produits associés</span>
                <div class="sc-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
            </div>
            <div class="sc-stat-val">{{ $categories->sum('total_products') }}</div>
            <div class="sc-stat-foot">Produits</div>
        </div>
    </div>

    {{-- SEARCH CARD --}}
    <div class="sc-card">
        <div class="sc-card-header">
            <div class="sc-card-header-l">
                <div class="sc-card-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <span class="sc-card-title">Recherche et filtres</span>
            </div>
        </div>
        <div class="sc-card-body">
            <form action="{{ route('categories.index') }}" method="GET" id="searchForm">
                <div class="sc-search">
                    <div class="sc-search-wrapper">
                        <span class="sc-search-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" 
                               name="search" 
                               id="searchInput" 
                               value="{{ request('search', '') }}" 
                               placeholder="Rechercher une catégorie par nom ou sous-nom..."
                               class="sc-search-input"
                               autocomplete="off">
                        @if(request('search'))
                            <button type="button" id="clearSearch" class="sc-search-clear" onclick="clearSearch()">
                                <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                    </div>
                    <button type="submit" class="btn-primary" id="searchButton">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Rechercher
                    </button>
                </div>

                <div class="sc-filters">
                    <a href="{{ route('categories.index') }}" 
                       class="sc-filter-chip {{ !request('filter') && !request('search') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Toutes
                    </a>
                    <a href="{{ route('categories.index', ['filter' => 'with_products']) }}" 
                       class="sc-filter-chip {{ request('filter') == 'with_products' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Avec produits
                    </a>
                    <a href="{{ route('categories.index', ['filter' => 'empty']) }}" 
                       class="sc-filter-chip {{ request('filter') == 'empty' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Vides
                    </a>
                    <a href="{{ route('categories.index', ['filter' => 'main']) }}" 
                       class="sc-filter-chip {{ request('filter') == 'main' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Principales
                    </a>
                    <a href="{{ route('categories.index', ['filter' => 'sub']) }}" 
                       class="sc-filter-chip {{ request('filter') == 'sub' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16h8m-8-4h8m-4 8h8M8 8h8" />
                        </svg>
                        Sous-catégories
                    </a>
                </div>

                @if(request('search') || request('filter'))
                    <div class="sc-filter-info">
                        <div class="sc-filter-info-left">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>
                                @if(request('search'))
                                    Recherche : "<strong>{{ request('search') }}</strong>" • 
                                @endif
                                @if(request('filter'))
                                    @php
                                        $filterLabels = [
                                            'with_products' => 'Avec produits',
                                            'empty' => 'Vides',
                                            'main' => 'Principales',
                                            'sub' => 'Sous-catégories'
                                        ];
                                    @endphp
                                    Filtre : <strong>{{ $filterLabels[request('filter')] ?? request('filter') }}</strong> • 
                                @endif
                                <strong>{{ $categories->count() }}</strong> résultat(s) trouvé(s)
                            </span>
                        </div>
                        <a href="{{ route('categories.index') }}" class="btn-outline" style="padding:4px 12px;">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Effacer
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="sc-card" style="margin-bottom:24px;">
        <div class="sc-card-body" style="padding:16px 24px;">
            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-size:13px; font-weight:600; color:var(--text-2);">Actions rapides :</span>
                </div>
                <a href="{{ route('reports.products') }}" class="btn-outline">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Rapport
                </a>
                @if(auth()->user() && auth()->user()->canManageStock() && (auth()->user()->isSuperAdminGlobal() || auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()))
                    <a href="{{ route('categories.create') }}" class="btn-outline">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouvelle catégorie
                    </a>
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
                        <th>Catégorie</th>
                        <th>Sous-nom</th>
                        <th>Sous-catégories</th>
                        <th>Produits</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        @php
                            $subCount = $category->children->count();
                            $productCount = $category->total_products ?? $category->products->count();
                        @endphp
                        <tr>
                            <td>
                                <span class="sc-id">{{ $category->id }}</span>
                            </td>
                            <td>
                                <div class="sc-category">
                                    <div class="sc-avatar">
                                        {{ strtoupper(substr($category->name, 0, 1)) }}
                                    </div>
                                    <div class="sc-category-info">
                                        <div class="sc-category-name">{{ $category->name }}</div>
                                        <div class="sc-category-id">Catégorie #{{ $category->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="sc-subname">{{ $category->sub_name ?? '-' }}</span>
                            </td>
                            <td>
                                @if($subCount > 0)
                                    <span class="sc-status status-multiple">{{ $subCount }} sous-catégorie(s)</span>
                                @else
                                    <span class="sc-status status-simple">-</span>
                                @endif
                            </td>
                            <td>
                                @if($productCount > 0)
                                    <span class="sc-status status-multiple">{{ $productCount }} produit(s)</span>
                                @else
                                    <span class="sc-status status-simple">-</span>
                                @endif
                            </td>
                            <td>
                                @if(auth()->user() && auth()->user()->canManageStock() && (auth()->user()->isSuperAdminGlobal() || auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()))
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
                                              onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cette catégorie ?');" 
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
                        <tr>
                            <td colspan="6">
                                <div class="sc-empty">
                                    <div class="sc-empty-ico">
                                        <svg viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h3>Aucune catégorie trouvée</h3>
                                    <p>
                                        @if(request('search') || request('filter'))
                                            Essayez de modifier vos critères de recherche
                                        @else
                                            Commencez par créer votre première catégorie
                                        @endif
                                    </p>
                                    @if(request('search') || request('filter'))
                                        <a href="{{ route('categories.index') }}" class="btn-outline">
                                            <svg viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Voir tous
                                        </a>
                                    @else
                                        @if(auth()->user() && auth()->user()->canManageStock() && (auth()->user()->isSuperAdminGlobal() || auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()))
                                            <a href="{{ route('categories.create') }}" class="btn-primary">
                                                <svg viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Créer une catégorie
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if(method_exists($categories, 'hasPages') && $categories->hasPages())
        <div class="sc-pagination">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div style="font-size:13px; color:var(--text-2);">
                    Page {{ $categories->currentPage() }} sur {{ $categories->lastPage() }} • 
                    {{ $categories->total() }} catégorie(s)
                </div>
                <div>{{ $categories->appends(request()->except('page'))->links() }}</div>
            </div>
        </div>
    @endif

    @endif
</div>

<script>
function clearSearch() {
    window.location.href = "{{ route('categories.index') }}";
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.innerHTML = '<svg viewBox="0 0 24 24" stroke-width="2" style="width:16px;height:16px; animation:spin 1s linear infinite;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Recherche...';
            btn.disabled = true;
        });
    }
});
</script>
@endsection