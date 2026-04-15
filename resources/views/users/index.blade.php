@extends('layouts.app')

@section('title', 'Employés — Inventix')

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
        max-width: 1280px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .se-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 16px;
        animation: fadeUp 0.35s ease both;
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

    /* Alertes */
    .se-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: var(--radius-sm);
        margin-bottom: 24px;
        animation: fadeUp 0.35s 0.07s ease both;
        border-left: 4px solid;
    }
    .se-alert svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
        flex-shrink: 0;
    }
    .se-alert-success {
        background: #f0fdf4;
        border-color: var(--success);
        color: #166534;
    }
    .se-alert-error {
        background: #fef2f2;
        border-color: var(--danger);
        color: #991b1b;
    }

    /* Card */
    .se-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        animation: fadeUp 0.35s 0.14s ease both;
        transition: border-color 0.2s;
    }
    .se-card:hover {
        border-color: var(--orange-soft);
    }

    /* Table */
    .se-table-wrap {
        overflow-x: auto;
    }
    .se-table {
        width: 100%;
        border-collapse: collapse;
    }
    .se-table thead th {
        padding: 16px 20px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-2);
        background: #f8fafc;
        border-bottom: 1px solid var(--border);
    }
    .se-table thead th:last-child {
        text-align: center;
    }
    .se-table tbody td {
        padding: 16px 20px;
        font-size: 13px;
        color: var(--text-2);
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }
    .se-table tbody td:last-child {
        text-align: center;
    }
    .se-table tbody tr {
        transition: background 0.15s;
    }
    .se-table tbody tr:hover td {
        background: var(--orange-pale);
    }

    /* Avatar */
    .se-employee {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .se-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 16px;
        box-shadow: var(--shadow-sm);
    }
    .se-employee-info {
        flex: 1;
    }
    .se-employee-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 2px;
    }
    .se-employee-id {
        font-size: 11px;
        color: var(--text-3);
    }

    /* Email */
    .se-email {
        font-size: 13px;
        color: var(--text-2);
    }

    /* Badges rôles - MIS À JOUR */
    .se-role {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .role-super_admin_global {
        background: #8b5cf6;
        color: white;
        border: 1px solid #a78bfa;
    }
    .role-super_admin {
        background: #f5f3ff;
        color: #6b21a8;
        border: 1px solid #c084fc;
    }
    .role-admin {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .role-manager {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }
    .role-cashier {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .role-storekeeper {
        background: #fff7ed;
        color: #9a3412;
        border: 1px solid #fdba74;
    }

    /* Actions */
    .se-actions {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .se-btn {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s;
        background: var(--card);
    }
    .se-btn svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        fill: none;
    }
    .se-btn:hover {
        transform: scale(1.08);
        box-shadow: var(--shadow-sm);
    }
    .se-btn-edit {
        border-color: var(--orange-soft);
        color: var(--orange);
    }
    .se-btn-edit:hover {
        background: var(--orange);
        border-color: var(--orange);
        color: #fff;
    }
    .se-btn-delete {
        border-color: #fecaca;
        color: var(--danger);
    }
    .se-btn-delete:hover {
        background: var(--danger);
        border-color: var(--danger);
        color: #fff;
    }

    /* Badge non modifiable */
    .se-readonly-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        background: #f1f5f9;
        border: 1px solid var(--border);
        border-radius: 20px;
        font-size: 11px;
        color: var(--text-3);
    }

    /* Empty state */
    .se-empty {
        padding: 64px 24px;
        text-align: center;
    }
    .se-empty-ico {
        width: 72px;
        height: 72px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .se-empty-ico svg {
        width: 32px;
        height: 32px;
        stroke: var(--text-3);
        fill: none;
    }
    .se-empty h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }
    .se-empty p {
        font-size: 14px;
        color: var(--text-2);
        margin-bottom: 20px;
    }

    /* Pagination */
    .se-pagination {
        background: #f8fafc;
        border-top: 1px solid var(--border);
        padding: 16px 24px;
    }
    .se-pagination nav { width: 100%; }
    .se-pagination .pagination {
        display: flex;
        justify-content: center;
        gap: 6px;
        list-style: none;
        flex-wrap: wrap;
    }
    .se-pagination .page-item .page-link {
        padding: 7px 14px;
        border-radius: 9px;
        border: 1.5px solid var(--border);
        color: var(--text-2);
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.18s;
        display: block;
        background: var(--card);
    }
    .se-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        border-color: var(--orange);
        color: #fff;
        box-shadow: 0 4px 12px rgba(249,115,22,0.3);
    }
    .se-pagination .page-item .page-link:hover {
        border-color: var(--orange);
        color: var(--orange);
        background: var(--orange-pale);
    }

    /* Info supplémentaire */
    .se-info-text {
        font-size: 12px;
        color: var(--text-3);
        margin-top: 4px;
    }
</style>
@endsection

@section('content')
<div class="se-page">

    {{-- HEADER --}}
    <div class="se-header">
        <div class="se-header-l">
            <div class="se-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <div class="se-title">
                    Gestion des <span>employés</span>
                </div>
                <div class="se-sub">Gérez les comptes et les permissions de vos collaborateurs</div>
            </div>
        </div>
        @if(auth()->user()->canManageUsers())
            <a href="{{ route('users.create') }}" class="btn-primary">
                <svg viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Ajouter un employé
            </a>
        @endif
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="se-alert se-alert-success">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="se-alert se-alert-error">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- TABLE CARD --}}
    <div class="se-card">
        <div class="se-table-wrap">
            <table class="se-table">
                <thead>
                    <tr>
                        <th>Employé</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="se-employee">
                                    <div class="se-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="se-employee-info">
                                        <div class="se-employee-name">{{ $user->name }}</div>
                                        <div class="se-employee-id">ID: {{ $user->id }}</div>
                                        @if($user->owner_id && $user->owner_id !== $user->id)
                                            <div class="se-info-text">
                                                <i class="bi bi-person-workspace"></i> Employé de #{{ $user->owner_id }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="se-email">{{ $user->email }}</div>
                            </td>
                            <td>
                                @php
                                    $roleClass = match($user->role) {
                                        'super_admin_global' => 'role-super_admin_global',
                                        'super_admin' => 'role-super_admin',
                                        'admin' => 'role-admin',
                                        'manager' => 'role-manager',
                                        'cashier' => 'role-cashier',
                                        'storekeeper' => 'role-storekeeper',
                                        default => 'role-user'
                                    };
                                    
                                    $roleLabel = match($user->role) {
                                        'super_admin_global' => 'Super Admin Global',
                                        'super_admin' => 'Super Admin',
                                        'admin' => 'Administrateur',
                                        'manager' => 'Gérant',
                                        'cashier' => 'Caissier',
                                        'storekeeper' => 'Magasinier',
                                        default => ucfirst($user->role)
                                    };
                                @endphp
                                <span class="se-role {{ $roleClass }}">
                                    @if($user->role === 'super_admin_global')
                                        <i class="bi bi-crown-fill" style="margin-right: 2px;"></i>
                                    @endif
                                    {{ $roleLabel }}
                                </span>
                                @if($user->can_manage_users)
                                    <div class="se-info-text" style="margin-top: 4px;">
                                        <i class="bi bi-shield-check"></i> Gère les utilisateurs
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if(auth()->user()->canManageUsers())
                                    <div class="se-actions">
                                        {{-- Ne pas afficher les actions si c'est super_admin_global et que l'utilisateur n'est pas super_admin_global --}}
                                        @if($user->role !== 'super_admin_global' || auth()->user()->isSuperAdminGlobal())
                                            <a href="{{ route('users.edit', $user->id) }}" class="se-btn se-btn-edit" title="Éditer">
                                                <svg viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            
                                            @if(!$user->isSuperAdminGlobal() && $user->id !== auth()->id())
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" 
                                                      onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cet employé ?');" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="se-btn se-btn-delete" title="Supprimer">
                                                        <svg viewBox="0 0 24 24" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="se-readonly-badge">
                                                <i class="bi bi-lock"></i> Non modifiable
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="se-empty">
                                    <div class="se-empty-ico">
                                        <svg viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <h3>Aucun employé trouvé</h3>
                                    <p>Commencez par ajouter votre premier employé</p>
                                    @if(auth()->user()->canManageUsers())
                                        <a href="{{ route('users.create') }}" class="btn-primary" style="display: inline-flex;">
                                            <svg viewBox="0 0 24 24" stroke-width="2.5" style="width:16px;height:16px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                            </svg>
                                            Ajouter un employé
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($users->hasPages())
            <div class="se-pagination">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection