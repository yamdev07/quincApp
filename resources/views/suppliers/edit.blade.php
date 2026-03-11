@extends('layouts.app')

@section('title', 'Modifier le fournisseur — QuincaApp')

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
    .sme-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .sme-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        animation: fadeUp 0.35s ease both;
    }

    .sme-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .sme-hex {
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
    .sme-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .sme-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .sme-title span {
        color: var(--orange);
        font-weight: 800;
    }
    .sme-sub {
        font-size: 13px;
        color: var(--text-3);
        margin-top: 4px;
    }

    .sme-badge {
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

    .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        background: #fee2e2;
        border: 1.5px solid #fecaca;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 600;
        color: var(--danger);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-danger svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
    }
    .btn-danger:hover {
        background: var(--danger);
        border-color: var(--danger);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(220,38,38,0.3);
    }

    /* Access denied */
    .sme-access-denied {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: var(--radius);
        padding: 32px;
        text-align: center;
        animation: fadeUp 0.35s ease both;
        margin-bottom: 24px;
    }
    .sme-access-denied svg {
        width: 48px;
        height: 48px;
        stroke: var(--danger);
        margin: 0 auto 16px;
    }
    .sme-access-denied h2 {
        font-size: 20px;
        font-weight: 700;
        color: var(--danger);
        margin-bottom: 8px;
    }
    .sme-access-denied p {
        font-size: 14px;
        color: var(--text-2);
        margin-bottom: 24px;
    }

    /* Alertes */
    .sme-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        border-radius: var(--radius-sm);
        margin-bottom: 24px;
        animation: fadeIn 0.3s ease-out;
        border-left: 4px solid;
    }
    .sme-alert svg {
        width: 22px;
        height: 22px;
        stroke: currentColor;
        fill: none;
        flex-shrink: 0;
    }
    .sme-alert-success {
        background: #f0fdf4;
        border-color: var(--success);
        color: #166534;
    }
    .sme-alert-error {
        background: #fef2f2;
        border-color: var(--danger);
        color: #991b1b;
    }
    .sme-alert-info {
        background: #eff6ff;
        border-color: var(--info);
        color: #1e40af;
    }

    /* Card */
    .sme-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        margin-bottom: 24px;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .sme-card:hover {
        border-color: var(--orange-soft);
    }

    .sme-card-header {
        padding: 18px 24px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .sme-card-header-l {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sme-card-ico {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sme-card-ico svg {
        width: 18px;
        height: 18px;
        stroke: #fff;
        fill: none;
    }
    .sme-card-header h2 {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
    }
    .sme-card-header p {
        font-size: 12px;
        color: rgba(255,255,255,0.8);
    }

    .sme-card-body {
        padding: 32px;
    }

    /* Form */
    .sme-form-group {
        margin-bottom: 24px;
    }
    .sme-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-2);
        margin-bottom: 8px;
    }
    .sme-label svg {
        width: 16px;
        height: 16px;
        stroke: var(--orange);
        margin-right: 4px;
    }
    .sme-label .required {
        color: var(--danger);
        margin-left: 4px;
    }

    .sme-field-wrapper {
        position: relative;
    }
    .sme-ico {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-3);
        transition: color 0.2s;
    }
    .sme-ico svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
    }
    .sme-field-wrapper:focus-within .sme-ico {
        color: var(--orange);
    }

    .sme-input {
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
    .sme-input:focus {
        border-color: var(--orange);
        outline: none;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
        background: var(--card);
    }
    .sme-input.error {
        border-color: var(--danger);
        background: #fef2f2;
    }

    .sme-error {
        margin-top: 6px;
        font-size: 13px;
        color: var(--danger);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .sme-error svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        fill: none;
    }

    /* Info card */
    .sme-info-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        padding: 24px;
        margin-top: 24px;
        transition: border-color 0.2s;
    }
    .sme-info-card:hover {
        border-color: var(--orange-soft);
    }
    .sme-info-card h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sme-info-card h3 svg {
        width: 20px;
        height: 20px;
        stroke: var(--orange);
        fill: none;
    }

    .sme-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    @media (max-width: 600px) {
        .sme-info-grid { grid-template-columns: 1fr; }
    }

    .sme-info-item {
        background: #fafbfd;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 16px;
    }
    .sme-info-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-3);
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .sme-info-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--text);
    }

    /* Form Actions */
    .sme-actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
        margin-top: 24px;
    }
    @media (max-width: 700px) {
        .sme-actions {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="sme-page">

    {{-- Vérification des autorisations --}}
    @if(!auth()->user()->isSuperAdminGlobal() && !auth()->user()->isSuperAdmin() && !auth()->user()->isAdmin())
        <div class="sme-access-denied">
            <svg viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h2>Accès refusé</h2>
            <p>Vous n'avez pas l'autorisation d'accéder à cette section.</p>
            <a href="{{ route('suppliers.index') }}" class="btn-secondary">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour aux fournisseurs
            </a>
        </div>
    @else

    {{-- HEADER --}}
    <div class="sme-header">
        <div class="sme-header-l">
            <div class="sme-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <div class="sme-title">
                    Modifier le <span>fournisseur</span>
                    <span class="sme-badge">#{{ $supplier->id }}</span>
                </div>
                <div class="sme-sub">Mettez à jour les informations du fournisseur</div>
            </div>
        </div>
        <a href="{{ route('suppliers.index') }}" class="btn-secondary">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Tous les fournisseurs
        </a>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="sme-alert sme-alert-success">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="sme-alert sme-alert-error">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p class="font-semibold">Erreur</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="sme-alert sme-alert-error">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p class="font-semibold">Erreur de validation</p>
                <ul style="list-style:disc; margin-left:16px; margin-top:4px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- FORM CARD --}}
    <div class="sme-card">
        <div class="sme-card-header">
            <div class="sme-card-header-l">
                <div class="sme-card-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h2>Modifier le fournisseur</h2>
                    <p>Mettez à jour les informations ci-dessous</p>
                </div>
            </div>
        </div>

        <div class="sme-card-body">
            {{-- 👈 CORRECTION ICI : route('admin.suppliers.update', $supplier->id) --}}
            <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nom du fournisseur --}}
                <div class="sme-form-group">
                    <label for="name" class="sme-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Nom du fournisseur
                        <span class="required">*</span>
                    </label>
                    <div class="sme-field-wrapper">
                        <span class="sme-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </span>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $supplier->name) }}" 
                               class="sme-input @error('name') error @enderror"
                               placeholder="Nom de l'entreprise..."
                               required>
                    </div>
                    @error('name')
                        <p class="sme-error">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Personne à contacter --}}
                <div class="sme-form-group">
                    <label for="contact" class="sme-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Personne à contacter
                    </label>
                    <div class="sme-field-wrapper">
                        <span class="sme-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <input type="text" 
                               id="contact" 
                               name="contact" 
                               value="{{ old('contact', $supplier->contact) }}" 
                               class="sme-input @error('contact') error @enderror"
                               placeholder="Nom du responsable...">
                    </div>
                    @error('contact')
                        <p class="sme-error">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Téléphone --}}
                <div class="sme-form-group">
                    <label for="phone" class="sme-label">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="display:inline;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        Numéro de téléphone
                    </label>
                    <div class="sme-field-wrapper">
                        <span class="sme-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </span>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $supplier->phone) }}" 
                               class="sme-input @error('phone') error @enderror"
                               placeholder="+221 77 123 45 67">
                    </div>
                    @error('phone')
                        <p class="sme-error">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="sme-actions">
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Mettre à jour
                    </button>
                    
                    <button type="button" onclick="confirmDelete()" class="btn-danger">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Supprimer
                    </button>
                    
                    <a href="{{ route('suppliers.index') }}" class="btn-outline">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- INFORMATIONS SUPPLÉMENTAIRES --}}
    <div class="sme-info-card">
        <h3>
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Informations supplémentaires
        </h3>
        <div class="sme-info-grid">
            <div class="sme-info-item">
                <div class="sme-info-label">Créé le</div>
                <div class="sme-info-value">{{ $supplier->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="sme-info-item">
                <div class="sme-info-label">Dernière modification</div>
                <div class="sme-info-value">{{ $supplier->updated_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="sme-info-item">
                <div class="sme-info-label">Produits associés</div>
                <div class="sme-info-value">{{ $supplier->products_count ?? $supplier->products()->count() }} produits</div>
            </div>
            <div class="sme-info-item">
                <div class="sme-info-label">ID du fournisseur</div>
                <div class="sme-info-value">#{{ $supplier->id }}</div>
            </div>
        </div>
    </div>

    @endif {{-- Fin de la condition d'autorisation --}}
</div>

<script>
function confirmDelete() {
    if (confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce fournisseur ? Cette action est irréversible.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        {{-- 👈 CORRECTION ICI : route('admin.suppliers.destroy', $supplier->id) --}}
        form.action = '{{ route('admin.suppliers.destroy', $supplier->id) }}';
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(method);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection