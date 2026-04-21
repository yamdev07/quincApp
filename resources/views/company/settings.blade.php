@extends('layouts.app')

@section('title', 'Paramètres Entreprise — Sellvantix')

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
        --text:          #0f172a;
        --text-2:        #475569;
        --text-3:        #94a3b8;
        --radius:        20px;
        --radius-sm:     12px;
        --shadow-sm:     0 1px 3px rgba(15,23,42,.06);
        --shadow-md:     0 4px 16px rgba(15,23,42,.08);
        --shadow-orange: 0 8px 24px rgba(249,115,22,.25);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', system-ui, sans-serif; background: var(--bg); color: var(--text); }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .cs-page {
        max-width: 860px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    .cs-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 32px;
        animation: fadeUp 0.35s ease both;
    }

    .cs-hex {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-orange);
        flex-shrink: 0;
    }
    .cs-hex svg { width: 22px; height: 22px; stroke: #fff; fill: none; }

    .cs-header h1 { font-size: 26px; font-weight: 800; letter-spacing: -0.3px; }
    .cs-header h1 span { color: var(--orange); }
    .cs-header p  { font-size: 13px; color: var(--text-3); margin-top: 4px; }

    .cs-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        padding: 36px;
        margin-bottom: 24px;
        animation: fadeUp 0.35s 0.07s ease both;
    }

    .cs-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px dashed var(--border);
    }
    .cs-section-title svg { width: 20px; height: 20px; stroke: var(--orange); fill: none; }

    .cs-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    @media (max-width: 640px) { .cs-grid { grid-template-columns: 1fr; } }

    .cs-field {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .cs-field.full { grid-column: 1 / -1; }

    .cs-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-2);
    }
    .cs-label span.required { color: var(--orange); margin-left: 2px; }

    .cs-input {
        padding: 11px 16px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 14px;
        color: var(--text);
        background: #fafbfd;
        transition: all 0.2s;
        outline: none;
        width: 100%;
    }
    .cs-input:focus {
        border-color: var(--orange);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
    }

    .cs-hint {
        font-size: 11px;
        color: var(--text-3);
    }

    /* Logo section */
    .logo-area {
        display: flex;
        align-items: flex-start;
        gap: 24px;
        flex-wrap: wrap;
    }

    .logo-preview {
        width: 100px;
        height: 100px;
        border-radius: 16px;
        border: 2px dashed var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #fafbfd;
        flex-shrink: 0;
        position: relative;
    }
    .logo-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .logo-preview svg { width: 40px; height: 40px; stroke: var(--text-3); fill: none; }

    .logo-actions {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .logo-upload-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border: 1.5px solid var(--orange);
        border-radius: 40px;
        font-size: 13px;
        font-weight: 600;
        color: var(--orange);
        background: transparent;
        cursor: pointer;
        transition: all 0.2s;
    }
    .logo-upload-btn:hover {
        background: var(--orange);
        color: #fff;
    }
    .logo-upload-btn svg { width: 16px; height: 16px; stroke: currentColor; fill: none; }

    .logo-delete-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border: 1px solid #fca5a5;
        border-radius: 40px;
        font-size: 12px;
        font-weight: 600;
        color: #dc2626;
        background: #fef2f2;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .logo-delete-btn:hover { background: #dc2626; color: #fff; border-color: #dc2626; }
    .logo-delete-btn svg { width: 14px; height: 14px; stroke: currentColor; fill: none; }

    /* Tax rate preview */
    .tax-preview {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: var(--orange-pale);
        border: 1px solid var(--orange-soft);
        border-radius: 40px;
        font-size: 13px;
        font-weight: 600;
        color: var(--orange-dark);
        margin-top: 6px;
    }

    /* Submit */
    .cs-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        border: none;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        box-shadow: var(--shadow-orange);
        transition: all 0.2s;
    }
    .cs-submit:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(249,115,22,0.4); }
    .cs-submit svg { width: 18px; height: 18px; stroke: #fff; fill: none; }

    /* Invoice preview banner */
    .invoice-preview-banner {
        background: linear-gradient(135deg, #fff7ed, #fed7aa30);
        border: 1px solid var(--orange-soft);
        border-radius: var(--radius-sm);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 13px;
        color: var(--orange-dark);
        font-weight: 500;
    }
    .invoice-preview-banner svg { width: 20px; height: 20px; stroke: var(--orange); fill: none; flex-shrink: 0; }

    .error-list { list-style: none; padding: 0; }
    .error-list li {
        font-size: 12px;
        color: #dc2626;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 4px;
    }
    .error-list li::before { content: '•'; }
</style>
@endsection

@section('content')
<div class="cs-page">

    {{-- Header --}}
    <div class="cs-header">
        <div class="cs-hex">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <div>
            <h1>Paramètres <span>Entreprise</span></h1>
            <p>Ces informations apparaîtront sur vos factures normalisées.</p>
        </div>
    </div>

    {{-- Info banner --}}
    <div class="invoice-preview-banner" style="margin-bottom: 24px;">
        <svg viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Logo, IFU, RCCM et taux de taxe seront affichés automatiquement sur toutes vos factures normalisées.
    </div>

    <form method="POST" action="{{ route('company.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Section logo --}}
        <div class="cs-card">
            <div class="cs-section-title">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                LOGO DE L'ENTREPRISE
            </div>

            <div class="logo-area">
                <div class="logo-preview" id="logoPreview">
                    @if($tenant->logo)
                        <img src="{{ Storage::url($tenant->logo) }}" alt="Logo" id="logoImg">
                    @else
                        <svg id="logoPlaceholder" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <img src="" alt="Logo" id="logoImg" style="display:none;">
                    @endif
                </div>
                <div class="logo-actions">
                    <div>
                        <label for="logo" class="logo-upload-btn">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            {{ $tenant->logo ? 'Changer le logo' : 'Télécharger un logo' }}
                        </label>
                        <input type="file" id="logo" name="logo" accept="image/*" class="hidden" style="display:none;" onchange="previewLogo(this)">
                    </div>
                    <p class="cs-hint">Formats acceptés : JPG, PNG, SVG, WEBP. Taille max : 2 Mo.<br>Recommandé : fond transparent (PNG) — min 200×200 px.</p>
                    @if($tenant->logo)
                        <a href="{{ route('company.settings.delete-logo') }}" class="logo-delete-btn"
                           onclick="return confirm('Supprimer le logo ?')">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Supprimer le logo
                        </a>
                    @endif
                    @error('logo')
                        <ul class="error-list"><li>{{ $message }}</li></ul>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Section infos entreprise --}}
        <div class="cs-card">
            <div class="cs-section-title">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                INFORMATIONS DE L'ENTREPRISE
            </div>

            <div class="cs-grid">
                {{-- Nom entreprise --}}
                <div class="cs-field full">
                    <label class="cs-label" for="company_name">Nom de l'entreprise <span class="required">*</span></label>
                    <input class="cs-input" type="text" id="company_name" name="company_name"
                           value="{{ old('company_name', $tenant->company_name) }}" required>
                    @error('company_name')
                        <ul class="error-list"><li>{{ $message }}</li></ul>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="cs-field">
                    <label class="cs-label" for="email">Email</label>
                    <input class="cs-input" type="email" id="email" name="email"
                           value="{{ old('email', $tenant->email) }}"
                           placeholder="contact@monentreprise.com">
                    @error('email')
                        <ul class="error-list"><li>{{ $message }}</li></ul>
                    @enderror
                </div>

                {{-- Téléphone --}}
                <div class="cs-field">
                    <label class="cs-label" for="phone">Téléphone</label>
                    <input class="cs-input" type="text" id="phone" name="phone"
                           value="{{ old('phone', $tenant->phone) }}"
                           placeholder="+229 XX XX XX XX">
                    @error('phone')
                        <ul class="error-list"><li>{{ $message }}</li></ul>
                    @enderror
                </div>

                {{-- Adresse --}}
                <div class="cs-field full">
                    <label class="cs-label" for="address">Adresse</label>
                    <input class="cs-input" type="text" id="address" name="address"
                           value="{{ old('address', $tenant->address) }}"
                           placeholder="Rue, Ville, Pays">
                    @error('address')
                        <ul class="error-list"><li>{{ $message }}</li></ul>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Section identifiants fiscaux --}}
        <div class="cs-card">
            <div class="cs-section-title">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                IDENTIFIANTS FISCAUX & TAXES
            </div>

            <div class="cs-grid">
                {{-- IFU --}}
                <div class="cs-field">
                    <label class="cs-label" for="ifu">IFU (Identifiant Fiscal Unique)</label>
                    <input class="cs-input" type="text" id="ifu" name="ifu"
                           value="{{ old('ifu', $tenant->ifu) }}"
                           placeholder="Ex: 3202300123456">
                    <p class="cs-hint">Numéro d'identification fiscale délivré par la DGI.</p>
                    @error('ifu')
                        <ul class="error-list"><li>{{ $message }}</li></ul>
                    @enderror
                </div>

                {{-- RCCM --}}
                <div class="cs-field">
                    <label class="cs-label" for="rccm">RCCM</label>
                    <input class="cs-input" type="text" id="rccm" name="rccm"
                           value="{{ old('rccm', $tenant->rccm) }}"
                           placeholder="Ex: RB/COT/23 A 12345">
                    <p class="cs-hint">Registre du Commerce et du Crédit Mobilier.</p>
                    @error('rccm')
                        <ul class="error-list"><li>{{ $message }}</li></ul>
                    @enderror
                </div>

                {{-- Taux de taxe --}}
                <div class="cs-field full">
                    <label class="cs-label" for="tax_rate">Taux de TVA (%)</label>
                    <input class="cs-input" type="number" id="tax_rate" name="tax_rate"
                           value="{{ old('tax_rate', $tenant->tax_rate ?? 0) }}"
                           min="0" max="100" step="0.01"
                           placeholder="0"
                           style="max-width: 200px;"
                           oninput="updateTaxPreview(this.value)">
                    <div class="tax-preview" id="taxPreview">
                        TVA appliquée : <strong>{{ number_format($tenant->tax_rate ?? 0, 2) }} %</strong>
                    </div>
                    <p class="cs-hint">0% = exonéré de TVA. Ce taux sera calculé automatiquement sur chaque facture.</p>
                    @error('tax_rate')
                        <ul class="error-list"><li>{{ $message }}</li></ul>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div style="display: flex; justify-content: flex-end; gap: 16px; align-items: center;">
            <a href="{{ route('dashboard') }}" style="font-size:14px; color: var(--text-3); text-decoration:none;">Annuler</a>
            <button type="submit" class="cs-submit">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('logoImg');
            const placeholder = document.getElementById('logoPlaceholder');
            img.src = e.target.result;
            img.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function updateTaxPreview(val) {
    const v = parseFloat(val) || 0;
    document.getElementById('taxPreview').innerHTML =
        'TVA appliquée : <strong>' + v.toFixed(2) + ' %</strong>';
}
</script>
@endsection
