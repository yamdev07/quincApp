@extends('layouts.app')

@section('title', 'Client - ' . $client->name)

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

    .client-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 32px 24px;
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
        margin-bottom: 24px;
    }
    .btn-back:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent-dark);
    }

    .profile-header {
        background: var(--bg-card);
        border-radius: 24px;
        border: 1px solid var(--border-light);
        padding: 32px;
        margin-bottom: 32px;
        display: flex;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }
    .client-avatar-large {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: var(--accent-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 48px;
        font-weight: 600;
        box-shadow: var(--shadow-hover);
    }
    .client-info h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 8px;
        color: var(--text-primary);
    }
    .client-info .client-meta {
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
        color: var(--text-tertiary);
        font-size: 14px;
    }
    .client-stats {
        display: flex;
        gap: 32px;
        margin-left: auto;
    }
    .stat-badge {
        text-align: center;
    }
    .stat-badge .stat-number {
        font-size: 28px;
        font-weight: 800;
        color: var(--accent);
    }
    .stat-badge .stat-label {
        font-size: 12px;
        color: var(--text-tertiary);
    }

    .info-card {
        background: var(--bg-card);
        border-radius: 24px;
        border: 1px solid var(--border-light);
        overflow: hidden;
        margin-bottom: 32px;
    }
    .info-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-light);
        background: #f9fafb;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .info-card-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
    }
    .info-card-header i {
        font-size: 22px;
        color: var(--accent);
    }
    .info-card-body {
        padding: 24px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-tertiary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-value {
        font-size: 16px;
        font-weight: 500;
        color: var(--text-primary);
    }

    .table-container {
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th {
        text-align: left;
        padding: 14px 16px;
        background: #f9fafb;
        font-weight: 600;
        font-size: 12px;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--border-light);
        font-size: 14px;
    }
    tr:hover td {
        background: var(--accent-light);
    }
    .text-accent {
        color: var(--accent);
        font-weight: 600;
    }
    .btn-sm {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        text-decoration: none;
        background: var(--accent-light);
        color: var(--accent-dark);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-sm:hover {
        background: var(--accent);
        color: white;
    }
    .empty-state {
        text-align: center;
        padding: 48px;
        color: var(--text-tertiary);
    }
    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    .pagination a, .pagination span {
        padding: 6px 12px;
        border: 1px solid var(--border-light);
        border-radius: 6px;
        text-decoration: none;
        color: var(--text-primary);
    }
    .pagination .active {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }
    .badge-product {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        background: var(--accent-light);
        color: var(--accent-dark);
        margin: 2px;
    }
</style>
@endsection

@section('content')
<div class="client-page">
    <a href="{{ route('clients.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Retour à la liste
    </a>

    {{-- En-tête du client --}}
    <div class="profile-header">
        <div class="client-avatar-large">
            {{ strtoupper(substr($client->name, 0, 1)) }}
        </div>
        <div class="client-info">
            <h1>{{ $client->name }}</h1>
            <div class="client-meta">
                <span><i class="bi bi-envelope"></i> {{ $client->email ?? 'Non renseigné' }}</span>
                <span><i class="bi bi-telephone"></i> {{ $client->phone ?? 'Non renseigné' }}</span>
                <span><i class="bi bi-calendar3"></i> Client depuis {{ $client->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
        <div class="client-stats">
            <div class="stat-badge">
                <div class="stat-number">{{ $stats['total_sales'] ?? 0 }}</div>
                <div class="stat-label">Commandes</div>
            </div>
            <div class="stat-badge">
                <div class="stat-number">{{ number_format($stats['total_spent'] ?? 0, 0, ',', ' ') }} FCFA</div>
                <div class="stat-label">Total dépensé</div>
            </div>
            <div class="stat-badge">
                <div class="stat-number">{{ number_format($stats['average_cart'] ?? 0, 0, ',', ' ') }} FCFA</div>
                <div class="stat-label">Panier moyen</div>
            </div>
        </div>
    </div>

    {{-- Informations détaillées --}}
    <div class="info-card">
        <div class="info-card-header">
            <i class="bi bi-person-badge"></i>
            <h3>Informations du client</h3>
            @if(Auth::user()->isSuperAdminOrAdmin())
            <div style="margin-left: auto;">
                <a href="{{ route('clients.edit', $client) }}" class="btn-sm">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
            </div>
            @endif
        </div>
        <div class="info-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nom complet</span>
                    <span class="info-value">{{ $client->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $client->email ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Téléphone</span>
                    <span class="info-value">{{ $client->phone ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Adresse</span>
                    <span class="info-value">{{ $client->address ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Inscrit le</span>
                    <span class="info-value">{{ $client->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Dernière activité</span>
                    <span class="info-value">{{ $stats['last_purchase']?->format('d/m/Y') ?? 'Aucune activité' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Premier achat</span>
                    <span class="info-value">{{ $stats['first_purchase']?->format('d/m/Y') ?? 'Aucun achat' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Articles achetés</span>
                    <span class="info-value">{{ $stats['products_purchased'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Produits préférés --}}
    @if(isset($favoriteProducts) && count($favoriteProducts) > 0)
    <div class="info-card">
        <div class="info-card-header">
            <i class="bi bi-star-fill"></i>
            <h3>Produits préférés</h3>
        </div>
        <div class="info-card-body">
            <div class="table-container">
                <table class="w-100">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité achetée</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            @foreach($favoriteProducts as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>{{ $product->total_quantity }} unités</td>
                                <td>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn-sm">
                                        <i class="bi bi-box-seam"></i> Voir produit
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- Historique des achats --}}
        <div class="info-card">
            <div class="info-card-header">
                <i class="bi bi-receipt"></i>
                <h3>Historique des achats</h3>
                <span class="stat-badge" style="margin-left: auto;">
                    <span class="stat-number">{{ $stats['total_sales'] ?? 0 }}</span>
                    <span class="stat-label">commandes</span>
                </span>
            </div>
            <div class="info-card-body">
                <div class="table-container">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th>Réf.</th>
                                <th>Date</th>
                                <th>Articles</th>
                                <th>Total</th>
                                <th>Caissier</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                @forelse($client->sales ?? [] as $sale)
                                <tr>
                                    <td>#{{ $sale->id }}</td>
                                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @foreach($sale->items->take(2) as $item)
                                            <span class="badge-product">{{ $item->product->name ?? 'Produit' }} (x{{ $item->quantity }})</span>
                                        @endforeach
                                        @if($sale->items->count() > 2)
                                            <span class="badge-product">+{{ $sale->items->count() - 2 }}</span>
                                        @endif
                                    </td>
                                    <td class="text-accent">{{ number_format($sale->total_price, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ $sale->user?->name ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('sales.show', $sale) }}" class="btn-sm">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <i class="bi bi-bag-x"></i>
                                        <p>Aucun achat enregistré pour ce client</p>
                                        <a href="{{ route('sales.create') }}" class="btn-sm" style="background: var(--accent); color: white;">
                                            <i class="bi bi-plus-circle"></i> Créer une vente
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Actions supplémentaires --}}
            <div class="info-card" style="margin-bottom: 0;">
                <div class="info-card-header">
                    <i class="bi bi-download"></i>
                    <h3>Export des données</h3>
                </div>
                <div class="info-card-body">
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('clients.export', $client) }}" class="btn-sm" style="background: #10b981; color: white;">
                            <i class="bi bi-file-excel"></i> Exporter en Excel
                        </a>
                        <a href="{{ route('clients.statistics', $client) }}" class="btn-sm">
                            <i class="bi bi-graph-up"></i> Voir les statistiques détaillées
                        </a>
                        @if(Auth::user()->isSuperAdminOrAdmin())
                            <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sm" style="background: #fee2e2; color: #991b1b; border: none; cursor: pointer;" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endsection