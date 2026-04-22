@extends('layouts.app')

@section('title', 'Rapport des ventes - Sellvantix')

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
        --accent-dark: #ea580c;
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

    .report-header .subtitle {
        color: var(--text-tertiary);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
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
        transition: all 0.25s;
    }
    .stat-card:hover {
        box-shadow: var(--shadow-hover);
        border-color: var(--accent);
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
    .stat-card .stat-icon {
        font-size: 28px;
        color: var(--accent);
        opacity: 0.7;
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
        align-items: flex-end;
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
        text-transform: uppercase;
    }
    .filter-group input, .filter-group select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--border-soft);
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.2s;
    }
    .filter-group input:focus, .filter-group select:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }
    .btn-filter {
        background: var(--accent-gradient);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(249, 115, 22, 0.25);
    }
    .btn-reset {
        background: transparent;
        border: 1px solid var(--border-soft);
        padding: 10px 24px;
        border-radius: 40px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-reset:hover {
        border-color: var(--accent);
        color: var(--accent);
    }

    .table-container {
        background: var(--bg-card);
        border-radius: 24px;
        border: 1px solid var(--border-light);
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th {
        text-align: left;
        padding: 16px 20px;
        background: #f9fafb;
        font-weight: 600;
        font-size: 13px;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border-light);
    }
    td {
        padding: 14px 20px;
        border-bottom: 1px solid var(--border-light);
        font-size: 14px;
    }
    tr:hover td {
        background: var(--accent-light);
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 24px;
        flex-wrap: wrap;
    }
    .pagination a, .pagination span {
        padding: 8px 12px;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        text-decoration: none;
        color: var(--text-primary);
        transition: all 0.2s;
    }
    .pagination a:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
    }
    .pagination .active {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }
    .pagination .disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endsection

@section('content')
<div class="report-page">
    <div class="report-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <a href="{{ route('reports.index') }}" class="btn-back mb-3 d-inline-flex">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
                <h1>Rapport des ventes</h1>
                <div class="subtitle">
                    <i class="bi bi-calendar3"></i> 
                    @if(request('start_date') || request('end_date'))
                        Période: 
                        @if(request('start_date')) du {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }} @endif
                        @if(request('end_date')) au {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }} @endif
                    @else
                        Toutes les ventes
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-back" onclick="window.print()">
                    <i class="bi bi-printer"></i> Imprimer
                </button>
                <a href="{{ route('reports.sales.export', ['format' => 'excel', 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn-back">
                    <i class="bi bi-file-excel"></i> Excel
                </a>
                <a href="{{ route('reports.sales.export', ['format' => 'csv', 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn-back">
                    <i class="bi bi-filetype-csv"></i> CSV
                </a>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-label">Total ventes</div>
                <i class="bi bi-cart3 stat-icon"></i>
            </div>
            <div class="stat-value">{{ $reportData['total_sales'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-label">Chiffre d'affaires</div>
                <i class="bi bi-currency-dollar stat-icon"></i>
            </div>
            <div class="stat-value">{{ $reportData['formatted_total_revenue'] ?? '0 FCFA' }}</div>
        </div>
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-label">Articles vendus</div>
                <i class="bi bi-box-seam stat-icon"></i>
            </div>
            <div class="stat-value">{{ $reportData['total_items'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-label">Vente moyenne</div>
                <i class="bi bi-calculator stat-icon"></i>
            </div>
            <div class="stat-value">{{ $reportData['formatted_average_sale'] ?? '0 FCFA' }}</div>
        </div>
    </div>

    <div class="filter-bar">
        <div class="filter-group">
            <label>Du</label>
            <input type="date" id="date_from" value="{{ request('start_date') }}">
        </div>
        <div class="filter-group">
            <label>Au</label>
            <input type="date" id="date_to" value="{{ request('end_date') }}">
        </div>
        <div class="filter-group">
            <label>Client</label>
            <select id="client_id">
                <option value="">Tous les clients</option>
                @foreach($clients ?? [] as $client)
                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Caissier</label>
            <select id="user_id">
                <option value="">Tous</option>
                @foreach($users ?? [] as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn-filter" onclick="applyFilters()"><i class="bi bi-funnel"></i> Filtrer</button>
        <button class="btn-reset" onclick="resetFilters()"><i class="bi bi-arrow-repeat"></i> Réinitialiser</button>
    </div>

    <div class="table-container">
        <table class="w-100">
            <thead>
                 <tr>
                    <th>Réf.</th>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Caissier</th>
                    <th>Articles</th>
                    <th>Total</th>
                    <th>Actions</th>
                 </tr>
            </thead>
            <tbody>
                @forelse($sales ?? [] as $sale)
                <tr>
                    <td>#{{ $sale->id }}</td>
                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $sale->client?->name ?? 'Particulier' }}</td>
                    <td>{{ $sale->user?->name ?? '-' }}</td>
                    <td>{{ $sale->items->sum('quantity') }}</td>
                    <td><strong class="text-accent">{{ number_format($sale->total_price, 0, ',', ' ') }} FCFA</strong></td>
                    <td>
                        <a href="{{ route('sales.show', $sale) }}" class="btn-back" style="padding: 4px 12px; font-size: 12px;">
                            <i class="bi bi-eye"></i> Voir
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Aucune vente trouvée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(method_exists($sales, 'links'))
        <div class="pagination">
            {{ $sales->links() }}
        </div>
    @endif
</div>

<script>
function applyFilters() {
    const params = new URLSearchParams();
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    const clientId = document.getElementById('client_id').value;
    const userId = document.getElementById('user_id').value;
    
    if (dateFrom) params.append('start_date', dateFrom);
    if (dateTo) params.append('end_date', dateTo);
    if (clientId) params.append('client_id', clientId);
    if (userId) params.append('user_id', userId);
    
    window.location.href = '{{ route("reports.sales") }}?' + params.toString();
}

function resetFilters() {
    window.location.href = '{{ route("reports.sales") }}';
}

function exportToExcel() {
    const params = new URLSearchParams(window.location.search);
    // Rediriger vers l'export Excel (à implémenter si besoin)
    alert('Export Excel - Fonctionnalité à venir');
}
</script>
@endsection