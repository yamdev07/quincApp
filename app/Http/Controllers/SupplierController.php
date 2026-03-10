<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Vérifier que le fournisseur appartient à la quincaillerie
     */
    private function authorizeSupplierAccess(Supplier $supplier)
    {
        if (!Auth::user()->hasAccessTo($supplier)) {
            abort(403, 'Vous n\'avez pas accès à ce fournisseur.');
        }
    }

    /**
     * Vérifier les permissions de gestion du stock (pour voir les fournisseurs)
     */
    private function authorizeStockAccess()
    {
        if (!Auth::user()->canManageStock()) {
            abort(403, 'Vous n\'avez pas les droits pour gérer les fournisseurs.');
        }
    }

    /**
     * Vérifier les permissions d'administration (pour créer/modifier/supprimer)
     */
    private function authorizeAdmin()
    {
        if (!Auth::user()->isSuperAdminOrAdmin()) {
            abort(403, 'Action réservée aux administrateurs.');
        }
    }

    /**
     * Afficher la liste des fournisseurs
     */
    public function index()
    {
        $this->authorizeStockAccess();
        
        // Le scope TenantScope s'applique automatiquement !
        $suppliers = Supplier::withCount('products')
                            ->latest()
                            ->paginate(10);
        
        // Calculer des statistiques supplémentaires
        foreach ($suppliers as $supplier) {
            $supplier->total_stock_value = $supplier->products->sum(function($product) {
                return $product->stock * $product->purchase_price;
            });
        }
        
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $this->authorizeAdmin();
        
        return view('suppliers.create');
    }

    /**
     * Enregistrer un nouveau fournisseur
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();
        
        $request->validate([
            'name'    => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'phone'   => 'nullable|string|max:20|regex:/^[0-9]+$/',
        ]);

        // Nettoyer le téléphone
        $data = $request->all();
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
        }

        // owner_id sera auto-assigné par le Trait TenantScope
        Supplier::create($data);

        return redirect()->route('suppliers.index')
                         ->with('success', 'Fournisseur ajouté avec succès ✅');
    }

    /**
     * Afficher les détails d'un fournisseur
     */
    public function show(Supplier $supplier)
    {
        $this->authorizeStockAccess();
        $this->authorizeSupplierAccess($supplier);
        
        // Charger les produits avec leurs statistiques
        $supplier->load(['products' => function($query) {
            $query->with('category');
        }]);
        
        // Statistiques du fournisseur
        $stats = [
            'total_products' => $supplier->products->count(),
            'total_stock' => $supplier->products->sum('stock'),
            'total_value' => $supplier->products->sum(function($product) {
                return $product->stock * $product->purchase_price;
            }),
            'total_sale_value' => $supplier->products->sum(function($product) {
                return $product->stock * $product->sale_price;
            }),
            'potential_profit' => $supplier->products->sum(function($product) {
                return ($product->sale_price - $product->purchase_price) * $product->stock;
            }),
            'out_of_stock' => $supplier->products->where('stock', 0)->count(),
            'low_stock' => $supplier->products->filter(function($p) {
                return $p->stock > 0 && $p->stock <= 5;
            })->count(),
        ];
        
        // Produits en faible stock
        $lowStockProducts = $supplier->products->filter(function($p) {
            return $p->stock <= 5;
        })->sortBy('stock')->take(5);
        
        return view('suppliers.show', compact('supplier', 'stats', 'lowStockProducts'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Supplier $supplier)
    {
        $this->authorizeAdmin();
        $this->authorizeSupplierAccess($supplier);
        
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Mettre à jour un fournisseur
     */
    public function update(Request $request, Supplier $supplier)
    {
        $this->authorizeAdmin();
        $this->authorizeSupplierAccess($supplier);
        
        $request->validate([
            'name'    => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'phone'   => 'nullable|string|max:20|regex:/^[0-9]+$/',
        ]);

        // Nettoyer le téléphone
        $data = $request->all();
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
        }

        $supplier->update($data);

        return redirect()->route('suppliers.index')
                         ->with('success', 'Fournisseur mis à jour avec succès ✅');
    }

    /**
     * Supprimer un fournisseur
     */
    public function destroy(Supplier $supplier)
    {
        $this->authorizeAdmin();
        $this->authorizeSupplierAccess($supplier);
        
        // Vérifier si le fournisseur a des produits associés
        if ($supplier->products()->exists()) {
            return redirect()->route('suppliers.index')
                ->with('warning', 'Impossible de supprimer ce fournisseur car des produits lui sont associés.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
                         ->with('success', 'Fournisseur supprimé avec succès ✅');
    }

    /**
     * Afficher les produits d'un fournisseur
     */
    public function products(Supplier $supplier)
    {
        $this->authorizeStockAccess();
        $this->authorizeSupplierAccess($supplier);
        
        $products = $supplier->products()
                            ->with('category')
                            ->paginate(10);
        
        return view('suppliers.products', compact('supplier', 'products'));
    }

    /**
     * Afficher les commandes d'un fournisseur (à implémenter)
     */
    public function orders(Supplier $supplier)
    {
        $this->authorizeStockAccess();
        $this->authorizeSupplierAccess($supplier);
        
        // Cette fonction dépend de votre modèle Order
        // $orders = $supplier->orders()->paginate(10);
        // return view('suppliers.orders', compact('supplier', 'orders'));
        
        return redirect()->route('suppliers.show', $supplier)
                         ->with('info', 'Fonctionnalité "Commandes" à implémenter');
    }

    /**
     * Rapport des fournisseurs
     */
    public function suppliersReport(Request $request)
    {
        if (!Auth::user()->canViewReports()) {
            abort(403, 'Vous n\'avez pas les droits pour voir les rapports.');
        }
        
        $query = Supplier::withCount('products')
                        ->with(['products' => function($query) {
                            $query->select('id', 'supplier_id', 'stock', 'purchase_price', 'sale_price');
                        }]);
        
        // Filtres optionnels
        if ($request->filled('has_products')) {
            if ($request->has_products === 'yes') {
                $query->has('products', '>', 0);
            } else {
                $query->has('products', '=', 0);
            }
        }
        
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }
        
        $suppliers = $query->orderBy('name')->get();

        // Calculer les statistiques
        $totalProducts = $suppliers->sum('products_count');
        $totalStockValue = $suppliers->sum(function($supplier) {
            return $supplier->products->sum(function($product) {
                return $product->stock * $product->purchase_price;
            });
        });
        
        $reportData = [
            'total_suppliers' => $suppliers->count(),
            'suppliers_with_products' => $suppliers->where('products_count', '>', 0)->count(),
            'suppliers_without_products' => $suppliers->where('products_count', 0)->count(),
            'average_products_per_supplier' => $suppliers->count() > 0 ? round($totalProducts / $suppliers->count(), 1) : 0,
            'total_products' => $totalProducts,
            'total_stock_value' => $totalStockValue,
            'formatted_total_stock_value' => number_format($totalStockValue, 0, ',', ' ') . ' FCFA',
        ];

        return view('reports.suppliers', compact('suppliers', 'reportData'));
    }

    /**
     * Recherche de fournisseurs (AJAX)
     */
    public function search(Request $request)
    {
        $this->authorizeStockAccess();
        
        $term = $request->get('q');
        
        $suppliers = Supplier::where('name', 'LIKE', "%{$term}%")
                            ->orWhere('contact', 'LIKE', "%{$term}%")
                            ->orWhere('phone', 'LIKE', "%{$term}%")
                            ->limit(10)
                            ->get();
        
        return response()->json($suppliers);
    }

    /**
     * Statistiques détaillées d'un fournisseur
     */
    public function statistics(Supplier $supplier)
    {
        $this->authorizeStockAccess();
        $this->authorizeSupplierAccess($supplier);
        
        $products = $supplier->products()->with('category')->get();
        
        // Produits par catégorie
        $productsByCategory = $products->groupBy(function($product) {
            return $product->category->name ?? 'Sans catégorie';
        })->map(function($group) {
            return [
                'count' => $group->count(),
                'total_stock' => $group->sum('stock'),
                'total_value' => $group->sum(function($p) {
                    return $p->stock * $p->purchase_price;
                }),
            ];
        });
        
        // Distribution des stocks
        $stockDistribution = [
            'out_of_stock' => $products->where('stock', 0)->count(),
            'low_stock' => $products->filter(function($p) {
                return $p->stock > 0 && $p->stock <= 5;
            })->count(),
            'medium_stock' => $products->filter(function($p) {
                return $p->stock > 5 && $p->stock <= 20;
            })->count(),
            'high_stock' => $products->where('stock', '>', 20)->count(),
        ];
        
        return view('suppliers.statistics', compact('supplier', 'productsByCategory', 'stockDistribution'));
    }

    /**
     * Exporter les données d'un fournisseur
     */
    public function export(Supplier $supplier)
    {
        $this->authorizeStockAccess();
        $this->authorizeSupplierAccess($supplier);
        
        $supplier->load('products');
        
        $filename = 'fournisseur_' . $supplier->id . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($supplier) {
            $handle = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($handle, ['Produit', 'Stock', 'Prix d\'achat', 'Prix de vente', 'Valeur stock', 'Catégorie']);
            
            // Données
            foreach ($supplier->products as $product) {
                fputcsv($handle, [
                    $product->name,
                    $product->stock,
                    number_format($product->purchase_price, 0, ',', ''),
                    number_format($product->sale_price, 0, ',', ''),
                    number_format($product->stock * $product->purchase_price, 0, ',', ''),
                    $product->category->name ?? 'N/A',
                ]);
            }
            
            // Totaux
            fputcsv($handle, []);
            fputcsv($handle, [
                'TOTAUX',
                $supplier->products->sum('stock'),
                '',
                '',
                number_format($supplier->products->sum(function($p) {
                    return $p->stock * $p->purchase_price;
                }), 0, ',', ''),
                '',
            ]);
            
            fclose($handle);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Mettre à jour les coordonnées du fournisseur (pour actions rapides)
     */
    public function updateContact(Request $request, Supplier $supplier)
    {
        $this->authorizeAdmin();
        $this->authorizeSupplierAccess($supplier);
        
        $request->validate([
            'contact' => 'nullable|string|max:255',
            'phone'   => 'nullable|string|max:20|regex:/^[0-9]+$/',
            'address' => 'nullable|string|max:255',
        ]);

        $data = [];
        if ($request->filled('contact')) {
            $data['contact'] = $request->contact;
        }
        if ($request->filled('phone')) {
            $data['phone'] = preg_replace('/[^0-9]/', '', $request->phone);
        }
        if ($request->filled('address')) {
            $data['address'] = $request->address;
        }
        
        $supplier->update($data);

        return redirect()->route('suppliers.show', $supplier)
                         ->with('success', 'Coordonnées mises à jour avec succès ✅');
    }
}