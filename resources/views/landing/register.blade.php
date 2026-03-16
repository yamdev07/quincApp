{{-- resources/views/landing/register.blade.php --}}
@extends('layouts.landing')

@section('title', 'Créer votre quincaillerie — QuincaApp')

@section('content')
<div class="register-page">
    <div class="container">
        {{-- En-tête de la page --}}
        <div class="register-header-section">
            <h1>Créez votre <span class="text-accent">quincaillerie</span></h1>
            <p>Commencez votre essai gratuit de 14 jours · Sans carte bancaire</p>
        </div>

        <div class="register-card">
            <div class="register-card-header">
                <h2>Nouvelle quincaillerie</h2>
                @php
                    $planNames = [
                        'monthly' => 'Formule Mensuelle',
                        'quarterly' => 'Formule Trimestrielle',
                        'semester' => 'Formule Semestrielle',
                        'yearly' => 'Formule Annuelle'
                    ];
                    $planPrices = [
                        'monthly' => '10 000 FCFA/mois',
                        'quarterly' => '28 500 FCFA/3 mois',
                        'semester' => '54 000 FCFA/6 mois',
                        'yearly' => '102 000 FCFA/an'
                    ];
                @endphp
                <span class="plan-badge">{{ $planNames[$plan] ?? 'Formule Mensuelle' }}</span>
            </div>

            <div class="register-card-body">
                <form method="POST" action="{{ route('register.tenant') }}" id="registerForm">
                    @csrf
                    <input type="hidden" name="plan" value="{{ $plan }}">

                    {{-- Informations entreprise --}}
                    <div class="section-title">
                        <i class="bi bi-building"></i>
                        Informations de votre entreprise
                    </div>

                    <div class="form-group">
                        <label for="company_name" class="form-label">
                            Nom de la quincaillerie <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="company_name"
                               name="company_name" 
                               class="form-control @error('company_name') error @enderror" 
                               value="{{ old('company_name') }}"
                               placeholder="Ex: Quincaillerie du Centre"
                               required>
                        @error('company_name')
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="subdomain" class="form-label">
                            Sous-domaine <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <input type="text" 
                                   id="subdomain"
                                   name="subdomain" 
                                   class="form-control @error('subdomain') error @enderror" 
                                   value="{{ old('subdomain') }}"
                                   placeholder="ma-quincaillerie"
                                   pattern="[a-z0-9\-]+"
                                   title="Lettres minuscules, chiffres et tirets uniquement"
                                   required>
                            <span class="input-group-text">.quincaapp.com</span>
                        </div>
                        <div class="help-text">
                            <i class="bi bi-info-circle"></i>
                            Uniquement lettres minuscules, chiffres et tirets
                        </div>
                        @error('subdomain')
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group half">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" 
                                   id="address"
                                   name="address" 
                                   class="form-control" 
                                   value="{{ old('address') }}"
                                   placeholder="Adresse de votre quincaillerie">
                        </div>

                        <div class="form-group half">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" 
                                   id="phone"
                                   name="phone" 
                                   class="form-control" 
                                   value="{{ old('phone') }}"
                                   placeholder="+221 77 123 45 67">
                        </div>
                    </div>

                    {{-- Informations propriétaire --}}
                    <div class="section-title">
                        <i class="bi bi-person"></i>
                        Vos informations personnelles
                    </div>

                    <div class="form-group">
                        <label for="name" class="form-label">
                            Nom complet <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="name"
                               name="name" 
                               class="form-control @error('name') error @enderror" 
                               value="{{ old('name') }}"
                               placeholder="Jean Dupont"
                               required>
                        @error('name')
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email professionnel <span class="required">*</span>
                        </label>
                        <input type="email" 
                               id="email"
                               name="email" 
                               class="form-control @error('email') error @enderror" 
                               value="{{ old('email') }}"
                               placeholder="contact@votre-entreprise.com"
                               required>
                        @error('email')
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Résumé de la commande --}}
                    <div class="order-summary">
                        <h3>Récapitulatif de votre commande</h3>
                        
                        <div class="summary-item">
                            <span>Formule choisie</span>
                            <span class="price-value">{{ $planNames[$plan] ?? 'Mensuelle' }}</span>
                        </div>
                        
                        <div class="summary-item">
                            <span>Prix</span>
                            <span class="price-value">{{ $planPrices[$plan] ?? '10 000 FCFA' }}</span>
                        </div>
                        
                        <div class="summary-item">
                            <span>Période d'essai</span>
                            <span class="price-value">14 jours offerts</span>
                        </div>
                        
                        <div class="summary-total">
                            <span>Total à payer aujourd'hui</span>
                            <span class="price-value">0 FCFA</span>
                        </div>
                    </div>

                    {{-- Note sur le mot de passe --}}
                    <div class="password-note">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>🔐 Mot de passe généré automatiquement</strong>
                            <p>Un mot de passe sécurisé sera généré et envoyé à votre adresse email. Vous pourrez le modifier dans votre espace.</p>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="bi bi-rocket-takeoff"></i>
                        Créer mon compte et commencer l'essai gratuit
                    </button>

                    <div class="terms-note">
                        En créant votre compte, vous acceptez nos 
                        <a href="#">conditions d'utilisation</a> et 
                        <a href="#">politique de confidentialité</a>.
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* =====================================================
   PAGE D'INSCRIPTION - DESIGN COHÉRENT AVEC LE LAYOUT
===================================================== */
.register-page {
    padding: 60px 0;
    background: var(--bg-body);
    min-height: calc(100vh - 400px);
}

.register-header-section {
    text-align: center;
    margin-bottom: 48px;
}

.register-header-section h1 {
    font-size: 36px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 12px;
}

.register-header-section p {
    font-size: 16px;
    color: var(--text-secondary);
}

.register-card {
    max-width: 700px;
    margin: 0 auto;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    transition: border-color 0.3s;
}

.register-card:hover {
    border-color: var(--accent);
}

.register-card-header {
    background: var(--accent-gradient);
    padding: 32px 40px;
    color: white;
    text-align: center;
    position: relative;
}

.register-card-header h2 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 16px;
}

.plan-badge {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    padding: 8px 24px;
    border-radius: 40px;
    font-size: 14px;
    font-weight: 600;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
}

.register-card-body {
    padding: 40px;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 32px 0 20px;
    display: flex;
    align-items: center;
    gap: 8px;
    position: relative;
    padding-left: 12px;
}

.section-title::before {
    content: '';
    position: absolute;
    left: 0;
    top: 2px;
    bottom: 2px;
    width: 4px;
    background: var(--accent-gradient);
    border-radius: 4px;
}

.section-title:first-of-type {
    margin-top: 0;
}

.section-title i {
    color: var(--accent);
    font-size: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group.half {
    margin-bottom: 0;
}

.form-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 6px;
    letter-spacing: 0.3px;
}

.form-label .required {
    color: #dc2626;
    margin-left: 4px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    background: var(--gray-50);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    font-size: 15px;
    color: var(--text-primary);
    transition: all 0.2s;
}

.form-control:focus {
    border-color: var(--accent);
    outline: none;
    box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
    background: var(--white);
}

.form-control.error {
    border-color: #dc2626;
    background: #fef2f2;
}

.input-group {
    display: flex;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    overflow: hidden;
    background: var(--gray-50);
}

.input-group .form-control {
    border: none;
    background: transparent;
}

.input-group .form-control:focus {
    box-shadow: none;
}

.input-group-text {
    padding: 0 16px;
    background: var(--gray-100);
    color: var(--text-secondary);
    font-size: 14px;
    display: flex;
    align-items: center;
    border-left: 1.5px solid var(--border);
}

.help-text {
    font-size: 12px;
    color: var(--text-tertiary);
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.help-text i {
    color: var(--accent);
}

.error-message {
    font-size: 12px;
    color: #dc2626;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.order-summary {
    background: var(--gray-50);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 28px;
    margin: 32px 0;
}

.order-summary h3 {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 20px;
    position: relative;
    padding-left: 12px;
}

.order-summary h3::before {
    content: '';
    position: absolute;
    left: 0;
    top: 2px;
    bottom: 2px;
    width: 4px;
    background: var(--accent-gradient);
    border-radius: 4px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid var(--border);
    font-size: 15px;
    color: var(--text-secondary);
}

.summary-total {
    display: flex;
    justify-content: space-between;
    padding: 16px 0 0;
    margin-top: 8px;
    font-weight: 700;
    font-size: 18px;
    color: var(--text-primary);
    border-top: 2px solid var(--accent-soft);
}

.price-value {
    color: var(--accent);
    font-weight: 700;
}

.password-note {
    background: var(--accent-light);
    border: 1px solid var(--accent-soft);
    border-radius: var(--radius-sm);
    padding: 16px 20px;
    margin-bottom: 28px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.password-note i {
    color: var(--accent);
    font-size: 20px;
    flex-shrink: 0;
    margin-top: 2px;
}

.password-note strong {
    display: block;
    font-size: 14px;
    color: var(--accent);
    margin-bottom: 4px;
}

.password-note p {
    font-size: 13px;
    color: var(--text-secondary);
    line-height: 1.5;
}

.btn-submit {
    width: 100%;
    padding: 16px 24px;
    background: var(--accent-gradient);
    border: none;
    border-radius: var(--radius-sm);
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: var(--shadow-orange);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px -8px rgba(249,115,22,0.4);
}

.btn-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.terms-note {
    text-align: center;
    margin-top: 24px;
    font-size: 13px;
    color: var(--text-tertiary);
}

.terms-note a {
    color: var(--accent);
    text-decoration: none;
    font-weight: 500;
}

.terms-note a:hover {
    text-decoration: underline;
}

/* Animation de chargement */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
    .register-page {
        padding: 40px 0;
    }

    .register-header-section h1 {
        font-size: 28px;
    }

    .register-card-header {
        padding: 28px 20px;
    }

    .register-card-body {
        padding: 28px 20px;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }

    .order-summary {
        padding: 20px;
    }

    .input-group {
        flex-direction: column;
    }

    .input-group-text {
        border-left: none;
        border-top: 1.5px solid var(--border);
        justify-content: center;
        padding: 10px;
    }
}

@media (max-width: 480px) {
    .register-header-section h1 {
        font-size: 24px;
    }

    .register-card-header h2 {
        font-size: 20px;
    }

    .plan-badge {
        padding: 6px 16px;
        font-size: 12px;
    }

    .summary-item {
        font-size: 14px;
        flex-direction: column;
        gap: 4px;
        text-align: center;
    }

    .summary-total {
        font-size: 16px;
        flex-direction: column;
        gap: 4px;
        text-align: center;
    }

    .password-note {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
document.getElementById('registerForm')?.addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = `
        <i class="bi bi-arrow-repeat" style="animation: spin 1s linear infinite;"></i>
        Création en cours...
    `;
    submitBtn.disabled = true;
});

// Validation en temps réel du sous-domaine
const subdomainInput = document.querySelector('input[name="subdomain"]');
if (subdomainInput) {
    subdomainInput.addEventListener('input', function(e) {
        this.value = this.value.toLowerCase().replace(/[^a-z0-9-]/g, '');
    });
}
</script>
@endsection