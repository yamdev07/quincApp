{{-- resources/views/landing/pricing.blade.php --}}
@extends('layouts.landing')

@section('content')
<div class="pricing-page">

    {{-- HEADER SECTION --}}
    <section class="pricing-header">
        <div class="header-bg">
            <div class="header-bg-grid"></div>
            <div class="header-bg-glow"></div>
        </div>
        
        <div class="container">
            <div class="header-content">
                <div class="header-badge">
                    <span class="badge-dot"></span>
                    Tarifs simples & transparents
                </div>
                
                <h1 class="header-title">
                    Choisissez la formule<br>
                    <span class="text-gradient">qui vous correspond</span>
                </h1>
                
                <p class="header-description">
                    14 jours d'essai gratuit · Sans carte bancaire · Sans engagement
                </p>
            </div>
        </div>
    </section>

    {{-- PRICING CARDS --}}
    <section class="pricing-cards">
        <div class="container">
            <div class="cards-grid">
                @foreach($plans as $key => $plan)
                    <div class="pricing-card {{ $plan['popular'] ? 'popular' : '' }}" data-plan="{{ $key }}">
                        @if($plan['popular'])
                            <div class="card-popular">
                                <span class="popular-icon">⭐</span>
                                Le plus choisi
                            </div>
                        @endif
                        
                        <div class="card-header">
                            <h3 class="card-title">{{ $plan['name'] }}</h3>
                            <div class="card-price">
                                <span class="price-number">{{ $plan['formatted'] }}</span>
                                <span class="price-period">/mois</span>
                            </div>
                            @if($plan['savings'] > 0)
                                <div class="card-saving">
                                    Économisez {{ $plan['savings'] }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-features">
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Toutes les fonctionnalités</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Support prioritaire</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Mises à jour incluses</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Sauvegardes quotidiennes</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Multi-utilisateurs</span>
                            </div>
                        </div>
                        
                        <button class="card-button" onclick="showRegistrationForm('{{ $key }}')">
                            <span>Choisir cette formule</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- COMPARISON TABLE --}}
    <section class="comparison-section">
        <div class="container">
            <div class="comparison-header">
                <h2 class="comparison-title">Comparez les formules</h2>
                <p class="comparison-subtitle">Tous nos plans incluent l'ensemble des fonctionnalités de base</p>
            </div>
            
            <div class="comparison-table">
                <div class="table-row table-header">
                    <div class="table-cell">Fonctionnalité</div>
                    <div class="table-cell">Mensuel</div>
                    <div class="table-cell">Trimestriel</div>
                    <div class="table-cell">Semestriel</div>
                    <div class="table-cell">Annuel</div>
                </div>
                
                <div class="table-row">
                    <div class="table-cell">Prix mensuel équivalent</div>
                    <div class="table-cell">299 €</div>
                    <div class="table-cell">284 €</div>
                    <div class="table-cell">269 €</div>
                    <div class="table-cell">254 €</div>
                </div>
                
                <div class="table-row">
                    <div class="table-cell">Économie réalisée</div>
                    <div class="table-cell">-</div>
                    <div class="table-cell">45 €</div>
                    <div class="table-cell">179 €</div>
                    <div class="table-cell">538 €</div>
                </div>
                
                <div class="table-row">
                    <div class="table-cell">Gestion de stock</div>
                    <div class="table-cell">✓</div>
                    <div class="table-cell">✓</div>
                    <div class="table-cell">✓</div>
                    <div class="table-cell">✓</div>
                </div>
                
                <div class="table-row">
                    <div class="table-cell">Ventes & factures</div>
                    <div class="table-cell">✓</div>
                    <div class="table-cell">✓</div>
                    <div class="table-cell">✓</div>
                    <div class="table-cell">✓</div>
                </div>
                
                <div class="table-row">
                    <div class="table-cell">Multi-utilisateurs</div>
                    <div class="table-cell">✓</div>
                    <div class="table-cell">✓</div>
                    <div class="table-cell">✓</div>
                    <div class="table-cell">✓</div>
                </div>
                
                <div class="table-row">
                    <div class="table-cell">Support prioritaire</div>
                    <div class="table-cell">-</div>
                    <div class="table-cell">✓</div>
                    <div class="table-cell">✓</div>
                    <div class="table-cell">✓</div>
                </div>
                
                <div class="table-row">
                    <div class="table-cell">Formation offerte</div>
                    <div class="table-cell">-</div>
                    <div class="table-cell">-</div>
                    <div class="table-cell">-</div>
                    <div class="table-cell">✓</div>
                </div>
            </div>
        </div>
    </section>

    {{-- REGISTRATION FORM --}}
    <section class="registration-section" id="registrationSection" style="display: none;">
        <div class="container">
            <div class="registration-card">
                <button class="registration-close" onclick="hideRegistrationForm()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
                
                <div class="registration-header">
                    <h2 class="registration-title">Créez votre quincaillerie</h2>
                    <p class="registration-subtitle">14 jours d'essai gratuit · Sans carte bancaire</p>
                </div>
                
                <form action="{{ route('register.tenant') }}" method="POST" class="registration-form">
                    @csrf
                    <input type="hidden" name="plan" id="selectedPlan">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="company_name">Nom de votre quincaillerie</label>
                            <input type="text" 
                                   id="company_name" 
                                   name="company_name" 
                                   placeholder="Ex: Quincaillerie du Centre"
                                   required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="subdomain">Sous-domaine</label>
                            <div class="input-group">
                                <input type="text" 
                                       id="subdomain" 
                                       name="subdomain" 
                                       placeholder="ma-quincaillerie"
                                       pattern="[a-z0-9-]+"
                                       title="Lettres minuscules, chiffres et tirets uniquement"
                                       required>
                                <span class="input-suffix">.quincaapp.com</span>
                            </div>
                            <div class="input-hint">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="12" x2="12" y2="16"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                                <span>Uniquement lettres minuscules, chiffres et tirets</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Votre nom complet</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   placeholder="Jean Dupont"
                                   required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email professionnel</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   placeholder="contact@votre-entreprise.com"
                                   required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Téléphone (optionnel)</label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   placeholder="+221 77 123 45 67">
                        </div>
                    </div>
                    
                    <div class="form-notice">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span>En créant votre compte, vous acceptez nos conditions d'utilisation et notre politique de confidentialité.</span>
                    </div>
                    
                    <button type="submit" class="form-submit">
                        <span>Commencer l'essai gratuit</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- FAQ SECTION --}}
    <section class="faq-section">
        <div class="container">
            <h2 class="faq-title">Questions fréquentes sur les tarifs</h2>
            
            <div class="faq-grid">
                <div class="faq-item">
                    <h3>Puis-je changer de formule en cours de route ?</h3>
                    <p>Oui, vous pouvez passer à une formule supérieure à tout moment. La différence vous sera facturée au prorata temporis.</p>
                </div>
                
                <div class="faq-item">
                    <h3>Comment fonctionne l'essai gratuit ?</h3>
                    <p>Vous bénéficiez de 14 jours avec toutes les fonctionnalités. Aucune carte bancaire n'est demandée pour commencer.</p>
                </div>
                
                <div class="faq-item">
                    <h3>Y a-t-il des frais cachés ?</h3>
                    <p>Non, tout est inclus dans le prix affiché. Pas de frais d'installation, pas de coûts supplémentaires.</p>
                </div>
                
                <div class="faq-item">
                    <h3>Que se passe-t-il après l'essai ?</h3>
                    <p>Vous pouvez choisir de souscrire à l'une de nos formules. Si vous ne le faites pas, votre accès sera simplement désactivé.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA SECTION --}}
    <section class="cta-section">
        <div class="container">
            <div class="cta-card">
                <h2 class="cta-title">Des questions ? Notre équipe est là pour vous</h2>
                <p class="cta-text">Contactez-nous pour une démo personnalisée ou pour toute question sur nos formules</p>
                <div class="cta-buttons">
                    <a href="#" class="cta-btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 2L15 9M22 2l-7 7M22 2v6M15 9L8 16M15 9l-7 7M2 22l7-7M2 22l7-7M2 22h6M9 15l7-7"/>
                        </svg>
                        Contacter le support
                    </a>
                    <a href="{{ route('demo') }}" class="cta-btn-secondary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="5 3 19 12 5 21 5 3"/>
                        </svg>
                        Voir la démo
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>

<style>
/* ============================================================================
   VARIABLES & BASE
   ============================================================================ */
.pricing-page {
    --white: #ffffff;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    
    --orange-50: #fff7ed;
    --orange-100: #ffedd5;
    --orange-200: #fed7aa;
    --orange-300: #fdba74;
    --orange-400: #fb923c;
    --orange-500: #f97316;
    --orange-600: #ea580c;
    --orange-700: #c2410c;
    
    --shadow-xs: 0 1px 2px rgba(0,0,0,0.05);
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
    --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
    --shadow-xl: 0 20px 25px rgba(0,0,0,0.15);
    --shadow-orange: 0 10px 25px -5px rgba(249,115,22,0.3);
    
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 24px;
    
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    background: var(--white);
    color: var(--gray-900);
    line-height: 1.5;
}

.container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 32px;
}

/* ============================================================================
   HEADER
   ============================================================================ */
.pricing-header {
    position: relative;
    padding: 80px 0 48px;
    text-align: center;
    overflow: hidden;
}

.header-bg {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.header-bg-grid {
    position: absolute;
    inset: 0;
    background-image: 
        linear-gradient(var(--gray-200) 1px, transparent 1px),
        linear-gradient(90deg, var(--gray-200) 1px, transparent 1px);
    background-size: 48px 48px;
    mask-image: radial-gradient(circle at 50% 0%, black, transparent 70%);
    opacity: 0.3;
}

.header-bg-glow {
    position: absolute;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at 50% 0%, var(--orange-100), transparent 70%);
    opacity: 0.5;
}

.header-content {
    position: relative;
    z-index: 2;
    max-width: 720px;
    margin: 0 auto;
}

.header-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    background: var(--orange-50);
    border: 1px solid var(--orange-200);
    border-radius: 100px;
    font-size: 13px;
    font-weight: 600;
    color: var(--orange-600);
    margin-bottom: 24px;
}

.badge-dot {
    width: 6px;
    height: 6px;
    background: var(--orange-500);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
}

.header-title {
    font-size: clamp(36px, 5vw, 48px);
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 16px;
    color: var(--gray-900);
}

.text-gradient {
    background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.header-description {
    font-size: 18px;
    color: var(--gray-600);
    max-width: 560px;
    margin: 0 auto;
    line-height: 1.6;
}

/* ============================================================================
   PRICING CARDS
   ============================================================================ */
.pricing-cards {
    padding: 48px 0 64px;
}

.cards-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    align-items: start;
}

.pricing-card {
    position: relative;
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-xl);
    padding: 32px 24px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    height: 100%;
}

.pricing-card:hover {
    transform: translateY(-4px);
    border-color: var(--orange-200);
    box-shadow: var(--shadow-xl);
}

.pricing-card.popular {
    border: 2px solid var(--orange-500);
    box-shadow: var(--shadow-orange);
    transform: scale(1.02);
    z-index: 2;
}

.card-popular {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 6px 16px;
    background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
    border-radius: 100px;
    color: white;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    box-shadow: var(--shadow-md);
}

.popular-icon {
    font-size: 14px;
}

.card-header {
    text-align: center;
    margin-bottom: 24px;
}

.card-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 12px;
}

.card-price {
    margin-bottom: 8px;
}

.price-number {
    font-size: 36px;
    font-weight: 800;
    color: var(--gray-900);
    line-height: 1;
}

.price-period {
    font-size: 14px;
    color: var(--gray-400);
    margin-left: 4px;
}

.card-saving {
    display: inline-block;
    padding: 4px 12px;
    background: var(--orange-50);
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: var(--orange-600);
}

.card-features {
    flex: 1;
    margin-bottom: 24px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
    border-bottom: 1px solid var(--gray-100);
    font-size: 14px;
    color: var(--gray-700);
}

.feature-item:last-child {
    border-bottom: none;
}

.feature-icon {
    color: var(--orange-500);
    font-weight: bold;
}

.card-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 24px;
    background: transparent;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius-lg);
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-700);
    cursor: pointer;
    transition: all 0.2s;
    width: 100%;
}

.card-button:hover {
    border-color: var(--orange-500);
    color: var(--orange-500);
    background: var(--orange-50);
}

.pricing-card.popular .card-button {
    background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
    border: none;
    color: white;
    box-shadow: var(--shadow-orange);
}

.pricing-card.popular .card-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px -8px rgba(249,115,22,0.4);
}

/* ============================================================================
   COMPARISON TABLE
   ============================================================================ */
.comparison-section {
    padding: 64px 0;
    background: var(--gray-50);
}

.comparison-header {
    text-align: center;
    margin-bottom: 48px;
}

.comparison-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 12px;
}

.comparison-subtitle {
    font-size: 16px;
    color: var(--gray-600);
}

.comparison-table {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
}

.table-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
    border-bottom: 1px solid var(--gray-200);
}

.table-row:last-child {
    border-bottom: none;
}

.table-header {
    background: var(--gray-50);
    font-weight: 600;
    color: var(--gray-700);
}

.table-cell {
    padding: 16px 20px;
    font-size: 14px;
    color: var(--gray-600);
}

.table-header .table-cell {
    color: var(--gray-700);
    font-weight: 600;
}

/* ============================================================================
   REGISTRATION FORM
   ============================================================================ */
.registration-section {
    padding: 48px 0;
    background: linear-gradient(135deg, var(--gray-900), #000000);
    position: relative;
}

.registration-card {
    position: relative;
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: 48px;
    max-width: 600px;
    margin: 0 auto;
    box-shadow: var(--shadow-xl);
}

.registration-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: transparent;
    border: none;
    color: var(--gray-400);
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: all 0.2s;
}

.registration-close:hover {
    background: var(--gray-100);
    color: var(--gray-700);
}

.registration-header {
    text-align: center;
    margin-bottom: 32px;
}

.registration-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 8px;
}

.registration-subtitle {
    font-size: 14px;
    color: var(--gray-600);
}

.registration-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-row {
    width: 100%;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-group label {
    font-size: 13px;
    font-weight: 600;
    color: var(--gray-700);
}

.form-group input {
    padding: 12px 16px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius-md);
    font-size: 14px;
    transition: all 0.2s;
    width: 100%;
}

.form-group input:hover {
    border-color: var(--gray-300);
}

.form-group input:focus {
    outline: none;
    border-color: var(--orange-500);
    box-shadow: 0 0 0 3px var(--orange-100);
}

.input-group {
    display: flex;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius-md);
    overflow: hidden;
}

.input-group input {
    flex: 1;
    border: none;
    border-radius: 0;
}

.input-group input:focus {
    box-shadow: none;
}

.input-suffix {
    display: flex;
    align-items: center;
    padding: 0 16px;
    background: var(--gray-50);
    color: var(--gray-500);
    font-size: 13px;
    border-left: 1px solid var(--gray-200);
}

.input-hint {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 6px;
    font-size: 11px;
    color: var(--gray-400);
}

.form-notice {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 12px 16px;
    background: var(--orange-50);
    border-radius: var(--radius-md);
    font-size: 12px;
    color: var(--gray-600);
    line-height: 1.5;
}

.form-notice svg {
    color: var(--orange-500);
    flex-shrink: 0;
    margin-top: 2px;
}

.form-submit {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 16px 24px;
    background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
    border: none;
    border-radius: var(--radius-lg);
    font-size: 16px;
    font-weight: 600;
    color: white;
    cursor: pointer;
    transition: all 0.2s;
    width: 100%;
    margin-top: 8px;
}

.form-submit:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-orange);
}

/* ============================================================================
   FAQ SECTION
   ============================================================================ */
.faq-section {
    padding: 80px 0;
    background: var(--white);
}

.faq-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--gray-900);
    text-align: center;
    margin-bottom: 48px;
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    max-width: 900px;
    margin: 0 auto;
}

.faq-item {
    padding: 24px;
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    transition: all 0.2s;
}

.faq-item:hover {
    border-color: var(--orange-200);
    background: var(--white);
}

.faq-item h3 {
    font-size: 16px;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 8px;
}

.faq-item p {
    font-size: 14px;
    color: var(--gray-600);
    line-height: 1.6;
}

/* ============================================================================
   CTA SECTION
   ============================================================================ */
.cta-section {
    padding: 64px 0;
    background: linear-gradient(135deg, var(--gray-900), #000000);
}

.cta-card {
    text-align: center;
    color: white;
}

.cta-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 12px;
    color: white;
}

.cta-text {
    font-size: 16px;
    color: var(--gray-400);
    margin-bottom: 32px;
}

.cta-buttons {
    display: flex;
    gap: 16px;
    justify-content: center;
}

.cta-btn-primary, .cta-btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 32px;
    border-radius: var(--radius-lg);
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}

.cta-btn-primary {
    background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
    color: white;
    box-shadow: var(--shadow-orange);
}

.cta-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px -8px rgba(249,115,22,0.4);
}

.cta-btn-secondary {
    background: transparent;
    border: 1px solid var(--gray-700);
    color: var(--gray-300);
}

.cta-btn-secondary:hover {
    border-color: var(--gray-500);
    color: white;
}

/* ============================================================================
   RESPONSIVE
   ============================================================================ */
@media (max-width: 1100px) {
    .cards-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .pricing-card.popular {
        transform: scale(1);
    }
}

@media (max-width: 768px) {
    .container {
        padding: 0 20px;
    }
    
    .cards-grid {
        grid-template-columns: 1fr;
        max-width: 400px;
        margin: 0 auto;
    }
    
    .comparison-table {
        overflow-x: auto;
    }
    
    .table-row {
        min-width: 700px;
    }
    
    .faq-grid {
        grid-template-columns: 1fr;
    }
    
    .cta-buttons {
        flex-direction: column;
    }
    
    .registration-card {
        padding: 32px 20px;
    }
}

@media (max-width: 480px) {
    .header-title {
        font-size: 32px;
    }
    
    .header-description {
        font-size: 16px;
    }
}
</style>

<script>
function showRegistrationForm(plan) {
    document.getElementById('selectedPlan').value = plan;
    const section = document.getElementById('registrationSection');
    section.style.display = 'block';
    setTimeout(() => {
        section.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 100);
}

function hideRegistrationForm() {
    document.getElementById('registrationSection').style.display = 'none';
}

// Validation en temps réel du sous-domaine
document.addEventListener('DOMContentLoaded', function() {
    const subdomainInput = document.getElementById('subdomain');
    if (subdomainInput) {
        subdomainInput.addEventListener('input', function(e) {
            this.value = this.value.toLowerCase().replace(/[^a-z0-9-]/g, '');
        });
    }
});
</script>
@endsection