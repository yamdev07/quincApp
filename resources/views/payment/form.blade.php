@extends('layouts.app')

@section('title', 'Paiement - QuincaPro')

@section('styles')
<style>
    /* -----------------------------------------------------
       VARIABLES - IDENTIQUES AU DASHBOARD
    ----------------------------------------------------- */
    :root {
        --bg-page: #f8fafc;
        --bg-card: #ffffff;
        --bg-side: #f1f5f9;
        --border-light: #e2e8f0;
        --border-soft: #cbd5e1;
        
        --text-primary: #0f172a;
        --text-secondary: #334155;
        --text-tertiary: #64748b;
        
        --accent: #f97316;
        --accent-dark: #ea580c;
        --accent-light: #ffedd5;
        --accent-soft: #fed7aa;
        --accent-gradient: linear-gradient(135deg, #f97316, #ea580c);
        
        --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        --shadow-hover: 0 10px 15px -3px rgba(249,115,22,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
    }

    * { box-sizing: border-box; }

    body {
        background: var(--bg-page);
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--text-primary);
    }

    .payment-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 32px 24px;
    }

    .payment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .payment-header-left h1 {
        font-size: 32px;
        font-weight: 700;
        background: linear-gradient(135deg, #0f172a 0%, #f97316 80%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.5px;
        margin: 0 0 6px;
    }

    .payment-header-left p {
        color: var(--text-tertiary);
        font-size: 14px;
        margin: 0;
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

    .payment-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 24px;
    }

    @media (max-width: 768px) {
        .payment-grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }
    }

    .summary-card {
        background: var(--bg-card);
        border-radius: 24px;
        border: 1px solid var(--border-light);
        overflow: hidden;
        box-shadow: var(--shadow-card);
        transition: all 0.25s;
        height: fit-content;
    }

    .summary-card:hover {
        box-shadow: var(--shadow-hover);
        border-color: var(--accent);
    }

    .summary-header {
        padding: 24px 24px 20px;
        border-bottom: 1px solid var(--border-light);
        background: linear-gradient(145deg, #ffffff, #f8fafc);
    }

    .summary-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.2px;
        color: var(--text-tertiary);
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .plan-badge {
        display: inline-block;
        background: var(--accent-light);
        border: 1px solid var(--accent-soft);
        border-radius: 30px;
        padding: 4px 14px;
        font-size: 11px;
        font-weight: 600;
        color: var(--accent-dark);
        margin-bottom: 16px;
    }

    .plan-name-large {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .plan-price-large {
        font-size: 42px;
        font-weight: 800;
        color: var(--accent);
        margin: 12px 0 8px;
    }

    .plan-price-large small {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-tertiary);
    }

    .saving-badge {
        background: #dcfce7;
        border: 1px solid #bbf7d0;
        border-radius: 30px;
        padding: 4px 12px;
        font-size: 11px;
        color: #16a34a;
        display: inline-block;
    }

    .summary-features {
        padding: 20px 24px;
    }

    .summary-feature {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--border-light);
    }

    .summary-feature:last-child {
        border-bottom: none;
    }

    .summary-feature i {
        width: 22px;
        color: var(--accent);
        font-size: 16px;
    }

    .summary-feature span {
        font-size: 13px;
        color: var(--text-secondary);
    }

    {{-- Bloc renouvellement --}}
    .renewal-block {
        background: var(--accent-light);
        border-radius: 20px;
        padding: 16px;
        margin: 0 24px 20px 24px;
        border-left: 4px solid var(--accent);
    }

    .renewal-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .renewal-header i {
        color: var(--accent);
        font-size: 18px;
    }

    .renewal-header span {
        font-weight: 600;
        color: var(--text-primary);
    }

    .renewal-current-plan {
        font-size: 13px;
        color: var(--text-tertiary);
        margin-bottom: 8px;
    }

    .renewal-current-plan strong {
        color: var(--accent);
    }

    .renewal-amount {
        font-size: 28px;
        font-weight: 800;
        color: var(--accent);
    }

    .renewal-expiry {
        font-size: 12px;
        color: var(--text-tertiary);
        margin-top: 8px;
    }

    .summary-total {
        background: var(--accent-light);
        padding: 20px 24px;
        border-top: 1px solid var(--border-light);
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .total-label {
        font-size: 13px;
        color: var(--text-tertiary);
    }

    .total-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .grand-total {
        padding-top: 10px;
        margin-top: 8px;
        border-top: 1px solid rgba(0,0,0,0.05);
    }

    .grand-total .total-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .grand-total .total-value {
        font-size: 20px;
        font-weight: 800;
        color: var(--accent);
    }

    .payment-card {
        background: var(--bg-card);
        border-radius: 24px;
        border: 1px solid var(--border-light);
        overflow: hidden;
        box-shadow: var(--shadow-card);
        transition: all 0.25s;
    }

    .payment-card:hover {
        box-shadow: var(--shadow-hover);
        border-color: var(--accent);
    }

    .payment-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-light);
        background: linear-gradient(145deg, #ffffff, #f8fafc);
    }

    .payment-card-header h2 {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
        padding-left: 12px;
    }

    .payment-card-header h2::before {
        content: '';
        position: absolute;
        left: 0;
        top: 2px;
        bottom: 2px;
        width: 4px;
        background: var(--accent-gradient);
        border-radius: 4px;
    }

    .payment-card-header h2 i { color: var(--accent); }

    .payment-card-header p {
        font-size: 13px;
        color: var(--text-tertiary);
        margin-top: 6px;
        margin-left: 16px;
    }

    .payment-body {
        padding: 24px;
    }

    .payment-methods-grid {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 24px;
    }

    .payment-method {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        border: 1.5px solid var(--border-light);
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
    }

    .payment-method:hover {
        border-color: var(--accent);
        background: var(--accent-light);
        transform: translateX(4px);
    }

    .payment-method.selected {
        border-color: var(--accent);
        background: var(--accent-light);
        box-shadow: 0 4px 12px rgba(249,115,22,0.1);
    }

    .method-icon {
        width: 44px;
        height: 44px;
        background: var(--bg-side);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .payment-method.selected .method-icon {
        background: white;
    }

    .method-info {
        flex: 1;
    }

    .method-name {
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 2px;
    }

    .method-desc {
        font-size: 11px;
        color: var(--text-tertiary);
    }

    .method-badge {
        font-size: 10px;
        background: var(--bg-side);
        padding: 3px 10px;
        border-radius: 30px;
        color: var(--text-secondary);
        font-weight: 600;
    }

    .phone-field {
        margin-bottom: 24px;
        padding: 16px 20px;
        background: var(--bg-side);
        border-radius: 20px;
        border: 1px solid var(--border-light);
        transition: all 0.2s;
    }

    .phone-field label {
        display: block;
        font-weight: 600;
        font-size: 13px;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .phone-input-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .country-code {
        background: white;
        border: 1px solid var(--border-light);
        border-radius: 14px;
        padding: 10px 14px;
        font-weight: 600;
        font-size: 13px;
        color: var(--text-primary);
    }

    .phone-input {
        flex: 1;
        padding: 10px 14px;
        border: 1px solid var(--border-light);
        border-radius: 14px;
        font-size: 13px;
        transition: all 0.2s;
    }

    .phone-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
    }

    .security-box {
        background: var(--bg-side);
        border-radius: 20px;
        padding: 18px;
        margin-bottom: 24px;
    }

    .security-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 0;
    }

    .security-item i {
        width: 22px;
        color: #22c55e;
        font-size: 16px;
    }

    .security-item span {
        font-size: 12px;
        color: var(--text-secondary);
    }

    .pay-btn {
        width: 100%;
        background: var(--accent-gradient);
        border: none;
        padding: 16px 24px;
        border-radius: 40px;
        font-size: 15px;
        font-weight: 700;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(249,115,22,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .pay-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(249,115,22,0.4);
    }

    .pay-btn:active {
        transform: translateY(0);
    }

    .test-mode-badge {
        margin-top: 24px;
        background: var(--accent-light);
        border: 1px solid var(--accent-soft);
        border-radius: 20px;
        padding: 16px;
        text-align: center;
    }

    .test-mode-badge p {
        font-size: 12px;
        color: var(--accent-dark);
        margin: 0;
    }

    .test-numbers {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    .test-number {
        background: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        color: var(--accent);
    }
</style>
@endsection

@section('content')
<div class="payment-container">

    {{-- HEADER --}}
    <div class="payment-header">
        <div class="payment-header-left">
            <h1>Paiement</h1>
            <p>Finalisez votre abonnement en toute sécurité</p>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="btn-outline">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="payment-grid">
        
        {{-- COLONNE GAUCHE - RÉSUMÉ --}}
        <div class="summary-card">
            <div class="summary-header">
                <div class="summary-title">RÉSUMÉ DE VOTRE COMMANDE</div>
                <div class="plan-badge">
                    <i class="bi bi-star-fill"></i> Offre {{ $currentPlan['name'] }}
                </div>
                <div class="plan-name-large">Formule {{ $currentPlan['name'] }}</div>
                <div class="plan-price-large">
                    {{ number_format($amount, 0, ',', ' ') }} <small>FCFA</small>
                </div>
                @if(isset($currentPlan['saving']))
                    <div class="saving-badge">
                        <i class="bi bi-piggy-bank"></i> {{ $currentPlan['saving'] }}
                    </div>
                @endif
            </div>

            <div class="summary-features">
                <div class="summary-feature">
                    <i class="bi bi-infinity"></i>
                    <span>Accès illimité à toutes les fonctionnalités</span>
                </div>
                <div class="summary-feature">
                    <i class="bi bi-headset"></i>
                    <span>Support prioritaire 7j/7</span>
                </div>
                <div class="summary-feature">
                    <i class="bi bi-cloud-upload"></i>
                    <span>Sauvegarde automatique des données</span>
                </div>
                <div class="summary-feature">
                    <i class="bi bi-shield-check"></i>
                    <span>Sécurité et confidentialité garanties</span>
                </div>
                @if($currentPlan['name'] === 'Semestriel' || $currentPlan['name'] === 'Annuel')
                <div class="summary-feature">
                    <i class="bi bi-mortarboard"></i>
                    <span>Formation offerte</span>
                </div>
                @endif
                @if($currentPlan['name'] === 'Annuel')
                <div class="summary-feature">
                    <i class="bi bi-graph-up"></i>
                    <span>Audit personnalisé annuel</span>
                </div>
                @endif
            </div>

            {{-- Bloc Renouvellement - Affichage du montant actuel --}}
            @if($isRenewal && $currentAmount)
            <div class="renewal-block">
                <div class="renewal-header">
                    <i class="bi bi-arrow-repeat"></i>
                    <span>Renouvellement d'abonnement</span>
                </div>
                <div class="renewal-current-plan">
                    Votre formule actuelle : <strong>{{ $currentPlanName }}</strong>
                </div>
                <div class="renewal-amount">
                    {{ number_format($currentAmount, 0, ',', ' ') }} FCFA
                </div>
                <div class="renewal-expiry">
                    📅 Valable jusqu'au {{ \Carbon\Carbon::now()->addMonths($currentPlan['duration'] == '1 mois' ? 1 : ($currentPlan['duration'] == '3 mois' ? 3 : ($currentPlan['duration'] == '6 mois' ? 6 : 12)))->format('d/m/Y') }}
                </div>
            </div>
            @endif

            <div class="summary-total">
                <div class="total-row">
                    <span class="total-label">Prix HT</span>
                    <span class="total-value">{{ number_format($amount, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="total-row">
                    <span class="total-label">TVA (0%)</span>
                    <span class="total-value">0 FCFA</span>
                </div>
                <div class="total-row grand-total">
                    <span class="total-label">TOTAL À PAYER</span>
                    <span class="total-value">{{ number_format($amount, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>

        {{-- COLONNE DROITE - PAIEMENT --}}
        <div class="payment-card">
            <div class="payment-card-header">
                <h2><i class="bi bi-credit-card"></i> Paiement sécurisé</h2>
                <p>Choisissez votre moyen de paiement</p>
            </div>

            <div class="payment-body">
                <form action="{{ route('payment.callback') }}" method="POST" id="paymentForm">
                    @csrf
                    <input type="hidden" name="amount" value="{{ $amount }}">
                    <input type="hidden" name="plan_type" value="{{ $selectedPlan }}">
                    <input type="hidden" name="is_renewal" value="{{ $isRenewal ?? false ? '1' : '0' }}">
                    
                    <div class="payment-methods-grid">
                        <div class="payment-method" data-method="mtn" onclick="selectMethod('mtn')">
                            <div class="method-icon">📱</div>
                            <div class="method-info">
                                <div class="method-name">MTN Mobile Money</div>
                                <div class="method-desc">Paiement mobile instantané</div>
                            </div>
                            <div class="method-badge">Populaire</div>
                        </div>

                        <div class="payment-method" data-method="moov" onclick="selectMethod('moov')">
                            <div class="method-icon">📱</div>
                            <div class="method-info">
                                <div class="method-name">Moov Money (T-Money)</div>
                                <div class="method-desc">Paiement via Moov</div>
                            </div>
                        </div>

                        <div class="payment-method" data-method="card" onclick="selectMethod('card')">
                            <div class="method-icon">💳</div>
                            <div class="method-info">
                                <div class="method-name">Carte Bancaire</div>
                                <div class="method-desc">Visa, Mastercard</div>
                            </div>
                            <div class="method-badge">International</div>
                        </div>

                        <div class="payment-method" data-method="wave" onclick="selectMethod('wave')">
                            <div class="method-icon">🌊</div>
                            <div class="method-info">
                                <div class="method-name">Wave</div>
                                <div class="method-desc">Paiement rapide et simple</div>
                            </div>
                        </div>
                    </div>

                    <div id="phone-field" class="phone-field" style="display: none;">
                        <label><i class="bi bi-phone"></i> Numéro de téléphone</label>
                        <div class="phone-input-group">
                            <span class="country-code">+229</span>
                            <input type="tel" name="phone" id="phone" class="phone-input" placeholder="01 01 01 01">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Vous recevrez une demande de paiement sur ce numéro</p>
                    </div>

                    <div class="security-box">
                        <div class="security-item">
                            <i class="bi bi-lock-fill"></i>
                            <span>Paiement 100% sécurisé par FedaPay</span>
                        </div>
                        <div class="security-item">
                            <i class="bi bi-shield-check"></i>
                            <span>Vos informations bancaires ne sont jamais stockées</span>
                        </div>
                        <div class="security-item">
                            <i class="bi bi-clock-history"></i>
                            <span>Activation immédiate après paiement</span>
                        </div>
                        <div class="security-item">
                            <i class="bi bi-envelope-paper"></i>
                            <span>Reçu par email en quelques minutes</span>
                        </div>
                    </div>

                    <script 
                        src="https://cdn.fedapay.com/checkout.js?v=1.1.7"
                        data-public-key="{{ config('services.fedapay.public_key') }}"
                        data-button-text="Payer {{ number_format($amount, 0) }} FCFA"
                        data-button-class="pay-btn"
                        data-transaction-amount="{{ $amount }}"
                        data-transaction-description="Abonnement QuincaPro - {{ $currentPlan['name'] }}"
                        data-currency-iso="XOF"
                        data-customer-email="{{ Auth::user()->email }}"
                        data-customer-firstname="{{ explode(' ', Auth::user()->name)[0] ?? '' }}"
                        data-customer-lastname="{{ explode(' ', Auth::user()->name)[1] ?? '' }}">
                    </script>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function selectMethod(method) {
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('selected');
        });
        document.querySelector(`.payment-method[data-method="${method}"]`).classList.add('selected');
        
        const phoneField = document.getElementById('phone-field');
        if (method === 'mtn' || method === 'moov') {
            phoneField.style.display = 'block';
        } else {
            phoneField.style.display = 'none';
        }
    }
    
    selectMethod('mtn');
</script>
@endsection