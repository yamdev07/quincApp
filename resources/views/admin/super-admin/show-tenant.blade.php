@extends('layouts.app')

@section('title', 'Détails - ' . $tenant->company_name)

@section('styles')
<style>
    /* Styles spécifiques */
    .tenant-header {
        background: linear-gradient(135deg, #fff7ed, #fed7aa);
        border-radius: 24px;
        padding: 24px;
        margin-bottom: 24px;
    }
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin: 24px 0;
    }
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #e5e7eb;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    {{-- En-tête --}}
    <div class="tenant-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>{{ $tenant->company_name }}</h1>
                <p class="text-muted">
                    <i class="bi bi-building"></i> ID: {{ $tenant->id }} | 
                    <i class="bi bi-calendar"></i> Créé le {{ $tenant->created_at->format('d/m/Y') }}
                </p>
            </div>
            <div>
                @if($tenant->hasActiveSubscription())
                    <span class="badge bg-success">Actif</span>
                @elseif($tenant->isExpired())
                    <span class="badge bg-danger">Expiré</span>
                @elseif($tenant->isOverdue())
                    <span class="badge bg-warning">En retard</span>
                @else
                    <span class="badge bg-info">Essai</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Stats rapides --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="text-muted">Utilisateurs</div>
            <div class="h3">{{ $tenant->users_count ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="text-muted">Produits</div>
            <div class="h3">{{ $stats['total_products'] }}</div>
        </div>
        <div class="stat-card">
            <div class="text-muted">Ventes</div>
            <div class="h3">{{ $stats['total_sales'] }}</div>
        </div>
        <div class="stat-card">
            <div class="text-muted">CA Total</div>
            <div class="h3">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} FCFA</div>
        </div>
    </div>

    {{-- Deux colonnes --}}
    <div class="row">
        <div class="col-md-6">
            {{-- Informations abonnement --}}
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📦 Abonnement</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Formule :</th>
                            <td>{{ $tenant->billing_cycle_label }}</td>
                        </tr>
                        <tr>
                            <th>Prix :</th>
                            <td>{{ $tenant->formatted_price }}</td>
                        </tr>
                        <tr>
                            <th>Début :</th>
                            <td>{{ $tenant->subscription_start_date?->format('d/m/Y') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Fin :</th>
                            <td>
                                {{ $tenant->subscription_end_date?->format('d/m/Y') ?? '-' }}
                                @if($tenant->daysRemaining() > 0)
                                    <span class="badge bg-warning ms-2">J-{{ $tenant->daysRemaining() }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Statut :</th>
                            <td>
                                @switch($tenant->payment_status)
                                    @case('paid') <span class="badge bg-success">Payé</span> @break
                                    @case('pending') <span class="badge bg-warning">En attente</span> @break
                                    @case('overdue') <span class="badge bg-danger">En retard</span> @break
                                    @case('trial') <span class="badge bg-info">Essai</span> @break
                                @endswitch
                            </td>
                        </tr>
                    </table>

                    {{-- Actions abonnement --}}
                    <div class="mt-3">
                        <button class="btn btn-outline-primary btn-sm" onclick="markPayment()">
                            <i class="bi bi-credit-card"></i> Marquer payé
                        </button>
                        <button class="btn btn-outline-warning btn-sm" onclick="extendSubscription()">
                            <i class="bi bi-calendar-plus"></i> Prolonger
                        </button>
                    </div>
                </div>
            </div>

            {{-- Propriétaire --}}
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">👤 Propriétaire</h5>
                </div>
                <div class="card-body">
                    @if($tenant->owner)
                        <p><strong>{{ $tenant->owner->name }}</strong></p>
                        <p><i class="bi bi-envelope"></i> {{ $tenant->owner->email }}</p>
                    @else
                        <p class="text-muted">Aucun propriétaire assigné</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            {{-- Stock faible --}}
            @if($lowStockProducts->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">⚠️ Stock faible</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($lowStockProducts as $product)
                                <li class="list-group-item d-flex justify-content-between">
                                    {{ $product->name }}
                                    <span class="badge bg-warning">{{ $product->stock }} unités</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Dernières ventes --}}
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">🛒 Dernières ventes</h5>
                </div>
                <div class="card-body">
                    @if($recentSales->count() > 0)
                        <ul class="list-group">
                            @foreach($recentSales as $sale)
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>
                                        {{ $sale->client->name ?? 'Client inconnu' }}
                                        <small class="text-muted d-block">{{ $sale->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <span class="fw-bold">{{ number_format($sale->total_price, 0, ',', ' ') }} FCFA</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">Aucune vente</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markPayment() {
    // Modal de paiement
    alert('Fonction à implémenter : Marquer comme payé');
}

function extendSubscription() {
    // Modal de prolongation
    alert('Fonction à implémenter : Prolonger abonnement');
}
</script>
@endsection