<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 📅 Dates
        $today = Carbon::today();
        $weekAgo = Carbon::today()->subDays(7);

        // 1️⃣ Ventes aujourd'hui
        $salesToday = Sale::whereDate('created_at', $today)->count();

        // 2️⃣ Chiffre d'affaires total (aujourd'hui)
        $totalRevenue = Sale::whereDate('created_at', $today)->sum('total_price');

        // 3️⃣ Chiffre d'affaires total (toutes les ventes)
        $totalRevenueAll = Sale::sum('total_price');

        // 4️⃣ Alertes Stock (basé sur le stock)
        $lowStockThreshold = 5;      // Produits à surveiller
        $criticalStockThreshold = 2; // Stock critique

        // Stock faible → <= 5 et > 2
        $lowStockProducts = Product::where('stock', '<=', $lowStockThreshold)
                                   ->where('stock', '>', $criticalStockThreshold)
                                   ->orderBy('stock')
                                   ->limit(10)
                                   ->get();

        // Stock critique → <= 2
        $criticalStockProducts = Product::where('stock', '<=', $criticalStockThreshold)
                                        ->orderBy('stock')
                                        ->limit(10)
                                        ->get();

        // Total alertes
        $lowStockCount = $lowStockProducts->count() + $criticalStockProducts->count();

        // 5️⃣ Clients actifs (mois en cours)
        $activeClients = Sale::whereMonth('created_at', $today->month)
                             ->distinct('client_id')
                             ->count('client_id');

        // 6️⃣ Nouveaux clients (7 jours)
        $newClients = Client::whereBetween('created_at', [$weekAgo, $today])->count();

        // 7️⃣ Ventes récentes
        $recentSales = Sale::with(['client', 'items.product'])
                           ->orderBy('created_at', 'desc')
                           ->take(10)
                           ->get();

        // 8️⃣ Données graphique (7 derniers jours)
        $dates = [];
        $totals = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates[] = $date->format('d/m');
            $totals[] = Sale::whereDate('created_at', $date)->sum('total_price') ?? 0;
        }

        // 9️⃣ Statistiques produits
        $totalProducts = Product::count();
        $totalStockValue = Product::sum(DB::raw('sale_price * stock'));

        // 🔟 Alerte ventes
        $dailyTarget = 5;
        $lowSalesAlert = $salesToday < $dailyTarget;

        // Retour vue
        return view('dashboard', compact(
            'salesToday',
            'totalRevenue',
            'totalRevenueAll',
            'lowStockProducts',
            'criticalStockProducts',
            'lowStockCount',
            'activeClients',
            'newClients',
            'recentSales',
            'dates',
            'totals',
            'lowSalesAlert',
            'totalProducts',
            'totalStockValue'
        ));
    }

    // 📊 Données du graphique en AJAX
    public function chartData(Request $request)
    {
        $period = $request->get('period', 7);
        $dates = [];
        $totals = [];

        for ($i = $period - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates[] = $date->format('d/m');
            $totals[] = Sale::whereDate('created_at', $date)->sum('total_price') ?? 0;
        }

        return response()->json([
            'dates' => $dates,
            'totals' => $totals
        ]);
    }

    // 📌 Stats AJAX (cartes auto-refresh)
    public function stats()
    {
        \Log::info('AJAX Stats called', [
        'authenticated' => auth()->check(),
        'user_id' => auth()->id(),
        'session_id' => session()->getId(),
        'ip' => request()->ip()
    ]);
        $today = Carbon::today();

        return response()->json([
            'salesToday' => Sale::whereDate('created_at', $today)->count(),
            'totalRevenue' => Sale::whereDate('created_at', $today)->sum('total_price'),
            'lowStockCount' => Product::where('stock', '<=', 5)->count(),
            'activeClients' => Sale::whereMonth('created_at', $today->month)
                                  ->distinct('client_id')
                                  ->count('client_id'),
            'averageSale' => Sale::whereDate('created_at', $today)->avg('total_price') ?? 0
        ]);
    }

    // 🧾 Ventes récentes AJAX
    public function recentSales()
    {
        $sales = Sale::with(['client', 'items.product'])
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get()
                    ->map(function($sale) {
                        $productNames = $sale->items->map(function($item) {
                            return $item->product->name ?? 'Produit inconnu';
                        })->implode(', ');

                        return [
                            'product_name' => $productNames,
                            'client_name' => $sale->client->name ?? 'Client inconnu',
                            'total_price' => $sale->total_price,
                            'created_at' => $sale->created_at->format('Y-m-d H:i:s')
                        ];
                    });

        return response()->json($sales);
    }

    // 🟥 Stock faible AJAX
    public function lowStock()
    {
        $products = Product::where('stock', '<=', 5)
                        ->orderBy('stock')
                        ->get()
                        ->map(function($product) {
                            return [
                                'name' => $product->name,
                                'stock' => $product->stock,
                                'sale_price' => $product->sale_price
                            ];
                        });

        return response()->json($products);
    }

}
