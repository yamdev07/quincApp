{{-- resources/views/landing/demo.blade.php --}}
@extends('layouts.landing')

@section('content')
<div class="demo-page">
    {{-- HEADER --}}
    <section class="demo-header">
        <div class="container">
            <h1>Découvrez <span class="text-gradient">QuincaApp</span> en action</h1>
            <p>Explorez les fonctionnalités principales de notre logiciel à travers cette démo interactive</p>
        </div>
    </section>

    {{-- TABS --}}
    <section class="demo-tabs-section">
        <div class="container">
            <div class="demo-tabs-container">
                <button class="demo-tab active" data-tab="stock">
                    <i class="bi bi-box-seam"></i>
                    Stock
                </button>
                <button class="demo-tab" data-tab="sales">
                    <i class="bi bi-cart-check"></i>
                    Ventes
                </button>
                <button class="demo-tab" data-tab="clients">
                    <i class="bi bi-people"></i>
                    Clients
                </button>
                <button class="demo-tab" data-tab="reports">
                    <i class="bi bi-graph-up"></i>
                    Rapports
                </button>
            </div>
        </div>
    </section>

    {{-- CONTENU DES TABS --}}
    <section class="demo-content-section">
        <div class="container">
            {{-- TAB STOCK --}}
            <div class="demo-panel active" id="stock-demo">
                <div class="demo-grid">
                    <div class="demo-visual">
                        <div class="demo-mockup large">
                            <div class="mockup-header">
                                <span class="mockup-dot"></span>
                                <span class="mockup-dot"></span>
                                <span class="mockup-dot"></span>
                                <span class="mockup-title">Gestion de stock</span>
                            </div>
                            <div class="mockup-body">
                                <div class="stock-table">
                                    <div class="stock-row header">
                                        <span>Produit</span>
                                        <span>Réf.</span>
                                        <span>Stock</span>
                                        <span>Prix</span>
                                    </div>
                                    <div class="stock-row">
                                        <span>Marteau</span>
                                        <span>MT-001</span>
                                        <span><span class="stock-badge warning">8</span></span>
                                        <span>4 500 FCFA</span>
                                    </div>
                                    <div class="stock-row">
                                        <span>Tournevis</span>
                                        <span>TV-023</span>
                                        <span><span class="stock-badge success">24</span></span>
                                        <span>1 200 FCFA</span>
                                    </div>
                                    <div class="stock-row">
                                        <span>Scie</span>
                                        <span>SC-045</span>
                                        <span><span class="stock-badge critical">2</span></span>
                                        <span>8 500 FCFA</span>
                                    </div>
                                    <div class="stock-row">
                                        <span>Clous (kg)</span>
                                        <span>CL-100</span>
                                        <span><span class="stock-badge warning">15</span></span>
                                        <span>1 800 FCFA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="demo-description">
                        <span class="demo-badge">📦 Gestion de stock</span>
                        <h2>Suivez vos produits en temps réel</h2>
                        <p>Notre système de gestion de stock vous permet de :</p>
                        <ul class="demo-features-list">
                            <li><i class="bi bi-check-circle-fill"></i> Visualiser instantanément les niveaux de stock</li>
                            <li><i class="bi bi-check-circle-fill"></i> Recevoir des alertes quand un produit est bas</li>
                            <li><i class="bi bi-check-circle-fill"></i> Historique complet des mouvements</li>
                            <li><i class="bi bi-check-circle-fill"></i> Entrées/sorties en un clic</li>
                            <li><i class="bi bi-check-circle-fill"></i> Scan code-barres intégré</li>
                        </ul>
                        <div class="demo-note">
                            <i class="bi bi-info-circle"></i>
                            <span>Les niveaux de stock se mettent à jour automatiquement après chaque vente</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB VENTES --}}
            <div class="demo-panel" id="sales-demo">
                <div class="demo-grid reverse">
                    <div class="demo-description">
                        <span class="demo-badge">🛒 Ventes et facturation</span>
                        <h2>Simplifiez votre processus de vente</h2>
                        <p>Créez des factures en quelques secondes :</p>
                        <ul class="demo-features-list">
                            <li><i class="bi bi-check-circle-fill"></i> Création rapide de devis et factures</li>
                            <li><i class="bi bi-check-circle-fill"></i> Gestion des paiements (espèces, carte, mobile money)</li>
                            <li><i class="bi bi-check-circle-fill"></i> Impression de tickets</li>
                            <li><i class="bi bi-check-circle-fill"></i> Historique des transactions</li>
                            <li><i class="bi bi-check-circle-fill"></i> Rendu monnaie automatique</li>
                        </ul>
                        <div class="demo-stats">
                            <div class="demo-stat">
                                <span class="stat-number">15s</span>
                                <span class="stat-label">pour créer une facture</span>
                            </div>
                            <div class="demo-stat">
                                <span class="stat-number">100%</span>
                                <span class="stat-label">factures personnalisables</span>
                            </div>
                        </div>
                    </div>
                    <div class="demo-visual">
                        <div class="demo-mockup">
                            <div class="mockup-header">
                                <span class="mockup-dot"></span>
                                <span class="mockup-dot"></span>
                                <span class="mockup-dot"></span>
                                <span class="mockup-title">Nouvelle vente</span>
                            </div>
                            <div class="mockup-body">
                                <div class="sale-preview">
                                    <div class="sale-client">
                                        <i class="bi bi-person"></i>
                                        <span>Client: Jean Dupont</span>
                                    </div>
                                    <div class="sale-items">
                                        <div class="sale-item">
                                            <span>Marteau x2</span>
                                            <span>9 000 FCFA</span>
                                        </div>
                                        <div class="sale-item">
                                            <span>Tournevis x3</span>
                                            <span>3 600 FCFA</span>
                                        </div>
                                        <div class="sale-item">
                                            <span>Clous (2kg)</span>
                                            <span>3 600 FCFA</span>
                                        </div>
                                    </div>
                                    <div class="sale-total">
                                        <span>Total</span>
                                        <span class="total-amount">16 200 FCFA</span>
                                    </div>
                                    <div class="sale-actions">
                                        <span class="sale-btn">Imprimer</span>
                                        <span class="sale-btn primary">Valider</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB CLIENTS --}}
            <div class="demo-panel" id="clients-demo">
                <div class="demo-grid">
                    <div class="demo-visual">
                        <div class="demo-mockup">
                            <div class="mockup-header">
                                <span class="mockup-dot"></span>
                                <span class="mockup-dot"></span>
                                <span class="mockup-dot"></span>
                                <span class="mockup-title">Fichier clients</span>
                            </div>
                            <div class="mockup-body">
                                <div class="clients-preview">
                                    <div class="client-card">
                                        <div class="client-avatar">JD</div>
                                        <div class="client-info">
                                            <strong>Jean Dupont</strong>
                                            <span>jean.dupont@email.com</span>
                                            <span>77 123 45 67</span>
                                        </div>
                                        <div class="client-stats">
                                            <span>12 achats</span>
                                            <span>145 000 FCFA</span>
                                        </div>
                                    </div>
                                    <div class="client-card">
                                        <div class="client-avatar">MM</div>
                                        <div class="client-info">
                                            <strong>Marie Martin</strong>
                                            <span>marie.m@email.com</span>
                                            <span>76 987 65 43</span>
                                        </div>
                                        <div class="client-stats">
                                            <span>8 achats</span>
                                            <span>89 000 FCFA</span>
                                        </div>
                                    </div>
                                    <div class="client-card">
                                        <div class="client-avatar">PL</div>
                                        <div class="client-info">
                                            <strong>Pierre Lambert</strong>
                                            <span>p.lambert@email.com</span>
                                            <span>70 456 78 90</span>
                                        </div>
                                        <div class="client-stats">
                                            <span>15 achats</span>
                                            <span>234 000 FCFA</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="demo-description">
                        <span class="demo-badge">👥 Gestion clients</span>
                        <h2>Fidélisez votre clientèle</h2>
                        <p>Un fichier client complet et des outils de fidélisation :</p>
                        <ul class="demo-features-list">
                            <li><i class="bi bi-check-circle-fill"></i> Historique d'achats détaillé</li>
                            <li><i class="bi bi-check-circle-fill"></i> Programme de fidélité intégré</li>
                            <li><i class="bi bi-check-circle-fill"></i> Envoi de promotions par SMS/email</li>
                            <li><i class="bi bi-check-circle-fill"></i> Statistiques client</li>
                            <li><i class="bi bi-check-circle-fill"></i> Gestion des dettes et crédits</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- TAB RAPPORTS --}}
            <div class="demo-panel" id="reports-demo">
                <div class="demo-grid reverse">
                    <div class="demo-description">
                        <span class="demo-badge">📊 Rapports et analyses</span>
                        <h2>Prenez des décisions éclairées</h2>
                        <p>Des rapports détaillés pour analyser votre activité :</p>
                        <ul class="demo-features-list">
                            <li><i class="bi bi-check-circle-fill"></i> Chiffre d'affaires (jour/semaine/mois)</li>
                            <li><i class="bi bi-check-circle-fill"></i> Produits les plus vendus</li>
                            <li><i class="bi bi-check-circle-fill"></i> Évolution des ventes</li>
                            <li><i class="bi bi-check-circle-fill"></i> Analyse des marges</li>
                            <li><i class="bi bi-check-circle-fill"></i> Export Excel/PDF</li>
                        </ul>
                    </div>
                    <div class="demo-visual">
                        <div class="demo-mockup large">
                            <div class="mockup-header">
                                <span class="mockup-dot"></span>
                                <span class="mockup-dot"></span>
                                <span class="mockup-dot"></span>
                                <span class="mockup-title">Tableau de bord</span>
                            </div>
                            <div class="mockup-body">
                                <div class="reports-preview">
                                    <div class="report-chart">
                                        <div class="chart-bar" style="height: 60px;"></div>
                                        <div class="chart-bar" style="height: 40px;"></div>
                                        <div class="chart-bar" style="height: 80px;"></div>
                                        <div class="chart-bar" style="height: 30px;"></div>
                                        <div class="chart-bar" style="height: 70px;"></div>
                                        <div class="chart-bar" style="height: 90px;"></div>
                                        <div class="chart-bar" style="height: 50px;"></div>
                                    </div>
                                    <div class="report-stats">
                                        <div class="report-stat">
                                            <span>CA aujourd'hui</span>
                                            <strong>124 500 FCFA</strong>
                                        </div>
                                        <div class="report-stat">
                                            <span>CA mois</span>
                                            <strong>2 845 000 FCFA</strong>
                                        </div>
                                        <div class="report-stat">
                                            <span>Meilleure vente</span>
                                            <strong>Ciment (45 sacs)</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA SECTION --}}
    <section class="demo-cta-section">
        <div class="container">
            <div class="demo-cta-card">
                <h2>Prêt à passer à l'action ?</h2>
                <p>Rejoignez plus de 500 quincailleries qui utilisent QuincaApp au quotidien</p>
                <div class="demo-cta-actions">
                    <a href="{{ route('pricing') }}" class="btn-primary btn-large">
                        <i class="bi bi-rocket-takeoff"></i>
                        Essai gratuit 14 jours
                    </a>
                    <a href="{{ route('landing') }}" class="btn-outline btn-large">
                        <i class="bi bi-arrow-left"></i>
                        Retour à l'accueil
                    </a>
                </div>
                <p class="small-note">Sans carte bancaire • Sans engagement</p>
            </div>
        </div>
    </section>
</div>

<style>
/* -----------------------------------------------------
   STYLES DE LA PAGE DÉMO
----------------------------------------------------- */

.demo-page {
    background: var(--white);
}

/* Header */
.demo-header {
    padding: 60px 0 40px;
    text-align: center;
    background: linear-gradient(135deg, var(--orange-50) 0%, var(--white) 100%);
}

.demo-header h1 {
    font-size: 42px;
    font-weight: 800;
    color: var(--gray-900);
    margin-bottom: 16px;
}

.demo-header p {
    font-size: 18px;
    color: var(--gray-600);
    max-width: 600px;
    margin: 0 auto;
}

/* Tabs */
.demo-tabs-section {
    padding: 20px 0 0;
    border-bottom: 1px solid var(--gray-200);
    background: var(--white);
}

.demo-tabs-container {
    display: flex;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
}

.demo-tab {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    background: transparent;
    border: none;
    border-bottom: 3px solid transparent;
    font-size: 16px;
    font-weight: 600;
    color: var(--gray-600);
    cursor: pointer;
    transition: all 0.2s;
}

.demo-tab i {
    font-size: 20px;
}

.demo-tab:hover {
    color: var(--orange-500);
}

.demo-tab.active {
    color: var(--orange-500);
    border-bottom-color: var(--orange-500);
}

/* Content Panels */
.demo-content-section {
    padding: 60px 0;
    background: var(--white);
}

.demo-panel {
    display: none;
}

.demo-panel.active {
    display: block;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.demo-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

.demo-grid.reverse {
    direction: rtl;
}

.demo-grid.reverse .demo-description {
    direction: ltr;
}

/* Mockups */
.demo-mockup {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
}

.demo-mockup.large {
    transform: scale(1.05);
}

.mockup-header {
    background: var(--gray-50);
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    border-bottom: 1px solid var(--gray-200);
}

.mockup-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--gray-300);
}

.mockup-dot:first-child { background: #ef4444; }
.mockup-dot:nth-child(2) { background: #eab308; }
.mockup-dot:nth-child(3) { background: #10b981; }

.mockup-title {
    margin-left: auto;
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-600);
}

.mockup-body {
    padding: 24px;
}

/* Stock Table */
.stock-table {
    width: 100%;
}

.stock-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    padding: 12px 0;
    border-bottom: 1px solid var(--gray-100);
}

.stock-row.header {
    color: var(--gray-500);
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
}

.stock-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.stock-badge.critical {
    background: #fee2e2;
    color: #dc2626;
}

.stock-badge.warning {
    background: var(--orange-100);
    color: var(--orange-600);
}

.stock-badge.success {
    background: #dcfce7;
    color: #16a34a;
}

/* Sale Preview */
.sale-preview {
    background: var(--gray-50);
    border-radius: 16px;
    padding: 20px;
}

.sale-client {
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--gray-200);
    margin-bottom: 16px;
}

.sale-client i {
    color: var(--orange-500);
    font-size: 20px;
}

.sale-items {
    margin-bottom: 16px;
}

.sale-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    color: var(--gray-700);
}

.sale-total {
    display: flex;
    justify-content: space-between;
    padding: 16px 0;
    border-top: 2px solid var(--gray-200);
    font-weight: 700;
    font-size: 18px;
}

.total-amount {
    color: var(--orange-500);
}

.sale-actions {
    display: flex;
    gap: 12px;
    margin-top: 16px;
}

.sale-btn {
    flex: 1;
    padding: 10px;
    text-align: center;
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    cursor: default;
}

.sale-btn.primary {
    background: var(--orange-500);
    border-color: var(--orange-500);
    color: var(--white);
}

/* Clients Preview */
.clients-preview {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.client-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: var(--gray-50);
    border-radius: 16px;
}

.client-avatar {
    width: 48px;
    height: 48px;
    background: var(--orange-100);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--orange-600);
    font-weight: 700;
    font-size: 16px;
}

.client-info {
    flex: 1;
}

.client-info strong {
    display: block;
    margin-bottom: 4px;
}

.client-info span {
    display: block;
    font-size: 13px;
    color: var(--gray-500);
}

.client-stats {
    text-align: right;
}

.client-stats span {
    display: block;
    font-size: 13px;
    color: var(--gray-600);
}

.client-stats span:first-child {
    font-weight: 600;
    color: var(--orange-500);
}

/* Reports Preview */
.reports-preview {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.report-chart {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    height: 100px;
}

.chart-bar {
    flex: 1;
    background: var(--orange-200);
    border-radius: 4px;
}

.report-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.report-stat {
    text-align: center;
    padding: 12px;
    background: var(--gray-50);
    border-radius: 12px;
}

.report-stat span {
    display: block;
    font-size: 12px;
    color: var(--gray-500);
    margin-bottom: 4px;
}

.report-stat strong {
    display: block;
    font-size: 14px;
    color: var(--gray-900);
}

/* Demo Description */
.demo-description {
    padding: 20px;
}

.demo-badge {
    display: inline-block;
    padding: 6px 14px;
    background: var(--orange-100);
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    color: var(--orange-600);
    margin-bottom: 20px;
}

.demo-description h2 {
    font-size: 32px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 16px;
}

.demo-description p {
    font-size: 16px;
    color: var(--gray-600);
    margin-bottom: 24px;
    line-height: 1.6;
}

.demo-features-list {
    list-style: none;
    margin-bottom: 24px;
}

.demo-features-list li {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    font-size: 16px;
    color: var(--gray-700);
}

.demo-features-list i {
    color: var(--orange-500);
    font-size: 20px;
}

.demo-note {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: var(--orange-50);
    border-radius: 12px;
    color: var(--gray-700);
    font-size: 14px;
}

.demo-note i {
    color: var(--orange-500);
    font-size: 20px;
}

.demo-stats {
    display: flex;
    gap: 30px;
    margin-top: 30px;
}

.demo-stat {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 28px;
    font-weight: 800;
    color: var(--orange-500);
    margin-bottom: 4px;
}

.stat-label {
    font-size: 13px;
    color: var(--gray-500);
}

/* CTA Section */
.demo-cta-section {
    padding: 80px 0;
    background: var(--gray-50);
}

.demo-cta-card {
    background: var(--white);
    border-radius: 30px;
    padding: 60px;
    text-align: center;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-lg);
}

.demo-cta-card h2 {
    font-size: 36px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 16px;
}

.demo-cta-card p {
    font-size: 18px;
    color: var(--gray-600);
    margin-bottom: 32px;
}

.demo-cta-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    margin-bottom: 24px;
}

.small-note {
    font-size: 13px;
    color: var(--gray-500);
}

/* Responsive */
@media (max-width: 1024px) {
    .demo-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    
    .demo-grid.reverse {
        direction: ltr;
    }
    
    .demo-mockup.large {
        transform: none;
    }
}

@media (max-width: 768px) {
    .demo-header h1 {
        font-size: 32px;
    }
    
    .demo-tabs-container {
        flex-direction: column;
        align-items: stretch;
    }
    
    .demo-tab {
        justify-content: center;
    }
    
    .demo-cta-card {
        padding: 40px 20px;
    }
    
    .demo-cta-actions {
        flex-direction: column;
    }
    
    .demo-stats {
        flex-direction: column;
        gap: 20px;
    }
    
    .report-stats {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.demo-tab');
    const panels = document.querySelectorAll('.demo-panel');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Désactiver tous les tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Activer le tab cliqué
            tab.classList.add('active');
            
            // Cacher tous les panels
            panels.forEach(panel => panel.classList.remove('active'));
            
            // Afficher le panel correspondant
            const tabId = tab.dataset.tab;
            document.getElementById(tabId + '-demo').classList.add('active');
        });
    });
});
</script>
@endsection