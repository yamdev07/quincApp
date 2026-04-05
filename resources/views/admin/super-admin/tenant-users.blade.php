@extends('layouts.app')

@section('title', $tenant->company_name . ' — Gestion des employés')

@section('styles')
<style>
    :root {
        /* Orange */
        --orange:        #f97316;
        --orange-dark:   #ea580c;
        --orange-pale:   #fff7ed;
        --orange-soft:   #fed7aa;
        
        /* Blanc et gris */
        --white:         #ffffff;
        --gray-50:       #f9fafb;
        --gray-100:      #f3f4f6;
        --gray-200:      #e5e7eb;
        --gray-300:      #d1d5db;
        --gray-400:      #9ca3af;
        --gray-500:      #6b7280;
        --gray-600:      #4b5563;
        --gray-700:      #374151;
        --gray-800:      #1f2937;
        --gray-900:      #111827;
        
        /* Fond et bordures */
        --bg:            var(--gray-50);
        --card:          var(--white);
        --border:        var(--gray-200);
        --border-light:  var(--gray-100);
        
        /* Textes */
        --text:          var(--gray-900);
        --text-2:        var(--gray-600);
        --text-3:        var(--gray-400);
        
        /* États */
        --success:       var(--orange);
        --danger:        var(--gray-600);
        --info:          var(--gray-500);
        
        /* Ombres */
        --shadow-sm:     0 1px 3px rgba(0,0,0,0.05);
        --shadow-md:     0 4px 16px rgba(0,0,0,0.05);
        --shadow-orange: 0 8px 24px rgba(249,115,22,0.15);
        
        /* Arrondis */
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
    .st-page {
        max-width: 1280px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
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
        flex-shrink: 0;
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
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .st-title span {
        color: var(--orange);
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

    /* Card */
    .st-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: border-color 0.2s;
        margin-bottom: 24px;
    }
    .st-card:hover {
        border-color: var(--orange);
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
    .st-card-header .st-badge {
        margin-left: auto;
    }

    .st-card-body {
        padding: 24px;
    }

    /* Badge */
    .st-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .st-badge-success {
        background: var(--orange-pale);
        color: var(--orange-dark);
    }
    .st-badge-danger {
        background: var(--gray-100);
        color: var(--gray-600);
    }
    .st-badge-warning {
        background: #fef3c7;
        color: #92400e;
    }
    .st-badge-info {
        background: #dbeafe;
        color: #1e40af;
    }

    /* Table */
    .st-table {
        width: 100%;
        border-collapse: collapse;
    }
    .st-table th {
        padding: 12px 8px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-3);
        border-bottom: 1px solid var(--border);
    }
    .st-table td {
        padding: 16px 8px;
        font-size: 14px;
        color: var(--text-2);
        border-bottom: 1px solid var(--border-light);
    }
    .st-table tr:last-child td {
        border-bottom: none;
    }

    /* Avatar */
    .st-avatar-sm {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 600;
        font-size: 14px;
    }

    /* Actions */
    .st-actions {
        display: flex;
        gap: 8px;
    }
    .st-action-btn {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }
    .st-action-edit {
        background: #fef3c7;
        color: #92400e;
        border: none;
        cursor: pointer;
    }
    .st-action-edit:hover {
        background: #fde68a;
    }
    .st-action-delete {
        background: #fee2e2;
        color: #991b1b;
        border: none;
        cursor: pointer;
    }
    .st-action-delete:hover {
        background: #fecaca;
    }

    /* Empty state */
    .st-empty {
        padding: 48px 24px;
        text-align: center;
    }
    .st-empty-ico {
        width: 64px;
        height: 64px;
        background: var(--gray-100);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .st-empty-ico svg {
        width: 28px;
        height: 28px;
        stroke: var(--text-3);
        fill: none;
    }
    .st-empty p {
        font-size: 14px;
        color: var(--text-2);
        margin-bottom: 16px;
    }

    /* Back link */
    .st-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 24px;
        font-size: 13px;
        color: var(--text-2);
        text-decoration: none;
    }
    .st-back svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
    }
    .st-back:hover {
        color: var(--orange);
    }

    /* Info tenant */
    .st-tenant-info {
        background: var(--orange-pale);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .st-tenant-info strong {
        color: var(--orange-dark);
    }

    /* Security note */
    .st-security {
        margin-top: 40px;
        text-align: center;
        font-size: 12px;
        color: var(--text-3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 16px 0;
        border-top: 1px solid var(--border);
    }
    .st-security svg {
        width: 14px;
        height: 14px;
        stroke: var(--orange);
    }
</style>
@endsection

@section('content')
<div class="st-page">

    {{-- BACK LINK --}}
    <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="st-back">
        <svg viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Retour à la quincaillerie
    </a>

    {{-- HEADER --}}
    <div class="st-header">
        <div class="st-header-l">
            <div class="st-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <div class="st-title">
                    Employés · {{ $tenant->company_name }}
                </div>
                <div class="st-sub">Gérez les comptes et les permissions de vos collaborateurs</div>
            </div>
        </div>
        <a href="{{ route('super-admin.tenants.users.create', $tenant) }}" class="btn-primary">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            Nouvel employé
        </a>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="st-alert st-alert-success">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="st-alert st-alert-error">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- INFO TENANT --}}
    <div class="st-tenant-info">
        <div>
            <strong>{{ $tenant->company_name }}</strong><br>
            <span style="font-size: 13px;">{{ $tenant->email ?? 'Email non renseigné' }}</span>
        </div>
        <div>
            <span class="st-badge" style="background: var(--orange-pale); color: var(--orange);">
                {{ $users->count() }} employé(s)
            </span>
        </div>
    </div>

    {{-- LISTE DES EMPLOYÉS --}}
    <div class="st-card">
        <div class="st-card-header">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <h2>Liste des employés</h2>
        </div>
        <div class="st-card-body">
            @if($users->isEmpty())
                <div class="st-empty">
                    <div class="st-empty-ico">
                        <svg viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <p>Aucun employé dans cette quincaillerie</p>
                    <a href="{{ route('super-admin.tenants.users.create', $tenant) }}" class="btn-primary">
                        Ajouter le premier employé
                    </a>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table class="st-table">
                        <thead>
                            <tr>
                                <th>EMPLOYÉ</th>
                                <th>EMAIL</th>
                                <th>RÔLE</th>
                                <th>CRÉÉ PAR</th>
                                <th>DATE</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td style="font-weight: 500;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div class="st-avatar-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            {{ $user->name }}
                                            @if($user->id == $tenant->owner_id)
                                                <span class="st-badge st-badge-warning" style="margin-left: 8px;">Propriétaire</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @switch($user->role)
                                        @case('super_admin_global')
                                            <span class="st-badge st-badge-danger">Super Admin Global</span>
                                            @break
                                        @case('super_admin')
                                            <span class="st-badge st-badge-danger">Super Admin</span>
                                            @break
                                        @case('admin')
                                            <span class="st-badge st-badge-info">Admin</span>
                                            @break
                                        @case('manager')
                                            <span class="st-badge" style="background: #e0f2fe; color: #0c4a6e;">Manager</span>
                                            @break
                                        @case('cashier')
                                            <span class="st-badge st-badge-success">Caissier</span>
                                            @break
                                        @case('storekeeper')
                                            <span class="st-badge" style="background: #f3f4f6; color: #374151;">Magasinier</span>
                                            @break
                                        @default
                                            <span class="st-badge" style="background: var(--gray-100); color: var(--gray-600);">{{ $user->role }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $user->owner->name ?? 'Système' }}</td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="st-actions">
                                        <a href="{{ route('super-admin.tenants.users.edit', [$tenant, $user]) }}" 
                                           class="st-action-btn st-action-edit">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                                <path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4Z" />
                                            </svg>
                                            Modifier
                                        </a>
                                        @if($user->id != $tenant->owner_id)
                                            <form action="{{ route('super-admin.tenants.users.destroy', [$tenant, $user]) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Supprimer {{ addslashes($user->name) }} ? Cette action est irréversible.')"
                                                  style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="st-action-btn st-action-delete" style="border: none; cursor: pointer;">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" />
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- NOTE DE SÉCURITÉ --}}
    <div class="st-security">
        <svg viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        Informations confidentielles · Accès réservé aux super administrateurs
    </div>
</div>
@endsection