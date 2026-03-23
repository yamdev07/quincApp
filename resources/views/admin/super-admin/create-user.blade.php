@extends('layouts.app')

@section('title', 'Ajouter un employé - ' . $tenant->company_name)

@section('styles')
<style>
    :root {
        --orange: #f97316;
        --orange-dark: #ea580c;
        --orange-pale: #fff7ed;
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
        --bg: var(--gray-50);
        --card: var(--white);
        --border: var(--gray-200);
        --border-light: var(--gray-100);
        --text: var(--gray-900);
        --text-2: var(--gray-600);
        --text-3: var(--gray-400);
        --success: var(--orange);
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 16px rgba(0,0,0,0.05);
        --shadow-orange: 0 8px 24px rgba(249,115,22,0.15);
        --radius: 20px;
        --radius-sm: 12px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Inter', system-ui, sans-serif;
        background: var(--bg);
        color: var(--text);
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .st-page {
        max-width: 1280px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    .st-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
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
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-orange);
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
        color: var(--text);
    }
    .st-sub {
        font-size: 13px;
        color: var(--text-3);
        margin-top: 4px;
    }

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
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(249,115,22,0.3);
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
        transition: all 0.2s ease;
    }
    .btn-outline:hover {
        border-color: var(--orange);
        color: var(--orange);
        background: var(--orange-pale);
    }

    .st-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .st-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        background: var(--gray-50);
    }
    .st-card-header svg {
        width: 22px;
        height: 22px;
        stroke: var(--orange);
        fill: none;
    }
    .st-card-header h2 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }

    .st-card-body {
        padding: 24px;
    }

    .st-form-group {
        margin-bottom: 24px;
    }
    .st-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-2);
        margin-bottom: 8px;
    }
    .st-form-group label span {
        color: var(--orange);
    }
    .st-form-control {
        width: 100%;
        padding: 12px 16px;
        font-size: 14px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        transition: all 0.2s;
        background: var(--card);
    }
    .st-form-control:focus {
        outline: none;
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
    }
    select.st-form-control {
        cursor: pointer;
    }

    .st-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
    }

    .st-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 24px;
        font-size: 13px;
        color: var(--text-2);
        text-decoration: none;
    }
    .st-back:hover {
        color: var(--orange);
    }

    .st-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: var(--radius-sm);
        margin-bottom: 24px;
        border-left: 4px solid;
    }
    .st-alert-success {
        background: var(--orange-pale);
        border-color: var(--orange);
        color: var(--orange-dark);
    }
    .st-alert-error {
        background: var(--gray-100);
        border-color: var(--gray-400);
        color: var(--gray-600);
    }
    .st-alert svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
        flex-shrink: 0;
    }
</style>
@endsection

@section('content')
<div class="st-page">

    <a href="{{ route('super-admin.tenants.users', $tenant) }}" class="st-back">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour à la liste des employés
    </a>

    <div class="st-header">
        <div class="st-header-l">
            <div class="st-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <div>
                <div class="st-title">
                    Nouvel employé · {{ $tenant->company_name }}
                </div>
                <div class="st-sub">Créez un compte pour un collaborateur</div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="st-alert st-alert-error">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="st-card">
        <div class="st-card-header">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <h2>Informations du collaborateur</h2>
        </div>
        <div class="st-card-body">
            <form action="{{ route('super-admin.tenants.users.store', $tenant) }}" method="POST">
                @csrf

                <div class="st-form-group">
                    <label>Nom complet <span>*</span></label>
                    <input type="text" 
                           name="name" 
                           class="st-form-control" 
                           value="{{ old('name') }}" 
                           required 
                           placeholder="Jean Dupont">
                    @error('name')
                        <small style="color: var(--orange-dark); font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="st-form-group">
                    <label>Email <span>*</span></label>
                    <input type="email" 
                           name="email" 
                           class="st-form-control" 
                           value="{{ old('email') }}" 
                           required 
                           placeholder="jean@exemple.com">
                    @error('email')
                        <small style="color: var(--orange-dark); font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="st-form-group">
                    <label>Rôle <span>*</span></label>
                    <select name="role" class="st-form-control" required>
                        <option value="">Sélectionner un rôle</option>
                        <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Caissier</option>
                        <option value="storekeeper" {{ old('role') == 'storekeeper' ? 'selected' : '' }}>Magasinier</option>
                    </select>
                    @error('role')
                        <small style="color: var(--orange-dark); font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="st-form-group">
                    <label>Mot de passe <span>*</span></label>
                    <input type="password" 
                           name="password" 
                           class="st-form-control" 
                           required 
                           placeholder="••••••••">
                    <small style="color: var(--text-3); font-size: 11px; margin-top: 4px; display: block;">
                        Minimum 8 caractères
                    </small>
                    @error('password')
                        <small style="color: var(--orange-dark); font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="st-actions">
                    <button type="submit" class="btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Créer l'employé
                    </button>
                    <a href="{{ route('super-admin.tenants.users', $tenant) }}" class="btn-outline">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div style="margin-top: 24px; text-align: center; font-size: 12px; color: var(--text-3);">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; margin-right: 4px;">
            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
        L'employé recevra ses identifiants par email après création
    </div>
</div>
@endsection