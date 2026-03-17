<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\LandingController;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Client;
use App\Mail\NewTenantWelcomeMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

// ======================
// ROUTES PUBLIQUES (VITRINE)
// ======================
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/demo', [LandingController::class, 'demo'])->name('demo');
Route::get('/tarifs', [LandingController::class, 'pricing'])->name('pricing');

// Routes d'inscription
Route::get('/inscription', [LandingController::class, 'registerForm'])->name('register.form');
Route::post('/inscription', [LandingController::class, 'register'])->name('register.tenant');
Route::get('/inscription/succes', [LandingController::class, 'registerSuccess'])->name('register.success');

// Route de test
Route::view('/welcome', 'welcome');

// ======================
// Routes AJAX pour le dashboard
// ======================
Route::middleware(['auth'])->prefix('ajax/dashboard')->name('ajax.dashboard.')->group(function () {
    Route::get('/chart-data', [DashboardController::class, 'chartData'])->name('chart');
    Route::get('/stats', [DashboardController::class, 'stats'])->name('stats');
    Route::get('/recent-sales', [DashboardController::class, 'recentSales'])->name('recent-sales');
    Route::get('/low-stock', [DashboardController::class, 'lowStock'])->name('low-stock');
});

// ======================
// Routes SUPER ADMIN GLOBAL (toi - le créateur)
// ======================
Route::middleware(['auth', 'super_admin_global'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/tenants', [SuperAdminController::class, 'tenants'])->name('tenants');
    Route::get('/tenants/create', [SuperAdminController::class, 'createTenant'])->name('tenants.create');
    Route::post('/tenants', [SuperAdminController::class, 'storeTenant'])->name('tenants.store');
    Route::get('/tenants/{tenant}', [SuperAdminController::class, 'showTenant'])->name('tenants.show');
    Route::patch('/tenants/{tenant}/toggle', [SuperAdminController::class, 'toggleTenant'])->name('tenants.toggle');
    Route::delete('/tenants/{tenant}', [SuperAdminController::class, 'destroyTenant'])->name('tenants.destroy');
    
    // Routes paiements
    Route::get('/tenants/{tenant}/payments', [SuperAdminController::class, 'managePayments'])->name('tenants.payments');
    Route::post('/tenants/{tenant}/payments', [SuperAdminController::class, 'markPaymentReceived'])->name('tenants.payments.store');
    Route::post('/tenants/{tenant}/extend', [SuperAdminController::class, 'extendSubscription'])->name('tenants.extend');
});

// ======================
// Routes ADMIN (super_admin et admin de la quincaillerie)
// ======================

// 1. CATÉGORIES - CRUD admin (admin uniquement)
Route::middleware(['auth', 'admin'])->prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::post('/', [CategoryController::class, 'store'])->name('store');
    Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
    Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    Route::get('/{category}/products', [CategoryController::class, 'products'])->name('products');
    Route::get('/{category}/stats', [CategoryController::class, 'detailedStats'])->name('stats');
});

// 2. PRODUITS - Gestion complète (admin, super_admin et magasinier)
Route::middleware(['auth', 'stock.manager'])->prefix('admin/products')->name('admin.products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    
    // Gestion du stock
    Route::post('/{product}/restock', [ProductController::class, 'restock'])->name('restock');
    Route::post('/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('adjust-stock');
    
    // Export
    Route::get('/{product}/history/export', [ProductController::class, 'exportHistory'])->name('history.export');
    Route::get('/global-history/export', [ProductController::class, 'exportGlobalHistory'])->name('global-history.export');
    
    // Gestion des cumuls et fusion
    Route::post('/merge', [ProductController::class, 'mergeProducts'])->name('merge');
    Route::post('/{product}/uncumulate', [ProductController::class, 'uncumulateProduct'])->name('uncumulate');
});

// 3. FOURNISSEURS - CRUD admin (admin uniquement)
Route::middleware(['auth', 'admin'])->prefix('admin/suppliers')->name('admin.suppliers.')->group(function () {
    Route::get('/', [SupplierController::class, 'index'])->name('index');
    Route::get('/create', [SupplierController::class, 'create'])->name('create');
    Route::post('/', [SupplierController::class, 'store'])->name('store');
    Route::get('/{supplier}', [SupplierController::class, 'show'])->name('show');
    Route::get('/{supplier}/edit', [SupplierController::class, 'edit'])->name('edit');
    Route::put('/{supplier}', [SupplierController::class, 'update'])->name('update');
    Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('destroy');
    Route::get('/{supplier}/products', [SupplierController::class, 'products'])->name('products');
    Route::get('/{supplier}/orders', [SupplierController::class, 'orders'])->name('orders');
});

// 4. Gestion des utilisateurs (admin uniquement)
Route::middleware(['auth', 'admin'])->prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    Route::get('/statistics', [UserController::class, 'statistics'])->name('statistics');
});

// ======================
// Routes protégées par authentification (pour tous les utilisateurs)
// ======================
Route::middleware(['auth'])->group(function () {
    // ----------------------
    // TABLEAU DE BORD PRINCIPAL
    // ----------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ----------------------
    // VENTES - Accessible à tous les utilisateurs authentifiés
    // ----------------------
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SaleController::class, 'index'])->name('index');
        Route::get('/create', [SaleController::class, 'create'])->name('create');
        Route::post('/', [SaleController::class, 'store'])->name('store');
        Route::get('/{sale}', [SaleController::class, 'show'])->name('show');
        Route::get('/{sale}/edit', [SaleController::class, 'edit'])->name('edit');
        Route::put('/{sale}', [SaleController::class, 'update'])->name('update');
        Route::delete('/{sale}', [SaleController::class, 'destroy'])->name('destroy');
        Route::get('/{sale}/invoice', [SaleController::class, 'invoice'])->name('invoice');
        Route::post('/{sale}/status', [SaleController::class, 'updateStatus'])->name('status');
    });

    // ----------------------
    // CLIENTS - Accessible à tous les utilisateurs authentifiés
    // ----------------------
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
        Route::get('/{client}/sales', [ClientController::class, 'sales'])->name('sales');
        Route::get('/{client}/statistics', [ClientController::class, 'statistics'])->name('statistics');
    });

    // ----------------------
    // FOURNISSEURS - Lecture seule pour les utilisateurs normaux
    // ----------------------
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('/{supplier}', [SupplierController::class, 'show'])->name('show');
        Route::get('/{supplier}/products', [SupplierController::class, 'products'])->name('products');
    });

    // ----------------------
    // PRODUITS - Lecture seule pour les utilisateurs normaux
    // ----------------------
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/category/{category}', [ProductController::class, 'byCategory'])->name('byCategory');
        Route::get('/search', [ProductController::class, 'search'])->name('search');
        Route::get('/{product}', [ProductController::class, 'show'])->whereNumber('product')->name('show');
        
        // Historique et mouvements (lecture pour tous)
        Route::get('/{product}/history', [ProductController::class, 'history'])->name('history');
        Route::get('/global-history', [ProductController::class, 'globalHistory'])->name('global-history');
        
        // Actions rapides (vente rapide)
        Route::post('/{product}/quick-sale', [ProductController::class, 'quickSale'])->name('quick-sale');
    });

    // ----------------------
    // CATEGORIES - Lecture seule pour les utilisateurs normaux
    // ----------------------
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
    });

    // -----------------------
    // RAPPORTS ET STATISTIQUES - Réservé aux managers et au-dessus
    // ----------------------
    Route::middleware(['manager'])->prefix('reports')->name('reports.')->group(function () {
        // Page d'accueil des rapports
        Route::get('/', function() {
            return view('reports.index');
        })->name('index');
        
        Route::get('/sales', [SaleController::class, 'salesReport'])->name('sales');
        Route::get('/clients', [ClientController::class, 'clientsReport'])->name('clients');
        Route::get('/products', [ProductController::class, 'productsReport'])->name('products');
        Route::get('/inventory', [ProductController::class, 'inventoryReport'])->name('inventory');
        Route::get('/grouped-stocks', [ProductController::class, 'groupedStocksReport'])->name('grouped-stocks');
        Route::get('/grouped-stocks/export/{format?}', [ProductController::class, 'exportGroupedStocks'])
            ->name('grouped-stocks.export');
    });

    // ----------------------
    // API TEMPS RÉEL
    // ----------------------
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/dashboard-stats', [SaleController::class, 'dashboardStats'])->name('dashboard.stats');
        Route::get('/recent-sales', [SaleController::class, 'recentSales'])->name('recent.sales');
        Route::get('/top-products', [ProductController::class, 'topProducts'])->name('top.products');
        Route::get('/grouped-stocks-stats', [ProductController::class, 'getQuickStats'])->name('grouped-stocks.stats');
        
        // Routes pour le modal de fusion
        Route::get('/modal/categories', function () {
            try {
                $categories = \App\Models\Category::orderBy('name', 'asc')->get(['id', 'name']);
                return response()->json([
                    'success' => true,
                    'data' => $categories
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'data' => []
                ], 500);
            }
        })->name('modal.categories');
        
        Route::get('/modal/suppliers', function () {
            try {
                $suppliers = \App\Models\Supplier::orderBy('name', 'asc')->get(['id', 'name']);
                return response()->json([
                    'success' => true,
                    'data' => $suppliers
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'data' => []
                ], 500);
            }
        })->name('modal.suppliers');
    });
    
    // ----------------------
    // PROFIL UTILISATEUR
    // ----------------------
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});

// ======================
// ROUTE DE TEST EMAIL (avec authentification)
// ======================
Route::middleware(['auth'])->get('/test-email', function () {
    try {
        Illuminate\Support\Facades\Mail::raw('Test de configuration email', function($message) {
            $message->to(config('mail.from.address'))
                    ->subject('Test Laravel - ' . now()->format('H:i:s'));
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Email envoyé avec succès !',
            'config' => [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'username' => config('mail.mailers.smtp.username') ? '✓' : '✗',
                'encryption' => config('mail.mailers.smtp.encryption'),
                'from' => config('mail.from.address'),
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
})->name('test.email');

// ======================
// ROUTES DE TEST EMAIL SANS AUTHENTIFICATION
// ======================

// Test SMTP simple
Route::get('/test-smtp', function() {
    try {
        $user = User::latest()->first();
        
        if (!$user) {
            return "❌ Aucun utilisateur trouvé dans la base de données";
        }
        
        Mail::raw('Test de connexion SMTP - ' . now(), function($message) use ($user) {
            $message->to($user->email)
                    ->subject('Test SMTP QuincaPro');
        });
        
        return "✅ Test SMTP réussi ! Email envoyé à " . $user->email;
        
    } catch (\Exception $e) {
        return "❌ Erreur SMTP : " . $e->getMessage();
    }
});

// Test avec le Mailable
Route::get('/test-mailable', function() {
    try {
        $user = User::latest()->first();
        
        if (!$user) {
            return "❌ Aucun utilisateur trouvé dans la base de données";
        }
        
        $plainPassword = 'TestPassword123';
        
        Mail::to($user->email)->send(new NewTenantWelcomeMail($user, $plainPassword));
        
        return "✅ Mailable envoyé avec succès à " . $user->email;
        
    } catch (\Exception $e) {
        return "❌ Erreur Mailable : " . $e->getMessage() . "<br>" .
               "Fichier : " . $e->getFile() . "<br>" .
               "Ligne : " . $e->getLine();
    }
});

// Test avec affichage de la vue
Route::get('/test-email-view', function() {
    try {
        $user = User::latest()->first();
        
        if (!$user) {
            return "❌ Aucun utilisateur trouvé";
        }
        
        $plainPassword = 'TestPassword123';
        
        return view('emails.new-tenant-welcome', [
            'user' => $user,
            'plainPassword' => $plainPassword
        ]);
        
    } catch (\Exception $e) {
        return "❌ Erreur d'affichage de la vue : " . $e->getMessage();
    }
});

// Test d'envoi direct (sans Mailable)
Route::get('/test-email-direct', function() {
    try {
        $user = User::latest()->first();
        
        if (!$user) {
            return "❌ Aucun utilisateur trouvé";
        }
        
        $plainPassword = 'TestPassword123';
        
        // Construire le HTML
        $html = view('emails.new-tenant-welcome', [
            'user' => $user,
            'plainPassword' => $plainPassword
        ])->render();
        
        // Envoyer l'email
        Mail::send([], [], function($message) use ($user, $html) {
            $message->to($user->email)
                    ->subject('Bienvenue sur QuincaPro - Test direct')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->setBody($html, 'text/html');
        });
        
        return "✅ Email direct envoyé avec succès à " . $user->email;
        
    } catch (\Exception $e) {
        return "❌ Erreur envoi direct : " . $e->getMessage();
    }
});

// Test de vérification de la configuration
Route::get('/mail-config', function() {
    return [
        'default' => config('mail.default'),
        'smtp' => [
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username') ? '✓ défini' : '✗ non défini',
            'encryption' => config('mail.mailers.smtp.encryption'),
        ],
        'from' => [
            'address' => config('mail.from.address'),
            'name' => config('mail.from.name'),
        ],
        'mailer_available' => Mail::mailer() ? '✓' : '✗',
    ];
});

// ======================
// Routes de débogage (optionnel)
// ======================
Route::get('/debug-categories-routes', function () {
    $categoriesRoutes = collect(Route::getRoutes())->filter(function ($route) {
        return str_contains($route->getName() ?? '', 'categories');
    })->map(function ($route) {
        return [
            'name' => $route->getName(),
            'uri' => $route->uri(),
            'methods' => $route->methods(),
        ];
    });

    return response()->json($categoriesRoutes);
})->middleware('auth');

// ======================
// Routes d'authentification
// ======================
require __DIR__ . '/auth.php';