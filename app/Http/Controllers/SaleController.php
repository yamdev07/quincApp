<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Client;
use App\Models\User;
use App\Services\SaleService;
use App\Http\Requests\StoreSaleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function __construct(private SaleService $saleService)
    {
        $this->middleware('auth');
    }

    private function getTenantId(): int
    {
        return Auth::user()->tenant_id;
    }

    private function authorizeViewSales(): void
    {
        // Tous les utilisateurs authentifiés peuvent voir les ventes
    }

    // ----------------------
    // Liste des ventes
    // ----------------------
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $query = Sale::with(['items', 'client:id,name', 'user:id,name'])
                     ->withSum('items', 'quantity')
                     ->where('tenant_id', $tenantId);

        if ($request->filled('client_id'))   $query->where('client_id', $request->client_id);
        if ($request->filled('user_id'))     $query->where('user_id', $request->user_id);
        if ($request->filled('date_from'))   $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))     $query->whereDate('created_at', '<=', $request->date_to);
        if ($request->filled('min_amount'))  $query->where('total_price', '>=', $request->min_amount);
        if ($request->filled('max_amount'))  $query->where('total_price', '<=', $request->max_amount);

        $sales   = $query->latest()->paginate(10);
        $clients = Client::where('tenant_id', $tenantId)->get(['id', 'name']);
        $users   = User::where('tenant_id', $tenantId)->where('role', '!=', 'super_admin_global')->get(['id', 'name']);

        return view('sales.index', compact('sales', 'clients', 'users'));
    }

    // ----------------------
    // Formulaire de création
    // ----------------------
    public function create()
    {
        $this->authorize('create', Sale::class);

        $tenantId = Auth::user()->tenant_id;
        $products = Product::where('stock', '>', 0)->where('tenant_id', $tenantId)->orderBy('name')->get();
        $clients  = Client::where('tenant_id', $tenantId)->get(['id', 'name']);

        return view('sales.create', compact('products', 'clients'));
    }

    // ----------------------
    // Enregistrer une vente
    // ----------------------
    public function store(StoreSaleRequest $request)
    {
        $tenantId = Auth::user()->tenant_id;

        if ($request->client_id) {
            $client = Client::where('tenant_id', $tenantId)->find($request->client_id);
            if (!$client) {
                return back()->with('error', 'Client invalide.')->withInput();
            }
        }

        try {
            $this->saleService->create($request->validated(), $tenantId, Auth::id());
            return redirect()->route('sales.index')->with('success', 'Vente enregistrée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    // ----------------------
    // Afficher une vente
    // ----------------------
    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $sale = Sale::with(['items.product', 'client', 'user'])
                    ->where('tenant_id', $tenantId)
                    ->findOrFail($id);

        return view('sales.show', [
            'sale'          => $sale,
            'totalQuantity' => $sale->items->sum('quantity'),
        ]);
    }

    // ----------------------
    // ANNULER une vente (DELETE /sales/{sale})
    // ----------------------
    public function destroy(Sale $sale)
    {
        $user = Auth::user();

        $allowedRoles = ['super_admin_global', 'super_admin', 'admin', 'manager', 'storekeeper'];
        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Vous n\'avez pas l\'autorisation d\'annuler des ventes.');
        }

        if ($sale->tenant_id !== $user->tenant_id && !$user->isSuperAdminGlobal()) {
            abort(403);
        }

        $sale->loadMissing('items');

        try {
            $this->saleService->cancel($sale, $user->id);
            return redirect()->route('sales.index')->with('success', 'Vente annulée et stock restauré.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ----------------------
    // ANNULER une vente (POST /sales/{id}/cancel — route legacy)
    // ----------------------
    public function cancel($id)
    {
        $user     = Auth::user();
        $tenantId = $user->tenant_id;

        $allowedRoles = ['super_admin_global', 'super_admin', 'admin', 'manager', 'storekeeper'];
        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Vous n\'avez pas l\'autorisation d\'annuler des ventes.');
        }

        $sale = Sale::with('items')->where('tenant_id', $tenantId)->findOrFail($id);

        try {
            $this->saleService->cancel($sale, $user->id);
            return redirect()->route('sales.index')->with('success', 'Vente annulée et stock restauré.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ----------------------
    // Générer une facture
    // ----------------------
    public function invoice($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $sale = Sale::with(['items.product', 'client', 'user'])
                    ->where('tenant_id', $tenantId)
                    ->findOrFail($id);

        $tenant = Auth::user()->tenant;

        // Embed logo as base64 so it renders correctly in any environment
        // (no symlink dependency, works for print and PDF export too)
        $logoBase64 = null;
        if ($tenant?->logo) {
            $path = storage_path('app/public/' . $tenant->logo);
            if (file_exists($path)) {
                $mime = mime_content_type($path);
                $logoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            }
        }

        return view('sales.invoice', [
            'sale'          => $sale,
            'totalQuantity' => $sale->items->sum('quantity'),
            'tenant'        => $tenant,
            'logoBase64'    => $logoBase64,
        ]);
    }

    // ----------------------
    // API pour les statistiques
    // ----------------------
    public function getStats()
    {
        $this->authorizeViewSales();
        
        $tenantId = $this->getTenantId();
        
        $stats = [
            'total_sales' => Sale::where('tenant_id', $tenantId)->count(),
            'total_revenue' => Sale::where('tenant_id', $tenantId)->sum('total_price'),
            'total_quantity_sold' => SaleItem::whereHas('sale', function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })->sum('quantity'),
            'average_sale' => Sale::where('tenant_id', $tenantId)->avg('total_price'),
            'unique_clients' => Sale::where('tenant_id', $tenantId)->distinct('client_id')->count('client_id'),
            'active_cashiers' => Sale::where('tenant_id', $tenantId)->distinct('user_id')->count('user_id'),
        ];
        
        return response()->json($stats);
    }

    // ----------------------
    // Rapport des ventes
    // ----------------------
    public function salesReport(Request $request)
    {
        $userRole = Auth::user()->role;
        $reportRoles = ['super_admin_global', 'super_admin', 'admin', 'manager'];
        
        if (!in_array($userRole, $reportRoles)) {
            abort(403, 'Vous n\'avez pas les droits pour voir les rapports.');
        }
        
        $tenantId = $this->getTenantId();
        
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $query = Sale::with(['client', 'user', 'items.product'])
                     ->where('tenant_id', $tenantId);
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Pagination pour l'affichage
        $sales = $query->latest()->paginate(15);
        
        // Toutes les ventes pour les statistiques
        $allSales = Sale::where('tenant_id', $tenantId)->get();
        
        $totalRevenue = $allSales->sum('total_price');
        $totalItems = $allSales->flatMap->items->sum('quantity');
        $averageSale = $allSales->avg('total_price') ?? 0;
        
        // Ventes par jour
        $salesByDay = $allSales->groupBy(function($sale) {
            return $sale->created_at->format('Y-m-d');
        })->map(function($daySales) {
            return [
                'count' => $daySales->count(),
                'total' => $daySales->sum('total_price'),
            ];
        });
        
        // Produits les plus vendus
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.tenant_id', $tenantId)
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_quantity'), DB::raw('SUM(sale_items.total_price) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();
        
        $reportData = [
            'total_sales' => $allSales->count(),
            'total_revenue' => $totalRevenue,
            'formatted_total_revenue' => number_format($totalRevenue, 0, ',', ' ') . ' FCFA',
            'total_items' => $totalItems,
            'average_sale' => $averageSale,
            'formatted_average_sale' => number_format($averageSale, 0, ',', ' ') . ' FCFA',
            'sales_by_day' => $salesByDay,
            'top_products' => $topProducts,
        ];
        
        // Pour les filtres
        $clients = Client::where('tenant_id', $tenantId)->get();
        $users = User::where('tenant_id', $tenantId)
                     ->where('role', '!=', 'super_admin_global')
                     ->get();
        
        return view('reports.sales', compact('sales', 'reportData', 'clients', 'users'));
    }
}