@extends('layouts.app')

@section('title', 'Rapport des clients - Inventix')

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
        margin-bottom: 32px;
    }

    .top-clients {
        background: var(--bg-card);
        border-radius: 24px;
        border: 1px solid var(--border-light);
        overflow: hidden;
        margin-bottom: 32px;
    }
    .top-clients-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-light);
        background: #f9fafb;
    }
    .top-clients-header h3 {
        margin: 0;
        font-weight: 700;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .top-clients-header h3 i {
        color: var(--accent);
        font-size: 24px;
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
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    td {
        padding: 14px 20px;
        border-bottom: 1px solid var(--border-light);
        font-size: 14px;
    }
    tr:hover td {
        background: var(--accent-light);
    }
    .rank-1 {
        background: linear-gradient(145deg, #fef3c7, #fff);
    }
    .rank-1 td {
        font-weight: 600;
    }
    .rank-1 .rank-number {
        color: var(--accent);
        font-weight: 800;
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
    .text-accent {
        color: var(--accent);
        font-weight: 600;
    }
    .badge-client {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: #dcfce7;
        color: #166534;
    }
    .client-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--accent-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-weight: 600;
        font-size: 14px;
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
                <h1>Rapport des clients</h1>
                <div class="subtitle">
                    <i class="bi bi-people"></i> Analyse de la fidélité et du comportement d'achat
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-back" onclick="window.print()">
                    <i class="bi bi-printer"></i> Imprimer
                </button>
                <button class="btn-back" onclick="exportToExcel()">
                    <i class="bi bi-file-excel"></i> Excel
                </button>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-label">Total clients</div>
                <i class="bi bi-people-fill stat-icon"></i>
            </div>
            <div class="stat-value">{{ $reportData['total_clients'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-label">Clients actifs</div>
                <i class="bi bi-person-check stat-icon"></i>
            </div>
            <div class="stat-value">{{ $reportData['active_clients'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-label">Total dépensé</div>
                <i class="bi bi-currency-dollar stat-icon"></i>
            </div>
            <div class="stat-value">{{ $reportData['formatted_total_spent'] ?? '0 FCFA' }}</div>
        </div>
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-label">Moyenne par client</div>
                <i class="bi bi-calculator stat-icon"></i>
            </div>
            <div class="stat-value">{{ $reportData['formatted_average_spent'] ?? '0 FCFA' }}</div>
        </div>
    </div>

    <div class="filter-bar">
        <div class="filter-group">
            <label>Rechercher</label>
            <input type="text" id="search" placeholder="Nom, email ou téléphone..." value="{{ request('search') }}">
        </div>
        <div class="filter-group">
            <label>Statut</label>
            <select id="status">
                <option value="">Tous les clients</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Clients actifs</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Clients inactifs</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Date d'inscription</label>
            <select id="period">
                <option value="">Toutes les périodes</option>
                <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Ce mois</option>
            </select>
        </div>
        <button class="btn-filter" onclick="applyFilters()"><i class="bi bi-funnel"></i> Filtrer</button>
        <button class="btn-reset" onclick="resetFilters()"><i class="bi bi-arrow-repeat"></i> Réinitialiser</button>
    </div>

    {{-- TOP CLIENTS --}}
    <div class="top-clients">
        <div class="top-clients-header">
            <h3>
                <i class="bi bi-trophy"></i> 
                Top 10 des meilleurs clients
                <span class="badge-client" style="margin-left: 12px;">Par montant total</span>
            </h3>
        </div>
        <div class="table-responsive">
            <table class="w-100">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Total dépensé</th>
                        <th>Nombre de commandes</th>
                        <th>Dernier achat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topClients ?? [] as $index => $client)
                    <tr class="{{ $index == 0 ? 'rank-1' : '' }}">
                        <td class="rank-number"><strong>{{ $index + 1 }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="client-avatar">
                                    {{ strtoupper(substr($client['name'], 0, 1)) }}
                                </div>
                                <strong>{{ $client['name'] }}</strong>
                            </div>
                        </td>
                        <td>{{ $client['email'] ?? '-' }}</td>
                        <td>{{ $client['phone'] ?? '-' }}</td>
                        <td class="text-accent"><strong>{{ number_format($client['total_spent'], 0, ',', ' ') }} FCFA</strong></td>
                        <td>{{ $client['total_orders'] }}</td>
                        <td>{{ $client['last_order'] ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Aucun client trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TOUS LES CLIENTS --}}
    <div class="table-container">
        <div class="top-clients-header" style="border-bottom: 1px solid var(--border-light);">
            <h3>
                <i class="bi bi-list-ul"></i> 
                Tous les clients
                <span class="badge-client" style="margin-left: 12px;">{{ $clients->total() ?? count($clients) }} clients</span>
            </h3>
        </div>
        <div class="table-responsive">
            <table class="w-100">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Total dépensé</th>
                        <th>Commandes</th>
                        <th>Dernière activité</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients ?? [] as $client)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="client-avatar">
                                    {{ strtoupper(substr($client->name, 0, 1)) }}
                                </div>
                                <strong>{{ $client->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $client->email ?? '-' }}</td>
                        <td>{{ $client->phone ?? '-' }}</td>
                        <td class="text-accent">{{ number_format($client->total_spent ?? 0, 0, ',', ' ') }} FCFA</td>
                        <td>{{ $client->orders_count ?? 0 }}</td>
                        <td>{{ $client->last_activity ? \Carbon\Carbon::parse($client->last_activity)->format('d/m/Y') : '-' }}</td>
                        <td>
                            <a href="{{ route('clients.show', $client) }}" class="btn-back" style="padding: 4px 12px; font-size: 12px;">
                                <i class="bi bi-eye"></i> Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Aucun client trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($clients) && method_exists($clients, 'links'))
            <div class="pagination">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function applyFilters() {
    const params = new URLSearchParams();
    const search = document.getElementById('search').value;
    const status = document.getElementById('status').value;
    const period = document.getElementById('period').value;
    
    if (search) params.append('search', search);
    if (status) params.append('status', status);
    if (period) params.append('period', period);
    
    window.location.href = '{{ route("reports.clients") }}?' + params.toString();
}

function resetFilters() {
    window.location.href = '{{ route("reports.clients") }}';
}

function exportToExcel() {
    const params = new URLSearchParams(window.location.search);
    alert('Export Excel - Fonctionnalité à venir');
}
</script>
@endsection