<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ovitech Smart Farm') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind & Alpine & Icons -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>

    @stack('styles')
</head>
<body class="font-sans antialiased text-neutral-900 bg-bg-light dark:bg-bg-dark dark:text-neutral-50 selection:bg-primary-500/30 selection:text-primary-900 dark:selection:text-primary-100 overflow-x-hidden">
    
    <div class="min-h-screen flex w-full">
        <!-- Sidebar Navigation -->
        <x-nav.sidebar>
            <!-- Slot content will be injected by pages -->
            {{ $sidebar ?? '' }}
        </x-nav.sidebar>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300 ml-24 lg:ml-72" id="main-content">
            <!-- Topbar -->
            <x-nav.topbar>
                <x-slot name="left">
                    {{ $topbarLeft ?? '' }}
                </x-slot>
                <x-slot name="right">
                    {{ $topbarRight ?? '' }}
                </x-slot>
            </x-nav.topbar>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full max-w-screen-2xl mx-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons();
        });
    </script>
    
    @stack('scripts')
</body>
</html>
