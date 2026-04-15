@extends('layouts.app')

@section('title', 'Modifier un employé — Inventix')

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

    /* Page */
    .se-page {
        max-width: 720px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .se-header {
        margin-bottom: 28px;
        animation: fadeUp 0.35s ease both;
    }

    .se-header-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .se-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .se-hex {
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
    .se-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .se-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .se-title span {
        color: var(--orange);
        font-weight: 800;
    }
    .se-sub {
        font-size: 13px;
        color: var(--text-3);
        margin-top: 4px;
    }

    /* Badge info */
    .se-role-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 8px;
    }
    .badge-current {
        background: var(--orange-pale);
        color: var(--orange-dark);
        border: 1px solid var(--orange-soft);
    }

    /* Boutons */
    .btn-back {
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
    .btn-back svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        fill: none;
    }
    .btn-back:hover {
        border-color: var(--orange);
        color: var(--orange);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
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
        padding: 10px 24px;
        background: var(--card);
        border: 1.5px solid var(--border);
        border-radius: 40px;
        font-size: 14px;
        font-weight: 600;
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
        border-color: var(--text-2);
        color: var(--text);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
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
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220,38,38,0.3);
    }

    /* Card */
    .se-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        animation: fadeUp 0.35s 0.07s ease both;
        transition: border-color 0.2s;
    }
    .se-card:hover {
        border-color: var(--orange-soft);
    }

    .se-card-header {
        padding: 20px 24px;
        background: #fafbfd;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .se-card-header-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .se-card-header svg {
        width: 20px;
        height: 20px;
        stroke: var(--orange);
        fill: none;
    }
    .se-card-header h2 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
    }

    .se-card-body {
        padding: 32px;
    }

    /* Info alert */
    .se-info-alert {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: var(--radius-sm);
        padding: 16px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    .se-info-alert svg {
        width: 20px;
        height: 20px;
        stroke: var(--info);
        fill: none;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .se-info-alert p {
        font-size: 13px;
        color: #1e40af;
    }
    .se-info-alert strong {
        font-weight: 700;
    }

    /* Form */
    .se-form-group {
        margin-bottom: 24px;
    }

    .se-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-2);
        margin-bottom: 8px;
        letter-spacing: 0.3px;
    }

    .se-field-wrapper {
        position: relative;
    }

    .se-ico {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }
    .se-ico svg {
        width: 18px;
        height: 18px;
        stroke: var(--text-3);
        fill: none;
        transition: stroke 0.15s;
    }
    .se-field-wrapper:focus-within .se-ico svg {
        stroke: var(--orange);
    }

    .se-input, .se-select {
        width: 100%;
        padding: 12px 16px 12px 44px;
        background: var(--card);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 14px;
        color: var(--text);
        font-family: inherit;
        outline: none;
        transition: all 0.2s;
    }
    .se-input:focus, .se-select:focus {
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
    }
    .se-input.error, .se-select.error {
        border-color: var(--danger);
        background: #fef2f2;
    }

    .se-error {
        margin-top: 6px;
        font-size: 12px;
        color: var(--danger);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .se-error svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        fill: none;
    }

    /* Password section */
    .se-password-section {
        background: #fafbfd;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 20px;
        margin: 32px 0 24px;
    }

    .se-password-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
    }
    .se-password-header svg {
        width: 20px;
        height: 20px;
        stroke: var(--orange);
        fill: none;
    }
    .se-password-header h3 {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
    }
    .se-password-header p {
        font-size: 12px;
        color: var(--text-3);
        margin-top: 4px;
    }

    .se-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    @media (max-width: 580px) {
        .se-grid-2 { grid-template-columns: 1fr; }
    }

    /* Footer */
    .se-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
        margin-top: 24px;
    }
    .se-footer-left {
        font-size: 12px;
        color: var(--text-3);
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .se-footer-left svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        fill: none;
    }

    .se-footer-actions {
        display: flex;
        gap: 12px;
    }

    /* Accès refusé */
    .se-access-denied {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: var(--radius);
        padding: 32px;
        text-align: center;
        animation: fadeUp 0.35s ease both;
    }
    .se-access-denied svg {
        width: 48px;
        height: 48px;
        stroke: var(--danger);
        margin: 0 auto 16px;
    }
    .se-access-denied h2 {
        font-size: 20px;
        font-weight: 700;
        color: var(--danger);
        margin-bottom: 8px;
    }
    .se-access-denied p {
        font-size: 14px;
        color: var(--text-2);
        margin-bottom: 24px;
    }
</style>
@endsection

@section('content')
<div class="se-page">

    {{-- Vérification d'accès --}}
    @if(!auth()->user()->canManageUsers())
        <div class="se-access-denied">
            <svg viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h2>Accès refusé</h2>
            <p>Vous n'avez pas les droits pour modifier les employés.</p>
            <a href="{{ route('users.index') }}" class="btn-secondary">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la liste
            </a>
        </div>
        @php return; @endphp
    @endif

    {{-- Vérification que l'utilisateur cible peut être modifié --}}
    @if($user->isSuperAdminGlobal() && !auth()->user()->isSuperAdminGlobal())
        <div class="se-access-denied">
            <svg viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h2>Action non autorisée</h2>
            <p>Vous ne pouvez pas modifier le super administrateur global.</p>
            <a href="{{ route('users.index') }}" class="btn-secondary">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la liste
            </a>
        </div>
        @php return; @endphp
    @endif

    {{-- HEADER --}}
    <div class="se-header">
        <div class="se-header-top">
            <a href="{{ route('users.index') }}" class="btn-back">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la liste
            </a>
            <div class="se-header-l">
                <div class="se-hex">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <div class="se-title">
                        Modifier <span>{{ $user->name }}</span>
                    </div>
                    <div class="se-sub">Mettez à jour les informations de l'employé</div>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM CARD --}}
    <div class="se-card">
        <div class="se-card-header">
            <div class="se-card-header-left">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <h2>Informations personnelles</h2>
            </div>
            <span class="se-role-badge badge-current">
                Rôle actuel : 
                @php
                    $roleLabels = [
                        'super_admin_global' => 'Super Admin Global',
                        'super_admin' => 'Super Admin',
                        'admin' => 'Administrateur',
                        'manager' => 'Gérant',
                        'cashier' => 'Caissier',
                        'storekeeper' => 'Magasinier',
                    ];
                @endphp
                {{ $roleLabels[$user->role] ?? ucfirst($user->role) }}
            </span>
        </div>

        <div class="se-card-body">
            {{-- Info si l'utilisateur est un super_admin --}}
            @if($user->isSuperAdmin())
                <div class="se-info-alert">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>
                        <strong>Super Admin</strong> - Ce compte appartient au propriétaire de l'entreprise.
                        @if(auth()->user()->isSuperAdminGlobal())
                            Vous pouvez le modifier car vous êtes super admin global.
                        @else
                            Seul le super admin global peut modifier ce compte.
                        @endif
                    </p>
                </div>
            @endif

            @if($user->isAdmin())
                <div class="se-info-alert">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <p>
                        <strong>Administrateur</strong> - Ce compte peut gérer les utilisateurs.
                    </p>
                </div>
            @endif

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nom --}}
                <div class="se-form-group">
                    <label for="name" class="se-label">Nom complet</label>
                    <div class="se-field-wrapper">
                        <span class="se-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required
                               class="se-input @error('name') error @enderror"
                               placeholder="Entrez le nom complet">
                    </div>
                    @error('name')
                        <p class="se-error">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="se-form-group">
                    <label for="email" class="se-label">Adresse email</label>
                    <div class="se-field-wrapper">
                        <span class="se-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required
                               class="se-input @error('email') error @enderror"
                               placeholder="Entrez l'adresse email">
                    </div>
                    @error('email')
                        <p class="se-error">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Rôle --}}
                <div class="se-form-group">
                    <label for="role" class="se-label">Rôle</label>
                    <div class="se-field-wrapper">
                        <span class="se-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <select id="role" 
                                name="role"
                                class="se-select @error('role') error @enderror"
                                {{ $user->isSuperAdminGlobal() && !auth()->user()->isSuperAdminGlobal() ? 'disabled' : '' }}>
                            @if(auth()->user()->isSuperAdminGlobal())
                                {{-- Super admin global peut voir tous les rôles --}}
                                <option value="super_admin_global" {{ old('role', $user->role) == 'super_admin_global' ? 'selected' : '' }}>Super Admin Global</option>
                                <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Gérant</option>
                                <option value="cashier" {{ old('role', $user->role) == 'cashier' ? 'selected' : '' }}>Caissier</option>
                                <option value="storekeeper" {{ old('role', $user->role) == 'storekeeper' ? 'selected' : '' }}>Magasinier</option>
                            @elseif(auth()->user()->isSuperAdmin())
                                {{-- Super admin ne peut pas créer d'autre super admin --}}
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Gérant</option>
                                <option value="cashier" {{ old('role', $user->role) == 'cashier' ? 'selected' : '' }}>Caissier</option>
                                <option value="storekeeper" {{ old('role', $user->role) == 'storekeeper' ? 'selected' : '' }}>Magasinier</option>
                            @elseif(auth()->user()->isAdmin())
                                {{-- Admin ne peut pas créer d'admin --}}
                                <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Gérant</option>
                                <option value="cashier" {{ old('role', $user->role) == 'cashier' ? 'selected' : '' }}>Caissier</option>
                                <option value="storekeeper" {{ old('role', $user->role) == 'storekeeper' ? 'selected' : '' }}>Magasinier</option>
                            @endif
                        </select>
                    </div>
                    @error('role')
                        <p class="se-error">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Section mot de passe --}}
                <div class="se-password-section">
                    <div class="se-password-header">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <div>
                            <h3>Modifier le mot de passe</h3>
                            <p>Laissez ces champs vides pour conserver le mot de passe actuel</p>
                        </div>
                    </div>

                    <div class="se-grid-2">
                        {{-- Nouveau mot de passe --}}
                        <div class="se-form-group">
                            <label for="password" class="se-label">Nouveau mot de passe</label>
                            <div class="se-field-wrapper">
                                <span class="se-ico">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="se-input @error('password') error @enderror"
                                       placeholder="Nouveau mot de passe">
                            </div>
                            @error('password')
                                <p class="se-error">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Confirmation --}}
                        <div class="se-form-group">
                            <label for="password_confirmation" class="se-label">Confirmer le mot de passe</label>
                            <div class="se-field-wrapper">
                                <span class="se-ico">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </span>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       class="se-input"
                                       placeholder="Confirmer le mot de passe">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="se-footer">
                    <div class="se-footer-left">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Dernière modification : {{ $user->updated_at->format('d/m/Y à H:i') }}
                    </div>
                    <div class="se-footer-actions">
                        <a href="{{ route('users.index') }}" class="btn-secondary">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Annuler
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Mettre à jour
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection