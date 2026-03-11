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
                    14 jours d'essai gratuit · Paiement sécurisé par FedaPay
                </p>
            </div>
        </div>
    </section>

    {{-- PRICING CARDS --}}
    <section class="pricing-cards">
        <div class="container">
            <div class="cards-grid">
                {{-- Mensuel --}}
                <div class="pricing-card" data-plan="monthly" data-price="10000" data-name="Mensuel">
                    <div class="card-header">
                        <h3 class="card-title">Mensuel</h3>
                        <div class="card-price">
                            <span class="price-number">10 000</span>
                            <span class="price-period">FCFA/mois</span>
                        </div>
                    </div>
                    
                    <div class="card-features">
                        <div class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Toutes les fonctionnalités</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Support standard</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Mises à jour incluses</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Sauvegardes quotidiennes</span>
                        </div>
                    </div>
                    
                    <button class="card-button" onclick="openPaymentModal('monthly')">
                        <span>Souscrire</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </button>
                </div>

                {{-- Trimestriel --}}
                <div class="pricing-card" data-plan="quarterly" data-price="28500" data-name="Trimestriel">
                    <div class="card-header">
                        <h3 class="card-title">Trimestriel</h3>
                        <div class="card-price">
                            <span class="price-number">28 500</span>
                            <span class="price-period">FCFA/3 mois</span>
                        </div>
                        <div class="card-saving">
                            Économisez 1 500 FCFA
                        </div>
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
                    </div>
                    
                    <button class="card-button" onclick="openPaymentModal('quarterly')">
                        <span>Souscrire</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </button>
                </div>

                {{-- Semestriel (Populaire) --}}
                <div class="pricing-card popular" data-plan="semester" data-price="54000" data-name="Semestriel">
                    <div class="card-popular">
                        <span class="popular-icon">⭐</span>
                        Le plus choisi
                    </div>
                    
                    <div class="card-header">
                        <h3 class="card-title">Semestriel</h3>
                        <div class="card-price">
                            <span class="price-number">54 000</span>
                            <span class="price-period">FCFA/6 mois</span>
                        </div>
                        <div class="card-saving">
                            Économisez 6 000 FCFA
                        </div>
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
                            <span>Formation offerte</span>
                        </div>
                    </div>
                    
                    <button class="card-button" onclick="openPaymentModal('semester')">
                        <span>Souscrire</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </button>
                </div>

                {{-- Annuel --}}
                <div class="pricing-card" data-plan="yearly" data-price="102000" data-name="Annuel">
                    <div class="card-header">
                        <h3 class="card-title">Annuel</h3>
                        <div class="card-price">
                            <span class="price-number">102 000</span>
                            <span class="price-period">FCFA/an</span>
                        </div>
                        <div class="card-saving">
                            Économisez 18 000 FCFA
                        </div>
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
                            <span>Formation offerte</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Audit personnalisé</span>
                        </div>
                    </div>
                    
                    <button class="card-button" onclick="openPaymentModal('yearly')">
                        <span>Souscrire</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- COMPARISON TABLE --}}
    <section class="comparison-section">
        <!-- ... (garder le même contenu que précédemment) ... -->
    </section>

    {{-- FEDAPAY PAYMENT MODAL --}}
    <div class="modal-overlay" id="paymentModal" style="display: none;">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Paiement sécurisé</h3>
                <button class="modal-close" onclick="closePaymentModal()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="subscription-summary">
                    <div class="summary-plan">
                        <span class="summary-label">Formule choisie :</span>
                        <span class="summary-value" id="selectedPlanName">Mensuel</span>
                    </div>
                    <div class="summary-price">
                        <span class="summary-label">Montant à payer :</span>
                        <span class="summary-value" id="selectedPlanPrice">10 000 FCFA</span>
                    </div>
                </div>
                
                <div class="payment-methods">
                    <h4>Mode de paiement</h4>
                    <div class="payment-options">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="card" checked>
                            <span class="payment-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                    <line x1="1" y1="10" x2="23" y2="10"/>
                                </svg>
                            </span>
                            <span>Carte bancaire</span>
                        </label>
                        
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="wave">
                            <span class="payment-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.66 0 3-4 3-9s-1.34-9-3-9m0 18c-1.66 0-3-4-3-9s1.34-9 3-9"/>
                                </svg>
                            </span>
                            <span>Wave</span>
                        </label>
                        
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="orange_money">
                            <span class="payment-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.362 1.903.7 2.81a2 2 0 01-.45 2.11L8 10a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.574 2.81.7A2 2 0 0122 16.92z"/>
                                </svg>
                            </span>
                            <span>Orange Money</span>
                        </label>
                        
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="free_money">
                            <span class="payment-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M21 12v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2M3 5h18"/>
                                </svg>
                            </span>
                            <span>Free Money</span>
                        </label>
                    </div>
                </div>
                
                <div class="card-details" id="cardDetails">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nom sur la carte</label>
                            <input type="text" id="cardName" placeholder="JEAN DUPONT">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Numéro de carte</label>
                            <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group half">
                            <label>Date d'expiration</label>
                            <input type="text" id="cardExpiry" placeholder="MM/AA">
                        </div>
                        <div class="form-group half">
                            <label>CVV</label>
                            <input type="text" id="cardCvv" placeholder="123">
                        </div>
                    </div>
                    
                    <div class="card-logos">
                        <span class="card-logo">VISA</span>
                        <span class="card-logo">MasterCard</span>
                        <span class="card-logo">VERVE</span>
                    </div>
                </div>
                
                <div class="fedapay-info">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span>Paiement sécurisé par FedaPay · Vos données sont chiffrées</span>
                </div>
            </div>
            
            <div class="modal-footer">
                <button class="modal-btn-cancel" onclick="closePaymentModal()">Annuler</button>
                <button class="modal-btn-pay" id="payButton">
                    <span>Payer <span id="payAmount">10 000 FCFA</span></span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <polyline points="12 5 19 12 12 19"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- SUCCESS MODAL --}}
    <div class="modal-overlay" id="successModal" style="display: none;">
        <div class="modal-container success-modal">
            <div class="success-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M8 12L11 15L16 9"/>
                </svg>
            </div>
            
            <h3>Paiement réussi !</h3>
            <p>Votre abonnement a été activé avec succès.</p>
            <p class="success-details">Un email de confirmation vous a été envoyé.</p>
            
            <button class="modal-btn-success" onclick="closeSuccessModal()">
                Commencer à utiliser QuincaApp
            </button>
        </div>
    </div>

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
   PRICING CARDS (mêmes styles que précédemment)
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

/* ============================================================================
   MODAL
   ============================================================================ */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-container {
    background: var(--white);
    border-radius: var(--radius-xl);
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: var(--shadow-xl);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px 24px 16px;
    border-bottom: 1px solid var(--gray-200);
}

.modal-header h3 {
    font-size: 20px;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}

.modal-close {
    background: transparent;
    border: none;
    color: var(--gray-400);
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.modal-close:hover {
    background: var(--gray-100);
    color: var(--gray-700);
}

.modal-body {
    padding: 24px;
}

.subscription-summary {
    background: var(--orange-50);
    border-radius: var(--radius-lg);
    padding: 20px;
    margin-bottom: 24px;
    border: 1px solid var(--orange-200);
}

.summary-plan, .summary-price {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 0;
}

.summary-plan {
    border-bottom: 1px dashed var(--orange-200);
}

.summary-label {
    font-size: 14px;
    color: var(--gray-600);
}

.summary-value {
    font-size: 16px;
    font-weight: 700;
    color: var(--gray-900);
}

#selectedPlanPrice {
    color: var(--orange-600);
    font-size: 20px;
}

.payment-methods {
    margin-bottom: 24px;
}

.payment-methods h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 12px;
}

.payment-options {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.payment-option {
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius-lg);
    cursor: pointer;
    transition: all 0.2s;
}

.payment-option:hover {
    border-color: var(--orange-300);
    background: var(--orange-50);
}

.payment-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.payment-option input[type="radio"]:checked + .payment-icon {
    color: var(--orange-500);
}

.payment-option input[type="radio"]:checked ~ span:last-child {
    color: var(--orange-600);
    font-weight: 600;
}

.payment-option input[type="radio"]:checked + .payment-icon + span {
    color: var(--orange-600);
    font-weight: 600;
}

.payment-option input[type="radio"]:checked ~ .payment-option {
    border-color: var(--orange-500);
    background: var(--orange-50);
}

.payment-icon {
    display: flex;
    align-items: center;
    color: var(--gray-400);
}

.card-details {
    background: var(--gray-50);
    border-radius: var(--radius-lg);
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid var(--gray-200);
}

.form-row {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
}

.form-group {
    flex: 1;
}

.form-group label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: var(--gray-500);
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-group input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-md);
    font-size: 14px;
    transition: all 0.2s;
}

.form-group input:focus {
    outline: none;
    border-color: var(--orange-500);
    box-shadow: 0 0 0 3px var(--orange-100);
}

.form-group.half {
    flex: 0.5;
}

.card-logos {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 12px;
}

.card-logo {
    padding: 4px 8px;
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-sm);
    font-size: 10px;
    font-weight: 600;
    color: var(--gray-500);
}

.fedapay-info {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: var(--gray-50);
    border-radius: var(--radius-md);
    color: var(--gray-500);
    font-size: 12px;
    border: 1px solid var(--gray-200);
}

.fedapay-info svg {
    color: var(--orange-500);
}

.modal-footer {
    display: flex;
    gap: 12px;
    padding: 16px 24px 24px;
    border-top: 1px solid var(--gray-200);
}

.modal-btn-cancel, .modal-btn-pay {
    flex: 1;
    padding: 14px;
    border-radius: var(--radius-lg);
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.modal-btn-cancel {
    background: transparent;
    border: 1.5px solid var(--gray-200);
    color: var(--gray-600);
}

.modal-btn-cancel:hover {
    background: var(--gray-100);
    color: var(--gray-800);
}

.modal-btn-pay {
    background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
    border: none;
    color: white;
    box-shadow: var(--shadow-orange);
}

.modal-btn-pay:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px -8px rgba(249,115,22,0.4);
}

/* Success Modal */
.success-modal {
    text-align: center;
    padding: 40px;
}

.success-icon {
    margin-bottom: 24px;
}

.success-icon svg {
    color: #10b981;
    stroke-width: 1.5;
}

.success-modal h3 {
    font-size: 24px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 12px;
}

.success-modal p {
    font-size: 16px;
    color: var(--gray-600);
    margin-bottom: 8px;
}

.success-details {
    font-size: 14px !important;
    color: var(--gray-400) !important;
    margin-bottom: 24px !important;
}

.modal-btn-success {
    background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
    border: none;
    border-radius: var(--radius-lg);
    padding: 16px 32px;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    width: 100%;
}

.modal-btn-success:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-orange);
}

/* ============================================================================
   RESPONSIVE
   ============================================================================ */
@media (max-width: 1100px) {
    .cards-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .cards-grid {
        grid-template-columns: 1fr;
        max-width: 400px;
        margin: 0 auto;
    }
    
    .payment-options {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        flex-direction: column;
    }
    
    .form-group.half {
        flex: 1;
    }
    
    .modal-footer {
        flex-direction: column;
    }
}
</style>

<script>
// Configuration FedaPay
const FEDAPAY_PUBLIC_KEY = 'votre_clé_publique_fedapay'; // À remplacer par votre clé
const FEDAPAY_API_KEY = 'votre_clé_api_fedapay'; // À remplacer par votre clé

// Plans et prix
const plans = {
    monthly: { name: 'Mensuel', price: 10000, duration: '1 mois' },
    quarterly: { name: 'Trimestriel', price: 28500, duration: '3 mois' },
    semester: { name: 'Semestriel', price: 54000, duration: '6 mois' },
    yearly: { name: 'Annuel', price: 102000, duration: '12 mois' }
};

let currentPlan = null;

// Ouvrir le modal de paiement
function openPaymentModal(planKey) {
    currentPlan = plans[planKey];
    
    // Mettre à jour le résumé
    document.getElementById('selectedPlanName').textContent = currentPlan.name;
    document.getElementById('selectedPlanPrice').textContent = currentPlan.price.toLocaleString('fr-FR') + ' FCFA';
    document.getElementById('payAmount').textContent = currentPlan.price.toLocaleString('fr-FR') + ' FCFA';
    
    // Afficher le modal
    document.getElementById('paymentModal').style.display = 'flex';
    
    // Bloquer le scroll
    document.body.style.overflow = 'hidden';
}

// Fermer le modal de paiement
function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Réinitialiser les champs
    document.getElementById('cardName').value = '';
    document.getElementById('cardNumber').value = '';
    document.getElementById('cardExpiry').value = '';
    document.getElementById('cardCvv').value = '';
}

// Afficher le modal de succès
function showSuccessModal() {
    closePaymentModal();
    document.getElementById('successModal').style.display = 'flex';
}

// Fermer le modal de succès
function closeSuccessModal() {
    document.getElementById('successModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    window.location.href = '{{ route("dashboard") }}'; // Rediriger vers le dashboard
}

// Gérer le changement de mode de paiement
document.addEventListener('DOMContentLoaded', function() {
    const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
    const cardDetails = document.getElementById('cardDetails');
    
    paymentOptions.forEach(option => {
        option.addEventListener('change', function() {
            if (this.value === 'card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        });
    });
    
    // Formater le numéro de carte
    const cardNumber = document.getElementById('cardNumber');
    if (cardNumber) {
        cardNumber.addEventListener('input', function(e) {
            let value = this.value.replace(/\s/g, '').replace(/[^0-9]/g, '');
            let formatted = '';
            
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formatted += ' ';
                }
                formatted += value[i];
            }
            
            this.value = formatted;
        });
    }
    
    // Formater la date d'expiration
    const cardExpiry = document.getElementById('cardExpiry');
    if (cardExpiry) {
        cardExpiry.addEventListener('input', function(e) {
            let value = this.value.replace(/\//g, '').replace(/[^0-9]/g, '');
            
            if (value.length >= 2) {
                this.value = value.substring(0, 2) + '/' + value.substring(2, 4);
            } else {
                this.value = value;
            }
        });
    }
    
    // Formater le CVV
    const cardCvv = document.getElementById('cardCvv');
    if (cardCvv) {
        cardCvv.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 3);
        });
    }
});

// Traiter le paiement
document.getElementById('payButton')?.addEventListener('click', function() {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    
    // Simuler un chargement
    this.innerHTML = '<span>Traitement en cours...</span>';
    this.disabled = true;
    
    // Simuler un appel API (à remplacer par l'intégration réelle FedaPay)
    setTimeout(() => {
        showSuccessModal();
    }, 2000);
    
    /* Intégration réelle FedaPay (à décommenter quand vous aurez les clés)
    FedaPay.init({
        public_key: FEDAPAY_PUBLIC_KEY,
        transaction: {
            amount: currentPlan.price,
            currency: 'XOF',
            description: `Abonnement ${currentPlan.name} - QuincaApp`,
            callback_url: window.location.origin + '/payment/callback'
        },
        customer: {
            email: document.getElementById('email')?.value || 'client@example.com',
            name: document.getElementById('name')?.value || 'Client'
        }
    });
    
    FedaPay.open();
    */
});
</script>
@endsection