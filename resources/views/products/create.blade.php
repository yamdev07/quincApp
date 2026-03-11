@extends('layouts.app')

@section('title', 'Nouveau produit — QuincaApp')

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
        --pink:          #db2777;
        --indigo:        #6366f1;
        --yellow:        #eab308;
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
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    /* Page */
    .sp-create-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .sp-create-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        animation: fadeUp 0.35s ease both;
    }

    .sp-create-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .sp-create-hex {
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
    .sp-create-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .sp-create-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .sp-create-title span {
        color: var(--orange);
        font-weight: 800;
    }
    .sp-create-sub {
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

    /* Card */
    .sp-create-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        margin-bottom: 24px;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .sp-create-card:hover {
        border-color: var(--orange-soft);
    }

    .sp-create-card-header {
        padding: 18px 24px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .sp-create-card-header-l {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sp-create-card-ico {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sp-create-card-ico svg {
        width: 18px;
        height: 18px;
        stroke: #fff;
        fill: none;
    }
    .sp-create-card-header h2 {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
    }
    .sp-create-card-header p {
        font-size: 12px;
        color: rgba(255,255,255,0.8);
    }

    .sp-create-card-body {
        padding: 32px;
    }

    /* Error Alert */
    .sp-create-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: var(--radius-sm);
        padding: 20px;
        margin-bottom: 24px;
        display: flex;
        gap: 16px;
        align-items: flex-start;
        animation: fadeIn 0.3s ease-out;
    }
    .sp-create-error svg {
        width: 24px;
        height: 24px;
        stroke: var(--danger);
        flex-shrink: 0;
    }
    .sp-create-error h3 {
        font-size: 15px;
        font-weight: 700;
        color: var(--danger);
        margin-bottom: 8px;
    }
    .sp-create-error ul {
        list-style: disc;
        padding-left: 20px;
        color: #991b1b;
        font-size: 13px;
    }
    .sp-create-error-close {
        position: absolute;
        top: 16px;
        right: 16px;
        color: var(--danger);
        opacity: 0.7;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .sp-create-error-close:hover {
        opacity: 1;
    }

    /* Form */
    .sp-create-form-group {
        margin-bottom: 28px;
    }
    .sp-create-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 8px;
    }
    .sp-create-label svg {
        width: 16px;
        height: 16px;
        stroke: var(--orange);
        margin-right: 6px;
    }
    .sp-create-label span.required {
        color: var(--danger);
        margin-left: 4px;
    }

    .sp-create-field-wrapper {
        position: relative;
    }
    .sp-create-ico {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-3);
        transition: color 0.2s;
    }
    .sp-create-ico svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
    }
    .sp-create-field-wrapper:focus-within .sp-create-ico {
        color: var(--orange);
        animation: pulse 2s infinite;
    }

    .sp-create-input, .sp-create-select, .sp-create-textarea {
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
    .sp-create-input:focus, .sp-create-select:focus, .sp-create-textarea:focus {
        border-color: var(--orange);
        outline: none;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
        background: var(--card);
    }
    .sp-create-input.error, .sp-create-select.error, .sp-create-textarea.error {
        border-color: var(--danger);
        background: #fef2f2;
    }

    .sp-create-select {
        appearance: none;
        padding-right: 40px;
    }
    .sp-create-select-arrow {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: var(--text-3);
    }
    .sp-create-select-arrow svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
    }

    .sp-create-textarea {
        padding: 14px 18px 14px 48px;
        resize: vertical;
        min-height: 120px;
    }
    .sp-create-ico-textarea {
        position: absolute;
        left: 16px;
        top: 18px;
        color: var(--text-3);
    }

    .sp-create-hint {
        margin-top: 6px;
        font-size: 12px;
        color: var(--text-3);
    }
    .sp-create-error-message {
        margin-top: 6px;
        font-size: 13px;
        color: var(--danger);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Price group */
    .sp-create-price-group {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }
    @media (max-width: 700px) {
        .sp-create-price-group { grid-template-columns: 1fr; }
    }

    .sp-create-price-field {
        position: relative;
    }
    .sp-create-currency {
        position: absolute;
        left: 48px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        font-weight: 600;
        color: var(--text-3);
        pointer-events: none;
    }
    .sp-create-price-field .sp-create-input {
        padding-left: 80px;
    }

    /* Preview Card */
    .sp-create-preview {
        background: #fafbfd;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 20px;
        margin: 28px 0;
    }
    .sp-create-preview h4 {
        font-size: 15px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sp-create-preview h4 svg {
        width: 18px;
        height: 18px;
        stroke: var(--orange);
        fill: none;
    }
    .sp-create-preview-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    @media (max-width: 600px) {
        .sp-create-preview-grid { grid-template-columns: 1fr; }
    }
    .sp-create-preview-item {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 14px;
    }
    .sp-create-preview-label {
        font-size: 11px;
        color: var(--text-3);
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .sp-create-preview-value {
        font-size: 15px;
        font-weight: 600;
    }
    .preview-name { color: var(--text); }
    .preview-price { color: var(--success); }
    .preview-stock { color: var(--purple); }

    /* Tips Card */
    .sp-create-tips {
        background: var(--orange-pale);
        border: 1px solid var(--orange-soft);
        border-radius: var(--radius);
        padding: 24px;
        margin-top: 24px;
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }
    .sp-create-tips svg {
        width: 24px;
        height: 24px;
        stroke: var(--orange);
        fill: none;
        flex-shrink: 0;
    }
    .sp-create-tips h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--orange-dark);
        margin-bottom: 8px;
    }
    .sp-create-tips ul {
        list-style: none;
        padding: 0;
    }
    .sp-create-tips li {
        font-size: 13px;
        color: var(--text-2);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sp-create-tips li::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--orange);
    }

    /* Form Actions */
    .sp-create-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
        margin-top: 24px;
    }
    .sp-create-actions-left, .sp-create-actions-right {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Input number spin hide */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    input[type="number"] { -moz-appearance: textfield; }

    /* Focus scale effect */
    .focus-scale {
        transition: transform 0.2s;
    }
    .focus-scale:focus-within {
        transform: scale(1.02);
    }
</style>
@endsection

@section('content')
<div class="sp-create-page">

    {{-- Vérification d'accès --}}
    @if(!auth()->user()->canManageStock() || !(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()))
        <div class="sp-create-error" style="justify-content: center; text-align: center;">
            <svg viewBox="0 0 24 24" stroke-width="1.5" style="width:48px;height:48px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h3>Accès refusé</h3>
            <p>Vous n'avez pas les droits pour créer un produit.</p>
            <a href="{{ route('products.index') }}" class="btn-outline" style="margin-top:16px;">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la liste
            </a>
        </div>
        @php return; @endphp
    @endif

    {{-- HEADER --}}
    <div class="sp-create-header">
        <div class="sp-create-header-l">
            <div class="sp-create-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <div>
                <div class="sp-create-title">
                    Nouveau <span>produit</span>
                </div>
                <div class="sp-create-sub">Ajouter un nouveau produit à l'inventaire</div>
            </div>
        </div>
        <a href="{{ route('products.index') }}" class="btn-secondary">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>
    </div>

    {{-- ERROR ALERT --}}
    @if($errors->any())
        <div class="sp-create-error" id="errorAlert">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div style="flex:1;">
                <h3>Veuillez corriger les erreurs suivantes :</h3>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" onclick="document.getElementById('errorAlert').remove()" class="sp-create-error-close">
                <svg viewBox="0 0 24 24" stroke-width="2" style="width:18px;height:18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- FORM CARD --}}
    <div class="sp-create-card">
        <div class="sp-create-card-header">
            <div class="sp-create-card-header-l">
                <div class="sp-create-card-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h2>Ajouter un produit</h2>
                    <p>Remplissez les informations ci-dessous</p>
                </div>
            </div>
        </div>

        <div class="sp-create-card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" id="createProductForm">
                @csrf

                {{-- Nom du produit --}}
                <div class="sp-create-form-group">
                    <label for="name" class="sp-create-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Nom du produit
                        <span class="required">*</span>
                    </label>
                    <div class="sp-create-field-wrapper focus-scale">
                        <span class="sp-create-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </span>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               class="sp-create-input @error('name') error @enderror"
                               placeholder="Ex: Marteau professionnel 500g"
                               required>
                    </div>
                    @error('name')
                        <p class="sp-create-error-message">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Prix --}}
                <div class="sp-create-price-group">
                    {{-- Prix de vente --}}
                    <div class="sp-create-price-field">
                        <label for="sale_price" class="sp-create-label">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Prix de vente (CFA)
                            <span class="required">*</span>
                        </label>
                        <div class="sp-create-field-wrapper focus-scale">
                            <span class="sp-create-ico">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <span class="sp-create-currency">CFA</span>
                            <input type="number" 
                                   id="sale_price" 
                                   name="sale_price" 
                                   value="{{ old('sale_price') }}" 
                                   class="sp-create-input @error('sale_price') error @enderror"
                                   step="1"
                                   min="0"
                                   placeholder="0"
                                   required>
                        </div>
                        @error('sale_price')
                            <p class="sp-create-error-message">
                                <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Prix d'achat --}}
                    <div class="sp-create-price-field">
                        <label for="purchase_price" class="sp-create-label">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Prix d'achat (CFA)
                            <span class="required">*</span>
                        </label>
                        <div class="sp-create-field-wrapper focus-scale">
                            <span class="sp-create-ico">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </span>
                            <span class="sp-create-currency">CFA</span>
                            <input type="number" 
                                   id="purchase_price" 
                                   name="purchase_price" 
                                   value="{{ old('purchase_price') }}" 
                                   class="sp-create-input @error('purchase_price') error @enderror"
                                   step="1"
                                   min="0"
                                   placeholder="0"
                                   required>
                        </div>
                        @error('purchase_price')
                            <p class="sp-create-error-message">
                                <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Stock --}}
                <div class="sp-create-form-group">
                    <label for="stock" class="sp-create-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Stock initial
                        <span class="required">*</span>
                    </label>
                    <div class="sp-create-field-wrapper focus-scale">
                        <span class="sp-create-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </span>
                        <input type="number" 
                               id="stock" 
                               name="stock" 
                               value="{{ old('stock', 0) }}" 
                               class="sp-create-input @error('stock') error @enderror"
                               min="0"
                               placeholder="0"
                               required>
                    </div>
                    @error('stock')
                        <p class="sp-create-error-message">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Catégorie --}}
                <div class="sp-create-form-group">
                    <label for="category_id" class="sp-create-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Catégorie
                        <span class="required">*</span>
                    </label>
                    <div class="sp-create-field-wrapper focus-scale">
                        <span class="sp-create-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </span>
                        <select id="category_id" name="category_id" class="sp-create-select @error('category_id') error @enderror" required>
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} {{ $category->sub_name ? '- ' . $category->sub_name : '' }}
                                </option>
                            @endforeach
                        </select>
                        <span class="sp-create-select-arrow">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>
                    @error('category_id')
                        <p class="sp-create-error-message">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Fournisseur --}}
                <div class="sp-create-form-group">
                    <label for="supplier_id" class="sp-create-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Fournisseur
                        <span class="required">*</span>
                    </label>
                    <div class="sp-create-field-wrapper focus-scale">
                        <span class="sp-create-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </span>
                        <select id="supplier_id" name="supplier_id" class="sp-create-select @error('supplier_id') error @enderror" required>
                            <option value="">Sélectionner un fournisseur</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="sp-create-select-arrow">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>
                    @error('supplier_id')
                        <p class="sp-create-error-message">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="sp-create-form-group">
                    <label for="description" class="sp-create-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        Description
                        <span style="font-weight:400; color:var(--text-3);">(optionnelle)</span>
                    </label>
                    <div class="sp-create-field-wrapper">
                        <span class="sp-create-ico-textarea">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="width:18px;height:18px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </span>
                        <textarea id="description" 
                                  name="description" 
                                  class="sp-create-textarea @error('description') error @enderror"
                                  rows="4"
                                  placeholder="Décrivez le produit en détail...">{{ old('description') }}</textarea>
                    </div>
                    <div class="sp-create-hint">
                        <span>Informations supplémentaires sur le produit</span>
                        <span id="charCount">0/500 caractères</span>
                    </div>
                    @error('description')
                        <p class="sp-create-error-message">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- PREVIEW CARD --}}
                <div class="sp-create-preview">
                    <h4>
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Aperçu du produit
                    </h4>
                    <div class="sp-create-preview-grid">
                        <div class="sp-create-preview-item">
                            <div class="sp-create-preview-label">Nom</div>
                            <div class="sp-create-preview-value preview-name" id="previewName">{{ old('name', '—') }}</div>
                        </div>
                        <div class="sp-create-preview-item">
                            <div class="sp-create-preview-label">Prix de vente</div>
                            <div class="sp-create-preview-value preview-price" id="previewSalePrice">
                                {{ old('sale_price') ? number_format(old('sale_price'), 0, ',', ' ') : '0' }} CFA
                            </div>
                        </div>
                        <div class="sp-create-preview-item">
                            <div class="sp-create-preview-label">Stock</div>
                            <div class="sp-create-preview-value preview-stock" id="previewStock">
                                {{ old('stock', 0) }} unités
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FORM ACTIONS --}}
                <div class="sp-create-actions">
                    <div class="sp-create-actions-left">
                        <a href="{{ route('products.index') }}" class="btn-outline">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Annuler
                        </a>
                    </div>
                    <div class="sp-create-actions-right">
                        <button type="reset" class="btn-outline">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Réinitialiser
                        </button>
                        <button type="submit" class="btn-primary">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Créer le produit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TIPS CARD --}}
    <div class="sp-create-tips">
        <svg viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
        </svg>
        <div>
            <h3>Conseils de création</h3>
            <ul>
                <li>Utilisez un nom clair et descriptif pour votre produit</li>
                <li>Le prix doit être en Francs CFA (FCFA)</li>
                <li>Sélectionnez la catégorie et le fournisseur appropriés</li>
                <li>Mettez à jour régulièrement le stock pour une gestion optimale</li>
                <li>Une description détaillée aide à mieux identifier le produit</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du formulaire
    const nameInput = document.getElementById('name');
    const salePriceInput = document.getElementById('sale_price');
    const stockInput = document.getElementById('stock');
    const descriptionInput = document.getElementById('description');

    // Éléments de prévisualisation
    const previewName = document.getElementById('previewName');
    const previewSalePrice = document.getElementById('previewSalePrice');
    const previewStock = document.getElementById('previewStock');
    const charCount = document.getElementById('charCount');

    // Fonction de mise à jour de l'aperçu
    function updatePreview() {
        // Nom
        previewName.textContent = nameInput.value || '—';
        
        // Prix de vente
        const salePrice = parseInt(salePriceInput.value) || 0;
        previewSalePrice.textContent = salePrice.toLocaleString('fr-FR') + ' CFA';
        
        // Stock
        const stock = parseInt(stockInput.value) || 0;
        previewStock.textContent = stock + ' unités';
        
        // Compteur de caractères
        const descLength = descriptionInput.value.length;
        charCount.textContent = descLength + '/500 caractères';
    }

    // Écouteurs d'événements
    [nameInput, salePriceInput, stockInput, descriptionInput].forEach(input => {
        if (input) input.addEventListener('input', updatePreview);
    });

    // Effet d'échelle au focus
    const wrappers = document.querySelectorAll('.focus-scale');
    wrappers.forEach(wrapper => {
        wrapper.addEventListener('focusin', () => {
            wrapper.style.transform = 'scale(1.02)';
        });
        wrapper.addEventListener('focusout', () => {
            wrapper.style.transform = 'scale(1)';
        });
    });

    // Initialisation
    updatePreview();

    // Validation du formulaire
    document.getElementById('createProductForm')?.addEventListener('submit', function(e) {
        const name = nameInput.value.trim();
        const salePrice = parseFloat(salePriceInput.value) || 0;
        const purchasePrice = parseFloat(document.getElementById('purchase_price').value) || 0;
        const stock = parseInt(stockInput.value) || 0;
        
        if (!name) {
            alert('❌ Le nom du produit est requis.');
            e.preventDefault();
            return false;
        }
        
        if (salePrice <= 0) {
            alert('❌ Le prix de vente doit être supérieur à 0.');
            e.preventDefault();
            return false;
        }
        
        if (purchasePrice <= 0) {
            alert('❌ Le prix d\'achat doit être supérieur à 0.');
            e.preventDefault();
            return false;
        }
        
        if (stock < 0) {
            alert('❌ Le stock ne peut pas être négatif.');
            e.preventDefault();
            return false;
        }
        
        if (salePrice < purchasePrice) {
            if (!confirm('⚠️ Le prix de vente est inférieur au prix d\'achat. Voulez-vous continuer ?')) {
                e.preventDefault();
                return false;
            }
        }
        
        // Effet de chargement
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = `
                <svg viewBox="0 0 24 24" stroke-width="2" style="width:16px;height:16px; animation:spin 1s linear infinite;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Création...
            `;
            submitBtn.disabled = true;
        }
    });

    // Animation de fermeture de l'alerte
    window.closeErrorAlert = function() {
        const alert = document.getElementById('errorAlert');
        if (alert) {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }
    };
});
</script>
@endsection