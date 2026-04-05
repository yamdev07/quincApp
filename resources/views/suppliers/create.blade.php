@extends('layouts.app')

@section('title', 'Ajouter un fournisseur — QuincaApp')

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
        --shadow-sm:     0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
        --shadow-md:     0 4px 16px rgba(15,23,42,.08);
        --shadow-orange: 0 8px 24px rgba(249,115,22,.25);
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
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Page */
    .sc-page {
        max-width: 800px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .sc-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        animation: fadeUp 0.35s ease both;
    }

    .sc-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .sc-hex {
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-orange);
    }
    .sc-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .sc-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .sc-title span {
        color: var(--orange);
        font-weight: 800;
    }
    .sc-sub {
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
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        border: none;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        text-decoration: none;
        cursor: pointer;
        box-shadow: var(--shadow-orange);
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
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
        box-shadow: 0 12px 28px rgba(249,115,22,0.4);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        background: var(--card);
        border: 1.5px solid var(--border);
        border-radius: 40px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-2);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-secondary svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        fill: none;
    }
    .btn-secondary:hover {
        border-color: var(--orange);
        color: var(--orange);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: transparent;
        border: 1.5px solid var(--border);
        border-radius: 40px;
        font-size: 12px;
        font-weight: 500;
        color: var(--text-2);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-outline svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        fill: none;
    }
    .btn-outline:hover {
        border-color: var(--orange);
        color: var(--orange);
        background: var(--orange-pale);
    }

    /* Access denied */
    .sc-access-denied {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: var(--radius);
        padding: 32px;
        text-align: center;
        animation: fadeUp 0.35s ease both;
        margin-bottom: 24px;
    }
    .sc-access-denied svg {
        width: 48px;
        height: 48px;
        stroke: var(--danger);
        margin: 0 auto 16px;
    }
    .sc-access-denied h2 {
        font-size: 20px;
        font-weight: 700;
        color: var(--danger);
        margin-bottom: 8px;
    }
    .sc-access-denied p {
        font-size: 14px;
        color: var(--text-2);
        margin-bottom: 24px;
    }

    /* Card */
    .sc-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        margin-bottom: 24px;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .sc-card:hover {
        border-color: var(--orange-soft);
    }

    .sc-card-header {
        padding: 18px 24px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .sc-card-header-l {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sc-card-ico {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sc-card-ico svg {
        width: 18px;
        height: 18px;
        stroke: #fff;
        fill: none;
    }
    .sc-card-header h2 {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
    }
    .sc-card-header p {
        font-size: 12px;
        color: rgba(255,255,255,0.8);
    }

    .sc-card-body {
        padding: 32px;
    }

    /* Form */
    .sc-form-group {
        margin-bottom: 24px;
    }
    .sc-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-2);
        margin-bottom: 8px;
    }
    .sc-label svg {
        width: 16px;
        height: 16px;
        stroke: var(--orange);
        margin-right: 4px;
    }
    .sc-label .required {
        color: var(--danger);
        margin-left: 4px;
    }

    .sc-field-wrapper {
        position: relative;
    }
    .sc-ico {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-3);
        transition: color 0.2s;
    }
    .sc-ico svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
    }
    .sc-field-wrapper:focus-within .sc-ico {
        color: var(--orange);
    }

    .sc-input {
        width: 100%;
        padding: 14px 18px 14px 48px;
        background: #fafbfd;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 15px;
        color: var(--text);
        font-family: inherit;
        transition: all 0.2s;
    }
    .sc-input:focus {
        border-color: var(--orange);
        outline: none;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
        background: var(--card);
    }
    .sc-input.error {
        border-color: var(--danger);
        background: #fef2f2;
    }

    .sc-error {
        margin-top: 6px;
        font-size: 13px;
        color: var(--danger);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .sc-error svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        fill: none;
    }

    /* Info box */
    .sc-info-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: var(--radius-sm);
        padding: 16px;
        margin: 24px 0;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }
    .sc-info-box svg {
        width: 20px;
        height: 20px;
        stroke: var(--info);
        fill: none;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .sc-info-box h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1e40af;
        margin-bottom: 4px;
    }
    .sc-info-box p {
        font-size: 13px;
        color: #2563eb;
        line-height: 1.5;
    }

    /* Form Actions */
    .sc-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
        margin-top: 24px;
    }
    @media (max-width: 600px) {
        .sc-actions {
            flex-direction: column-reverse;
        }
        .sc-actions .btn-primary,
        .sc-actions .btn-outline {
            width: 100%;
            justify-content: center;
        }
    }

    /* Preview Card */
    .sc-preview {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        padding: 24px;
        margin-top: 24px;
        transition: border-color 0.2s;
    }
    .sc-preview:hover {
        border-color: var(--orange-soft);
    }
    .sc-preview h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sc-preview h3 svg {
        width: 20px;
        height: 20px;
        stroke: var(--orange);
        fill: none;
    }

    .sc-preview-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    @media (max-width: 600px) {
        .sc-preview-grid {
            grid-template-columns: 1fr;
        }
    }

    .sc-preview-item {
        background: #fafbfd;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 14px;
    }
    .sc-preview-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-3);
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .sc-preview-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--text);
    }
    .preview-empty {
        color: var(--text-3);
        font-style: italic;
    }
</style>
@endsection

@section('content')
<div class="sc-page">

    {{-- Vérification d'accès --}}
    @if(!auth()->user()->canManageStock() || !(auth()->user()->isSuperAdminGlobal() || auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()))
        <div class="sc-access-denied">
            <svg viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h2>Accès refusé</h2>
            <p>Vous n'avez pas les droits pour créer un fournisseur.</p>
            <a href="{{ route('suppliers.index') }}" class="btn-secondary">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour aux fournisseurs
            </a>
        </div>
    @else

    {{-- HEADER --}}
    <div class="sc-header">
        <div class="sc-header-l">
            <div class="sc-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <div class="sc-title">
                    Ajouter un <span>fournisseur</span>
                </div>
                <div class="sc-sub">Renseignez les informations du nouveau partenaire fournisseur</div>
            </div>
        </div>
        <a href="{{ route('suppliers.index') }}" class="btn-secondary">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>
    </div>

    {{-- FORM CARD --}}
    <div class="sc-card">
        <div class="sc-card-header">
            <div class="sc-card-header-l">
                <div class="sc-card-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h2>Nouveau fournisseur</h2>
                    <p>Informations du fournisseur</p>
                </div>
            </div>
        </div>

        <div class="sc-card-body">
            {{-- 👈 CORRECTION ICI : route('admin.suppliers.store') au lieu de route('suppliers.store') --}}
            <form method="POST" action="{{ route('admin.suppliers.store') }}">
                @csrf

                {{-- Nom du fournisseur --}}
                <div class="sc-form-group">
                    <label for="name" class="sc-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Nom du fournisseur
                        <span class="required">*</span>
                    </label>
                    <div class="sc-field-wrapper">
                        <span class="sc-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </span>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               class="sc-input @error('name') error @enderror"
                               placeholder="Nom de l'entreprise..."
                               required>
                    </div>
                    @error('name')
                        <p class="sc-error">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Personne à contacter --}}
                <div class="sc-form-group">
                    <label for="contact" class="sc-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Personne à contacter
                    </label>
                    <div class="sc-field-wrapper">
                        <span class="sc-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <input type="text" 
                               id="contact" 
                               name="contact" 
                               value="{{ old('contact') }}" 
                               class="sc-input @error('contact') error @enderror"
                               placeholder="Nom du responsable...">
                    </div>
                    @error('contact')
                        <p class="sc-error">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Téléphone --}}
                <div class="sc-form-group">
                    <label for="phone" class="sc-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        Numéro de téléphone
                    </label>
                    <div class="sc-field-wrapper">
                        <span class="sc-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </span>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               class="sc-input @error('phone') error @enderror"
                               placeholder="+221 77 123 45 67">
                    </div>
                    @error('phone')
                        <p class="sc-error">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Info box --}}
                <div class="sc-info-box">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4>Champs facultatifs</h4>
                        <p>Le contact et le téléphone sont facultatifs, mais recommandés pour une meilleure communication avec votre fournisseur.</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="sc-actions">
                    <a href="{{ route('suppliers.index') }}" class="btn-outline">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Annuler
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Enregistrer le fournisseur
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- PREVIEW CARD --}}
    <div class="sc-preview">
        <h3>
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            Aperçu de la fiche fournisseur
        </h3>
        <div class="sc-preview-grid">
            <div class="sc-preview-item">
                <div class="sc-preview-label">Nom</div>
                <div class="sc-preview-value" id="preview-name">-</div>
            </div>
            <div class="sc-preview-item">
                <div class="sc-preview-label">Contact</div>
                <div class="sc-preview-value" id="preview-contact">-</div>
            </div>
            <div class="sc-preview-item">
                <div class="sc-preview-label">Téléphone</div>
                <div class="sc-preview-value" id="preview-phone">-</div>
            </div>
        </div>
    </div>

    @endif {{-- Fin de la condition d'autorisation --}}
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du formulaire
    const nameInput = document.getElementById('name');
    const contactInput = document.getElementById('contact');
    const phoneInput = document.getElementById('phone');
    
    // Éléments de prévisualisation
    const previewName = document.getElementById('preview-name');
    const previewContact = document.getElementById('preview-contact');
    const previewPhone = document.getElementById('preview-phone');

    // Vérifier que les éléments existent avant d'ajouter les écouteurs
    if (nameInput && previewName) {
        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || '-';
        });
    }
    
    if (contactInput && previewContact) {
        contactInput.addEventListener('input', function() {
            previewContact.textContent = this.value || '-';
        });
    }
    
    if (phoneInput && previewPhone) {
        phoneInput.addEventListener('input', function() {
            previewPhone.textContent = this.value || '-';
        });
    }

    // Initialisation de l'aperçu
    if (nameInput && previewName) previewName.textContent = nameInput.value || '-';
    if (contactInput && previewContact) previewContact.textContent = contactInput.value || '-';
    if (phoneInput && previewPhone) previewPhone.textContent = phoneInput.value || '-';

    // Validation du formulaire
    document.querySelector('form')?.addEventListener('submit', function(e) {
        const name = nameInput?.value.trim();
        
        if (!name) {
            e.preventDefault();
            alert('❌ Le nom du fournisseur est requis.');
            nameInput?.focus();
            return false;
        }
        
        // Effet de chargement
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = `
                <svg viewBox="0 0 24 24" stroke-width="2" style="width:16px;height:16px; animation:spin 1s linear infinite;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Enregistrement...
            `;
            submitBtn.disabled = true;
        }
    });
});
</script>
@endsection