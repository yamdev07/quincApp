{{-- resources/views/landing/index.blade.php --}}
@extends('layouts.landing')

@section('content')
<div class="landing-page">
    {{-- HERO SECTION --}}
    <section class="hero-section">
        <div class="hero-pattern"></div>
        <div class="container">
            <div class="hero-content">
                <span class="hero-badge">✨ La solution n°1 pour les quincailleries</span>
                <h1 class="hero-title">
                    Gérez votre <span class="text-gradient">quincaillerie</span><br>
                    en toute simplicité
                </h1>
                <p class="hero-subtitle">
                    Logiciel tout-en-un pour gérer vos stocks, ventes, clients et fournisseurs.<br>
                    Essayez gratuitement pendant 14 jours, sans engagement.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('demo') }}" class="btn-outline btn-large">
                        <i class="bi bi-play-circle"></i>
                        Voir la démo
                    </a>
                    <a href="{{ route('pricing') }}" class="btn-primary btn-large">
                        <i class="bi bi-rocket-takeoff"></i>
                        Commencer gratuitement
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-value">500+</span>
                        <span class="stat-label">Quincailleries</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">98%</span>
                        <span class="stat-label">Clients satisfaits</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">24/7</span>
                        <span class="stat-label">Support</span>
                    </div>
                </div>
            </div>
            <div class="hero-image">
                <div class="dashboard-preview">
                    <div class="preview-card">
                        <div class="preview-header">
                            <span class="preview-dot red"></span>
                            <span class="preview-dot yellow"></span>
                            <span class="preview-dot green"></span>
                        </div>
                        <div class="preview-body">
                            <div class="preview-row">
                                <span class="preview-label">Ventes aujourd'hui</span>
                                <span class="preview-value">24</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Chiffre d'affaires</span>
                                <span class="preview-value">12 450 €</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Produits en stock</span>
                                <span class="preview-value">156</span>
                            </div>
                            <div class="preview-chart">
                                <div class="preview-bar" style="height: 40px;"></div>
                                <div class="preview-bar" style="height: 60px;"></div>
                                <div class="preview-bar" style="height: 30px;"></div>
                                <div class="preview-bar" style="height: 80px;"></div>
                                <div class="preview-bar" style="height: 50px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES SECTION --}}
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Fonctionnalités</span>
                <h2 class="section-title">Tout ce dont vous avez besoin</h2>
                <p class="section-subtitle">
                    Un logiciel complet pour gérer votre quincaillerie efficacement
                </p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h3>Gestion de stock avancée</h3>
                    <p>Suivez vos produits en temps réel avec alertes de stock bas et historique des mouvements</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <h3>Ventes et facturation</h3>
                    <p>Créez des devis, factures et gérez les paiements en quelques clics</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3>Multi-utilisateurs</h3>
                    <p>Gérez les droits de votre équipe (caissiers, magasiniers, gérants)</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h3>Rapports détaillés</h3>
                    <p>Analysez vos performances avec des graphiques et des statistiques</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h3>Gestion fournisseurs</h3>
                    <p>Centralisez vos commandes et suivez vos relations avec les fournisseurs</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h3>Sécurisé</h3>
                    <p>Données chiffrées, sauvegardes quotidiennes et hébergement sécurisé</p>
                </div>
            </div>
        </div>
    </section>

    {{-- STATS SECTION --}}
    <section class="stats-highlight">
        <div class="container">
            <div class="stats-grid-large">
                <div class="stat-large">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Quincailleries clients</span>
                </div>
                <div class="stat-large">
                    <span class="stat-number">50k+</span>
                    <span class="stat-label">Produits gérés</span>
                </div>
                <div class="stat-large">
                    <span class="stat-number">98%</span>
                    <span class="stat-label">Taux de satisfaction</span>
                </div>
                <div class="stat-large">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Support disponible</span>
                </div>
            </div>
        </div>
    </section>

    {{-- PRICING TEASER --}}
    <section class="pricing-teaser">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Tarifs</span>
                <h2 class="section-title">Des prix adaptés à toutes les tailles</h2>
                <p class="section-subtitle">
                    Commencez avec un essai gratuit de 14 jours, sans carte bancaire
                </p>
            </div>

            <div class="pills-container">
                <a href="{{ route('pricing') }}?plan=monthly" class="pill-card">
                    <span class="pill-name">Mensuel</span>
                    <span class="pill-price">299 €</span>
                    <span class="pill-period">/mois</span>
                </a>
                <a href="{{ route('pricing') }}?plan=quarterly" class="pill-card">
                    <span class="pill-name">Trimestriel</span>
                    <span class="pill-price">284 €</span>
                    <span class="pill-period">/mois</span>
                    <span class="pill-badge">-5%</span>
                </a>
                <a href="{{ route('pricing') }}?plan=semester" class="pill-card popular">
                    <span class="pill-popular">⭐ Populaire</span>
                    <span class="pill-name">Semestriel</span>
                    <span class="pill-price">269 €</span>
                    <span class="pill-period">/mois</span>
                    <span class="pill-badge">-10%</span>
                </a>
                <a href="{{ route('pricing') }}?plan=yearly" class="pill-card">
                    <span class="pill-name">Annuel</span>
                    <span class="pill-price">254 €</span>
                    <span class="pill-period">/mois</span>
                    <span class="pill-badge">-15%</span>
                </a>
            </div>

            <div class="teaser-cta">
                <a href="{{ route('pricing') }}" class="btn-primary btn-large">
                    Voir tous les détails
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS --}}
    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Témoignages</span>
                <h2 class="section-title">Ils nous font confiance</h2>
            </div>

            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">
                        "Depuis que j'utilise QuincaApp, j'ai réduit mon temps de gestion de stock de 70%. L'interface est intuitive et le support est réactif."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">JD</div>
                        <div>
                            <strong>Jean Dupont</strong>
                            <span>Brico Centre</span>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">
                        "La gestion multi-utilisateurs nous a changé la vie. Mes caissiers et magasiniers travaillent en parfaite synchronicité."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">MM</div>
                        <div>
                            <strong>Marie Martin</strong>
                            <span>Outils Plus</span>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">
                        "Le meilleur rapport qualité-prix du marché. Les rapports de vente m'ont permis d'augmenter ma marge de 15%."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">PL</div>
                        <div>
                            <strong>Pierre Lambert</strong>
                            <span>Matériaux Pro</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">FAQ</span>
                <h2 class="section-title">Questions fréquentes</h2>
            </div>

            <div class="faq-grid">
                <div class="faq-item">
                    <h3>Comment fonctionne l'essai gratuit ?</h3>
                    <p>Vous bénéficiez de 14 jours d'essai gratuit avec toutes les fonctionnalités. Aucune carte bancaire n'est demandée.</p>
                </div>
                <div class="faq-item">
                    <h3>Puis-je changer de formule ?</h3>
                    <p>Oui, vous pouvez changer de formule à tout moment. La différence vous sera facturée au prorata.</p>
                </div>
                <div class="faq-item">
                    <h3>Les données sont-elles sauvegardées ?</h3>
                    <p>Oui, sauvegarde automatique quotidienne avec chiffrement de bout en bout.</p>
                </div>
                <div class="faq-item">
                    <h3>Quels moyens de paiement ?</h3>
                    <p>Carte bancaire, virement, Wave, Orange Money (Afrique).</p>
                </div>
                <div class="faq-item">
                    <h3>Y a-t-il un engagement ?</h3>
                    <p>Non, vous pouvez résilier à tout moment directement depuis votre espace.</p>
                </div>
                <div class="faq-item">
                    <h3>Le support est-il inclus ?</h3>
                    <p>Oui, support par email et chat inclus dans toutes les formules.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section class="final-cta">
        <div class="container">
            <div class="cta-card">
                <h2>Prêt à digitaliser votre quincaillerie ?</h2>
                <p>Rejoignez plus de 500 quincailleries qui nous font confiance</p>
                <div class="cta-actions">
                    <a href="{{ route('demo') }}" class="btn-outline btn-large btn-white-outline">
                        <i class="bi bi-play-circle"></i>
                        Voir la démo
                    </a>
                    <a href="{{ route('pricing') }}" class="btn-primary btn-large btn-white-bg">
                        <i class="bi bi-rocket-takeoff"></i>
                        Essai gratuit 14 jours
                    </a>
                </div>
                <p class="small-note">Sans carte bancaire • Sans engagement</p>
            </div>
        </div>
    </section>
</div>

<style>
/* -----------------------------------------------------
   STYLES SPÉCIFIQUES À LA PAGE D'ACCUEIL
----------------------------------------------------- */

/* Hero Section */
.hero-section {
    position: relative;
    background: linear-gradient(135deg, var(--orange-50) 0%, var(--white) 100%);
    padding: 80px 0;
    overflow: hidden;
}

.hero-pattern {
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: radial-gradient(circle at 70% 50%, rgba(249,115,22,0.05) 0%, transparent 50%);
}

.hero-section .container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    position: relative;
    z-index: 2;
}

.hero-badge {
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

.hero-title {
    font-size: 48px;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 24px;
    color: var(--gray-900);
}

.text-gradient {
    background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-subtitle {
    font-size: 18px;
    color: var(--gray-600);
    margin-bottom: 32px;
    line-height: 1.6;
}

.hero-actions {
    display: flex;
    gap: 16px;
    margin-bottom: 48px;
}

.btn-large {
    padding: 14px 32px;
    font-size: 16px;
}

.hero-stats {
    display: flex;
    gap: 40px;
}

.stat-item {
    text-align: center;
}

.stat-value {
    display: block;
    font-size: 28px;
    font-weight: 800;
    color: var(--gray-900);
}

.stat-label {
    font-size: 14px;
    color: var(--gray-500);
}

/* Dashboard Preview */
.dashboard-preview {
    background: var(--white);
    border-radius: 20px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-200);
    overflow: hidden;
}

.preview-card {
    padding: 24px;
}

.preview-header {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--gray-200);
}

.preview-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--gray-300);
}

.preview-dot.red { background: #ef4444; }
.preview-dot.yellow { background: #eab308; }
.preview-dot.green { background: #10b981; }

.preview-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding: 0 5px;
}

.preview-label {
    color: var(--gray-500);
    font-size: 14px;
    font-weight: 500;
}

.preview-value {
    color: var(--orange-500);
    font-weight: 700;
    font-size: 18px;
}

.preview-chart {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    height: 100px;
    margin-top: 20px;
    padding-top: 10px;
    border-top: 1px solid var(--gray-200);
}

.preview-bar {
    flex: 1;
    background: var(--orange-200);
    border-radius: 4px;
    transition: height 0.3s;
}

/* Features Section */
.features-section {
    padding: 100px 0;
    background: var(--white);
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

.features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.feature-card {
    padding: 32px;
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 20px;
    transition: all 0.3s;
    text-align: center;
}

.feature-card:hover {
    transform: translateY(-5px);
    border-color: var(--orange-500);
    box-shadow: var(--shadow-orange);
}

.feature-icon {
    width: 64px;
    height: 64px;
    background: var(--orange-50);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    color: var(--orange-500);
    font-size: 28px;
    transition: all 0.3s;
}

.feature-card:hover .feature-icon {
    background: var(--orange-500);
    color: var(--white);
}

.feature-card h3 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 12px;
    color: var(--gray-900);
}

.feature-card p {
    color: var(--gray-600);
    line-height: 1.6;
}

/* Stats Highlight */
.stats-highlight {
    padding: 80px 0;
    background: var(--gray-50);
}

.stats-grid-large {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
    text-align: center;
}

.stat-large .stat-number {
    display: block;
    font-size: 48px;
    font-weight: 800;
    color: var(--orange-500);
    margin-bottom: 8px;
}

.stat-large .stat-label {
    font-size: 16px;
    color: var(--gray-600);
}

/* Pricing Teaser */
.pricing-teaser {
    padding: 80px 0;
    background: var(--white);
}

.pills-container {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 40px;
}

.pill-card {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 30px;
    padding: 24px 32px;
    text-align: center;
    text-decoration: none;
    color: var(--gray-900);
    transition: all 0.3s;
    position: relative;
    min-width: 180px;
}

.pill-card:hover {
    transform: translateY(-3px);
    border-color: var(--orange-500);
    box-shadow: var(--shadow-orange);
}

.pill-card.popular {
    border-color: var(--orange-500);
    box-shadow: var(--shadow-orange);
}

.pill-popular {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--orange-500);
    color: var(--white);
    padding: 4px 16px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.pill-name {
    display: block;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
}

.pill-price {
    display: block;
    font-size: 28px;
    font-weight: 800;
    color: var(--orange-500);
    line-height: 1.2;
}

.pill-period {
    display: block;
    font-size: 13px;
    color: var(--gray-500);
    margin-bottom: 8px;
}

.pill-badge {
    display: inline-block;
    padding: 4px 12px;
    background: var(--orange-50);
    color: var(--orange-600);
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
}

.teaser-cta {
    text-align: center;
}

/* Testimonials */
.testimonials {
    padding: 100px 0;
    background: var(--gray-50);
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.testimonial-card {
    padding: 32px;
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 20px;
    transition: all 0.3s;
}

.testimonial-card:hover {
    border-color: var(--orange-500);
    box-shadow: var(--shadow-orange);
}

.testimonial-stars {
    color: var(--orange-500);
    font-size: 20px;
    margin-bottom: 20px;
    letter-spacing: 2px;
}

.testimonial-text {
    color: var(--gray-600);
    line-height: 1.6;
    margin-bottom: 24px;
    font-style: italic;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 12px;
}

.author-avatar {
    width: 48px;
    height: 48px;
    background: var(--orange-50);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--orange-500);
    font-weight: 600;
    font-size: 16px;
}

.testimonial-author strong {
    display: block;
    font-size: 14px;
    color: var(--gray-900);
}

.testimonial-author span {
    font-size: 13px;
    color: var(--gray-500);
}

/* FAQ */
.faq-section {
    padding: 80px 0;
    background: var(--white);
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    max-width: 900px;
    margin: 0 auto;
}

.faq-item {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 16px;
    padding: 24px;
}

.faq-item h3 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--gray-900);
}

.faq-item p {
    color: var(--gray-600);
    line-height: 1.6;
    font-size: 14px;
}

/* Final CTA */
.final-cta {
    padding: 80px 0;
    background: var(--gray-50);
}

.cta-card {
    background: var(--gray-900);
    border-radius: 30px;
    padding: 80px;
    text-align: center;
    color: var(--white);
}

.cta-card h2 {
    font-size: 40px;
    font-weight: 700;
    margin-bottom: 16px;
}

.cta-card p {
    font-size: 18px;
    margin-bottom: 32px;
    opacity: 0.9;
}

.cta-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    margin-bottom: 24px;
}

.btn-white-outline {
    border-color: var(--white);
    color: var(--white);
    background: transparent;
}

.btn-white-outline:hover {
    background: var(--white);
    color: var(--orange-500);
}

.btn-white-bg {
    background: var(--white);
    color: var(--orange-500);
    box-shadow: none;
}

.btn-white-bg:hover {
    background: var(--orange-50);
    color: var(--orange-600);
    transform: translateY(-2px);
}

.small-note {
    font-size: 13px;
    opacity: 0.7;
}

/* Responsive */
@media (max-width: 1024px) {
    .hero-section .container {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .hero-actions {
        justify-content: center;
    }
    
    .hero-stats {
        justify-content: center;
    }
    
    .features-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .testimonials-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .stats-grid-large {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 36px;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .testimonials-grid {
        grid-template-columns: 1fr;
    }
    
    .faq-grid {
        grid-template-columns: 1fr;
    }
    
    .cta-card {
        padding: 40px 20px;
    }
    
    .cta-card h2 {
        font-size: 28px;
    }
    
    .cta-actions {
        flex-direction: column;
    }
    
    .pills-container {
        flex-direction: column;
        align-items: center;
    }
    
    .pill-card {
        width: 100%;
        max-width: 300px;
    }
}
</style>
@endsection