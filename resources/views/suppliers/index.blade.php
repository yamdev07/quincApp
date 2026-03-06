@extends('layouts.app')

@section('title', 'Fournisseurs — QuincaApp')

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

    /* Page */
    .sf-page {
        max-width: 1280px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .sf-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        animation: fadeUp 0.35s ease both;
    }

    .sf-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .sf-hex {
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
    .sf-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .sf-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .sf-title span {
        color: var(--orange);
        font-weight: 800;
    }
    .sf-sub {
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
    .sf-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: var(--radius-sm);
        margin-bottom: 24px;
        animation: fadeUp 0.35s 0.07s ease both;
        border-left: 4px solid var(--success);
        background: #f0fdf4;
        color: #166534;
    }
    .sf-alert svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
        flex-shrink: 0;
    }

    /* Stats Cards */
    .sf-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1100px) { .sf-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 580px)  { .sf-stats { grid-template-columns: 1fr; } }

    .sf-stat {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px 20px;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
        animation: fadeUp 0.35s ease both;
    }
    .sf-stat:nth-child(2) { animation-delay:0.07s; }
    .sf-stat:nth-child(3) { animation-delay:0.14s; }
    .sf-stat:nth-child(4) { animation-delay:0.21s; }
    .sf-stat:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--orange-soft);
    }

    .sf-stat-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sf-stat-ico {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sf-stat-ico svg {
        width: 22px;
        height: 22px;
        stroke: currentColor;
        fill: none;
    }
    .sf-stat-info {
        flex: 1;
    }
    .sf-stat-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .sf-stat-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--text);
        line-height: 1.2;
    }

    /* Card */
    .sf-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: border-color 0.2s;
    }
    .sf-card:hover {
        border-color: var(--orange-soft);
    }

    /* Table */
    .sf-table-wrap {
        overflow-x: auto;
    }
    .sf-table {
        width: 100%;
        border-collapse: collapse;
    }
    .sf-table thead th {
        padding: 16px 20px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-2);
        background: #f8fafc;
        border-bottom: 1px solid var(--border);
    }
    .sf-table thead th:last-child {
        text-align: center;
    }
    .sf-table tbody td {
        padding: 16px 20px;
        font-size: 13px;
        color: var(--text-2);
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }
    .sf-table tbody td:last-child {
        text-align: center;
    }
    .sf-table tbody tr {
        transition: background 0.15s;
    }
    .sf-table tbody tr:hover td {
        background: var(--orange-pale);
    }

    /* Supplier */
    .sf-supplier {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sf-avatar {
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
    .sf-supplier-info {
        flex: 1;
    }
    .sf-supplier-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 2px;
    }
    .sf-supplier-id {
        font-size: 11px;
        color: var(--text-3);
    }

    /* Contact */
    .sf-contact {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--text-2);
        margin-bottom: 4px;
    }
    .sf-contact svg {
        width: 14px;
        height: 14px;
        stroke: var(--text-3);
        fill: none;
    }
    .sf-contact-empty {
        font-size: 12px;
        color: var(--text-3);
        font-style: italic;
    }

    /* Date */
    .sf-date {
        font-size: 13px;
        font-weight: 500;
        color: var(--text);
    }
    .sf-time {
        font-size: 11px;
        color: var(--text-3);
        margin-top: 2px;
    }

    /* Actions */
    .sf-actions {
        display: flex;
        justify-content: center;
        gap: 8px;
    }
    .sf-btn {
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
    .sf-btn svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        fill: none;
    }
    .sf-btn:hover {
        transform: scale(1.08);
        box-shadow: var(--shadow-sm);
    }
    .sf-btn-edit {
        border-color: var(--orange-soft);
        color: var(--orange);
    }
    .sf-btn-edit:hover {
        background: var(--orange);
        border-color: var(--orange);
        color: #fff;
    }
    .sf-btn-delete {
        border-color: #fecaca;
        color: var(--danger);
    }
    .sf-btn-delete:hover {
        background: var(--danger);
        border-color: var(--danger);
        color: #fff;
    }

    /* Empty state */
    .sf-empty {
        padding: 64px 24px;
        text-align: center;
    }
    .sf-empty-ico {
        width: 72px;
        height: 72px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .sf-empty-ico svg {
        width: 32px;
        height: 32px;
        stroke: var(--text-3);
        fill: none;
    }
    .sf-empty h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }
    .sf-empty p {
        font-size: 14px;
        color: var(--text-2);
        margin-bottom: 20px;
    }

    /* Pagination */
    .sf-pagination {
        background: #f8fafc;
        border-top: 1px solid var(--border);
        padding: 16px 24px;
    }
    .sf-pagination nav { width: 100%; }
    .sf-pagination .pagination {
        display: flex;
        justify-content: center;
        gap: 6px;
        list-style: none;
        flex-wrap: wrap;
    }
    .sf-pagination .page-item .page-link {
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
    .sf-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        border-color: var(--orange);
        color: #fff;
        box-shadow: 0 4px 12px rgba(249,115,22,0.3);
    }
    .sf-pagination .page-item .page-link:hover {
        border-color: var(--orange);
        color: var(--orange);
        background: var(--orange-pale);
    }
</style>
@endsection

@section('content')
<div class="sf-page">

    {{-- HEADER --}}
    <div class="sf-header">
        <div class="sf-header-l">
            <div class="sf-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <div class="sf-title">
                    Gestion des <span>fournisseurs</span>
                </div>
                <div class="sf-sub">Consultez et gérez vos partenaires fournisseurs</div>
            </div>
        </div>
        @if(Auth::user() && Auth::user()->role === 'admin')
            <a href="{{ route('suppliers.create') }}" class="btn-primary">
                <svg viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau fournisseur
            </a>
        @endif
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="sf-alert">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- STATS CARDS --}}
    <div class="sf-stats">
        <div class="sf-stat">
            <div class="sf-stat-content">
                <div class="sf-stat-ico" style="background: #fff7ed; color: var(--orange);">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="sf-stat-info">
                    <div class="sf-stat-label">Total Fournisseurs</div>
                    <div class="sf-stat-value">{{ $suppliers->total() }}</div>
                </div>
            </div>
        </div>

        <div class="sf-stat">
            <div class="sf-stat-content">
                <div class="sf-stat-ico" style="background: #f0fdf4; color: var(--success);">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="sf-stat-info">
                    <div class="sf-stat-label">Ajoutés ce mois</div>
                    <div class="sf-stat-value">{{ $suppliers->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
                </div>
            </div>
        </div>

        <div class="sf-stat">
            <div class="sf-stat-content">
                <div class="sf-stat-ico" style="background: #eff6ff; color: var(--info);">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="sf-stat-info">
                    <div class="sf-stat-label">Avec contact</div>
                    <div class="sf-stat-value">{{ $suppliers->where('contact', '!=', null)->count() }}</div>
                </div>
            </div>
        </div>

        <div class="sf-stat">
            <div class="sf-stat-content">
                <div class="sf-stat-ico" style="background: #f5f3ff; color: var(--purple);">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div class="sf-stat-info">
                    <div class="sf-stat-label">Avec téléphone</div>
                    <div class="sf-stat-value">{{ $suppliers->where('phone', '!=', null)->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="sf-card">
        <div class="sf-table-wrap">
            <table class="sf-table">
                <thead>
                    <tr>
                        <th>Fournisseur</th>
                        <th>Coordonnées</th>
                        <th>Date d'ajout</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td>
                                <div class="sf-supplier">
                                    <div class="sf-avatar">
                                        {{ strtoupper(substr($supplier->name, 0, 1)) }}
                                    </div>
                                    <div class="sf-supplier-info">
                                        <div class="sf-supplier-name">{{ $supplier->name }}</div>
                                        <div class="sf-supplier-id">ID: {{ $supplier->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($supplier->contact)
                                        <div class="sf-contact">
                                            <svg viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ $supplier->contact }}
                                        </div>
                                    @endif
                                    @if($supplier->phone)
                                        <div class="sf-contact">
                                            <svg viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $supplier->phone }}
                                        </div>
                                    @endif
                                    @if(!$supplier->contact && !$supplier->phone)
                                        <div class="sf-contact-empty">Aucune coordonnée</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="sf-date">{{ $supplier->created_at->format('d/m/Y') }}</div>
                                <div class="sf-time">{{ $supplier->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                @if(Auth::user() && Auth::user()->role === 'admin')
                                    <div class="sf-actions">
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="sf-btn sf-btn-edit" title="Éditer">
                                            <svg viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" 
                                              onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce fournisseur ?');" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sf-btn sf-btn-delete" title="Supprimer">
                                                <svg viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="sf-empty">
                                    <div class="sf-empty-ico">
                                        <svg viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <h3>Aucun fournisseur trouvé</h3>
                                    <p>Commencez par ajouter votre premier fournisseur</p>
                                    @if(Auth::user() && Auth::user()->role === 'admin')
                                        <a href="{{ route('suppliers.create') }}" class="btn-primary">
                                            <svg viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Ajouter un fournisseur
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($suppliers->hasPages())
            <div class="sf-pagination">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection