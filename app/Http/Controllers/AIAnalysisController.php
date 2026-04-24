<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Category;
use App\Services\GroqAIService;
use App\Services\PlanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AIAnalysisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user->isSuperAdminGlobal() && $user->tenant) {
            $plan = PlanService::for($user->tenant);
            if (!$plan->canUseAI()) {
                return redirect()->route('reports.index')
                    ->with('upgrade', "L'Analyse IA est disponible à partir du plan Business (15 000 FCFA/mois).");
            }
        }
        return view('ai.analysis');
    }

    /**
     * Analyse des performances produits
     */
    public function analyzeProducts(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $period   = $request->get('period', 30);

        $since = Carbon::now()->subDays($period);

        // Top produits vendus
        $topProducts = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.tenant_id', $tenantId)
            ->where('sales.created_at', '>=', $since)
            ->select(
                'products.name',
                'products.stock',
                'products.stock_alert',
                'products.sale_price',
                'products.purchase_price',
                DB::raw('SUM(sale_items.quantity) as qty_sold'),
                DB::raw('SUM(sale_items.total_price) as revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.stock', 'products.stock_alert', 'products.sale_price', 'products.purchase_price')
            ->orderBy('qty_sold', 'desc')
            ->limit(20)
            ->get();

        // Produits sans vente sur la période
        $unsoldProducts = Product::where('tenant_id', $tenantId)
            ->where('stock', '>', 0)
            ->whereNotIn('id', function ($q) use ($tenantId, $since) {
                $q->select('sale_items.product_id')
                  ->from('sale_items')
                  ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                  ->where('sales.tenant_id', $tenantId)
                  ->where('sales.created_at', '>=', $since);
            })
            ->select('name', 'stock', 'purchase_price', 'sale_price')
            ->limit(15)
            ->get();

        // Produits en alerte stock
        $lowStockProducts = Product::where('tenant_id', $tenantId)
            ->whereColumn('stock', '<=', 'stock_alert')
            ->where('stock', '>', 0)
            ->select('name', 'stock', 'stock_alert', 'sale_price')
            ->get();

        // Stats globales
        $totalRevenue   = Sale::where('tenant_id', $tenantId)->where('created_at', '>=', $since)->sum('total_price');
        $totalSales     = Sale::where('tenant_id', $tenantId)->where('created_at', '>=', $since)->count();
        $totalProducts  = Product::where('tenant_id', $tenantId)->count();
        $outOfStock     = Product::where('tenant_id', $tenantId)->where('stock', 0)->count();

        // Construire le contexte pour l'IA
        $context = $this->buildProductContext(
            $topProducts, $unsoldProducts, $lowStockProducts,
            $totalRevenue, $totalSales, $totalProducts, $outOfStock, $period
        );

        $systemPrompt = $this->getSystemPrompt();
        $userMessage  = "Voici les données de performance de ma boutique sur les {$period} derniers jours :\n\n{$context}\n\nFais une analyse complète et donne des recommandations concrètes sur quels produits acheter, lesquels éviter, et comment améliorer les performances.";

        $ai      = new GroqAIService();
        $analysis = $ai->analyze($systemPrompt, $userMessage);

        return view('ai.analysis', compact('analysis', 'period', 'topProducts', 'unsoldProducts', 'lowStockProducts', 'totalRevenue', 'totalSales'));
    }

    /**
     * Analyse des rapports financiers — données identiques au ReportController
     */
    public function analyzeReports(Request $request)
    {
        $tenantId  = Auth::user()->tenant_id;
        $period    = $request->get('period', 30);
        $since     = Carbon::now()->subDays($period);
        $prevSince = Carbon::now()->subDays($period * 2);

        // ── Période actuelle (final_price comme ReportController) ──
        $currentRevenue = Sale::where('tenant_id', $tenantId)->where('created_at', '>=', $since)->sum('final_price');
        $currentSales   = Sale::where('tenant_id', $tenantId)->where('created_at', '>=', $since)->count();
        $averageSale    = $currentSales > 0 ? round($currentRevenue / $currentSales, 0) : 0;

        // ── Période précédente ──
        $prevRevenue = Sale::where('tenant_id', $tenantId)->whereBetween('created_at', [$prevSince, $since])->sum('final_price');
        $prevSales   = Sale::where('tenant_id', $tenantId)->whereBetween('created_at', [$prevSince, $since])->count();

        // ── Top produits (comme ReportController) ──
        $topProducts = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.tenant_id', $tenantId)
            ->where('sales.created_at', '>=', $since)
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_quantity'), DB::raw('SUM(sale_items.total_price) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();

        // ── Top catégories (comme ReportController) ──
        $topCategories = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('sales.tenant_id', $tenantId)
            ->where('sales.created_at', '>=', $since)
            ->select('categories.name', DB::raw('SUM(sale_items.total_price) as revenue'), DB::raw('SUM(sale_items.quantity) as qty'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->limit(10)
            ->get();

        // ── Ventes par jour de la semaine ──
        $salesByDay = Sale::where('tenant_id', $tenantId)
            ->where('created_at', '>=', $since)
            ->select(DB::raw('DAYOFWEEK(created_at) as day_of_week'), DB::raw('COUNT(*) as total'), DB::raw('SUM(final_price) as revenue'))
            ->groupBy('day_of_week')
            ->orderBy('day_of_week')
            ->get();

        // ── Stock (comme ReportController) ──
        $totalStockValue  = Product::where('tenant_id', $tenantId)->sum(DB::raw('purchase_price * stock'));
        $totalProducts    = Product::where('tenant_id', $tenantId)->count();
        $outOfStock       = Product::where('tenant_id', $tenantId)->where('stock', 0)->count();
        $lowStock         = Product::where('tenant_id', $tenantId)->whereColumn('stock', '<=', 'stock_alert')->where('stock', '>', 0)->count();

        // ── Clients (comme ReportController) ──
        $totalClients   = \App\Models\Client::where('tenant_id', $tenantId)->count();
        $activeClients  = Sale::where('tenant_id', $tenantId)->where('created_at', '>=', $since)->distinct('client_id')->count('client_id');

        $evolutionPct = $prevRevenue > 0 ? round((($currentRevenue - $prevRevenue) / $prevRevenue) * 100, 1) : 0;

        $context = $this->buildReportContext(
            $currentRevenue, $currentSales, $averageSale,
            $prevRevenue, $prevSales, $evolutionPct,
            $topProducts, $topCategories, $salesByDay,
            $totalStockValue, $totalProducts, $outOfStock, $lowStock,
            $totalClients, $activeClients, $period
        );

        $systemPrompt = $this->getSystemPrompt();
        $userMessage  = "Voici le rapport complet de ma boutique sur les {$period} derniers jours :\n\n{$context}\n\nAnalyse ces données et donne des recommandations stratégiques concrètes.";

        $ai       = new GroqAIService();
        $analysis = $ai->analyze($systemPrompt, $userMessage);

        return view('ai.analysis', compact(
            'analysis', 'period',
            'currentRevenue', 'currentSales', 'averageSale',
            'prevRevenue', 'evolutionPct',
            'topCategories', 'topProducts',
            'totalStockValue', 'totalProducts', 'outOfStock', 'lowStock',
            'totalClients', 'activeClients'
        ));
    }

    private function buildProductContext($topProducts, $unsoldProducts, $lowStockProducts, $totalRevenue, $totalSales, $totalProducts, $outOfStock, $period): string
    {
        $ctx = "=== STATISTIQUES GLOBALES ===\n";
        $ctx .= "Période analysée : {$period} derniers jours\n";
        $ctx .= "Chiffre d'affaires : " . number_format($totalRevenue, 0, ',', ' ') . " FCFA\n";
        $ctx .= "Nombre de ventes : {$totalSales}\n";
        $ctx .= "Total produits en catalogue : {$totalProducts}\n";
        $ctx .= "Produits en rupture de stock : {$outOfStock}\n\n";

        $ctx .= "=== TOP PRODUITS VENDUS ===\n";
        foreach ($topProducts as $p) {
            $margin = $p->purchase_price > 0 ? round((($p->sale_price - $p->purchase_price) / $p->sale_price) * 100, 1) : 0;
            $ctx .= "- {$p->name} : {$p->qty_sold} vendus, " . number_format($p->revenue, 0, ',', ' ') . " FCFA CA, stock={$p->stock}, seuil alerte={$p->stock_alert}, marge={$margin}%\n";
        }

        $ctx .= "\n=== PRODUITS SANS VENTE (stock immobilisé) ===\n";
        if ($unsoldProducts->isEmpty()) {
            $ctx .= "Aucun produit sans vente sur la période.\n";
        } else {
            foreach ($unsoldProducts as $p) {
                $stockValue = number_format($p->stock * $p->purchase_price, 0, ',', ' ');
                $ctx .= "- {$p->name} : stock={$p->stock}, valeur immobilisée={$stockValue} FCFA\n";
            }
        }

        $ctx .= "\n=== PRODUITS EN ALERTE STOCK ===\n";
        if ($lowStockProducts->isEmpty()) {
            $ctx .= "Aucun produit en alerte stock.\n";
        } else {
            foreach ($lowStockProducts as $p) {
                $ctx .= "- {$p->name} : stock={$p->stock} (seuil={$p->stock_alert})\n";
            }
        }

        return $ctx;
    }

    private function buildReportContext(
        $currentRevenue, $currentSales, $averageSale,
        $prevRevenue, $prevSales, $evolutionPct,
        $topProducts, $topCategories, $salesByDay,
        $totalStockValue, $totalProducts, $outOfStock, $lowStock,
        $totalClients, $activeClients, $period
    ): string {
        // DAYOFWEEK() MariaDB : 1=Dimanche, 2=Lundi, ..., 7=Samedi
        $days = [1 => 'Dimanche', 2 => 'Lundi', 3 => 'Mardi', 4 => 'Mercredi', 5 => 'Jeudi', 6 => 'Vendredi', 7 => 'Samedi'];

        $ctx = "=== RAPPORT FINANCIER ===\n";
        $ctx .= "Période : {$period} derniers jours\n";
        $ctx .= "CA actuel : " . number_format($currentRevenue, 0, ',', ' ') . " FCFA ({$currentSales} ventes)\n";
        $ctx .= "Panier moyen : " . number_format($averageSale, 0, ',', ' ') . " FCFA\n";
        $ctx .= "CA période précédente : " . number_format($prevRevenue, 0, ',', ' ') . " FCFA ({$prevSales} ventes)\n";
        $ctx .= "Évolution : {$evolutionPct}%\n\n";

        $ctx .= "=== TOP PRODUITS ===\n";
        foreach ($topProducts as $p) {
            $ctx .= "- {$p->name} : " . number_format($p->total_revenue, 0, ',', ' ') . " FCFA, {$p->total_quantity} unités\n";
        }

        $ctx .= "\n=== VENTES PAR CATÉGORIE ===\n";
        foreach ($topCategories as $cat) {
            $ctx .= "- {$cat->name} : " . number_format($cat->revenue, 0, ',', ' ') . " FCFA, {$cat->qty} unités vendues\n";
        }

        $ctx .= "\n=== VENTES PAR JOUR DE LA SEMAINE ===\n";
        foreach ($salesByDay as $day) {
            $dayName = $days[(int)$day->day_of_week] ?? 'Inconnu';
            $ctx .= "- {$dayName} : {$day->total} ventes, " . number_format($day->revenue, 0, ',', ' ') . " FCFA\n";
        }

        $ctx .= "\n=== STOCK ===\n";
        $ctx .= "Valeur totale du stock : " . number_format($totalStockValue, 0, ',', ' ') . " FCFA\n";
        $ctx .= "Total produits : {$totalProducts} | En rupture : {$outOfStock} | En alerte : {$lowStock}\n";

        $ctx .= "\n=== CLIENTS ===\n";
        $ctx .= "Total clients : {$totalClients} | Actifs sur la période : {$activeClients}\n";

        return $ctx;
    }

    private function getSystemPrompt(): string
    {
        return "Tu es un expert en gestion de stock et en performance commerciale pour des boutiques et quincailleries en Afrique de l'Ouest. Tu analyses les données de ventes et tu donnes des recommandations concrètes, pratiques et adaptées au contexte local (monnaie FCFA, marché africain). Réponds toujours en français. Sois direct, structuré et actionnable. Utilise des emojis pour rendre la lecture agréable. Format : titre en gras, points clés avec puces, section recommandations claire.";
    }
}
