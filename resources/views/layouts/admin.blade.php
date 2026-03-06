<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Livewire (if you're using it) -->
    @livewireStyles
</head>
<body x-data="{ isLoading: false }" :class="{ 'loading': isLoading }">
    <!-- Loading Overlay -->
    <div x-show="isLoading" 
         x-transition:enter="transition-opacity duration-300"
         x-transition:leave="transition-opacity duration-300"
         class="loading-overlay"
         :class="{ 'active': isLoading }"
         style="display: none;">
        <div class="text-center">
            <div class="spinner"></div>
            <p class="text-white mt-2">Chargement...</p>
        </div>
    </div>

    <div class="admin-sidebar">
        <ul>
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('users.index') }}">Gestion des employés</a></li>
            <li><a href="{{ route('products.index') }}">Produits</a></li>
            <li><a href="{{ route('clients.index') }}">Clients</a></li>
        </ul>
    </div>

    <div class="admin-content" :class="{ 'opacity-50': isLoading }">
        @yield('content')
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- Add CSS for loading overlay -->
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
        }
        
        .loading-overlay.active {
            display: flex;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        body.loading {
            pointer-events: none;
            cursor: wait;
        }
        
        .opacity-50 {
            opacity: 0.5;
            transition: opacity 0.3s ease;
        }
    </style>
    
    @livewireScripts
    @yield('scripts')
    
    <script>
        // Helper functions to control loading
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
    </script>
</body>
</html>