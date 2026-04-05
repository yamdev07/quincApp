@extends('layouts.app')

@section('title', 'Nouvelle quincaillerie — Super Admin')

@section('styles')
<style>
    :root {
        --orange:        #f97316;
        --orange-dark:   #ea580c;
        --orange-pale:   #fff7ed;
        --orange-soft:   #fed7aa;
        --bg:            #f1f5f9;
        --card:          #ffffff;
        --border:        #e2e8f0;
        --border-light:  #f1f5f9;
        --text:          #0f172a;
        --text-2:        #475569;
        --text-3:        #94a3b8;
        --success:       #16a34a;
        --danger:        #dc2626;
        --info:          #2563eb;
        --purple:        #7c3aed;
        --violet:        #8b5cf6;
        --shadow-sm:     0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
        --shadow-md:     0 4px 16px rgba(15,23,42,.08);
        --shadow-violet: 0 8px 24px rgba(139,92,246,.25);
        --radius:        20px;
        --radius-sm:     12px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Inter', system-ui, sans-serif;
        background: var(--bg);
        color: var(--text);
        -webkit-font-smoothing: antialiased;
    }

    /* Animations */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-10px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    /* Page */
    .st-create-page {
        max-width: 800px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .st-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 28px;
        animation: fadeUp 0.35s ease both;
    }

    .st-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .st-hex {
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        background: linear-gradient(135deg, var(--violet), var(--purple));
        clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-violet);
    }
    .st-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .st-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .st-title span {
        color: var(--violet);
        font-weight: 800;
    }
    .st-sub {
        font-size: 13px;
        color: var(--text-3);
        margin-top: 4px;
    }

    /* Boutons */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        background: linear-gradient(135deg, var(--violet), var(--purple));
        border: none;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        text-decoration: none;
        cursor: pointer;
        box-shadow: var(--shadow-violet);
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
        border: none;
    }
    .btn-primary svg {
        width: 16px;
        height: 16px;
        stroke: #fff;
        fill: none;
    }
    .btn-primary::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        transform: translateX(-100%);
        transition: transform 0.5s;
    }
    .btn-primary:hover::after { transform: translateX(100%); }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(139,92,246,0.4);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        background: transparent;
        border: 1.5px solid var(--border);
        border-radius: 40px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-2);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-secondary svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
    }
    .btn-secondary:hover {
        border-color: var(--violet);
        color: var(--violet);
        background: #f5f3ff;
    }

    /* Alert */
    .st-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: var(--radius-sm);
        margin-bottom: 24px;
        animation: fadeUp 0.35s 0.07s ease both;
        border-left: 4px solid;
    }
    .st-alert svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
        flex-shrink: 0;
    }
    .st-alert-error {
        background: #fef2f2;
        border-color: var(--danger);
        color: #991b1b;
    }

    /* Card */
    .st-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: border-color 0.2s;
        animation: fadeUp 0.35s 0.1s ease both;
    }
    .st-card:hover {
        border-color: var(--violet);
    }

    .st-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-light);
        background: #fafafa;
    }
    .st-card-header h2 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .st-card-header h2 svg {
        width: 18px;
        height: 18px;
        stroke: var(--violet);
    }
    .st-card-header p {
        font-size: 13px;
        color: var(--text-2);
        margin-top: 4px;
    }

    .st-card-body {
        padding: 24px;
    }

    /* Form */
    .st-form-group {
        margin-bottom: 20px;
        animation: slideIn 0.3s ease both;
    }
    .st-form-group:nth-child(1) { animation-delay: 0.1s; }
    .st-form-group:nth-child(2) { animation-delay: 0.15s; }
    .st-form-group:nth-child(3) { animation-delay: 0.2s; }
    .st-form-group:nth-child(4) { animation-delay: 0.25s; }
    .st-form-group:nth-child(5) { animation-delay: 0.3s; }
    .st-form-group:nth-child(6) { animation-delay: 0.35s; }

    .st-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: var(--text-2);
        margin-bottom: 6px;
    }

    .st-input, .st-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 14px;
        color: var(--text);
        background: var(--card);
        transition: all 0.2s ease;
    }
    .st-input:hover, .st-textarea:hover {
        border-color: var(--violet);
    }
    .st-input:focus, .st-textarea:focus {
        outline: none;
        border-color: var(--violet);
        box-shadow: 0 0 0 3px rgba(139,92,246,0.1);
    }
    .st-input::placeholder, .st-textarea::placeholder {
        color: var(--text-3);
        font-size: 13px;
    }

    /* Input avec préfixe */
    .st-input-prefix {
        display: flex;
        align-items: center;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        transition: all 0.2s ease;
    }
    .st-input-prefix:hover {
        border-color: var(--violet);
    }
    .st-input-prefix:focus-within {
        border-color: var(--violet);
        box-shadow: 0 0 0 3px rgba(139,92,246,0.1);
    }
    .st-prefix {
        padding: 12px 16px;
        background: #f8fafc;
        border-right: 1.5px solid var(--border);
        border-radius: var(--radius-sm) 0 0 var(--radius-sm);
        font-size: 13px;
        font-weight: 500;
        color: var(--text-2);
        white-space: nowrap;
    }
    .st-prefix-input {
        flex: 1;
        padding: 12px 16px;
        border: none;
        background: transparent;
        font-size: 14px;
        color: var(--text);
    }
    .st-prefix-input:focus {
        outline: none;
    }

    .st-textarea {
        min-height: 100px;
        resize: vertical;
    }

    /* Row */
    .st-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    /* Help text */
    .st-help {
        font-size: 11px;
        color: var(--text-3);
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .st-help svg {
        width: 12px;
        height: 12px;
        stroke: currentColor;
    }

    /* Info box */
    .st-info-box {
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        border-radius: var(--radius-sm);
        padding: 16px;
        margin-top: 24px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        animation: slideIn 0.3s 0.4s ease both;
    }
    .st-info-box svg {
        width: 20px;
        height: 20px;
        stroke: var(--violet);
        flex-shrink: 0;
    }
    .st-info-box p {
        font-size: 13px;
        color: var(--text-2);
        line-height: 1.5;
    }
    .st-info-box strong {
        color: var(--violet);
        font-weight: 700;
    }

    /* Actions */
    .st-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid var(--border-light);
    }

    /* Validation */
    .st-error {
        color: var(--danger);
        font-size: 11px;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .st-error svg {
        width: 12px;
        height: 12px;
        stroke: currentColor;
    }
    .st-input-error {
        border-color: var(--danger);
    }
    .st-input-error:hover,
    .st-input-error:focus {
        border-color: var(--danger);
        box-shadow: 0 0 0 3px rgba(220,38,38,0.1);
    }

    /* Password generated */
    .st-password-generated {
        background: #f0fdf4;
        border: 1px solid var(--success);
        border-radius: var(--radius-sm);
        padding: 12px 16px;
        margin-top: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        animation: fadeUp 0.3s ease both;
    }
    .st-password-generated code {
        font-family: 'Monaco', 'Menlo', monospace;
        font-size: 14px;
        font-weight: 600;
        color: var(--success);
        background: #fff;
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid #bbf7d0;
    }
    .st-password-generated button {
        background: transparent;
        border: 1px solid var(--success);
        color: var(--success);
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s;
    }
    .st-password-generated button:hover {
        background: var(--success);
        color: #fff;
    }
</style>
@endsection

@section('content')
<div class="st-create-page">

    {{-- HEADER --}}
    <div class="st-header">
        <div class="st-header-l">
            <div class="st-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <div class="st-title">
                    Nouvelle <span>quincaillerie</span>
                </div>
                <div class="st-sub">Créez une nouvelle boutique multi-tenant</div>
            </div>
        </div>
    </div>

    {{-- ALERT --}}
    @if($errors->any())
        <div class="st-alert st-alert-error">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul class="mt-1" style="list-style: disc; margin-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- FORMULAIRE --}}
    <div class="st-card">
        <div class="st-card-header">
            <h2>
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Informations de la boutique
            </h2>
            <p>Renseignez les informations de la nouvelle quincaillerie</p>
        </div>

        <div class="st-card-body">
            <form action="{{ route('super-admin.tenants.store') }}" method="POST" id="createTenantForm">
                @csrf

                {{-- Informations de la quincaillerie --}}
                <div class="st-form-group">
                    <label for="company_name" class="st-label">Nom de l'entreprise <span style="color: var(--danger);">*</span></label>
                    <input type="text" 
                           id="company_name" 
                           name="company_name" 
                           value="{{ old('company_name') }}"
                           class="st-input @error('company_name') st-input-error @enderror"
                           placeholder="Ex: Quincaillerie du Centre"
                           required>
                    @error('company_name')
                        <div class="st-error">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="st-form-group">
                    <label for="subdomain" class="st-label">Sous-domaine <span style="color: var(--danger);">*</span></label>
                    <div class="st-input-prefix @error('subdomain') st-input-error @enderror">
                        <span class="st-prefix">https://</span>
                        <input type="text" 
                               id="subdomain" 
                               name="subdomain" 
                               value="{{ old('subdomain') }}"
                               class="st-prefix-input"
                               placeholder="ma-quincaillerie"
                               pattern="[a-z0-9-]+"
                               title="Lettres minuscules, chiffres et tirets uniquement"
                               required>
                        <span class="st-prefix">.{{ config('app.domain') }}</span>
                    </div>
                    <div class="st-help">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Uniquement lettres minuscules, chiffres et tirets
                    </div>
                    @error('subdomain')
                        <div class="st-error">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="st-form-group">
                    <label for="address" class="st-label">Adresse</label>
                    <textarea id="address" 
                              name="address" 
                              class="st-textarea @error('address') st-input-error @enderror"
                              placeholder="Adresse complète de la quincaillerie">{{ old('address') }}</textarea>
                    @error('address')
                        <div class="st-error">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="st-row">
                    <div class="st-form-group">
                        <label for="email" class="st-label">Email de contact <span style="color: var(--danger);">*</span></label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="st-input @error('email') st-input-error @enderror"
                               placeholder="contact@exemple.com"
                               required>
                        @error('email')
                            <div class="st-error">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="st-form-group">
                        <label for="phone" class="st-label">Téléphone</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               class="st-input @error('phone') st-input-error @enderror"
                               placeholder="+221 77 123 45 67">
                        @error('phone')
                            <div class="st-error">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- Informations du propriétaire --}}
                <div style="margin: 24px 0 16px; border-top: 1px solid var(--border-light);"></div>
                
                <div class="st-card-header" style="padding: 0 0 16px 0; background: transparent;">
                    <h2>
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Administrateur de la boutique
                    </h2>
                    <p>Créez le compte du propriétaire/gérant</p>
                </div>

                <div class="st-form-group">
                    <label for="name" class="st-label">Nom complet <span style="color: var(--danger);">*</span></label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="st-input @error('name') st-input-error @enderror"
                           placeholder="Jean Dupont"
                           required>
                    @error('name')
                        <div class="st-error">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Mot de passe généré automatiquement --}}
                <div class="st-info-box">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>
                        <strong>Mot de passe généré automatiquement</strong><br>
                        Un mot de passe sécurisé sera généré automatiquement. Il devra être communiqué au propriétaire de la quincaillerie.
                    </p>
                </div>

                {{-- Actions --}}
                <div class="st-actions">
                    <a href="{{ route('super-admin.tenants') }}" class="btn-secondary">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Annuler
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Créer la quincaillerie
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('generated_password'))
    {{-- Afficher le mot de passe généré si présent dans la session --}}
    <div style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; max-width: 400px;" class="st-password-generated">
        <div>
            <strong style="display: block; margin-bottom: 4px;">🔐 Mot de passe généré</strong>
            <code>{{ session('generated_password') }}</code>
        </div>
        <button onclick="copyPassword()">Copier</button>
    </div>

    <script>
        function copyPassword() {
            navigator.clipboard.writeText('{{ session('generated_password') }}');
            alert('Mot de passe copié dans le presse-papier !');
        }
    </script>
@endif

<script>
    // Validation en temps réel du sous-domaine
    document.getElementById('subdomain')?.addEventListener('input', function(e) {
        this.value = this.value.toLowerCase().replace(/[^a-z0-9-]/g, '');
    });
</script>
@endsection