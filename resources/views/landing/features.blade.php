{{-- resources/views/landing/features.blade.php --}}
@extends('layouts.landing')

@section('content')
<div class="features-page">
    {{-- HERO SECTION --}}
    <section class="features-hero">
        <div class="container">
            <div class="hero-content">
                <span class="hero-badge">🚀 Fonctionnalités</span>
                <h1 class="hero-title">
                    Tout ce dont vous avez besoin pour<br>
                    <span class="text-gradient">gérer votre stock</span>
                </h1>
                <p class="hero-subtitle">
                    Quincaillerie, librairie, épicerie, boutique de vêtements — un logiciel complet qui s'adapte à votre commerce
                </p>
            </div>
        </div>
    </section>

    {{-- MAIN FEATURES GRID --}}
    <section class="main-features">
        <div class="container">
            <div class="features-grid">
                {{-- Gestion de stock avancée --}}
                <div class="feature-card-large">
                    <div class="feature-icon-large">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h3>Gestion de stock avancée</h3>
                    <p>Suivez vos produits en temps réel avec des alertes automatiques lorsque le stock est bas. Consultez l'historique complet des mouvements et anticipez vos réapprovisionnements.</p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-lg"></i> Alertes stock bas automatiques</li>
                        <li><i class="bi bi-check-lg"></i> Historique des mouvements</li>
                        <li><i class="bi bi-check-lg"></i> Gestion par lots et emplacements</li>
                        <li><i class="bi bi-check-lg"></i> Import/Export Excel/CSV</li>
                    </ul>
                </div>

                {{-- Ventes et facturation --}}
                <div class="feature-card-large">
                    <div class="feature-icon-large">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <h3>Ventes et facturation</h3>
                    <p>Créez des devis, factures et tickets de caisse en quelques secondes. Gérez les paiements et suivez vos encaissements en temps réel.</p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-lg"></i> Devis et factures professionnelles</li>
                        <li><i class="bi bi-check-lg"></i> Tickets de caisse imprimables</li>
                        <li><i class="bi bi-check-lg"></i> Gestion des remises et promotions</li>
                        <li><i class="bi bi-check-lg"></i> Multi-devises et TVA</li>
                    </ul>
                </div>

                {{-- Clients --}}
                <div class="feature-card-large">
                    <div class="feature-icon-large">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3>Gestion clients</h3>
                    <p>Centralisez toutes les informations de vos clients, suivez leur historique d'achats et fidélisez-les avec des programmes de fidélité.</p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-lg"></i> Fiche client complète</li>
                        <li><i class="bi bi-check-lg"></i> Historique des achats</li>
                        <li><i class="bi bi-check-lg"></i> Programme de fidélité</li>
                        <li><i class="bi bi-check-lg"></i> Envoi de factures par email</li>
                    </ul>
                </div>

                {{-- Fournisseurs --}}
                <div class="feature-card-large">
                    <div class="feature-icon-large">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h3>Gestion fournisseurs</h3>
                    <p>Gérez vos fournisseurs, suivez vos commandes et optimisez vos achats avec des historiques de prix et des alertes de réapprovisionnement.</p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-lg"></i> Catalogue fournisseurs</li>
                        <li><i class="bi bi-check-lg"></i> Commandes d'achat</li>
                        <li><i class="bi bi-check-lg"></i> Historique des prix</li>
                        <li><i class="bi bi-check-lg"></i> Alertes réapprovisionnement</li>
                    </ul>
                </div>

                {{-- Rapports et statistiques --}}
                <div class="feature-card-large">
                    <div class="feature-icon-large">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h3>Rapports et statistiques</h3>
                    <p>Analysez vos performances avec des graphiques interactifs et des rapports détaillés. Prenez des décisions éclairées grâce à des données précises.</p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-lg"></i> Tableau de bord temps réel</li>
                        <li><i class="bi bi-check-lg"></i> Rapports de vente personnalisables</li>
                        <li><i class="bi bi-check-lg"></i> Analyse des produits les plus vendus</li>
                        <li><i class="bi bi-check-lg"></i> Export PDF et Excel</li>
                    </ul>
                </div>

                {{-- Multi-utilisateurs --}}
                <div class="feature-card-large">
                    <div class="feature-icon-large">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <h3>Multi-utilisateurs</h3>
                    <p>Gérez les droits d'accès de votre équipe. Attribuez des rôles spécifiques à chaque utilisateur (caissier, magasinier, administrateur).</p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-lg"></i> Gestion des rôles et permissions</li>
                        <li><i class="bi bi-check-lg"></i> Journal des actions</li>
                        <li><i class="bi bi-check-lg"></i> Accès sécurisé</li>
                        <li><i class="bi bi-check-lg"></i> Audit trail complet</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ADVANCED FEATURES SECTION --}}
    <section class="advanced-features">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">⭐ Fonctionnalités avancées</span>
                <h2 class="section-title">Des outils puissants pour booster votre activité</h2>
                <p class="section-subtitle">
                    Des fonctionnalités pensées pour <strong>tous les commerçants</strong> — quelle que soit votre activité
                </p>
            </div>

            <div class="advanced-grid">
                <div class="advanced-card">
                    <div class="advanced-icon">
                        <i class="bi bi-qr-code"></i>
                    </div>
                    <h3>Scanner de codes-barres</h3>
                    <p>Scannez vos produits avec un simple smartphone ou lecteur USB pour des ventes ultra-rapides et une gestion simplifiée.</p>
                </div>

                <div class="advanced-card">
                    <div class="advanced-icon">
                        <i class="bi bi-bell"></i>
                    </div>
                    <h3>Alertes et notifications</h3>
                    <p>Recevez des notifications en temps réel sur les stocks bas, les commandes en attente et les factures impayées.</p>
                </div>

                <div class="advanced-card">
                    <div class="advanced-icon">
                        <i class="bi bi-phone"></i>
                    </div>
                    <h3>Application mobile</h3>
                    <p>Accédez à votre entreprise depuis n'importe où avec notre application mobile disponible sur iOS et Android.</p>
                </div>

                <div class="advanced-card">
                    <div class="advanced-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h3>Sécurité renforcée</h3>
                    <p>Données chiffrées, sauvegardes automatiques quotidiennes et hébergement sécurisé en France.</p>
                </div>

                <div class="advanced-card">
                    <div class="advanced-icon">
                        <i class="bi bi-currency-exchange"></i>
                    </div>
                    <h3>Multi-devises</h3>
                    <p>Gérez vos ventes et achats en plusieurs devises. Taux de change automatiques et historiques.</p>
                </div>

                <div class="advanced-card">
                    <div class="advanced-icon">
                        <i class="bi bi-cloud-upload"></i>
                    </div>
                    <h3>Export cloud</h3>
                    <p>Exportez automatiquement vos données vers le cloud pour une sauvegarde supplémentaire et un accès à distance.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- INTEGRATIONS SECTION --}}
    <section class="integrations">
        <div class="container">
            <div class="integrations-content">
                <div class="integrations-text">
                    <span class="section-badge">🔌 Intégrations</span>
                    <h2>Connectez Inventix à vos outils préférés</h2>
                    <p>Inventix s'intègre parfaitement avec les logiciels que vous utilisez déjà.</p>
                    <div class="integrations-list">
                        <div class="integration-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>QuickBooks</span>
                        </div>
                        <div class="integration-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Sage</span>
                        </div>
                        <div class="integration-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Cegid</span>
                        </div>
                        <div class="integration-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Dolibarr</span>
                        </div>
                        <div class="integration-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Stripe</span>
                        </div>
                        <div class="integration-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>PayPal</span>
                        </div>
                    </div>
                    <div class="api-note">
                        <i class="bi bi-code-slash"></i>
                        <span>API REST complète pour des intégrations personnalisées</span>
                    </div>
                </div>
                <div class="integrations-image">
                    <div class="integration-preview">
                        <div class="preview-card">
                            <div class="preview-header">
                                <span class="preview-dot red"></span>
                                <span class="preview-dot yellow"></span>
                                <span class="preview-dot green"></span>
                            </div>
                            <div class="preview-body">
                                <div class="integration-icons">
                                    <i class="bi bi-box"></i>
                                    <i class="bi bi-arrow-right"></i>
                                    <i class="bi bi-graph-up"></i>
                                    <i class="bi bi-arrow-right"></i>
                                    <i class="bi bi-people"></i>
                                </div>
                                <p>Synchronisation automatique avec vos outils</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- BENEFITS SECTION --}}
    <section class="benefits">
        <div class="container">
            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="benefit-text">
                        <h3>Gain de temps</h3>
                        <p>Réduisez votre temps de gestion jusqu'à 70% grâce à l'automatisation</p>
                    </div>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="benefit-text">
                        <h3>Rentabilité</h3>
                        <p>Augmentez votre marge jusqu'à 15% avec des rapports d'analyse précis</p>
                    </div>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-hand-thumbs-up"></i>
                    </div>
                    <div class="benefit-text">
                        <h3>Satisfaction client</h3>
                        <p>Fidélisez vos clients avec un service plus rapide et personnalisé</p>
                    </div>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="benefit-text">
                        <h3>Sérénité</h3>
                        <p>Données sécurisées et sauvegardées quotidiennement</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA SECTION --}}
    <section class="features-cta">
        <div class="container">
            <div class="cta-card">
                <h2>Prêt à découvrir toutes ces fonctionnalités ?</h2>
                <p>Essayez Inventix gratuitement pendant 14 jours</p>
                <div class="cta-actions">
                    <a href="{{ route('register.form') }}" class="btn-primary btn-large">
                        <i class="bi bi-rocket-takeoff"></i>
                        Commencer gratuitement
                    </a>
                    <a href="{{ route('demo') }}" class="btn-outline btn-large">
                        <i class="bi bi-play-circle"></i>
                        Voir la démo
                    </a>
                </div>
                <p class="small-note">Sans carte bancaire • Sans engagement</p>
            </div>
        </div>
    </section>
</div>

<style>
/* Features Page Styles */
.features-page {
    background: var(--white);
}

/* Hero Section */
.features-hero {
    position: relative;
    background: linear-gradient(135deg, var(--orange-50) 0%, var(--white) 100%);
    padding: 80px 0;
    text-align: center;
}

.features-hero .hero-badge {
    display: inline-block;
    padding: 8px 16px;
    background: var(--white);
    border-radius: 40px;
    font-size: 14px;
    font-weight: 600;
    color: var(--orange-500);
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--orange-200);
}

.features-hero .hero-title {
    font-size: 48px;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 24px;
    color: var(--gray-900);
}

.features-hero .text-gradient {
    background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.features-hero .hero-subtitle {
    font-size: 18px;
    color: var(--gray-600);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Main Features */
.main-features {
    padding: 80px 0;
    background: var(--white);
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
}

.feature-card-large {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 24px;
    padding: 32px;
    transition: all 0.3s ease;
}

.feature-card-large:hover {
    transform: translateY(-5px);
    border-color: var(--orange-500);
    box-shadow: var(--shadow-orange);
}

.feature-icon-large {
    width: 64px;
    height: 64px;
    background: var(--orange-50);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
    color: var(--orange-500);
    font-size: 28px;
    transition: all 0.3s;
}

.feature-card-large:hover .feature-icon-large {
    background: var(--orange-500);
    color: var(--white);
}

.feature-card-large h3 {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 12px;
    color: var(--gray-900);
}

.feature-card-large p {
    color: var(--gray-600);
    line-height: 1.6;
    margin-bottom: 20px;
}

.feature-list {
    list-style: none;
    padding: 0;
}

.feature-list li {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    font-size: 14px;
    color: var(--gray-700);
}

.feature-list li i {
    color: var(--orange-500);
    font-size: 16px;
}

/* Advanced Features */
.advanced-features {
    padding: 80px 0;
    background: var(--gray-50);
}

.section-header {
    text-align: center;
    max-width: 600px;
    margin: 0 auto 60px;
}

.section-badge {
    display: inline-block;
    padding: 6px 14px;
    background: var(--orange-50);
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    color: var(--orange-600);
    margin-bottom: 16px;
}

.section-title {
    font-size: 36px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 16px;
}

.section-subtitle {
    font-size: 16px;
    color: var(--gray-600);
    line-height: 1.6;
}

.advanced-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.advanced-card {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 20px;
    padding: 32px;
    text-align: center;
    transition: all 0.3s ease;
}

.advanced-card:hover {
    transform: translateY(-5px);
    border-color: var(--orange-500);
    box-shadow: var(--shadow-orange);
}

.advanced-icon {
    width: 56px;
    height: 56px;
    background: var(--orange-50);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: var(--orange-500);
    font-size: 24px;
}

.advanced-card h3 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 12px;
    color: var(--gray-900);
}

.advanced-card p {
    color: var(--gray-600);
    font-size: 14px;
    line-height: 1.6;
}

/* Integrations */
.integrations {
    padding: 80px 0;
    background: var(--white);
}

.integrations-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

.integrations-text h2 {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 16px;
    color: var(--gray-900);
}

.integrations-text p {
    color: var(--gray-600);
    margin-bottom: 32px;
    font-size: 16px;
}

.integrations-list {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.integration-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: var(--gray-50);
    border-radius: 12px;
    font-size: 15px;
    font-weight: 500;
    color: var(--gray-700);
}

.integration-item i {
    color: var(--orange-500);
    font-size: 18px;
}

.api-note {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: var(--orange-50);
    border-radius: 12px;
    font-size: 14px;
    color: var(--orange-700);
}

.api-note i {
    font-size: 20px;
}

.integrations-image {
    position: relative;
}

.integration-preview {
    background: var(--gray-50);
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
}

.integration-preview .preview-card {
    padding: 32px;
}

.integration-preview .preview-header {
    display: flex;
    gap: 8px;
    margin-bottom: 24px;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--gray-200);
}

.integration-preview .preview-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--gray-300);
}

.integration-preview .preview-dot.red { background: #ef4444; }
.integration-preview .preview-dot.yellow { background: #eab308; }
.integration-preview .preview-dot.green { background: #10b981; }

.integration-icons {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    margin-bottom: 24px;
    font-size: 28px;
    color: var(--orange-500);
}

.integration-preview p {
    text-align: center;
    color: var(--gray-600);
    font-size: 14px;
}

/* Benefits */
.benefits {
    padding: 80px 0;
    background: var(--gray-50);
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
}

.benefit-card {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 20px;
    padding: 24px;
    transition: all 0.3s;
}

.benefit-card:hover {
    transform: translateY(-3px);
    border-color: var(--orange-500);
    box-shadow: var(--shadow-orange);
}

.benefit-icon {
    width: 48px;
    height: 48px;
    background: var(--orange-50);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--orange-500);
    font-size: 24px;
    flex-shrink: 0;
}

.benefit-text h3 {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 4px;
    color: var(--gray-900);
}

.benefit-text p {
    font-size: 13px;
    color: var(--gray-600);
    line-height: 1.5;
}

/* CTA Section */
.features-cta {
    padding: 80px 0;
    background: linear-gradient(135deg, var(--gray-900), #000000);
}

.features-cta .cta-card {
    text-align: center;
    color: var(--white);
}

.features-cta .cta-card h2 {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 16px;
}

.features-cta .cta-card p {
    font-size: 18px;
    margin-bottom: 32px;
    opacity: 0.9;
}

.features-cta .cta-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    margin-bottom: 24px;
}

.features-cta .btn-primary {
    background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
    color: white;
}

.features-cta .btn-outline {
    border: 1px solid var(--gray-700);
    color: var(--gray-300);
}

.features-cta .btn-outline:hover {
    background: var(--white);
    color: var(--orange-500);
    border-color: var(--white);
}

.features-cta .small-note {
    font-size: 13px;
    opacity: 0.7;
}

/* Responsive */
@media (max-width: 1024px) {
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .advanced-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .benefits-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .features-hero .hero-title {
        font-size: 32px;
    }
    
    .advanced-grid {
        grid-template-columns: 1fr;
    }
    
    .integrations-content {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .integrations-list {
        grid-template-columns: 1fr;
    }
    
    .benefits-grid {
        grid-template-columns: 1fr;
    }
    
    .features-cta .cta-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .features-cta .cta-card h2 {
        font-size: 28px;
    }
}
</style>
@endsection