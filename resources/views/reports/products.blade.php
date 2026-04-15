@extends('layouts.app')

@section('title', 'Rapport des produits - Inventix')

@section('styles')
<style>
    :root {
        --bg-page: #f8fafc;
        --bg-card: #ffffff;
        --border-light: #e2e8f0;
        --border-soft: #cbd5e1;
        --text-primary: #0f172a;
        --text-secondary: #334155;
        --text-tertiary: #64748b;
        --accent: #f97316;
        --accent-light: #ffedd5;
        --accent-gradient: linear-gradient(135deg, #f97316, #ea580c);
        --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        --shadow-hover: 0 10px 15px -3px rgba(249, 115, 22, 0.1);
    }

    .report-page {
        max-width: 1440px;
        margin: 0 auto;
        padding: 32px 24px;
    }
    .report-header {
        margin-bottom: 32px;
    }
    .report-header h1 {
        font-size: 32px;
        font-weight: 700;
        background: linear-gradient(135deg, #0f172a 0%, #f97316 80%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0 0 8px;
    }
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: transparent;
        border: 1.5px solid var(--border-soft);
        color: var(--text-primary);
        padding: 8px 20px;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-back:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent-dark);
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;
        margin-bottom: 32px;
    }
    @media (max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .stats-grid { grid-template-columns: 1fr; } }
    .stat-card {
        background: var(--bg-card);
        border-radius: 24px;
        padding: 20px;
        border: 1px solid var(--border-light);
    }
    .stat-card .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: var(--accent);
        margin: 8px 0 4px;
    }
    .stat-card .stat-label {
        font-size: 13px;
        color: var(--text-tertiary);
        font-weight: 500;
    }
    .filter-bar {
        background: var(--bg-card);
        border-radius: 20px;
        padding: 20px 24px;
        margin-bottom: 32px;
        border: 1px solid var(--border-light);
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
    }
    .filter-group {
        flex: 1;
        min-width: 150px;
    }
    .filter-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-tertiary);
        margin-bottom: 6px;
    }
    .filter-group select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--border-soft);
        border-radius: 12px;
    }
    .btn-filter {
        background: var(--accent-gradient);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
    }
    .table-container {
        background: var(--bg-card);
        border-radius: 24px;
        border: 1px solid var(--border-light);
        overflow-x: auto;
    }
    table { width: 100%; border-collapse: collapse; }
    th {
        text-align: left;
        padding: 16px 20px;
        background: #f9fafb;
        font-weight: 600;
        font-size: 13px;
        border-bottom: 1px solid var(--border-light);
    }
    td {
        padding: 14px 20px;
        border-bottom: 1px solid var(--border-light);
        font-size: 14px;
    }
    tr:hover td { background: var(--accent-light); }
    .badge-stock {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-critical { background: #fee2e2; color: #991b1b; }
    .badge-warning { background: #fff3cd; color: #f97316; }
    .badge-good { background: #dcfce7; color: #166534; }
</style>
@endsection

@section('content')
<div class="report-page">
    <div class="report-header">
        <a href="{{ route('reports.index') }}" class="btn-back mb-3 d-inline-flex">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
        <h1>Rapport des produits</h1>
        <div class="subtitle">Analyse détaillée des stocks et performances des produits</div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-label">Total produits</div>
                <i class="bi bi-box-seam fs-3 text-accent"></i>
            </div>
            <div class="stat-value">{{ $reportData['total_products'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-label">Valeur du stock</div>
                <i class="bi bi-currency-dollar fs-3 text-accent"></i>
            </div>
            <div class="stat-value">{{ number_format($reportData['total_stock_value'] ?? 0, 0, ',', ' ') }} FCFA</div>
        </div>
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-label">Stock faible</div>
                <i class="bi bi-exclamation-triangle fs-3 text-accent"></i>
            </div>
            <div class="stat-value">{{ $reportData['low_stock'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-label">Ruptures</div>
                <i class="bi bi-x-circle fs-3 text-accent"></i>
            </div>
            <div class="stat-value">{{ $reportData['out_of_stock'] ?? 0 }}</div>
        </div>
    </div>

    <div class="filter-bar">
        <div class="filter-group">
            <label>Catégorie</label>
            <select id="category_id">
                <option value="">Toutes les catégories</option>
                @foreach($categories ?? [] as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Fournisseur</label>
            <select id="supplier_id">
                <option value="">Tous les fournisseurs</option>
                @foreach($suppliers ?? [] as $sup)
                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn-filter" onclick="applyFilters()"><i class="bi bi-funnel"></i> Filtrer</button>
        <button class="btn-back" onclick="resetFilters()">Réinitialiser</button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Prix vente</th>
                    <th>Stock</th>
                    <th>Valeur stock</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products ?? [] as $product)
                <tr>
                    <td><strong>{{ $product->name }}</strong></td>
                    <td>{{ $product->category?->name ?? '-' }}</td>
                    <td>{{ number_format($product->sale_price, 0, ',', ' ') }} FCFA</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ number_format($product->stock * $product->sale_price, 0, ',', ' ') }} FCFA</td>
                    <td>
                        @if($product->stock <= 0)
                            <span class="badge-stock badge-critical">Rupture</span>
                        @elseif($product->stock <= 5)
                            <span class="badge-stock badge-warning">Stock faible</span>
                        @else
                            <span class="badge-stock badge-good">Disponible</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5">Aucun produit trouvé</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function applyFilters() {
    const params = new URLSearchParams();
    const catId = document.getElementById('category_id').value;
    const supId = document.getElementById('supplier_id').value;
    if (catId) params.append('category_id', catId);
    if (supId) params.append('supplier_id', supId);
    window.location.href = '{{ route("reports.products") }}?' + params.toString();
}
function resetFilters() { window.location.href = '{{ route("reports.products") }}'; }
</script>
@endsection