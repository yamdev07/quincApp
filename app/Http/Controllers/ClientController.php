<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Vérifier que le client appartient à la quincaillerie
     */
    private function authorizeClientAccess(Client $client)
    {
        if (!Auth::user()->hasAccessTo($client)) {
            abort(403, 'Vous n\'avez pas accès à ce client.');
        }
    }

    /**
     * Vérifier les permissions de gestion des ventes (pour voir les clients)
     */
    private function authorizeSalesAccess()
    {
        if (!Auth::user()->canManageSales()) {
            abort(403, 'Vous n\'avez pas les droits pour gérer les clients.');
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
     * Afficher la liste des clients
     */
    public function index()
    {
        $this->authorizeSalesAccess();
        
        // Le scope TenantScope s'applique automatiquement !
        $clients = Client::withCount('sales')
                        ->latest()
                        ->paginate(10);
        
        // Calculer des statistiques supplémentaires
        foreach ($clients as $client) {
            $client->total_spent = $client->sales()->sum('total_price');
            $client->last_purchase = $client->sales()->latest()->first()?->created_at;
        }
        
        return view('clients.index', compact('clients'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $this->authorizeAdmin();
        
        return view('clients.create');
    }

    /**
     * Enregistrer un nouveau client
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();
        
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        // owner_id sera auto-assigné par le Trait TenantScope
        Client::create($request->only(['name', 'email', 'phone']));

        return redirect()->route('clients.index')
                         ->with('success', 'Client ajouté avec succès ✅');
    }

    /**
     * Afficher les détails d'un client
     */
    public function show(Client $client)
    {
        $this->authorizeSalesAccess();
        $this->authorizeClientAccess($client);
        
        // Charger les ventes du client avec leurs articles
        $client->load(['sales' => function($query) {
            $query->latest()->with('items.product');
        }]);
        
        // Statistiques du client
        $stats = [
            'total_sales' => $client->sales->count(),
            'total_spent' => $client->sales->sum('total_price'),
            'average_cart' => $client->sales->avg('total_price') ?? 0,
            'first_purchase' => $client->sales->min('created_at'),
            'last_purchase' => $client->sales->max('created_at'),
            'products_purchased' => $client->sales->flatMap->items->sum('quantity'),
        ];
        
        // Produits préférés (top 5)
        $favoriteProducts = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.client_id', $client->id)
            ->select('products.id', 'products.name', DB::raw('SUM(sale_items.quantity) as total_quantity'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
        
        return view('clients.show', compact('client', 'stats', 'favoriteProducts'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Client $client)
    {
        $this->authorizeAdmin();
        $this->authorizeClientAccess($client);
        
        return view('clients.edit', compact('client'));
    }

    /**
     * Mettre à jour un client
     */
    public function update(Request $request, Client $client)
    {
        $this->authorizeAdmin();
        $this->authorizeClientAccess($client);
        
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $client->update($request->only(['name', 'email', 'phone']));

        return redirect()->route('clients.index')
                         ->with('success', 'Client mis à jour avec succès ✅');
    }

    /**
     * Supprimer un client
     */
    public function destroy(Client $client)
    {
        $this->authorizeAdmin();
        $this->authorizeClientAccess($client);
        
        // Vérifier si le client a des ventes
        if ($client->sales()->count() > 0) {
            return redirect()->route('clients.index')
                ->with('warning', 
                    "Impossible de supprimer ce client car il a {$client->sales()->count()} vente(s) associée(s).");
        }
        
        $client->delete();

        return redirect()->route('clients.index')
                         ->with('success', 'Client supprimé avec succès ✅');
    }

    /**
     * Recherche de clients (AJAX)
     */
    public function search(Request $request)
    {
        $this->authorizeSalesAccess();
        
        $term = $request->get('q');
        
        $clients = Client::where('name', 'LIKE', "%{$term}%")
                        ->orWhere('email', 'LIKE', "%{$term}%")
                        ->orWhere('phone', 'LIKE', "%{$term}%")
                        ->limit(10)
                        ->get();
        
        return response()->json($clients);
    }

    /**
     * Historique des achats d'un client
     */
    public function history(Client $client)
    {
        $this->authorizeSalesAccess();
        $this->authorizeClientAccess($client);
        
        $sales = $client->sales()
                       ->with('items.product')
                       ->latest()
                       ->paginate(15);
        
        return view('clients.history', compact('client', 'sales'));
    }

    /**
     * Statistiques détaillées d'un client
     */
    public function statistics(Client $client)
    {
        $this->authorizeSalesAccess();
        $this->authorizeClientAccess($client);
        
        // Ventes par mois
        $salesByMonth = DB::table('sales')
            ->select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count, SUM(total_price) as total'))
            ->where('client_id', $client->id)
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
        
        // Produits les plus achetés
        $topProducts = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.client_id', $client->id)
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_quantity'), DB::raw('SUM(sale_items.total_price) as total_spent'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();
        
        return view('clients.statistics', compact('client', 'salesByMonth', 'topProducts'));
    }

    /**
     * Exporter les données d'un client
     */
    public function export(Client $client)
    {
        $this->authorizeSalesAccess();
        $this->authorizeClientAccess($client);
        
        $client->load('sales.items.product');
        
        $filename = 'client_' . $client->id . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($client) {
            $handle = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($handle, ['Date', 'Vente #', 'Produit', 'Quantité', 'Prix unitaire', 'Total']);
            
            // Données
            foreach ($client->sales as $sale) {
                foreach ($sale->items as $item) {
                    fputcsv($handle, [
                        $sale->created_at->format('d/m/Y H:i'),
                        $sale->id,
                        $item->product->name ?? 'Produit inconnu',
                        $item->quantity,
                        number_format($item->unit_price, 0, ',', ''),
                        number_format($item->total_price, 0, ',', ''),
                    ]);
                }
            }
            
            // Total général
            fputcsv($handle, []);
            fputcsv($handle, ['TOTAL GÉNÉRAL', '', '', '', '', number_format($client->sales->sum('total_price'), 0, ',', '')]);
            
            fclose($handle);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clients les plus fidèles (pour le dashboard)
     */
    public function topClients()
    {
        $this->authorizeSalesAccess();
        
        $topClients = Client::withCount('sales')
            ->withSum('sales', 'total_price')
            ->orderBy('sales_count', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json($topClients);
    }
}