@extends('layouts.app')

@section('title', isset($category) ? 'Modifier la catégorie : ' . $category->name . ' — Sellvantix' : 'Nouvelle catégorie — Sellvantix')

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

    .sc-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        background: var(--orange-pale);
        border: 1px solid var(--orange-soft);
        border-radius: 40px;
        font-size: 12px;
        color: var(--orange);
        margin-left: 8px;
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

    /* Alert */
    .sc-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: var(--radius-sm);
        margin-bottom: 24px;
        animation: fadeIn 0.3s ease-out;
        border-left: 4px solid;
        position: relative;
    }
    .sc-alert svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
        flex-shrink: 0;
    }
    .sc-alert-success {
        background: #f0fdf4;
        border-color: var(--success);
        color: #166534;
    }
    .sc-alert-error {
        background: #fef2f2;
        border-color: var(--danger);
        color: #991b1b;
    }
    .sc-alert-close {
        position: absolute;
        top: 16px;
        right: 16px;
        opacity: 0.6;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .sc-alert-close:hover {
        opacity: 1;
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
        animation: pulse 2s infinite;
    }

    .sc-input, .sc-select, .sc-textarea {
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
    .sc-input:focus, .sc-select:focus, .sc-textarea:focus {
        border-color: var(--orange);
        outline: none;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
        background: var(--card);
    }
    .sc-input.error, .sc-select.error, .sc-textarea.error {
        border-color: var(--danger);
        background: #fef2f2;
    }

    .sc-select {
        appearance: none;
        padding-right: 40px;
    }
    .sc-select-arrow {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: var(--text-3);
    }
    .sc-select-arrow svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
    }

    .sc-textarea {
        padding: 14px 18px;
        min-height: 100px;
        resize: vertical;
    }

    .sc-hint {
        margin-top: 6px;
        font-size: 11px;
        color: var(--text-3);
    }

    .sc-error-message {
        margin-top: 6px;
        font-size: 13px;
        color: var(--danger);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Info grid */
    .sc-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-top: 24px;
    }
    @media (max-width: 600px) {
        .sc-info-grid { grid-template-columns: 1fr; }
    }

    .sc-info-item {
        background: #fafbfd;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 16px;
    }
    .sc-info-label {
        font-size: 11px;
        color: var(--text-3);
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .sc-info-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--text);
    }
    .sc-info-link {
        color: var(--info);
        text-decoration: none;
    }
    .sc-info-link:hover {
        text-decoration: underline;
    }

    /* Form Actions */
    .sc-actions {
        display: flex;
        gap: 16px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
        margin-top: 24px;
    }
    @media (max-width: 600px) {
        .sc-actions {
            flex-direction: column;
        }
        .sc-actions .btn-primary,
        .sc-actions .btn-outline {
            width: 100%;
            justify-content: center;
        }
    }

    /* Animation pulse */
    .animate-pulse {
        animation: pulse 1s ease-in-out;
    }
</style>
@endsection

@section('content')
<div class="sc-page">

    {{-- Vérification des autorisations --}}
    @if(!auth()->user()->isSuperAdminGlobal() && !auth()->user()->isSuperAdmin() && !auth()->user()->isAdmin())
        <div class="sc-access-denied">
            <svg viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h2>Accès refusé</h2>
            <p>Vous n'avez pas l'autorisation d'accéder à cette section.</p>
            <a href="{{ route('dashboard') }}" class="btn-secondary">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour au tableau de bord
            </a>
        </div>
    @else

    {{-- HEADER --}}
    <div class="sc-header">
        <div class="sc-header-l">
            <div class="sc-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <div class="sc-title">
                    @if(isset($category))
                        Modifier la <span>catégorie</span>
                        <span class="sc-badge">#{{ $category->id }}</span>
                    @else
                        Nouvelle <span>catégorie</span>
                    @endif
                </div>
                <div class="sc-sub">
                    @if(isset($category))
                        Mettez à jour les informations de la catégorie
                    @else
                        Créez une nouvelle catégorie pour organiser vos produits
                    @endif
                </div>
            </div>
        </div>
        <a href="{{ route('categories.index') }}" class="btn-secondary">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Toutes les catégories
        </a>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="sc-alert sc-alert-success">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
            <button class="sc-alert-close" onclick="this.parentElement.remove()">
                <svg viewBox="0 0 24 24" stroke-width="2" style="width:16px;height:16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="sc-alert sc-alert-error">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <ul style="list-style:disc; margin-left:16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button class="sc-alert-close" onclick="this.parentElement.remove()">
                <svg viewBox="0 0 24 24" stroke-width="2" style="width:16px;height:16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- FORM CARD --}}
    <div class="sc-card">
        <div class="sc-card-header">
            <div class="sc-card-header-l">
                <div class="sc-card-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        @if(isset($category))
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        @endif
                    </svg>
                </div>
                <div>
                    <h2>{{ isset($category) ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}</h2>
                    <p>{{ isset($category) ? 'Mettez à jour les informations ci-dessous' : 'Remplissez les informations ci-dessous' }}</p>
                </div>
            </div>
        </div>

        <div class="sc-card-body">
            <form action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" method="POST">
                @csrf
                @if(isset($category))
                    @method('PUT')
                @endif

                {{-- Nom de la catégorie --}}
                <div class="sc-form-group">
                    <label for="name" class="sc-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Nom de la catégorie
                        <span class="required">*</span>
                    </label>
                    <div class="sc-field-wrapper">
                        <span class="sc-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </span>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $category->name ?? '') }}" 
                               class="sc-input @error('name') error @enderror"
                               placeholder="Ex: Électronique, Mode, Maison..."
                               required>
                    </div>
                    @error('name')
                        <p class="sc-error-message">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Catégorie parente --}}
                <div class="sc-form-group">
                    <label for="parent_id" class="sc-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Catégorie parente
                        <span style="font-weight:400; color:var(--text-3);">(optionnel)</span>
                    </label>
                    <div class="sc-field-wrapper">
                        <span class="sc-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </span>
                        <select id="parent_id" name="parent_id" class="sc-select @error('parent_id') error @enderror">
                            <option value="">-- Catégorie principale (sans parent) --</option>
                            @foreach($mainCategories ?? [] as $mainCat)
                                <option value="{{ $mainCat->id }}" 
                                    {{ old('parent_id', $category->parent_id ?? '') == $mainCat->id ? 'selected' : '' }}
                                    {{ isset($category) && $category->id == $mainCat->id ? 'disabled' : '' }}>
                                    {{ $mainCat->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="sc-select-arrow">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>
                    <div class="sc-hint">
                        Laissez vide pour créer une catégorie principale. Sélectionnez une catégorie existante pour créer une sous-catégorie.
                    </div>
                    @error('parent_id')
                        <p class="sc-error-message">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="sc-form-group">
                    <label for="description" class="sc-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        Description
                        <span style="font-weight:400; color:var(--text-3);">(optionnelle)</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              class="sc-textarea @error('description') error @enderror"
                              rows="4"
                              placeholder="Décrivez cette catégorie en détail...">{{ old('description', $category->description ?? '') }}</textarea>
                    @error('description')
                        <p class="sc-error-message">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ACTIONS --}}
                <div class="sc-actions">
                    <button type="submit" class="btn-primary" style="flex:1;">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            @if(isset($category))
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            @endif
                        </svg>
                        {{ isset($category) ? 'Mettre à jour' : 'Créer la catégorie' }}
                    </button>
                    
                    <a href="{{ route('categories.index') }}" class="btn-outline" style="flex:1;">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- INFORMATIONS SUPPLÉMENTAIRES (pour l'édition) --}}
    @if(isset($category))
        <div class="sc-card">
            <div class="sc-card-header">
                <div class="sc-card-header-l">
                    <div class="sc-card-ico">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2>Informations supplémentaires</h2>
                        <p>Détails et métadonnées de la catégorie</p>
                    </div>
                </div>
            </div>
            <div class="sc-card-body">
                <div class="sc-info-grid">
                    <div class="sc-info-item">
                        <div class="sc-info-label">Créée le</div>
                        <div class="sc-info-value">{{ $category->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="sc-info-item">
                        <div class="sc-info-label">Dernière modification</div>
                        <div class="sc-info-value">{{ $category->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="sc-info-item">
                        <div class="sc-info-label">Type</div>
                        <div class="sc-info-value">
                            @if($category->parent_id)
                                <span style="color:var(--purple);">Sous-catégorie</span>
                            @else
                                <span style="color:var(--success);">Catégorie principale</span>
                            @endif
                        </div>
                    </div>
                    <div class="sc-info-item">
                        <div class="sc-info-label">Produits associés</div>
                        <div class="sc-info-value">{{ $category->products->count() }} produits</div>
                    </div>
                    @if($category->parent_id && $category->parent)
                        <div class="sc-info-item">
                            <div class="sc-info-label">Catégorie parente</div>
                            <div class="sc-info-value">
                                <a href="{{ route('categories.show', $category->parent->id) }}" class="sc-info-link">
                                    {{ $category->parent->name }}
                                </a>
                            </div>
                        </div>
                    @endif
                    @if($category->children->count() > 0)
                        <div class="sc-info-item">
                            <div class="sc-info-label">Sous-catégories</div>
                            <div class="sc-info-value">{{ $category->children->count() }} sous-catégorie(s)</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
    
    @endif {{-- Fin de la condition d'autorisation --}}
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const nameInput = document.getElementById('name');
    
    if (form && nameInput) {
        form.addEventListener('submit', function(e) {
            const name = nameInput.value.trim();
            
            if (!name) {
                e.preventDefault();
                
                // Animation pour le champ vide
                nameInput.classList.add('error', 'animate-pulse');
                nameInput.style.borderColor = '#dc2626';
                nameInput.style.backgroundColor = '#fef2f2';
                
                setTimeout(() => {
                    nameInput.classList.remove('animate-pulse');
                }, 1000);
                
                // Scroll vers le champ vide
                nameInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                nameInput.focus();
                
                // Message d'erreur
                const existingError = nameInput.parentElement.parentElement.querySelector('.sc-error-message');
                if (!existingError) {
                    const errorMsg = document.createElement('p');
                    errorMsg.className = 'sc-error-message';
                    errorMsg.innerHTML = `
                        <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Le nom de la catégorie est obligatoire.
                    `;
                    nameInput.parentElement.parentElement.appendChild(errorMsg);
                }
                
                return false;
            }
            
            // Effet de chargement
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = `
                    <svg viewBox="0 0 24 24" stroke-width="2" style="width:16px;height:16px; animation:spin 1s linear infinite;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    {{ isset($category) ? 'Mise à jour...' : 'Création...' }}
                `;
                submitBtn.disabled = true;
            }
        });

        // Supprimer l'erreur quand l'utilisateur commence à taper
        nameInput.addEventListener('input', function() {
            this.classList.remove('error', 'animate-pulse');
            this.style.borderColor = '#e2e8f0';
            this.style.backgroundColor = '#fafbfd';
            
            const errorMsg = this.parentElement.parentElement.querySelector('.sc-error-message');
            if (errorMsg) {
                errorMsg.remove();
            }
        });
    }
});
</script>
@endsection