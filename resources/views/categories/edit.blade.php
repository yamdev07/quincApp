@extends('layouts.app')

@section('title', 'Modifier la catégorie : ' . $category->name . ' — Inventix')

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
    .sc-edit-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .sc-edit-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        animation: fadeUp 0.35s ease both;
    }

    .sc-edit-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .sc-edit-hex {
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
    .sc-edit-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .sc-edit-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .sc-edit-title span {
        color: var(--orange);
        font-weight: 800;
    }
    .sc-edit-sub {
        font-size: 13px;
        color: var(--text-3);
        margin-top: 4px;
    }

    .sc-edit-badge {
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

    /* Card */
    .sc-edit-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        margin-bottom: 24px;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .sc-edit-card:hover {
        border-color: var(--orange-soft);
    }

    .sc-edit-card-header {
        padding: 18px 24px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .sc-edit-card-header-l {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sc-edit-card-ico {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sc-edit-card-ico svg {
        width: 18px;
        height: 18px;
        stroke: #fff;
        fill: none;
    }
    .sc-edit-card-header h2 {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
    }
    .sc-edit-card-header p {
        font-size: 12px;
        color: rgba(255,255,255,0.8);
    }

    .sc-edit-card-body {
        padding: 32px;
    }

    /* Error Alert */
    .sc-edit-error {
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
    .sc-edit-error svg {
        width: 24px;
        height: 24px;
        stroke: var(--danger);
        flex-shrink: 0;
    }
    .sc-edit-error h3 {
        font-size: 15px;
        font-weight: 700;
        color: var(--danger);
        margin-bottom: 8px;
    }
    .sc-edit-error ul {
        list-style: disc;
        padding-left: 20px;
        color: #991b1b;
        font-size: 13px;
    }

    /* Success Alert */
    .sc-edit-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: var(--radius-sm);
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #166534;
        animation: fadeIn 0.3s ease-out;
    }
    .sc-edit-success svg {
        width: 20px;
        height: 20px;
        stroke: var(--success);
        fill: none;
    }

    /* Form */
    .sc-edit-form-group {
        margin-bottom: 24px;
    }
    .sc-edit-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-2);
        margin-bottom: 8px;
    }
    .sc-edit-label svg {
        width: 16px;
        height: 16px;
        stroke: var(--orange);
        margin-right: 4px;
    }
    .sc-edit-label .required {
        color: var(--danger);
        margin-left: 4px;
    }

    .sc-edit-field-wrapper {
        position: relative;
    }
    .sc-edit-ico {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-3);
        transition: color 0.2s;
    }
    .sc-edit-ico svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
    }
    .sc-edit-field-wrapper:focus-within .sc-edit-ico {
        color: var(--orange);
        animation: pulse 2s infinite;
    }

    .sc-edit-input, .sc-edit-textarea {
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
    .sc-edit-input:focus, .sc-edit-textarea:focus {
        border-color: var(--orange);
        outline: none;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
        background: var(--card);
    }
    .sc-edit-input.error, .sc-edit-textarea.error {
        border-color: var(--danger);
        background: #fef2f2;
    }

    .sc-edit-textarea {
        padding: 14px 18px 14px 48px;
        resize: vertical;
        min-height: 120px;
    }

    .sc-edit-hint {
        display: flex;
        justify-content: space-between;
        margin-top: 6px;
        font-size: 11px;
        color: var(--text-3);
    }

    .sc-edit-error-message {
        margin-top: 6px;
        font-size: 13px;
        color: var(--danger);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .sc-edit-error-message svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        fill: none;
    }

    /* Stats Card */
    .sc-edit-stats {
        background: #fafbfd;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 20px;
        margin: 32px 0 24px;
    }
    .sc-edit-stats h3 {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sc-edit-stats h3 svg {
        width: 18px;
        height: 18px;
        stroke: var(--orange);
        fill: none;
    }
    .sc-edit-stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    .sc-edit-stat-item {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 14px;
    }
    .sc-edit-stat-label {
        font-size: 11px;
        color: var(--text-3);
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .sc-edit-stat-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
    }

    /* Form Actions */
    .sc-edit-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
        margin-top: 24px;
    }
    .sc-edit-actions-left, .sc-edit-actions-right {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    @media (max-width: 700px) {
        .sc-edit-actions {
            flex-direction: column;
        }
        .sc-edit-actions-left, .sc-edit-actions-right {
            width: 100%;
        }
        .sc-edit-actions-left .btn-outline,
        .sc-edit-actions-right .btn-outline,
        .sc-edit-actions-right .btn-primary {
            flex: 1;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<div class="sc-edit-page">

    {{-- HEADER --}}
    <div class="sc-edit-header">
        <div class="sc-edit-header-l">
            <div class="sc-edit-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <div class="sc-edit-title">
                    Modifier la <span>catégorie</span>
                    <span class="sc-edit-badge">#{{ $category->id }}</span>
                </div>
                <div class="sc-edit-sub">Mettez à jour les informations de la catégorie</div>
            </div>
        </div>
        <a href="{{ route('categories.index') }}" class="btn-secondary">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour aux catégories
        </a>
    </div>

    {{-- ERROR ALERT --}}
    @if($errors->any())
        <div class="sc-edit-error">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <h3>Veuillez corriger les erreurs suivantes :</h3>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
        <div class="sc-edit-success">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- FORM CARD --}}
    <div class="sc-edit-card">
        <div class="sc-edit-card-header">
            <div class="sc-edit-card-header-l">
                <div class="sc-edit-card-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h2>Modifier la catégorie</h2>
                    <p>Mettez à jour les informations ci-dessous</p>
                </div>
            </div>
        </div>

        <div class="sc-edit-card-body">
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nom de la catégorie --}}
                <div class="sc-edit-form-group">
                    <label for="name" class="sc-edit-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Nom de la catégorie
                        <span class="required">*</span>
                    </label>
                    <div class="sc-edit-field-wrapper">
                        <span class="sc-edit-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </span>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $category->name) }}" 
                               class="sc-edit-input @error('name') error @enderror"
                               placeholder="Ex: Outils électriques"
                               required>
                    </div>
                    <div class="sc-edit-hint">
                        <span>Nom principal de la catégorie</span>
                    </div>
                    @error('name')
                        <p class="sc-edit-error-message">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Sous-nom --}}
                <div class="sc-edit-form-group">
                    <label for="sub_name" class="sc-edit-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        </svg>
                        Sous-catégorie
                        <span style="font-weight:400; color:var(--text-3);">(optionnel)</span>
                    </label>
                    <div class="sc-edit-field-wrapper">
                        <span class="sc-edit-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </span>
                        <input type="text" 
                               id="sub_name" 
                               name="sub_name" 
                               value="{{ old('sub_name', $category->sub_name) }}" 
                               class="sc-edit-input @error('sub_name') error @enderror"
                               placeholder="Ex: Marteaux, tournevis, clés">
                    </div>
                    <div class="sc-edit-hint">
                        <span>Détails ou spécificités de la catégorie</span>
                    </div>
                    @error('sub_name')
                        <p class="sc-edit-error-message">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="sc-edit-form-group">
                    <label for="description" class="sc-edit-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        Description
                        <span style="font-weight:400; color:var(--text-3);">(optionnelle)</span>
                    </label>
                    <div class="sc-edit-field-wrapper">
                        <span class="sc-edit-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </span>
                        <textarea id="description" 
                                  name="description" 
                                  class="sc-edit-textarea @error('description') error @enderror"
                                  rows="4"
                                  placeholder="Décrivez cette catégorie en détail...">{{ old('description', $category->description) }}</textarea>
                    </div>
                    <div class="sc-edit-hint">
                        <span>Informations supplémentaires sur la catégorie</span>
                        <span id="charCount">{{ strlen(old('description', $category->description)) }}/500 caractères</span>
                    </div>
                    @error('description')
                        <p class="sc-edit-error-message">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- STATS --}}
                <div class="sc-edit-stats">
                    <h3>
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Statistiques de la catégorie
                    </h3>
                    <div class="sc-edit-stats-grid">
                        <div class="sc-edit-stat-item">
                            <div class="sc-edit-stat-label">Produits dans cette catégorie</div>
                            <div class="sc-edit-stat-value">{{ $category->products->count() }}</div>
                        </div>
                        <div class="sc-edit-stat-item">
                            <div class="sc-edit-stat-label">Date de création</div>
                            <div class="sc-edit-stat-value">
                                @if($category->created_at)
                                    {{ $category->created_at->format('d/m/Y') }}
                                @else
                                    Non définie
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FORM ACTIONS --}}
                <div class="sc-edit-actions">
                    <div class="sc-edit-actions-left">
                        <a href="{{ route('categories.show', $category->id) }}" class="btn-outline">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Voir la catégorie
                        </a>
                        <a href="{{ route('categories.index') }}" class="btn-outline">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Annuler
                        </a>
                    </div>
                    
                    <div class="sc-edit-actions-right">
                        <button type="reset" class="btn-outline">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Réinitialiser
                        </button>
                        
                        <button type="submit" class="btn-primary">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Enregistrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const description = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    
    function updateCharCount() {
        const count = description.value.length;
        charCount.textContent = count + '/500 caractères';
        
        if (count > 500) {
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-gray-400');
        } else {
            charCount.classList.remove('text-red-500');
            charCount.classList.add('text-gray-400');
        }
    }
    
    description.addEventListener('input', updateCharCount);
    updateCharCount(); // Initialiser
    
    // Validation avant envoi
    document.querySelector('form').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        
        if (!name) {
            e.preventDefault();
            alert('❌ Le nom de la catégorie est obligatoire.');
            document.getElementById('name').focus();
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