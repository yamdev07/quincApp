@extends('layouts.app')

@section('title', 'Entreprises — Super Admin')

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
    }
    .st-card:hover {
        border-color: var(--orange);
    }

    /* Table */
    .st-table-wrap {
        overflow-x: auto;
    }
    .st-table {
        width: 100%;
        border-collapse: collapse;
    }
    .st-table thead th {
        padding: 16px 20px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-2);
        background: var(--gray-50);
        border-bottom: 1px solid var(--border);
    }
    .st-table thead th:last-child {
        text-align: center;
    }
    .st-table tbody td {
        padding: 16px 20px;
        font-size: 13px;
        color: var(--text-2);
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }
    .st-table tbody td:last-child {
        text-align: center;
    }
    .st-table tbody tr {
        transition: background 0.15s;
    }
    .st-table tbody tr:hover td {
        background: var(--orange-pale);
    }

    /* Tenant */
    .st-tenant {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .st-avatar {
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
    .st-tenant-info {
        flex: 1;
    }
    .st-tenant-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 2px;
    }
    .st-tenant-id {
        font-size: 11px;
        color: var(--text-3);
    }

    /* Badge */
    .st-badge {
        display: inline-block;
        padding: 4px 10px;
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
    .st-contact-empty {
        font-size: 12px;
        color: var(--text-3);
        font-style: italic;
    }

    /* Date */
    .st-date {
        font-size: 13px;
        font-weight: 500;
        color: var(--text);
    }
    .st-time {
        font-size: 11px;
        color: var(--text-3);
        margin-top: 2px;
    }

    /* Actions */
    .st-actions {
        display: flex;
        justify-content: center;
        gap: 8px;
    }
    .st-btn {
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
    .st-btn svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        fill: none;
    }
    .st-btn:hover {
        transform: scale(1.08);
        box-shadow: var(--shadow-sm);
    }
    .st-btn-view {
        border-color: var(--orange-soft);
        color: var(--orange);
    }
    .st-btn-view:hover {
        background: var(--orange);
        border-color: var(--orange);
        color: #fff;
    }
    .st-btn-toggle {
        border-color: var(--gray-300);
        color: var(--gray-600);
    }
    .st-btn-toggle:hover {
        background: var(--gray-600);
        border-color: var(--gray-600);
        color: #fff;
    }
    .st-btn-delete {
        border-color: var(--gray-300);
        color: var(--gray-600);
    }
    .st-btn-delete:hover {
        background: var(--gray-600);
        border-color: var(--gray-600);
        color: #fff;
    }

    /* Empty state */
    .st-empty {
        padding: 64px 24px;
        text-align: center;
    }
    .st-empty-ico {
        width: 72px;
        height: 72px;
        background: var(--gray-100);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .st-empty-ico svg {
        width: 32px;
        height: 32px;
        stroke: var(--text-3);
        fill: none;
    }
    .st-empty h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }
    .st-empty p {
        font-size: 14px;
        color: var(--text-2);
        margin-bottom: 20px;
    }

    /* Pagination */
    .st-pagination {
        background: var(--gray-50);
        border-top: 1px solid var(--border);
        padding: 16px 24px;
    }
    .st-pagination nav { width: 100%; }
    .st-pagination .pagination {
        display: flex;
        justify-content: center;
        gap: 6px;
        list-style: none;
        flex-wrap: wrap;
    }
    .st-pagination .page-item .page-link {
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
    .st-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        border-color: var(--orange);
        color: #fff;
        box-shadow: 0 4px 12px rgba(249,115,22,0.2);
    }
    .st-pagination .page-item .page-link:hover {
        border-color: var(--orange);
        color: var(--orange);
        background: var(--orange-pale);
    }
</style>
@endsection

@section('content')
<div class="st-page">

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
                    Gestion des <span>entreprises</span>
                </div>
                <div class="st-sub">Gérez l'ensemble des boutiques multi-tenants</div>
            </div>
        </div>
        <a href="{{ route('super-admin.tenants.create') }}" class="btn-primary">
            <svg viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Nouvelle entreprise
        </a>
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="st-stat-info">
                    <div class="st-stat-label">Total boutiques</div>
                    <div class="st-stat-value">{{ $tenants->total() }}</div>
                </div>
            </div>
        </div>

        <div class="st-stat">
            <div class="st-stat-content">
                <div class="st-stat-ico" style="background: var(--orange-pale); color: var(--orange);">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="st-stat-info">
                    <div class="st-stat-label">Actives</div>
                    <div class="st-stat-value">{{ $tenants->where('is_active', true)->count() }}</div>
                </div>
            </div>
        </div>

        <div class="st-stat">
            <div class="st-stat-content">
                <div class="st-stat-ico" style="background: var(--gray-100); color: var(--gray-500);">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
                <div class="st-stat-info">
                    <div class="st-stat-label">Inactives</div>
                    <div class="st-stat-value">{{ $tenants->where('is_active', false)->count() }}</div>
                </div>
            </div>
        </div>

        <div class="st-stat">
            <div class="st-stat-content">
                <div class="st-stat-ico" style="background: var(--gray-100); color: var(--gray-500);">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="st-stat-info">
                    <div class="st-stat-label">Utilisateurs</div>
                    <div class="st-stat-value">{{ \App\Models\User::count() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="st-card">
        <div class="st-table-wrap">
            <table class="st-table">
                <thead>
                    <tr>
                        <th>Entreprise</th>
                        <th>Sous-domaine</th>
                        <th>Propriétaire</th>
                        <th>Statut</th>
                        <th>Date création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $tenant)
                        <tr>
                            <td>
                                <div class="st-tenant">
                                    <div class="st-avatar">
                                        {{ strtoupper(substr($tenant->company_name, 0, 1)) }}
                                    </div>
                                    <div class="st-tenant-info">
                                        <div class="st-tenant-name">{{ $tenant->company_name }}</div>
                                        <div class="st-tenant-id">ID: {{ $tenant->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code class="st-code">{{ $tenant->subdomain }}.{{ config('app.domain') }}</code>
                            </td>
                            <td>
                                @if($tenant->owner)
                                    <div class="st-contact">
                                        <svg viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $tenant->owner->name }}
                                    </div>
                                    <div class="st-contact">
                                        <svg viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ $tenant->owner->email }}
                                    </div>
                                @else
                                    <div class="st-contact-empty">Aucun propriétaire</div>
                                @endif
                            </td>
                            <td>
                                @if($tenant->is_active)
                                    <span class="st-badge st-badge-success">Actif</span>
                                @else
                                    <span class="st-badge st-badge-danger">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <div class="st-date">{{ $tenant->created_at->format('d/m/Y') }}</div>
                                <div class="st-time">{{ $tenant->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                <div class="st-actions">
                                    <a href="{{ route('super-admin.tenants.show', $tenant) }}" 
                                       class="st-btn st-btn-view" title="Voir détails">
                                        <svg viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    
                                    <form action="{{ route('super-admin.tenants.toggle', $tenant) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="st-btn st-btn-toggle" 
                                                title="{{ $tenant->is_active ? 'Désactiver' : 'Activer' }}">
                                            <svg viewBox="0 0 24 24" stroke-width="2">
                                                @if($tenant->is_active)
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                @endif
                                            </svg>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('super-admin.tenants.destroy', $tenant) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cette entreprise ? Toutes les données (utilisateurs, produits, ventes, etc.) seront définitivement perdues.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="st-btn st-btn-delete" title="Supprimer">
                                            <svg viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="st-empty">
                                    <div class="st-empty-ico">
                                        <svg viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <h3>Aucune entreprise trouvée</h3>
                                    <p>Commencez par créer votre première boutique</p>
                                    <a href="{{ route('super-admin.tenants.create') }}" class="btn-primary">
                                        <svg viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Créer une entreprise
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($tenants->hasPages())
            <div class="st-pagination">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>
</div>
@endsection