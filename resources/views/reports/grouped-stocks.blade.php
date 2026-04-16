{{-- resources/views/reports/grouped-stocks.blade.php --}}
@extends('layouts.app')

@section('title', 'Rapport des stocks groupés par lot')

@section('styles')
<style>
    /* =====================================================
       VARIABLES - NOIR & ORANGE
    ===================================================== */
    :root {
        --bg-page: #f8fafc;
        --bg-card: #ffffff;
        --border-light: #e2e8f0;
        --text-primary: #0f172a;
        --text-secondary: #334155;
        --text-tertiary: #64748b;
        
        /* Orange - accent principal */
        --accent: #f97316;
        --accent-dark: #ea580c;
        --accent-light: #ffedd5;
        --accent-soft: #fed7aa;
        --accent-gradient: linear-gradient(135deg, #f97316, #ea580c);
        
        /* Noir */
        --dark: #0f172a;
        --dark-soft: #1e293b;
        
        /* Badges */
        --badge-success: #22c55e;
        --badge-warning: #f59e0b;
        --badge-info: #3b82f6;
        
        --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        --shadow-hover: 0 10px 15px -3px rgba(249, 115, 22, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
    <div class="container mx-auto px-4 max-w-7xl py-8">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold flex items-center gap-3" style="background: linear-gradient(135deg, #0f172a 0%, #f97316 80%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        <i class="fas fa-boxes text-4xl" style="color: #f97316; -webkit-text-fill-color: #f97316;"></i>
                        Stocks groupés par lot/prix
                    </h2>
                    <p class="text-gray-500 mt-1 text-sm">Analyse détaillée des stocks par lot de prix d'achat</p>
                </div>
                <div>
                    <a href="{{ route('reports.grouped-stocks.export', ['format' => 'csv']) }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-lg"
                       style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white;">
                        <i class="fas fa-file-csv text-lg"></i>
                        <span>Exporter CSV</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-gray-100">
            <form method="GET" action="{{ route('reports.grouped-stocks') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                        <select name="category_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 transition-all duration-200" style="focus:ring-color: #f97316;">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fournisseur</label>
                        <select name="supplier_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 transition-all duration-200" style="focus:ring-color: #f97316;">
                            <option value="">Tous les fournisseurs</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trier par</label>
                        <select name="sort_by" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 transition-all duration-200" style="focus:ring-color: #f97316;">
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nom produit</option>
                            <option value="total_value" {{ request('sort_by') == 'total_value' ? 'selected' : '' }}>Valeur totale</option>
                            <option value="batches_count" {{ request('sort_by') == 'batches_count' ? 'selected' : '' }}>Nombre de lots</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full font-semibold py-2.5 px-4 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #f97316, #ea580c); color: white;">
                            <i class="fas fa-filter"></i>
                            <span>Filtrer</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Produits -->
            <div class="rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm font-medium">Produits</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $reportStats['total_products'] }}</h3>
                    </div>
                    <div class="bg-white/10 rounded-full p-3">
                        <i class="fas fa-box text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Valeur totale -->
            <div class="rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Valeur totale</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($reportStats['total_value'], 0, ',', ' ') }} F</h3>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Total lots -->
            <div class="rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow" style="background: linear-gradient(135deg, #334155 0%, #475569 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm font-medium">Total lots</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $reportStats['total_batches'] }}</h3>
                    </div>
                    <div class="bg-white/10 rounded-full p-3">
                        <i class="fas fa-layer-group text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Produits multi-lots -->
            <div class="rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow" style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Produits multi-lots</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $reportStats['products_with_multiple_batches'] }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fas fa-clone text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau principal -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <!-- En-tête du tableau -->
            <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4" style="background: linear-gradient(135deg, #fef3c7 0%, #ffedd5 100%);">
                <h2 class="text-xl font-bold" style="color: #0f172a;">Détail des stocks par produit</h2>
                <div class="flex gap-2">
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold transition-all duration-200 hover:shadow-md transform hover:-translate-y-0.5" style="background: #0f172a; color: white;">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour</span>
                    </a>
                </div>
            </div>
            
            <!-- Tableau -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Stock total</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Prix vente</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Prix achat moy.</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Valeur totale</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Lots</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Marge</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($productsData as $index => $data)
                            @php
                                $product = $data['product'];
                                $summary = $data['summary'];
                                $marge = $summary['current_price'] - $summary['average_purchase_price'];
                                $marge_pourcentage = $summary['average_purchase_price'] > 0 
                                    ? ($marge / $summary['average_purchase_price']) * 100 
                                    : 0;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold shadow-md" style="background: linear-gradient(135deg, #f97316, #ea580c);">
                                            {{ substr($product->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $product->category->name ?? 'N/A' }}</div>
                                        </div>
                                        @if($summary['has_multiple_batches'])
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full" style="background: #fef3c7; color: #ea580c;">
                                                <i class="fas fa-layer-group"></i>
                                                Multiple
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-semibold text-sm" style="background: #fef3c7; color: #ea580c;">
                                        {{ number_format($summary['total_stock'], 0) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-lg font-semibold text-gray-900">
                                    {{ number_format($summary['current_price'], 0, ',', ' ') }} F
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-lg font-semibold text-gray-900">
                                    {{ number_format($summary['average_purchase_price'], 0, ',', ' ') }} F
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-lg font-bold" style="color: #ea580c;">
                                    {{ number_format($summary['total_value'], 0, ',', ' ') }} F
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-semibold text-sm" style="background: #f1f5f9; color: #0f172a;">
                                        {{ $summary['batches_count'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold {{ $marge_pourcentage >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <span class="w-2 h-2 rounded-full {{ $marge_pourcentage >= 0 ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        {{ number_format($marge_pourcentage, 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <button type="button" 
                                            onclick="toggleDetails('details-{{ $product->id }}')"
                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg transition-colors font-medium" style="background: #fef3c7; color: #ea580c;">
                                        <i class="fas fa-chevron-down"></i>
                                        Détails
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Détails des lots (hidden by default) -->
                            <tr id="details-{{ $product->id }}" class="hidden">
                                <td colspan="9" class="p-0">
                                    <div class="p-6 bg-gray-50 border-t border-gray-200">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-bold flex items-center gap-2" style="color: #0f172a;">
                                                <i class="fas fa-boxes" style="color: #f97316;"></i>
                                                Détail des lots - {{ $product->name }}
                                            </h3>
                                            <button onclick="toggleDetails('details-{{ $product->id }}')"
                                                    class="text-gray-500 hover:text-gray-700">
                                                <i class="fas fa-times text-xl"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg shadow-sm">
                                                <thead style="background: #fef3c7;">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Lot/Référence</th>
                                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Quantité</th>
                                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Prix d'achat</th>
                                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Valeur achat</th>
                                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Valeur actuelle</th>
                                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Bénéfice</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200">
                                                    @foreach($data['grouped_stocks'] as $batch)
                                                        @php
                                                            $valeur_achat = $batch->total_quantity * $batch->purchase_price;
                                                            $valeur_actuelle = $batch->total_quantity * $summary['current_price'];
                                                            $benefice = $valeur_actuelle - $valeur_achat;
                                                        @endphp
                                                        <tr class="hover:bg-gray-50">
                                                            <td class="px-4 py-3">
                                                                <span class="px-2 py-1 text-xs font-medium rounded" style="background: #0f172a; color: white;">
                                                                    {{ $batch->batch ?? 'Lot ' . $loop->iteration }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 text-center font-medium">{{ number_format($batch->total_quantity, 0) }}</td>
                                                            <td class="px-4 py-3 text-right font-medium">{{ number_format($batch->purchase_price, 0, ',', ' ') }} F</td>
                                                            <td class="px-4 py-3 text-right font-medium">{{ number_format($valeur_achat, 0, ',', ' ') }} F</td>
                                                            <td class="px-4 py-3 text-right font-bold" style="color: #ea580c;">{{ number_format($valeur_actuelle, 0, ',', ' ') }} F</td>
                                                            <td class="px-4 py-3 text-right font-bold {{ $benefice >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                                {{ $benefice >= 0 ? '+' : '' }}{{ number_format($benefice, 0, ',', ' ') }} F
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    
                                                    <!-- Ligne TOTALE -->
                                                    <tr class="font-bold border-t-2" style="background: linear-gradient(135deg, #fef3c7 0%, #ffedd5 100%); border-color: #f97316;">
                                                        <td class="px-4 py-3">
                                                            <div class="flex items-center gap-2">
                                                                <i class="fas fa-calculator" style="color: #f97316;"></i>
                                                                <span style="color: #0f172a;">TOTAL {{ $product->name }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3 text-center" style="color: #ea580c;">{{ number_format($summary['total_stock'], 0) }}</td>
                                                        <td class="px-4 py-3 text-right">
                                                            <div>
                                                                <div class="text-xs" style="color: #f97316;">Moyenne</div>
                                                                {{ number_format($summary['average_purchase_price'], 0, ',', ' ') }} F
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3 text-right" style="color: #ea580c;">
                                                            {{ number_format($data['totals']['total_value_purchase'] ?? 0, 0, ',', ' ') }} F
                                                        </td>
                                                        <td class="px-4 py-3 text-right" style="color: #22c55e;">
                                                            {{ number_format($summary['total_value'], 0, ',', ' ') }} F
                                                        </td>
                                                        <td class="px-4 py-3 text-right" style="color: #22c55e;">
                                                            +{{ number_format($data['totals']['profit_potential'] ?? 0, 0, ',', ' ') }} F
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        
                        @if(count($productsData) === 0)
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-box-open text-4xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun produit trouvé</h3>
                                        <p class="text-gray-500">Aucun produit ne correspond à vos critères de filtrage.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Note -->
        <div class="mt-6 p-4 rounded-xl border" style="background: #fef3c7; border-color: #fed7aa;">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle mt-1" style="color: #ea580c;"></i>
                <div>
                    <p class="font-medium" style="color: #0f172a;">Comment lire ce rapport ?</p>
                    <p class="text-sm mt-1" style="color: #ea580c;">
                        Ce rapport montre les produits qui ont été achetés à différents prix (lots). 
                        Chaque ligne représente un produit, avec une <strong>ligne TOTALE</strong> en bas qui regroupe 
                        tous les lots de ce produit. Cliquez sur "Détails" pour voir le détail par lot.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fonction pour afficher/masquer les détails
    function toggleDetails(id) {
        const element = document.getElementById(id);
        if (element.classList.contains('hidden')) {
            element.classList.remove('hidden');
            element.classList.add('animate-fadeIn');
        } else {
            element.classList.add('hidden');
        }
    }
    
    // Fermer les autres détails quand on ouvre un
    document.addEventListener('DOMContentLoaded', function() {
        const detailButtons = document.querySelectorAll('button[onclick^="toggleDetails"]');
        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('onclick').match(/'([^']+)'/)[1];
                
                // Fermer tous les autres détails ouverts
                detailButtons.forEach(otherButton => {
                    if (otherButton !== button) {
                        const otherTargetId = otherButton.getAttribute('onclick').match(/'([^']+)'/)[1];
                        const otherElement = document.getElementById(otherTargetId);
                        if (otherElement && !otherElement.classList.contains('hidden')) {
                            otherElement.classList.add('hidden');
                        }
                    }
                });
            });
        });
    });
</script>
@endsection