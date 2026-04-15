<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Inventix - Gestion de stock')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Scripts / Styles principaux -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles personnalisés -->
    <style>
        :root {
            --orange: #f97316;
            --orange-dark: #ea580c;
            --orange-pale: #fff7ed;
            --bg: #f1f5f9;
            --card: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-secondary: #475569;
        }

        body {
            font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        /* Badges de rôle - MISE À JOUR AVEC SUPER_ADMIN_GLOBAL */
        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .role-super_admin_global {
            background-color: #8b5cf6;
            color: white;
            border: 1px solid #a78bfa;
        }

        .role-super_admin {
            background-color: #f5f3ff;
            color: #6b21a8;
            border: 1px solid #c084fc;
        }

        .role-admin {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }

        .role-manager {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #60a5fa;
        }

        .role-cashier {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #4ade80;
        }

        .role-storekeeper {
            background-color: #fff7ed;
            color: #9a3412;
            border: 1px solid #fdba74;
        }

        /* User info bar */
        .user-info-bar {
            background-color: white;
            border-bottom: 1px solid var(--border);
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .user-info-container {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .user-info-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 9999px;
            background: linear-gradient(135deg, var(--orange), var(--orange-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .user-details {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .user-name {
            font-weight: 600;
            color: var(--text);
        }

        .user-company {
            color: var(--text-secondary);
            font-size: 0.75rem;
        }

        .permission-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.125rem 0.5rem;
            background-color: #f1f5f9;
            border-radius: 9999px;
            font-size: 0.7rem;
            color: var(--text-secondary);
        }

        .permission-badge i {
            font-size: 0.7rem;
        }

        /* 👑 Badge spécial pour super_admin_global */
        .global-crown {
            color: #8b5cf6;
            margin-right: 2px;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Contenu principal -->
    <div class="min-h-screen bg-[#f1f5f9]">
        <!-- Navigation Livewire -->
        <livewire:layout.navigation />

        @auth
            <!-- Barre d'information utilisateur - MISE À JOUR -->
            <div class="user-info-bar">
                <div class="user-info-container">
                    <div class="user-info-left">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-details">
                            <span class="user-name">{{ auth()->user()->name }}</span>
                            
                            {{-- Badge avec icône spéciale pour super_admin_global --}}
                            @if(auth()->user()->isSuperAdminGlobal())
                                <span class="role-badge role-super_admin_global">
                                    <i class="bi bi-crown-fill global-crown"></i> Super Admin Global
                                </span>
                            @else
                                <span class="role-badge role-{{ auth()->user()->role }}">
                                    {{ auth()->user()->role_label }}
                                </span>
                            @endif
                            
                            @if(auth()->user()->canManageUsers())
                                <span class="permission-badge">
                                    <i class="bi bi-people"></i> Gère les utilisateurs
                                </span>
                            @endif
                            
                            @if(auth()->user()->canManageStock())
                                <span class="permission-badge">
                                    <i class="bi bi-box-seam"></i> Gère le stock
                                </span>
                            @endif
                            
                            @if(auth()->user()->canManageSales())
                                <span class="permission-badge">
                                    <i class="bi bi-cart"></i> Gère les ventes
                                </span>
                            @endif
                            
                            @if(auth()->user()->canViewReports())
                                <span class="permission-badge">
                                    <i class="bi bi-graph-up"></i> Rapports
                                </span>
                            @endif

                            {{-- Indicateur global pour super_admin_global --}}
                            @if(auth()->user()->isSuperAdminGlobal())
                                <span class="permission-badge" style="background-color:#8b5cf6; color:white;">
                                    <i class="bi bi-globe2"></i> Vue globale
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if(auth()->user()->tenant && !auth()->user()->isSuperAdminGlobal())
                        <div class="user-company">
                            <i class="bi bi-building"></i>
                            {{ auth()->user()->tenant->company_name ?? 'Mon Entreprise' }}
                        </div>
                    @endif

                    @if(auth()->user()->isSuperAdminGlobal())
                        <div class="user-company" style="color:#8b5cf6; font-weight:600;">
                            <i class="bi bi-globe2"></i> Toutes les entreprises
                        </div>
                    @endif
                </div>
            </div>
        @endauth

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="py-8">
            <!-- Messages flash -->
            @foreach (['success', 'error', 'warning', 'info'] as $msg)
                @if(session($msg))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="rounded-lg px-4 py-3 mb-4 flex items-center justify-between
                            @if($msg === 'success') bg-green-50 border border-green-200 text-green-700 @endif
                            @if($msg === 'error') bg-red-50 border border-red-200 text-red-700 @endif
                            @if($msg === 'warning') bg-yellow-50 border border-yellow-200 text-yellow-700 @endif
                            @if($msg === 'info') bg-blue-50 border border-blue-200 text-blue-700 @endif"
                            role="alert">
                            <div class="flex items-center gap-2">
                                <i class="bi 
                                    @if($msg === 'success') bi-check-circle-fill @endif
                                    @if($msg === 'error') bi-exclamation-circle-fill @endif
                                    @if($msg === 'warning') bi-exclamation-triangle-fill @endif
                                    @if($msg === 'info') bi-info-circle-fill @endif
                                "></i>
                                <span>{{ session($msg) }}</span>
                            </div>
                            <button onclick="this.parentElement.remove()" class="opacity-50 hover:opacity-100">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                @endif
            @endforeach

            @yield('content')
        </main>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @yield('scripts')
</body>
</html>