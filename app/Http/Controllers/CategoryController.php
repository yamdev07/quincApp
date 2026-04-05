<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Vérifier que la catégorie appartient à la quincaillerie
     */
    private function authorizeCategoryAccess(Category $category)
    {
        // Vérifier par tenant_id plutôt que par hasAccessTo()
        if (!auth()->user()->isSuperAdminGlobal() && $category->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Vous n\'avez pas accès à cette catégorie.');
        }
    }

    /**
     * Vérifier les permissions d'administration (inclut le manager maintenant)
     */
    private function authorizeAdmin()
    {
        if (!auth()->user()->isSuperAdminOrAdmin() && !auth()->user()->isManager()) {
            abort(403, 'Action réservée aux administrateurs et managers.');
        }
    }

    // -------------------------
    // ROUTES PUBLIQUES (avec filtre par quincaillerie)
    // -------------------------

    /**
     * Affiche toutes les catégories principales avec leurs sous-catégories
     */
    public function index()
    {
        // Le trait TenantScope filtre automatiquement par tenant_id
        $categories = Category::with(['children' => function($query) {
                $query->withCount('products')
                    ->orderBy('name');
            }])
            ->withCount(['products as total_products'])
            ->withSum('products as total_stock', 'stock')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->distinct() // 👈 Évite les doublons
            ->paginate(15);
        
        // Statistiques globales (par tenant) - déjà filtrées par le trait
        $totalCategories = Category::distinct()->count('id');
        $categoriesWithChildren = Category::has('children')->distinct()->count('id');
        $totalProductsInCategories = Product::whereNotNull('category_id')->distinct('id')->count('id');
        
        return view('categories.index', compact(
            'categories', 
            'totalCategories', 
            'categoriesWithChildren', 
            'totalProductsInCategories'
        ));
    }

    /**
     * Affiche une catégorie spécifique avec ses sous-catégories et produits
     */
    public function show($id)
    {
        $category = Category::with(['children' => function($query) {
                $query->withCount('products')
                      ->orderBy('name');
            }])
            ->with(['products' => function($query) {
                $query->orderBy('name')->with('supplier')->distinct();
            }])
            ->where('tenant_id', auth()->user()->tenant_id) // 👈 FILTRE PAR TENANT
            ->findOrFail($id);
        
        $this->authorizeCategoryAccess($category);
        
        // Toutes les catégories principales pour les menus
        $mainCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->where('tenant_id', auth()->user()->tenant_id) // 👈 FILTRE PAR TENANT
            ->orderBy('name')
            ->distinct()
            ->get();
        
        // Calculer les statistiques (incluant les sous-catégories)
        $allProducts = $category->getAllProducts();
        $stats = [
            'total_products' => $allProducts->count(),
            'total_subcategories' => $category->children->count(),
            'total_stock' => $allProducts->sum('stock'),
            'total_value' => $allProducts->sum(function($product) {
                return $product->stock * $product->purchase_price;
            }),
            'potential_revenue' => $allProducts->sum(function($product) {
                return $product->stock * $product->sale_price;
            }),
            'low_stock' => $allProducts->where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'out_of_stock' => $allProducts->where('stock', '=', 0)->count(),
            'in_stock' => $allProducts->where('stock', '>', 5)->count(),
            'direct_products' => $category->products->count(),
        ];
        
        // Produits à faible stock (incluant les sous-catégories)
        $lowStockProducts = $allProducts->where('stock', '<=', 5)
            ->sortBy('stock')
            ->take(10);
        
        return view('categories.show', compact('category', 'mainCategories', 'stats', 'lowStockProducts'));
    }

    // -------------------------
    // ROUTES ADMIN (avec vérifications)
    // -------------------------

    /**
     * Formulaire pour créer une nouvelle catégorie
     */
    public function create()
    {
        $this->authorizeAdmin();
        
        $mainCategories = Category::whereNull('parent_id')
            ->where('tenant_id', auth()->user()->tenant_id) // 👈 FILTRE PAR TENANT
            ->orderBy('name')
            ->distinct()
            ->get();
            
        return view('categories.create', compact('mainCategories'));
    }

    /**
     * Enregistre une nouvelle catégorie
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
        ]);

        // Vérifier que la catégorie parente appartient à la même quincaillerie
        if ($request->parent_id) {
            $parentCategory = Category::where('tenant_id', auth()->user()->tenant_id)
                ->find($request->parent_id);
                
            if (!$parentCategory) {
                return back()->with('error', 'Catégorie parente invalide ou n\'appartient pas à votre quincaillerie.');
            }
        }

        // Création de la catégorie
        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'owner_id' => Auth::id(),
            'tenant_id' => auth()->user()->tenant_id,
        ]);

        $message = $request->parent_id 
            ? 'Sous-catégorie créée avec succès.'
            : 'Catégorie principale créée avec succès.';

        return redirect()->route('categories.index')->with('success', $message);
    }

    /**
     * Formulaire pour éditer une catégorie existante
     */
    public function edit($id)
    {
        $this->authorizeAdmin();
        
        $category = Category::with('parent')
            ->where('tenant_id', auth()->user()->tenant_id) // 👈 FILTRE PAR TENANT
            ->findOrFail($id);
            
        $this->authorizeCategoryAccess($category);
        
        // Récupérer toutes les catégories principales (sauf elle-même et ses enfants)
        $mainCategories = Category::whereNull('parent_id')
            ->where('tenant_id', auth()->user()->tenant_id) // 👈 FILTRE PAR TENANT
            ->where('id', '!=', $id)
            ->whereNotIn('id', function($query) use ($id) {
                $query->select('id')
                      ->from('categories')
                      ->where('parent_id', $id);
            })
            ->orderBy('name')
            ->distinct()
            ->get();
        
        return view('categories.edit', compact('category', 'mainCategories'));
    }

    /**
     * Met à jour une catégorie existante
     */
    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();
        
        $category = Category::where('tenant_id', auth()->user()->tenant_id)
            ->findOrFail($id);
        $this->authorizeCategoryAccess($category);

        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id|different:id',
            'description' => 'nullable|string|max:1000',
        ]);

        // Vérifier que la catégorie parente appartient à la même quincaillerie
        if ($request->parent_id) {
            $parentCategory = Category::where('tenant_id', auth()->user()->tenant_id)
                ->find($request->parent_id);
                
            if (!$parentCategory) {
                return back()->with('error', 'Catégorie parente invalide.');
            }
        }

        // Empêcher de créer une boucle dans l'arborescence
        if ($request->parent_id) {
            $potentialParent = Category::find($request->parent_id);
            if ($this->isDescendant($potentialParent, $category)) {
                return redirect()->back()
                    ->withErrors(['parent_id' => 'Impossible de sélectionner une sous-catégorie comme parent.'])
                    ->withInput();
            }
        }

        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Vérifie si une catégorie est descendante d'une autre (pour éviter les boucles)
     */
    private function isDescendant($parent, $child)
    {
        if (!$parent || !$child) return false;
        
        $current = $child->parent;
        while ($current) {
            if ($current->id === $parent->id) {
                return true;
            }
            $current = $current->parent;
        }
        
        return false;
    }

    /**
     * Supprime une catégorie
     */
    public function destroy($id)
    {
        $this->authorizeAdmin();
        
        $category = Category::withCount(['products', 'children'])
            ->where('tenant_id', auth()->user()->tenant_id) // 👈 FILTRE PAR TENANT
            ->findOrFail($id);
            
        $this->authorizeCategoryAccess($category);
        
        // Vérifier si la catégorie peut être supprimée
        if ($category->products_count > 0) {
            return redirect()->route('categories.index')
                ->with('warning', 
                    "Impossible de supprimer cette catégorie car elle contient {$category->products_count} produit(s)."
                );
        }
        
        // Transférer les sous-catégories au parent ou les rendre principales
        if ($category->children_count > 0) {
            if ($category->parent_id) {
                // Vérifier que le parent appartient à la même quincaillerie
                $parentCategory = Category::where('tenant_id', auth()->user()->tenant_id)
                    ->find($category->parent_id);
                    
                if (!$parentCategory) {
                    return back()->with('error', 'Impossible de transférer les sous-catégories.');
                }
                $category->children()->update(['parent_id' => $category->parent_id]);
            } else {
                $category->children()->update(['parent_id' => null]);
            }
        }
        
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }

    /**
     * Supprime une catégorie et transfère ses produits et sous-catégories
     */
    public function destroyWithTransfer(Request $request, $id)
    {
        $this->authorizeAdmin();
        
        $category = Category::where('tenant_id', auth()->user()->tenant_id)
            ->findOrFail($id);
        $this->authorizeCategoryAccess($category);
        
        $request->validate([
            'new_category_id' => 'required|exists:categories,id|different:id',
            'move_subcategories' => 'sometimes|boolean',
        ]);
        
        // Vérifier que la nouvelle catégorie appartient à la même quincaillerie
        $newCategory = Category::where('tenant_id', auth()->user()->tenant_id)
            ->find($request->new_category_id);
            
        if (!$newCategory) {
            return back()->with('error', 'Catégorie de destination invalide.');
        }
        
        DB::transaction(function () use ($category, $request, $newCategory) {
            // Transférer les produits vers la nouvelle catégorie
            $category->products()->update(['category_id' => $request->new_category_id]);
            
            // Transférer les sous-catégories si demandé
            if ($request->move_subcategories) {
                $category->children()->update(['parent_id' => $request->new_category_id]);
            }
            
            // Supprimer la catégorie
            $category->delete();
        });
        
        return redirect()->route('categories.index')
            ->with('success', "Catégorie supprimée. Tous les produits ont été transférés vers '{$newCategory->name}'.");
    }

    // -------------------------
    // ROUTES PRODUITS
    // -------------------------

    /**
     * Ajouter un produit à une catégorie
     */
    public function addProduct(Request $request, $id)
    {
        $this->authorizeAdmin();
        
        $category = Category::where('tenant_id', auth()->user()->tenant_id)
            ->findOrFail($id);
        $this->authorizeCategoryAccess($category);
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        
        $product = Product::where('tenant_id', auth()->user()->tenant_id)
            ->find($request->product_id);
        
        // Vérifier que le produit appartient à la même quincaillerie
        if (!$product) {
            return back()->with('error', 'Produit invalide.');
        }
        
        $product->category_id = $category->id;
        $product->save();
        
        return redirect()->route('categories.show', $category->id)
            ->with('success', "Le produit '{$product->name}' a été ajouté à la catégorie.");
    }

    /**
     * Transférer un produit vers une autre catégorie
     */
    public function transferProduct(Request $request, $id, $productId)
    {
        $this->authorizeAdmin();
        
        $category = Category::where('tenant_id', auth()->user()->tenant_id)
            ->findOrFail($id);
        $this->authorizeCategoryAccess($category);
        
        $product = Product::where('tenant_id', auth()->user()->tenant_id)
            ->findOrFail($productId);
        
        $request->validate([
            'target_category_id' => 'required|exists:categories,id|different:' . $id,
        ]);
        
        $targetCategory = Category::where('tenant_id', auth()->user()->tenant_id)
            ->find($request->target_category_id);
            
        if (!$targetCategory) {
            return back()->with('error', 'Catégorie de destination invalide.');
        }
        
        $product->category_id = $targetCategory->id;
        $product->save();
        
        return redirect()->route('categories.show', $category->id)
            ->with('success', "Le produit '{$product->name}' a été transféré vers '{$targetCategory->name}'.");
    }

    /**
     * Voir tous les produits d'une catégorie (avec pagination)
     */
    public function products($id)
    {
        $category = Category::with(['products' => function($query) {
            $query->with('supplier')
                  ->orderBy('name')
                  ->distinct()
                  ->paginate(20);
        }])->where('tenant_id', auth()->user()->tenant_id)
          ->findOrFail($id);
        
        $this->authorizeCategoryAccess($category);
        
        $allProducts = $category->getAllProducts();
        
        return view('categories.products', compact('category', 'allProducts'));
    }

    /**
     * Statistiques avancées de la catégorie
     */
    public function detailedStats($id)
    {
        $category = Category::with(['products', 'children.products'])
            ->where('tenant_id', auth()->user()->tenant_id)
            ->findOrFail($id);
            
        $this->authorizeCategoryAccess($category);
        
        $allProducts = $category->getAllProducts();
        
        $stats = [
            'total_products' => $allProducts->count(),
            'direct_products' => $category->products->count(),
            'subcategory_products' => $allProducts->count() - $category->products->count(),
            'by_subcategory' => $category->children->mapWithKeys(function($child) {
                return [$child->name => $child->getAllProducts()->count()];
            })->toArray(),
            'stock_distribution' => [
                'out_of_stock' => $allProducts->where('stock', 0)->count(),
                'low_stock' => $allProducts->whereBetween('stock', [1, 5])->count(),
                'medium_stock' => $allProducts->whereBetween('stock', [6, 20])->count(),
                'high_stock' => $allProducts->where('stock', '>', 20)->count(),
            ],
            'value_by_subcategory' => $category->children->mapWithKeys(function($child) {
                $products = $child->getAllProducts();
                $value = $products->sum(function($product) {
                    return $product->stock * $product->purchase_price;
                });
                return [$child->name => $value];
            })->toArray(),
        ];
        
        return response()->json($stats);
    }
}