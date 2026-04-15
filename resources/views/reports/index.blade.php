@extends('layouts.app')

@section('title', 'Rapports - Gestion de stock')

@section('styles')
<style>
    /* -----------------------------------------------------
       VARIABLES - NOIR & ORANGE (COULEURS PRINCIPALES)
    ----------------------------------------------------- */
    :root {
        --bg-page: #f8fafc;
        --bg-card: #ffffff;
        --bg-side: #f1f5f9;
        --border-light: #e2e8f0;
        --border-soft: #cbd5e1;
        
        /* Noir - textes principaux */
        --text-primary: #0f172a;
        --text-secondary: #334155;
        --text-tertiary: #64748b;
        
        /* Orange - accent principal */
        --accent: #f97316;
        --accent-dark: #ea580c;
        --accent-light: #ffedd5;
        --accent-soft: #fed7aa;
        --accent-gradient: linear-gradient(135deg, #f97316, #ea580c);
        
        /* Dégradé noir-orange */
        --gradient-dark: linear-gradient(145deg, #0f172a, #1e293b);
        
        --badge-low: #b91c1c;
        --badge-bg-low: #fee2e2;
        --badge-warning: #f97316;
        --badge-bg-warning: #fff3cd;
        
        --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        --shadow-hover: 0 10px 15px -3px rgba(249, 115, 22, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    * {
        box-sizing: border-box;
    }

    body {
        background: var(--bg-page);
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--text-primary);
    }

    .reports-page {
        max-width: 1440px;
        margin: 0 auto;
        padding: 32px 24px;
    }

    /* =====================================================
       HEADER - NOIR & ORANGE
    ===================================================== */
    .reports-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .reports-header-left h1 {
        font-size: 32px;
        font-weight: 700;
        background: linear-gradient(135deg, #0f172a 0%, #f97316 80%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.5px;
        margin: 0 0 6px;
    }

    .reports-header-left .subtitle {
        color: var(--text-tertiary);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .date-badge {
        background: var(--accent-light);
        color: var(--accent-dark);
        padding: 4px 14px;
        border-radius: 30px;
        font-weight: 500;
        font-size: 13px;
    }

    .btn-outline {
        background: transparent;
        border: 1.5px solid var(--border-soft);
        color: var(--text-primary);
        height: 46px;
        padding: 0 22px;
        border-radius: 40px;
        font-weight: 500;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-outline i { color: var(--accent); }
    .btn-outline:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent-dark);
    }

    /* =====================================================
       STATISTIQUES RAPIDES
    ===================================================== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 22px;
        margin-bottom: 32px;
    }
    @media (min-width: 580px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .stats-grid { grid-template-columns: repeat(4, 1fr); } }

    .stat-card {
        background: var(--bg-card);
        border-radius: 24px;
        padding: 24px 22px;
        box-shadow: var(--shadow-card);
        border: 1px solid var(--border-light);
        transition: all 0.25s;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        box-shadow: var(--shadow-hover);
        border-color: var(--accent);
        transform: translateY(-2px);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--accent-gradient);
        opacity: 0;
        transition: opacity 0.2s;
    }
    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        background: var(--accent-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 26px;
        transition: all 0.2s;
    }
    .stat-card:hover .stat-icon {
        background: var(--accent);
        color: white;
    }

    .stat-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-tertiary);
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.1;
        margin-bottom: 6px;
    }

    .stat-desc {
        font-size: 13px;
        color: var(--text-tertiary);
    }

    /* =====================================================
       GRILLE DES RAPPORTS
    ===================================================== */
    .reports-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 24px;
        margin-bottom: 40px;
    }
    @media (min-width: 768px) {
        .reports-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (min-width: 1024px) {
        .reports-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .report-card {
        background: var(--bg-card);
        border-radius: 24px;
        border: 1px solid var(--border-light);
        overflow: hidden;
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
        cursor: pointer;
    }
    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: var(--accent);
    }

    .report-card-header {
        padding: 24px 24px 16px;
        border-bottom: 1px solid var(--border-light);
        background: linear-gradient(145deg, #ffffff, #f8fafc);
    }
    .report-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        background: var(--accent-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        transition: all 0.2s;
    }
    .report-icon i {
        font-size: 28px;
        color: var(--accent);
    }
    .report-card:hover .report-icon {
        background: var(--accent);
    }
    .report-card:hover .report-icon i {
        color: white;
    }
    .report-card-header h3 {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 8px;
    }
    .report-card-header p {
        font-size: 14px;
        color: var(--text-tertiary);
        margin: 0;
        line-height: 1.5;
    }

    .report-card-footer {
        padding: 16px 24px;
        background: var(--bg-card);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid var(--border-light);
    }
    .report-link {
        color: var(--accent);
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .report-card:hover .report-link {
        gap: 12px;
    }

    /* =====================================================
       SECTION EXPORT
    ===================================================== */
    .export-section {
        background: linear-gradient(145deg, #0f172a, #1e293b);
        border-radius: 28px;
        padding: 32px;
        margin-top: 20px;
    }
    .export-section h3 {
        color: white;
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 8px;
    }
    .export-section p {
        color: rgba(255,255,255,0.7);
        font-size: 14px;
        margin-bottom: 24px;
    }
    .export-buttons {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }
    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 28px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s;
        background: rgba(255,255,255,0.1);
        color: white;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .btn-export:hover {
        background: var(--accent);
        border-color: var(--accent);
        transform: translateY(-2px);
    }
    .btn-export i {
        font-size: 18px;
    }

    /* =====================================================
       SECURITY NOTE
    ===================================================== */
    .security-note {
        margin-top: 40px;
        text-align: center;
        color: var(--text-tertiary);
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 16px 0;
        border-top: 1px solid var(--border-light);
    }
    .security-note i {
        color: var(--accent);
    }

    .text-accent { color: var(--accent); }
    .bg-accent-light { background: var(--accent-light); }
</style>
@endsection

@section('content')
<div class="reports-page">

    {{-- HEADER --}}
    <div class="reports-header">
        <div class="reports-header-left">
            <h1>Rapports & Statistiques</h1>
            <div class="subtitle">
                <span>📊 Analysez vos performances et prenez des décisions éclairées</span>
                <span class="date-badge">{{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</span>
            </div>
        </div>
        <div class="reports-header-right">
            <a href="{{ route('dashboard') }}" class="btn-outline">
                <i class="bi bi-arrow-left"></i> Retour au tableau de bord
            </a>
        </div>
    </div>

    {{-- STATISTIQUES RAPIDES --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-row">
                <span class="stat-title">Ventes totales</span>
                <span class="stat-icon"><i class="bi bi-cart3"></i></span>
            </div>
            <div class="stat-value" id="stat-total-sales">-</div>
            <div class="stat-desc">depuis le début</div>
        </div>

        <div class="stat-card">
            <div class="stat-row">
                <span class="stat-title">Chiffre d'affaires</span>
                <span class="stat-icon"><i class="bi bi-currency-dollar"></i></span>
            </div>
            <div class="stat-value" id="stat-total-revenue">-</div>
            <div class="stat-desc">cumulé</div>
        </div>

        <div class="stat-card">
            <div class="stat-row">
                <span class="stat-title">Produits vendus</span>
                <span class="stat-icon"><i class="bi bi-box-seam"></i></span>
            </div>
            <div class="stat-value" id="stat-total-products">-</div>
            <div class="stat-desc">unités écoulées</div>
        </div>

        <div class="stat-card">
            <div class="stat-row">
                <span class="stat-title">Stock faible</span>
                <span class="stat-icon"><i class="bi bi-exclamation-triangle"></i></span>
            </div>
            <div class="stat-value" id="stat-low-stock">-</div>
            <div class="stat-desc">à réapprovisionner</div>
        </div>
    </div>

    {{-- GRILLE DES RAPPORTS --}}
    <div class="reports-grid">
        <!-- Rapport des ventes -->
        <a href="{{ route('reports.sales') }}" class="report-card">
            <div class="report-card-header">
                <div class="report-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
                <h3>Rapport des ventes</h3>
                <p>Analyse détaillée des ventes, tendances, meilleures périodes et évolution du chiffre d'affaires.</p>
            </div>
            <div class="report-card-footer">
                <span class="report-link">Consulter <i class="bi bi-arrow-right"></i></span>
            </div>
        </a>

        <!-- Rapport des produits -->
        <a href="{{ route('reports.products') }}" class="report-card">
            <div class="report-card-header">
                <div class="report-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h3>Rapport des produits</h3>
                <p>Top produits, performances par catégorie, produits les plus rentables et analyse des stocks.</p>
            </div>
            <div class="report-card-footer">
                <span class="report-link">Consulter <i class="bi bi-arrow-right"></i></span>
            </div>
        </a>

        <!-- Rapport des clients -->
        <a href="{{ route('reports.clients') }}" class="report-card">
            <div class="report-card-header">
                <div class="report-icon">
                    <i class="bi bi-people"></i>
                </div>
                <h3>Rapport des clients</h3>
                <p>Analyse de la fidélité, meilleurs clients, historique d'achats et comportement d'achat.</p>
            </div>
            <div class="report-card-footer">
                <span class="report-link">Consulter <i class="bi bi-arrow-right"></i></span>
            </div>
        </a>

        <!-- Rapport d'inventaire -->
        <a href="{{ route('reports.inventory') }}" class="report-card">
            <div class="report-card-header">
                <div class="report-icon">
                    <i class="bi bi-clipboard-data"></i>
                </div>
                <h3>Rapport d'inventaire</h3>
                <p>État des stocks, valeur totale du stock, produits par catégorie et rotation des produits.</p>
            </div>
            <div class="report-card-footer">
                <span class="report-link">Consulter <i class="bi bi-arrow-right"></i></span>
            </div>
        </a>

        <!-- Stocks groupés -->
        <a href="{{ route('reports.grouped-stocks') }}" class="report-card">
            <div class="report-card-header">
                <div class="report-icon">
                    <i class="bi bi-diagram-3"></i>
                </div>
                <h3>Stocks groupés</h3>
                <p>Analyse des stocks par lot, prix d'achat, valeur par lot et historique des réapprovisionnements.</p>
            </div>
            <div class="report-card-footer">
                <span class="report-link">Consulter <i class="bi bi-arrow-right"></i></span>
            </div>
        </a>

        <!-- Performances -->
        <div class="report-card" style="cursor: default;">
            <div class="report-card-header">
                <div class="report-icon">
                    <i class="bi bi-trophy"></i>
                </div>
                <h3>Performances</h3>
                <p>Indicateurs clés de performance, marges bénéficiaires et analyse comparative.</p>
            </div>
            <div class="report-card-footer">
                <span class="text-muted" style="font-size: 13px;">Bientôt disponible</span>
            </div>
        </div>
    </div>

    {{-- SECTION EXPORT --}}
    <div class="export-section">
        <h3>📤 Exporter vos données</h3>
        <p>Téléchargez vos rapports au format Excel, CSV ou PDF pour une analyse plus poussée</p>
        <div class="export-buttons">
            <a href="{{ route('reports.grouped-stocks.export', 'excel') }}" class="btn-export">
                <i class="bi bi-file-excel"></i> Excel
            </a>
            <a href="{{ route('reports.grouped-stocks.export', 'csv') }}" class="btn-export">
                <i class="bi bi-filetype-csv"></i> CSV
            </a>
            <a href="{{ route('reports.grouped-stocks.export', 'pdf') }}" class="btn-export">
                <i class="bi bi-file-pdf"></i> PDF
            </a>
        </div>
    </div>

    {{-- SECURITY NOTE --}}
    <div class="security-note">
        <i class="bi bi-shield-check"></i> Données chiffrées et sécurisées · Rapports mis à jour en temps réel
        <span class="date-badge" style="margin-left: 10px;">v2.0</span>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function() {
    // Charger les statistiques rapides via AJAX
    async function loadQuickStats() {
        try {
            const response = await fetch('/api/dashboard-stats', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) return;
            
            const data = await response.json();
            
            const formatMoney = (value) => {
                if (!value) return '0 FCFA';
                return Number(value).toLocaleString('fr-FR') + ' FCFA';
            };
            
            document.getElementById('stat-total-sales') && (document.getElementById('stat-total-sales').innerText = data.total_sales || 0);
            document.getElementById('stat-total-revenue') && (document.getElementById('stat-total-revenue').innerText = formatMoney(data.total_revenue));
            document.getElementById('stat-total-products') && (document.getElementById('stat-total-products').innerText = data.total_quantity_sold || 0);
            document.getElementById('stat-low-stock') && (document.getElementById('stat-low-stock').innerText = data.low_stock_count || 0);
            
        } catch (error) {
            console.error('Erreur lors du chargement des statistiques:', error);
        }
    }
    
    // Charger au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        loadQuickStats();
        
        // Rafraîchir toutes les 30 secondes
        setInterval(function() {
            if (document.visibilityState === 'visible') {
                loadQuickStats();
            }
        }, 30000);
    });
})();
</script>
@endsection