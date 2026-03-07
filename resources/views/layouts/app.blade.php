<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'QuincaPro - Gestion Quincaillerie')</title>

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
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Contenu principal -->
    <div class="min-h-screen bg-[#f1f5f9]">
        <!-- Navigation Livewire -->
        <livewire:layout.navigation />

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