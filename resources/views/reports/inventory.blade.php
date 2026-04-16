@extends('layouts.app')

@section('title', 'Rapport d\'inventaire - Gestion de stock')

@section('styles')
<style>
    /* -----------------------------------------------------
       VARIABLES - NOIR & ORANGE (COULEURS PRINCIPALES)
    ----------------------------------------------------- */
    :root {
        --bg-page: #f8fafc;
        --bg-card: #ffffff;
        --bg-side: #f1f5f9;
        --border-light: #e2e8f0;
        --border-soft: #cbd5e1;
        
        /* Noir - textes principaux */
        --text-primary: #0f172a;
        --text-secondary: #334155;
        --text-tertiary: #64748b;
        
        /* Orange - accent principal */
        --accent: #f97316;
        --accent-dark: #ea580c;
        --accent-light: #ffedd5;
        --accent-soft: #fed7aa;
        --accent-gradient: linear-gradient(135deg, #f97316, #ea580c);
        
        /* Dégradé noir-orange */
        --gradient-dark: linear-gradient(145deg, #0f172a, #1e293b);
        
        --badge-low: #b91c1c;
        --badge-bg-low: #fee2e2;
        --badge-warning: #f97316;
        --badge-bg-warning: #fff3cd;
        --badge-success: #22c55e;
        --badge-bg-success: #dcfce7;
        
        --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        --shadow-hover: 0 10px 15px -3px rgba(249, 115, 22, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    * {
        box-sizing: border-box;
    }

    body {
        background: var(--bg-page);
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--text-primary);
    }

    .inventory-page {
        max-width: 1440px;
        margin: 0 auto;
        padding: 32px 24px;
    }

    /* =====================================================
       HEADER
    ===================================================== */
    .inventory-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .inventory-header-left h1 {
        font-size: 32px;
        font-weight: 700;
        background: linear-gradient(135deg, #0f172a 0%, #f97316 80%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.5px;
        margin: 0 0 6px;
    }

    .inventory-header-left .subtitle {
        color: var(--text-tertiary);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .date-badge {
        background: var(--accent-light);
        color: var(--accent-dark);
        padding: 4px 14px;
        border-radius: 30px;
        font-weight: 500;
        font-size: 13px;
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

    /* =====================================================
       FILTRES
    ===================================================== */
    .filters-card {
        background: var(--bg-card);
        border-radius: 24px;
        padding: 20px 24px;
        margin-bottom: 28px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-card);
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 160px;
    }

    .filter-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .filter-select, .filter-input {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid var(--border-light);
        border-radius: 14px;
        font-size: 14px;
        color: var(--text-primary);
        background: var(--bg-card);
        transition: all 0.2s;
    }

    .filter-select:focus, .filter-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }

    .btn-primary {
        background: var(--accent-gradient);
        border: none;
        padding: 10px 24px;
        border-radius: 40px;
        color: white;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
    }

    .btn-secondary {
        background: transparent;
        border: 1.5px solid var(--border-soft);
        padding: 10px 24px;
        border-radius: 40px;
        color: var(--text-secondary);
        font-weight: 500;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-secondary:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent-dark);
    }

    /* =====================================================
       STATISTIQUES
    ===================================================== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 22px;
        margin-bottom: 32px;
    }
    @media (min-width: 580px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .stats-grid { grid-template-columns: repeat(4, 1fr); } }

    .stat-card {
        background: var(--bg-card);
        border-radius: 24px;
        padding: 24px 22px;
        box-shadow: var(--shadow-card);
        border: 1px solid var(--border-light);
        transition: all 0.25s;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        box-shadow: var(--shadow-hover);
        border-color: var(--accent);
        transform: translateY(-2px);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--accent-gradient);
        opacity: 0;
        transition: opacity 0.2s;
    }
    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        background: var(--accent-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 26px;
        transition: all 0.2s;
    }
    .stat-card:hover .stat-icon {
        background: var(--accent);
        color: white;
    }

    .stat-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-tertiary);
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.1;
        margin-bottom: 6px;
    }

    .stat-desc {
        font-size: 13px;
        color: var(--text-tertiary);
    }

    /* =====================================================
       ALERTS
    ===================================================== */
    .alert-warning {
        background: var(--badge-bg-warning);
        border-left: 4px solid var(--badge-warning);
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--accent-dark);
    }
    .alert-warning i {
        font-size: 22px;
    }
    .alert-danger {
        background: var(--badge-bg-low);
        border-left: 4px solid var(--badge-low);
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--badge-low);
    }
    .alert-danger i {
        font-size: 22px;
    }

    /* =====================================================
       TABLEAU
    ===================================================== */
    .card {
        background: var(--bg-card);
        border-radius: 24px;
        border: 1px solid var(--border-light);
        overflow: hidden;
        margin-bottom: 28px;
    }

    .card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .card-header h4 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        padding: 16px 20px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-tertiary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: var(--bg-side);
        border-bottom: 1px solid var(--border-light);
    }

    .table tbody td {
        padding: 14px 20px;
        font-size: 14px;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border-light);
    }

    .table tbody tr:hover {
        background: var(--accent-light);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-success {
        background: var(--badge-bg-success);
        color: var(--badge-success);
    }
    .badge-warning {
        background: var(--badge-bg-warning);
        color: var(--badge-warning);
    }
    .badge-danger {
        background: var(--badge-bg-low);
        color: var(--badge-low);
    }

    .product-link {
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 600;
    }
    .product-link:hover {
        color: var(--accent);
    }

    .pagination {
        display: flex;
        justify-content: center;
        padding: 20px;
        gap: 8px;
    }

    .pagination .page-link {
        padding: 8px 14px;
        border: 1px solid var(--border-light);
        border-radius: 10px;
        color: var(--text-secondary);
        text-decoration: none;
        transition: all 0.2s;
    }
    .pagination .page-link:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent-dark);
    }
    .pagination .active .page-link {
        background: var(--accent);
        border-color: var(--accent);
        color: white;
    }

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
    .security-note i {
        color: var(--accent);
    }

    .text-accent { color: var(--accent); }
</style>
@endsection

@section('content')
<div class="inventory-page">

    {{-- HEADER --}}
    <div class="inventory-header">
        <div class="inventory-header-left">
            <h1>Rapport d'inventaire</h1>
            <div class="subtitle">
                <span>📦 État des stocks et valeur totale des produits</span>
                <span class="date-badge">{{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</span>
            </div>
        </div>
        <div class="inventory-header-right">
            <a href="{{ route('reports.index') }}" class="btn-outline">
                <i class="bi bi-arrow-left"></i> Retour aux rapports
            </a>
        </div>
    </div>

    {{-- FILTRES --}}
    <div class="filters-card">
        <form method="GET" action="{{ route('reports.inventory') }}" class="filter-form">
            <div class="filter-group">
                <label><i class="bi bi-tag"></i> Catégorie</label>
                <select name="category_id" class="filter-select">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label><i class="bi bi-building"></i> Fournisseur</label>
                <select name="supplier_id" class="filter-select">
                    <option value="">Tous les fournisseurs</option>
                    @foreach($suppliers ?? [] as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label><i class="bi bi-filter"></i> Statut stock</label>
                <select name="stock_status" class="filter-select">
                    <option value="">Tous</option>
                    <option value="in" {{ request('stock_status') == 'in' ? 'selected' : '' }}>En stock</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stock faible (≤10)</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Rupture</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label><i class="bi bi-search"></i> Recherche</label>
                <input type="text" name="search" class="filter-input" placeholder="Nom du produit..." value="{{ request('search') }}">
            </div>
            
            <div class="filter-group">
                <button type="submit" class="btn-primary">
                    <i class="bi bi-search"></i> Filtrer
                </button>
                <a href="{{ route('reports.inventory') }}" class="btn-secondary">
                    <i class="bi bi-arrow-repeat"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    {{-- STATISTIQUES --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-row">
                <span class="stat-title">Total produits</span>
                <span class="stat-icon"><i class="bi bi-boxes"></i></span>
            </div>
            <div class="stat-value">{{ $stats['total_products'] ?? 0 }}</div>
            <div class="stat-desc">références actives</div>
        </div>

        <div class="stat-card">
            <div class="stat-row">
                <span class="stat-title">Unités en stock</span>
                <span class="stat-icon"><i class="bi bi-cubes"></i></span>
            </div>
            <div class="stat-value">{{ number_format($stats['total_stock'] ?? 0) }}</div>
            <div class="stat-desc">articles disponibles</div>
        </div>

        <div class="stat-card">
            <div class="stat-row">
                <span class="stat-title">Valeur d'achat</span>
                <span class="stat-icon"><i class="bi bi-cart"></i></span>
            </div>
            <div class="stat-value">{{ number_format($stats['total_value'] ?? 0, 0, ',', ' ') }} FCFA</div>
            <div class="stat-desc">prix d'achat total</div>
        </div>

        <div class="stat-card">
            <div class="stat-row">
                <span class="stat-title">Valeur de vente</span>
                <span class="stat-icon"><i class="bi bi-tag"></i></span>
            </div>
            <div class="stat-value">{{ number_format($stats['total_sale_value'] ?? 0, 0, ',', ' ') }} FCFA</div>
            <div class="stat-desc">prix de vente total</div>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(($stats['low_stock_count'] ?? 0) > 0)
        <div class="alert-warning">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>{{ $stats['low_stock_count'] }} produit(s)</strong> en stock faible (≤ 10 unités). 
                Pensez à réapprovisionner bientôt.
            </div>
        </div>
    @endif
    
    @if(($stats['out_of_stock_count'] ?? 0) > 0)
        <div class="alert-danger">
            <i class="bi bi-x-circle-fill"></i>
            <div>
                <strong>{{ $stats['out_of_stock_count'] }} produit(s)</strong> en rupture de stock. 
                Réapprovisionnement urgent recommandé.
            </div>
        </div>
    @endif

    {{-- TABLEAU DES PRODUITS --}}
    <div class="card">
        <div class="card-header">
            <h4><i class="bi bi-list-ul me-2"></i> Liste des produits</h4>
            <div>
                <span class="date-badge">{{ $products->total() ?? 0 }} produit(s)</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Fournisseur</th>
                        <th>Stock</th>
                        <th>Prix achat</th>
                        <th>Prix vente</th>
                        <th>Marge</th>
                        <th>Valeur totale</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products ?? [] as $product)
                    <tr>
                        <td>#{{ $product->id }}</td>
                        <td>
                            <a href="{{ route('products.show', $product) }}" class="product-link">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td>{{ $product->supplier->name ?? 'N/A' }}</td>
                        <td>
                            @if($product->stock <= 0)
                                <span class="badge badge-danger">Rupture ({{ $product->stock }})</span>
                            @elseif($product->stock <= 10)
                                <span class="badge badge-warning">Stock faible ({{ $product->stock }})</span>
                            @else
                                <span class="badge badge-success">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td>{{ number_format($product->purchase_price, 0, ',', ' ') }} FCFA</td>
                        <td>{{ number_format($product->sale_price, 0, ',', ' ') }} FCFA</td>
                        <td class="text-accent">
                            {{ number_format(($product->sale_price - $product->purchase_price) / max($product->purchase_price, 1) * 100, 1) }}%
                        </td>
                        <td>
                            <strong>{{ number_format($product->stock * $product->sale_price, 0, ',', ' ') }} FCFA</strong>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 60px;">
                            <i class="bi bi-inbox" style="font-size: 48px; color: var(--text-tertiary);"></i>
                            <p style="margin-top: 16px; color: var(--text-tertiary);">Aucun produit trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($products) && method_exists($products, 'links'))
            <div class="pagination">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- SECURITY NOTE --}}
    <div class="security-note">
        <i class="bi bi-shield-check"></i> Données chiffrées et sécurisées · Inventaire mis à jour en temps réel
        <span class="date-badge" style="margin-left: 10px;">v2.0</span>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function() {
    // Optionnel: Rafraîchissement des statistiques toutes les 30 secondes
    async function refreshStats() {
        try {
                const response = await fetch('{{ route("api.grouped-stocks.stats") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) return;
            
            const data = await response.json();
            
            // Mettre à jour les statistiques si les éléments existent
            const statValues = document.querySelectorAll('.stat-value');
            if (statValues[0]) statValues[0].innerText = data.total_products || 0;
            if (statValues[1]) statValues[1].innerText = (data.total_stock || 0).toLocaleString('fr-FR');
            if (statValues[2]) statValues[2].innerText = (data.total_value || 0).toLocaleString('fr-FR') + ' FCFA';
            if (statValues[3]) statValues[3].innerText = (data.total_sale_value || 0).toLocaleString('fr-FR') + ' FCFA';
            
        } catch (error) {
            console.error('Erreur lors du rafraîchissement:', error);
        }
    }
    
    // Rafraîchir toutes les 30 secondes si la page est visible
    if(document.querySelector('.stat-value')) {
        setInterval(function() {
            if (document.visibilityState === 'visible') {
                refreshStats();
            }
        }, 30000);
    }
})();
</script>
@endsection