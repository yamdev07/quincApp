@extends('layouts.app')

@section('title', Auth::user()->isSuperAdminGlobal() ? 'Dashboard Global - Super Admin' : 'Dashboard - QuincaApp')

@section('styles')
<style>
    /* -----------------------------------------------------
       VARIABLES - UNIQUEMENT BLANC, GRIS, ORANGE
    ----------------------------------------------------- */
    :root {
        /* Blanc */
        --white: #ffffff;
        
        /* Gris */
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;
        
        /* Orange */
        --orange-50: #fff7ed;
        --orange-100: #ffedd5;
        --orange-200: #fed7aa;
        --orange-300: #fdba74;
        --orange-400: #fb923c;
        --orange-500: #f97316;
        --orange-600: #ea580c;
        --orange-700: #c2410c;
        
        /* Sémantique (uniquement nuances de gris/orange) */
        --bg-page: var(--gray-50);
        --bg-card: var(--white);
        --border: var(--gray-200);
        --border-soft: var(--gray-300);
        --text-primary: var(--gray-900);
        --text-secondary: var(--gray-600);
        --text-tertiary: var(--gray-500);
        
        /* Accent - uniquement orange */
        --accent: var(--orange-500);
        --accent-light: var(--orange-50);
        --accent-soft: var(--orange-200);
        --accent-gradient: linear-gradient(135deg, var(--orange-500), var(--orange-600));
        
        /* Badges (uniquement gris et orange) */
        --badge-bg: var(--gray-100);
        --badge-text: var(--gray-700);
        --badge-orange-bg: var(--orange-100);
        --badge-orange-text: var(--orange-700);
        
        /* Ombres */
        --shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        --shadow-orange: 0 8px 20px rgba(249, 115, 22, 0.15);
        
        /* Arrondis */
        --radius: 20px;
        --radius-sm: 12px;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

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

    /* =====================================================
       HEADER
    ===================================================== */
    .dash-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 20px;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .dash-header-left h1 {
        font-size: 32px;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0 0 6px;
        line-height: 1.2;
    }

    .dash-header-left h1 span {
        color: var(--accent);
    }

    .greeting {
        color: var(--text-tertiary);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .greeting-role {
        background: var(--gray-200);
        color: var(--gray-700);
        padding: 4px 14px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .greeting-date {
        background: var(--accent-light);
        color: var(--accent);
        padding: 4px 14px;
        border-radius: 30px;
        font-weight: 500;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .dash-header-right {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: var(--accent-gradient);
        color: var(--white);
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
        box-shadow: var(--shadow-orange);
        cursor: pointer;
    }

    .btn-primary i { font-size: 18px; }
    .btn-primary:hover {
        background: linear-gradient(135deg, var(--orange-600), var(--orange-700));
        transform: translateY(-2px);
        box-shadow: 0 12px 20px rgba(249, 115, 22, 0.25);
    }

    .btn-outline {
        background: transparent;
        border: 1.5px solid var(--border);
        color: var(--text-secondary);
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
        color: var(--accent);
    }

    /* =====================================================
       STATISTIQUES
    ===================================================== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    @media (min-width: 640px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .stats-grid { grid-template-columns: repeat(4, 1fr); } }

    .stat-card {
        background: var(--bg-card);
        border-radius: var(--radius);
        padding: 22px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        transition: all 0.25s;
        animation: slideUp 0.4s ease;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stat-card:hover {
        border-color: var(--accent);
        transform: translateY(-3px);
        box-shadow: var(--shadow-orange);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: var(--gray-100);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 26px;
        transition: all 0.2s;
    }
    .stat-card:hover .stat-icon {
        background: var(--accent-light);
    }

    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-tertiary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .stat-value {
        font-size: 34px;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.1;
        margin-bottom: 4px;
    }

    .stat-desc {
        font-size: 13px;
        color: var(--text-tertiary);
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .stat-badge {
        background: var(--gray-100);
        color: var(--gray-600);
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* =====================================================
       ALERTES
    ===================================================== */
    .alerts-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }
    @media (min-width: 768px) { .alerts-grid { grid-template-columns: repeat(3, 1fr); } }

    .alert-card {
        background: var(--bg-card);
        border-radius: 16px;
        padding: 16px 18px;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.2s;
    }

    .alert-card:hover {
        border-color: var(--accent);
    }

    .alert-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: var(--orange-100);
        color: var(--orange-600);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .alert-content {
        flex: 1;
    }

    .alert-title {
        font-weight: 700;
        font-size: 15px;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .alert-desc {
        font-size: 13px;
        color: var(--text-tertiary);
    }

    .alert-amount {
        font-weight: 700;
        color: var(--accent);
        font-size: 15px;
    }

    /* =====================================================
       TABLEAUX
    ===================================================== */
    .table-card {
        background: var(--bg-card);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: var(--shadow);
        margin-bottom: 24px;
    }

    .table-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        background: var(--gray-50);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .table-header h3 {
        font-weight: 700;
        font-size: 18px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-primary);
    }

    .badge-count {
        background: var(--gray-200);
        color: var(--gray-700);
        border-radius: 40px;
        padding: 4px 14px;
        font-size: 12px;
        font-weight: 600;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        text-align: left;
        padding: 16px 24px 12px;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border);
        background: var(--gray-50);
    }

    .table td {
        padding: 16px 24px;
        border-bottom: 1px solid var(--gray-200);
        color: var(--text-primary);
        font-size: 14px;
        font-weight: 500;
    }

    .table tbody tr:hover td {
        background: var(--orange-50);
    }

    /* =====================================================
       BADGES (uniquement gris et orange)
    ===================================================== */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 16px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 12px;
        gap: 6px;
    }

    .badge-gray {
        background: var(--gray-100);
        color: var(--gray-700);
    }

    .badge-orange {
        background: var(--orange-100);
        color: var(--orange-700);
    }

    /* =====================================================
       PRODUITS
    ===================================================== */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (min-width: 768px) { .products-grid { grid-template-columns: repeat(4, 1fr); } }

    .product-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 16px;
        transition: all 0.2s;
    }
    .product-card:hover {
        border-color: var(--accent);
    }

    .product-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .product-stock {
        font-size: 13px;
        color: var(--text-tertiary);
        margin-bottom: 12px;
    }

    .stock-indicator {
        width: 100%;
        height: 6px;
        background: var(--gray-200);
        border-radius: 3px;
        overflow: hidden;
    }

    .stock-bar {
        height: 100%;
        background: var(--accent);
        border-radius: 3px;
    }

    /* =====================================================
       ACTIONS
    ===================================================== */
    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-secondary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
    }

    .action-btn:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
    }

    /* =====================================================
       FILTRES
    ===================================================== */
    .filters-bar {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 60px;
        padding: 6px 6px 6px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 24px;
    }

    .filter-tabs {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 8px 18px;
        border-radius: 40px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-tab:hover {
        color: var(--accent);
        background: var(--orange-50);
    }

    .filter-tab.active {
        background: var(--accent);
        color: var(--white);
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--gray-50);
        border: 1px solid var(--border);
        border-radius: 40px;
        padding: 4px 4px 4px 16px;
    }

    .search-box input {
        border: none;
        background: transparent;
        padding: 8px 0;
        font-size: 14px;
        min-width: 220px;
        outline: none;
    }

    .search-box button {
        background: var(--accent);
        border: none;
        color: var(--white);
        padding: 8px 22px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .search-box button:hover {
        background: var(--orange-600);
    }

    /* =====================================================
       PAGINATION
    ===================================================== */
    .pagination {
        padding: 20px 24px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: center;
    }

    .pagination .page-link {
        padding: 8px 14px;
        border-radius: 10px;
        border: 1px solid var(--border);
        color: var(--text-secondary);
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        background: var(--bg-card);
    }

    .pagination .page-link:hover {
        border-color: var(--accent);
        color: var(--accent);
    }

    .pagination .active .page-link {
        background: var(--accent);
        border-color: var(--accent);
        color: var(--white);
    }

    /* =====================================================
       CHARTES
    ===================================================== */
    .chart-card {
        background: var(--bg-card);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        padding: 22px;
        box-shadow: var(--shadow);
        margin-bottom: 24px;
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
        padding-left: 12px;
        border-left: 4px solid var(--accent);
    }

    /* =====================================================
       SECURITY NOTE
    ===================================================== */
    .security-note {
        margin-top: 32px;
        padding: 16px 24px;
        background: var(--gray-100);
        border-radius: 60px;
        color: var(--text-secondary);
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        border: 1px solid var(--border);
    }

    .security-note i {
        color: var(--accent);
        font-size: 18px;
    }

    .security-badge {
        background: var(--gray-200);
        color: var(--gray-700);
        padding: 4px 16px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 11px;
        margin-left: auto;
    }
</style>
@endsection

@section('content')
<div class="dashboard">
    {{-- HEADER --}}
    <div class="dash-header">
        <div class="dash-header-left">
            @if(Auth::user()->isSuperAdminGlobal())
                <h1>Dashboard <span>Global</span></h1>
            @else
                <h1>Dashboard <span>QuincaApp</span></h1>
            @endif
            <div class="greeting">
                <span>👋 {{ Auth::user()->name }}</span>
                <span class="greeting-role">
                    <i class="bi bi-person-badge"></i> {{ ucfirst(Auth::user()->role) }}
                </span>
                <span class="greeting-date">
                    <i class="bi bi-calendar3"></i> {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                </span>
            </div>
        </div>
        <div class="dash-header-right">
            @if(Auth::user()->isSuperAdminGlobal())
                <a href="{{ route('super-admin.tenants') }}" class="btn-primary">
                    <i class="bi bi-building"></i> Toutes les quincailleries
                </a>
            @endif
            @if(Auth::user()->isSuperAdminOrAdmin())
                <a href="{{ route('products.create') }}" class="btn-outline">
                    <i class="bi bi-plus-circle"></i> Nouveau produit
                </a>
            @endif
        </div>
    </div>

    @if(Auth::user()->isSuperAdminGlobal())
        {{-- =====================================================
             DASHBOARD SUPER ADMIN GLOBAL
        ===================================================== --}}
        
        {{-- Stats globales --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Total quincailleries</span>
                    <span class="stat-icon"><i class="bi bi-building"></i></span>
                </div>
                <div class="stat-value">{{ $totalTenants ?? 0 }}</div>
                <div class="stat-desc">
                    <span class="stat-badge">{{ $activeTenants ?? 0 }} actives</span>
                    <span>{{ $inactiveTenants ?? 0 }} inactives</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">CA mensuel</span>
                    <span class="stat-icon"><i class="bi bi-currency-exchange"></i></span>
                </div>
                <div class="stat-value">{{ number_format($totalRevenueThisMonth ?? 0, 0, ',', ' ') }} FCFA</div>
                <div class="stat-desc">
                    <span class="stat-badge">{{ number_format($totalRevenueToday ?? 0, 0, ',', ' ') }} aujourd'hui</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Utilisateurs</span>
                    <span class="stat-icon"><i class="bi bi-people"></i></span>
                </div>
                <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                <div class="stat-desc">
                    <span class="stat-badge">{{ $totalSuperAdmins ?? 0 }} super admins</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Expirations</span>
                    <span class="stat-icon"><i class="bi bi-calendar"></i></span>
                </div>
                <div class="stat-value">{{ $expiringSoon ?? 0 }}</div>
                <div class="stat-desc">dans les 30 jours</div>
            </div>
        </div>

        {{-- Alertes --}}
        <div class="alerts-grid">
            <div class="alert-card">
                <div class="alert-icon"><i class="bi bi-exclamation-triangle"></i></div>
                <div class="alert-content">
                    <div class="alert-title">Paiements en retard</div>
                    <div class="alert-desc">5 quincailleries</div>
                </div>
                <div class="alert-amount">1 495 €</div>
            </div>
            <div class="alert-card">
                <div class="alert-icon"><i class="bi bi-clock-history"></i></div>
                <div class="alert-content">
                    <div class="alert-title">Essais gratuits</div>
                    <div class="alert-desc">3 expirent cette semaine</div>
                </div>
                <div class="alert-amount">J-3</div>
            </div>
            <div class="alert-card">
                <div class="alert-icon"><i class="bi bi-check-circle"></i></div>
                <div class="alert-content">
                    <div class="alert-title">Paiements OK</div>
                    <div class="alert-desc">42 ce mois</div>
                </div>
                <div class="alert-amount">13 450 €</div>
            </div>
        </div>

        {{-- Graphique --}}
        <div class="chart-card">
            <div class="chart-header">
                <h3>Évolution des inscriptions</h3>
            </div>
            <div style="height: 300px; background: var(--gray-50); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; color: var(--text-tertiary);">
                <i class="bi bi-bar-chart" style="font-size: 48px; margin-right: 16px;"></i>
                Graphique des inscriptions (12 mois)
            </div>
        </div>

        {{-- Top 5 --}}
        <div class="table-card">
            <div class="table-header">
                <h3><i class="bi bi-trophy" style="color: var(--accent);"></i> Top 5 quincailleries</h3>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Quincaillerie</th>
                            <th>Propriétaire</th>
                            <th>Utilisateurs</th>
                            <th>CA</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topTenants ?? [] as $tenant)
                        <tr>
                            <td>{{ $tenant->company_name }}</td>
                            <td>{{ $tenant->owner->name ?? 'N/A' }}</td>
                            <td>{{ $tenant->users_count ?? 0 }}</td>
                            <td>{{ number_format($tenant->sales_sum_total_price ?? 0, 0, ',', ' ') }} FCFA</td>
                            <td>
                                @if($tenant->is_active)
                                    <span class="badge badge-orange">Actif</span>
                                @else
                                    <span class="badge badge-gray">Inactif</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-tertiary);">
                                <i class="bi bi-building" style="font-size: 32px;"></i>
                                <p>Aucune quincaillerie</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @else
        {{-- =====================================================
             DASHBOARD UTILISATEUR NORMAL (quincaillerie)
        ===================================================== --}}
        
        {{-- Stats du jour --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Ventes aujourd'hui</span>
                    <span class="stat-icon"><i class="bi bi-cart"></i></span>
                </div>
                <div class="stat-value">{{ $salesToday ?? 0 }}</div>
                <div class="stat-desc">
                    <span class="stat-badge">{{ number_format($totalRevenue ?? 0, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Chiffre d'affaires</span>
                    <span class="stat-icon"><i class="bi bi-currency-exchange"></i></span>
                </div>
                <div class="stat-value">{{ number_format($totalRevenueAll ?? 0, 0, ',', ' ') }} FCFA</div>
                <div class="stat-desc">Total global</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Stock faible</span>
                    <span class="stat-icon"><i class="bi bi-exclamation-triangle"></i></span>
                </div>
                <div class="stat-value">{{ $lowStockCount ?? 0 }}</div>
                <div class="stat-desc">produits à réapprovisionner</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Clients actifs</span>
                    <span class="stat-icon"><i class="bi bi-people"></i></span>
                </div>
                <div class="stat-value">{{ $activeClients ?? 0 }}</div>
                <div class="stat-desc">{{ $newClients ?? 0 }} nouveaux (7j)</div>
            </div>
        </div>

        {{-- Graphique des ventes --}}
        <div class="chart-card">
            <div class="chart-header">
                <h3>Ventes des 7 derniers jours</h3>
            </div>
            <div style="height: 250px; background: var(--gray-50); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; color: var(--text-tertiary);">
                <i class="bi bi-graph-up" style="font-size: 32px; margin-right: 12px;"></i>
                Graphique des ventes
            </div>
        </div>

        {{-- Produits en stock critique --}}
        @if(isset($criticalStockProducts) && $criticalStockProducts->count() > 0)
        <div class="table-card">
            <div class="table-header">
                <h3><i class="bi bi-exclamation-triangle" style="color: var(--accent);"></i> Stock critique</h3>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Stock</th>
                            <th>Prix</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($criticalStockProducts as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>
                                <span style="color: var(--accent); font-weight: 700;">{{ $product->stock }}</span>
                            </td>
                            <td>{{ number_format($product->sale_price, 0, ',', ' ') }} FCFA</td>
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="action-btn">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Ventes récentes --}}
        <div class="table-card">
            <div class="table-header">
                <h3><i class="bi bi-clock-history" style="color: var(--accent);"></i> Ventes récentes</h3>
                <a href="{{ route('sales.index') }}" class="btn-outline" style="height: 36px; padding: 0 16px;">
                    Voir tout
                </a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Produit</th>
                            <th>Montant</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSales ?? [] as $sale)
                        <tr>
                            <td>{{ $sale->client->name ?? 'Client inconnu' }}</td>
                            <td>
                                @foreach($sale->items as $item)
                                    {{ $item->product->name }} x{{ $item->quantity }}@if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>{{ number_format($sale->total_price, 0, ',', ' ') }} FCFA</td>
                            <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: var(--text-tertiary);">
                                <i class="bi bi-cart" style="font-size: 32px;"></i>
                                <p>Aucune vente récente</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Security note --}}
    <div class="security-note">
        <i class="bi bi-shield-check"></i>
        <span>QuincaApp - Gestion de quincaillerie</span>
        <span class="security-badge">v2.0</span>
    </div>
</div>
@endsection