<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventix - Administration')</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Livewire (if you're using it) -->
    @livewireStyles
    
    <!-- Styles personnalisés -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --primary-light: #dbeafe;
            --primary-soft: #eff6ff;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --success: #059669;
            --success-light: #d1fae5;
            --warning: #d97706;
            --warning-light: #fef3c7;
            --danger: #dc2626;
            --danger-light: #fee2e2;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
            --border-light: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.02);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.03), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-page);
            color: var(--gray-800);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
        }

        /* =====================================================
           SIDEBAR STYLE - PROPRE & LUMINEUX
        ===================================================== */
        .admin-sidebar {
            width: 280px;
            background: var(--bg-card);
            border-right: 1px solid var(--border-light);
            padding: 32px 0;
            box-shadow: var(--shadow-md);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
            transition: all 0.2s;
            z-index: 30;
        }

        /* Logo / header sidebar */
        .sidebar-header {
            padding: 0 24px 28px;
            border-bottom: 1px solid var(--border-light);
            margin-bottom: 24px;
        }

        .logo-quincapro {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            background: var(--primary-light);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        .logo-icon i {
            font-size: 24px;
        }

        .logo-text {
            font-size: 22px;
            font-weight: 700;
            color: var(--gray-800);
            letter-spacing: -0.4px;
        }

        .logo-text span {
            color: var(--primary);
        }

        .logo-slogan {
            font-size: 11px;
            color: var(--gray-500);
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* Menu principal */
        .admin-sidebar ul {
            list-style: none;
            padding: 0 16px;
        }

        .admin-sidebar li {
            margin-bottom: 6px;
        }

        .admin-sidebar a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 18px;
            border-radius: 14px;
            color: var(--gray-600);
            font-weight: 500;
            font-size: 15px;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .admin-sidebar a i {
            width: 24px;
            font-size: 1.2rem;
            color: var(--gray-400);
            transition: color 0.2s;
        }

        .admin-sidebar a:hover {
            background: var(--primary-soft);
            color: var(--primary);
            border-color: var(--primary-light);
            transform: translateX(4px);
        }

        .admin-sidebar a:hover i {
            color: var(--primary);
        }

        .admin-sidebar .active a {
            background: var(--primary-light);
            color: var(--primary-dark);
            font-weight: 600;
            border-color: var(--primary-light);
        }

        .admin-sidebar .active a i {
            color: var(--primary);
        }

        /* section title dans menu (optionnel) */
        .menu-section {
            padding: 16px 18px 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--gray-400);
        }

        /* badge (ex: nb alertes) */
        .menu-badge {
            margin-left: auto;
            background: var(--primary-light);
            color: var(--primary);
            padding: 2px 10px;
            border-radius: 40px;
            font-size: 12px;
            font-weight: 600;
        }

        /* user info en bas (exemple) */
        .sidebar-footer {
            position: absolute;
            bottom: 20px;
            left: 24px;
            right: 24px;
            padding: 16px 0;
            border-top: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--gray-200);
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-600);
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .user-role {
            font-size: 12px;
            color: var(--gray-500);
        }

        /* =====================================================
           CONTENU PRINCIPAL
        ===================================================== */
        .admin-content {
            flex: 1;
            margin-left: 280px;
            padding: 32px 40px;
            transition: opacity 0.2s;
            min-height: 100vh;
            background: var(--bg-page);
        }

        /* Header de page */
        .content-header {
            margin-bottom: 32px;
        }

        .content-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-800);
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--gray-500);
            font-size: 14px;
        }

        .breadcrumb a {
            color: var(--gray-600);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: var(--primary);
        }

        /* =====================================================
           LOADING OVERLAY MODERNE (BLEU/GRIS)
        ===================================================== */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(5px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            transition: opacity 0.25s;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-card {
            background: var(--bg-card);
            border-radius: 28px;
            padding: 36px 48px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
            text-align: center;
            max-width: 360px;
            animation: fadeScale 0.3s;
        }

        @keyframes fadeScale {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .loading-spinner {
            width: 64px;
            height: 64px;
            margin: 0 auto 24px;
            border: 3px solid var(--gray-200);
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 8px;
        }

        .loading-subtitle {
            color: var(--gray-500);
            font-size: 14px;
        }

        .loading-progress {
            width: 200px;
            height: 4px;
            background: var(--gray-200);
            border-radius: 100px;
            margin: 24px auto 0;
            overflow: hidden;
        }

        .loading-progress-bar {
            height: 100%;
            width: 30%;
            background: linear-gradient(90deg, var(--primary), #60a5fa);
            border-radius: 100px;
            animation: progress 1.5s ease infinite;
        }

        @keyframes progress {
            0% { width: 0%; opacity: 0.8; }
            50% { width: 100%; opacity: 1; }
            100% { width: 0%; opacity: 0.8; }
        }

        body.loading {
            pointer-events: none;
            cursor: wait;
        }

        .opacity-50 {
            opacity: 0.5;
            transition: opacity 0.2s;
        }

        /* =====================================================
           UTILITAIRES RAPIDES
        ===================================================== */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-primary {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.15s;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-outline {
            background: transparent;
            border-color: var(--border-light);
            color: var(--gray-700);
        }

        .btn-outline:hover {
            background: var(--gray-100);
        }
    </style>
    
    @yield('styles')
</head>
<body x-data="{ isLoading: false }" :class="{ 'loading': isLoading }">

    <!-- Loading Overlay moderne -->
    <div x-show="isLoading" 
         x-transition:enter="transition duration-200 ease-out"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition duration-150 ease-in"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="loading-overlay"
         :class="{ 'active': isLoading }"
         style="display: none;">
        <div class="loading-card">
            <div class="loading-spinner"></div>
            <div class="loading-title">Chargement</div>
            <div class="loading-subtitle">Veuillez patienter</div>
            <div class="loading-progress">
                <div class="loading-progress-bar"></div>
            </div>
        </div>
    </div>

    <!-- Sidebar Inventix -->
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <div class="logo-quincapro">
                <div class="logo-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <div>
                    <div class="logo-text">Inventix</div>
                    <div class="logo-slogan">Gestion de stock</div>
                </div>
            </div>
        </div>

        <ul>
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Employés</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
                <a href="{{ route('products.index') }}">
                    <i class="fas fa-boxes"></i>
                    <span>Produits</span>
                    @php
                        $lowStockCount = \App\Models\Product::where('stock', '<', 5)->count(); // exemple
                    @endphp
                    @if($lowStockCount > 0)
                        <span class="menu-badge">{{ $lowStockCount }}</span>
                    @endif
                </a>
            </li>
            <li class="{{ request()->routeIs('clients.index') ? 'active' : '' }}">
                <a href="{{ route('clients.index') }}">
                    <i class="fas fa-user-tie"></i>
                    <span>Clients</span>
                </a>
            </li>
            <li class="menu-section">GESTION</li>
            <li>
                <a href="{{ route('suppliers.index') }}">
                    <i class="fas fa-truck"></i>
                    <span>Fournisseurs</span>
                </a>
            </li>
            <li>
                <a href="{{ route('categories.index') }}">
                    <i class="fas fa-tags"></i>
                    <span>Catégories</span>
                </a>
            </li>
        </ul>

        <!-- Footer sidebar avec utilisateur -->
        <div class="sidebar-footer">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="user-role">{{ auth()->user()->role ?? 'Administrateur' }}</div>
            </div>
            <a href="{{ route('logout') }}" style="color: var(--gray-400);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="admin-content" :class="{ 'opacity-50': isLoading }">
        <!-- Header de page dynamique -->
        <div class="content-header">
            <h1>@yield('page-title', 'Dashboard')</h1>
            <div class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Accueil</a>
                <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
                <span>@yield('breadcrumb', 'Dashboard')</span>
            </div>
        </div>

        <!-- Messages flash -->
        @if(session('success'))
            <div class="alert alert-success" style="background: var(--success-light); border: 1px solid #a7f3d0; color: #065f46; padding: 14px 18px; border-radius: 16px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
                <button onclick="this.parentElement.remove()" style="margin-left: auto; background: none; border: none; cursor: pointer; color: currentColor; opacity: 0.7;">✕</button>
            </div>
        @endif

        @yield('content')
    </div>

    @livewireScripts
    
    <script>
        // Helpers pour contrôler le loading
        window.showLoading = () => {
            if (window.Alpine) {
                Alpine.$data(document.body).isLoading = true;
            }
        };
        
        window.hideLoading = () => {
            if (window.Alpine) {
                Alpine.$data(document.body).isLoading = false;
            }
        };
        
        // Auto-hide loading after page load
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.hideLoading();
            }, 500);
        });

        // Intercepter les clics sur liens pour montrer le loading (optionnel)
        document.querySelectorAll('a:not([target="_blank"])').forEach(link => {
            link.addEventListener('click', (e) => {
                if (!e.ctrlKey && !e.metaKey && link.href && link.href.indexOf('#') === -1) {
                    window.showLoading();
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>