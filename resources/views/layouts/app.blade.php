<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="oviApp()" :class="{ 'dark': darkMode }" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="OviTech Smart Farm Enterprise — AI-powered livestock management platform">
    <meta name="theme-color" content="#22c55e">
    <link rel="manifest" href="/manifest.json">
    <title>@yield('title', 'Dashboard') — OviTech Enterprise</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;0,14..32,900&family=Cairo:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- Icons --}}
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.js" defer></script>

    {{-- Charts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js" defer></script>

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Page-specific head --}}
    @stack('head')

    <script>
        /* Instant dark mode init — no FOUC */
        (function () {
            var d = localStorage.getItem('ovi_dark'); if (d === 'true' || (!d && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>

<body class="flex h-screen overflow-hidden antialiased" x-effect="
        if (darkMode) {
            document.documentElement.style.setProperty('--page-bg', '#0A1409');
            document.documentElement.style.setProperty('--page-text', '#d1fae5');
            document.body.style.backgroundColor = '#0A1409';
            document.body.style.color = '#d1fae5';
        } else {
            document.documentElement.style.setProperty('--page-bg', '#F0F5ED');
            document.documentElement.style.setProperty('--page-text', '#1a2e1d');
            document.body.style.backgroundColor = '#F0F5ED';
            document.body.style.color = '#1a2e1d';
        }
    " @keydown.escape="searchOpen=false; commandOpen=false;">



    {{-- ═══════════════════════════════════════════════
    SIDEBAR
    ═══════════════════════════════════════════════ --}}
    <aside
        class="agri-sidebar h-screen flex-shrink-0 flex transition-all duration-300 ease-in-out fixed z-50 md:relative md:translate-x-0 inset-y-0 start-0"
        :class="[
               sidebarOpen ? 'translate-x-0' : '{{ app()->getLocale() == 'ar' ? 'translate-x-full' : '-translate-x-full' }} md:translate-x-0',
               sidebarCollapsed ? 'w-[70px]' : 'w-[350px]'
           ]">

        {{-- ════════════════════════════════════
        COLUMN 1: PRIMARY STRIP (70px) - ONLY VISIBLE WHEN COLLAPSED
        ════════════════════════════════════ --}}
        <div class="agri-primary-strip w-[70px] h-full flex flex-col items-center py-4 flex-shrink-0 relative z-20"
            x-show="sidebarCollapsed" x-transition:enter="transition-all ease-out duration-300"
            x-transition:enter-start="{{ app()->getLocale() == 'ar' ? 'translate-x-full' : '-translate-x-full' }} opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition-all ease-in duration-200"
            x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="{{ app()->getLocale() == 'ar' ? 'translate-x-full' : '-translate-x-full' }} opacity-0"
            x-cloak>

            {{-- Expand Sidebar Button --}}
            <button @click="sidebarCollapsed = false"
                class="w-11 h-11 mb-8 rounded-xl flex items-center justify-center bg-gradient-to-br from-green-500/20 to-green-500/5 border border-green-500/30 shadow-[0_0_15px_rgba(16,185,129,0.2)] hover:shadow-[0_0_25px_rgba(16,185,129,0.4)] transition-all group">
                <i data-lucide="leaf"
                    class="w-5 h-5 text-green-400 drop-shadow-[0_0_5px_rgba(16,185,129,0.8)] group-hover:scale-110 transition-transform"></i>
            </button>

            {{-- Primary Nav Icons --}}
            <nav class="flex-1 w-full flex flex-col items-center space-y-3">
                <a href="{{ route('dashboard') }}" class="agri-primary-icon w-10 h-10 flex items-center justify-center">
                    <i data-lucide="home" class="w-[18px] h-[18px]"></i>
                </a>
                <div
                    class="agri-primary-icon agri-primary-icon-active w-10 h-10 flex items-center justify-center relative cursor-pointer">
                    <i data-lucide="layout-grid" class="w-[18px] h-[18px]"></i>
                </div>
                <div class="agri-primary-icon w-10 h-10 flex items-center justify-center relative cursor-pointer group">
                    <i data-lucide="bell"
                        class="w-[18px] h-[18px] group-hover:animate-[wiggle_1s_ease-in-out_infinite]"></i>
                    <span
                        class="absolute top-2 right-2.5 w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse shadow-[0_0_5px_rgba(16,185,129,0.8)]"></span>
                </div>
                <div class="agri-primary-icon w-10 h-10 flex items-center justify-center cursor-pointer">
                    <i data-lucide="bar-chart-2" class="w-[18px] h-[18px]"></i>
                </div>
                <div class="agri-primary-icon w-10 h-10 flex items-center justify-center cursor-pointer">
                    <i data-lucide="package" class="w-[18px] h-[18px]"></i>
                </div>
                <div class="agri-primary-icon w-10 h-10 flex items-center justify-center cursor-pointer">
                    <i data-lucide="calendar" class="w-[18px] h-[18px]"></i>
                </div>
                <div class="agri-primary-icon w-10 h-10 flex items-center justify-center cursor-pointer">
                    <i data-lucide="message-square" class="w-[18px] h-[18px]"></i>
                </div>
            </nav>
        </div>

        {{-- ════════════════════════════════════
        COLUMN 2: SECONDARY PANEL (280px)
        ════════════════════════════════════ --}}
        <div class="agri-panel w-[280px] h-full flex flex-col flex-shrink-0 relative overflow-hidden transition-all duration-300 border-r border-white/5"
            x-show="!sidebarCollapsed" x-transition:enter="transition-all ease-out duration-300"
            x-transition:enter-start="{{ app()->getLocale() == 'ar' ? 'translate-x-full' : '-translate-x-full' }} opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition-all ease-in duration-200"
            x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="{{ app()->getLocale() == 'ar' ? 'translate-x-full' : '-translate-x-full' }} opacity-0"
            x-cloak>

            {{-- Mobile Close --}}
            <button @click="sidebarOpen=false"
                class="md:hidden absolute top-4 end-4 p-1.5 rounded-lg text-stone-400 hover:text-white hover:bg-white/10 z-50">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>

            {{-- ZONE 1: Brand --}}
            <div class="p-5 pb-4 flex items-center justify-between flex-shrink-0 border-b border-white/[0.08]">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                        style="background: linear-gradient(135deg, rgba(74,222,128,0.25), rgba(22,163,74,0.1)); border: 1px solid rgba(74,222,128,0.3);">
                        <i data-lucide="sprout" class="w-4 h-4" style="color: #4ade80;"></i>
                    </div>
                    <div>
                        <h1 class="font-black text-[15px] tracking-tight" style="color: #ffffff;">
                            OviTech
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md ml-1"
                                style="background: rgba(74,222,128,0.12); color: #4ade80; border: 1px solid rgba(74,222,128,0.2);">{{ __('FARM') }}</span>
                        </h1>
                        <p class="text-[10px] mt-0.5" style="color: rgba(255,255,255,0.35);">{{ __('Smart Agriculture Platform') }}
                        </p>
                    </div>
                </div>
                <button @click="sidebarCollapsed = true" class="p-1.5 rounded-lg transition-all"
                    style="color: rgba(255,255,255,0.35);"
                    onmouseover="this.style.background='rgba(255,255,255,0.08)'; this.style.color='white';"
                    onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.35)';"
                    title="Collapse Sidebar">
                    <i data-lucide="panel-left-close" class="w-[18px] h-[18px]"></i>
                </button>
            </div>

            {{-- ZONE 3: Navigation --}}
            <nav class="flex-1 overflow-y-auto custom-scroll px-3 pb-4 pt-3 space-y-1">

                {{-- Dashboard Hero Button --}}
                <a href="{{ route('dashboard') }}"
                    class="flex items-center justify-between px-3 py-2.5 rounded-xl transition-all group relative overflow-hidden mb-3"
                    style="background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.25);">
                    <div class="flex items-center gap-2.5 relative z-10">
                        <i data-lucide="layout-dashboard" class="w-4 h-4" style="color: #4ade80;"></i>
                        <span class="font-bold text-[13px]" style="color: #4ade80;">{{ __('Dashboard') }}</span>
                    </div>
                    <i data-lucide="{{ app()->getLocale() == 'ar' ? 'chevron-left' : 'chevron-right' }}" class="w-3.5 h-3.5" style="color: rgba(74,222,128,0.5);"></i>
                </a>

                <div class="pt-2">
                    <div class="section-label px-1 mb-2">🌿 {{ __("Platform Modules") }}</div>
                    <div class="space-y-1.5">
                        {{-- 1. Farm Operations --}}
                        <x-nav.sidebar-group icon="map" label="{{ __('Farm Operations') }}" color="emerald"
                            :active="request()->routeIs('farm.*')" count="6">
                            <x-nav.sidebar-link route="farm.dashboard" icon="layout-dashboard" label="{{ __('Farm Dashboard') }}"
                                :active="request()->routeIs('farm.dashboard')" />
                            <x-nav.sidebar-link route="farm.zones.index" icon="map-pin" label="{{ __('Farms & Zones') }}"
                                :active="request()->routeIs('farm.zones.*')" />
                            <x-nav.sidebar-link route="farm.barns.index" icon="warehouse" label="{{ __('Barns & Capacity') }}"
                                :active="request()->routeIs('farm.barns.*')" />
                            <x-nav.sidebar-link route="farm.equipment.index" icon="tractor" label="{{ __('Equipment & Fleet') }}"
                                :active="request()->routeIs('farm.equipment.*')" />
                            <x-nav.sidebar-link route="water.index" icon="droplets" label="{{ __('Water Management') }}" />
                            <x-nav.sidebar-link route="energy.index" icon="zap" label="{{ __('Energy Management') }}" />
                        </x-nav.sidebar-group>

                        {{-- 2. Livestock & Health --}}
                        <x-nav.sidebar-group icon="paw-print" label="{{ __('Livestock & Health') }}" color="rose"
                            :active="request()->routeIs('livestock.*', 'health.*', 'feed.*', 'breeding-cycles.*')"
                            count="4">
                            <x-nav.sidebar-link route="livestock.index" icon="sheep" label="{{ __('Livestock Directory') }}"
                                :active="request()->routeIs('livestock.*')" />
                            <x-nav.sidebar-link route="health.dashboard" icon="heart-pulse" label="{{ __('Health Dashboard') }}"
                                :active="request()->routeIs('health.*')" badge="2" />
                            <x-nav.sidebar-link route="feed.dashboard" icon="wheat" label="{{ __('Feed & Nutrition') }}"
                                :active="request()->routeIs('feed.*')" />
                            <x-nav.sidebar-link route="breeding-cycles.index" icon="dna" label="{{ __('Breeding & Genetics') }}"
                                :active="request()->routeIs('breeding-cycles.*')" />
                        </x-nav.sidebar-group>

                        {{-- 3. Logistics & Supply --}}
                        <x-nav.sidebar-group icon="truck" label="{{ __('Logistics & Supply') }}" color="emerald"
                            :active="request()->routeIs('transportation.*', 'customers.*')" count="2">
                            <x-nav.sidebar-link route="transportation.index" icon="route" label="{{ __('Transportation') }}"
                                :active="request()->routeIs('transportation.*')" />
                            <x-nav.sidebar-link route="customers.index" icon="users-round" label="{{ __('Customers') }}"
                                :active="request()->routeIs('customers.*')" />
                        </x-nav.sidebar-group>
                    </div>
                </div>

                <div class="pt-3">
                    <div class="section-label px-1 mb-2">⚡ {{ __("Intelligence & Settings") }}</div>
                    <div class="space-y-1.5">
                        {{-- 4. Smart Tech & AI --}}
                        <x-nav.sidebar-group icon="cpu" label="{{ __('Smart Tech & AI') }}" color="purple"
                            :active="request()->routeIs('iot.*', 'ai.*')" count="2">
                            <x-nav.sidebar-link route="iot.dashboard" icon="radio" label="{{ __('IoT Sensors') }}"
                                :active="request()->routeIs('iot.*')" />
                            <x-nav.sidebar-link route="ai.dashboard" icon="brain-circuit" label="{{ __('AI Hub') }}"
                                :active="request()->routeIs('ai.*')" />
                        </x-nav.sidebar-group>

                        {{-- 5. Enterprise --}}
                        <x-nav.sidebar-group icon="briefcase" label="{{ __('Enterprise') }}" color="amber"
                            :active="request()->routeIs('finance.*', 'marketplace.*', 'reports.*')" count="3">
                            <x-nav.sidebar-link route="finance.dashboard" icon="trending-up" label="{{ __('Finance') }}"
                                :active="request()->routeIs('finance.*')" />
                            <x-nav.sidebar-link route="marketplace.index" icon="store" label="{{ __('Marketplace') }}"
                                :active="request()->routeIs('marketplace.*')" />
                            <x-nav.sidebar-link route="reports.index" icon="file-bar-chart" label="{{ __('Reports & Analytics') }}"
                                :active="request()->routeIs('reports.*')" />
                        </x-nav.sidebar-group>

                        {{-- 6. System --}}
                        <x-nav.sidebar-group icon="settings" label="{{ __('System') }}" color="slate"
                            :active="request()->routeIs('staff.*', 'settings.*', 'admin.*')" count="2">
                            <x-nav.sidebar-link route="settings.index" icon="settings-2" label="{{ __('Settings') }}"
                                :active="request()->routeIs('settings.*')" />
                            <x-nav.sidebar-link route="admin.dashboard" icon="shield-check" label="{{ __('Admin') }}"
                                :active="request()->routeIs('admin.*')" color="red" />
                        </x-nav.sidebar-group>
                    </div>
                </div>

            </nav>

            {{-- ZONE 4: User Card --}}
            <div class="flex-shrink-0 p-3 mt-auto" style="border-top: 1px solid rgba(255,255,255,0.07);">
                <div class="rounded-xl p-3 flex items-center justify-between cursor-pointer group transition-all"
                    style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07);"
                    onmouseover="this.style.background='rgba(255,255,255,0.08)';"
                    onmouseout="this.style.background='rgba(255,255,255,0.04)';" x-data="{ userMenu: false }"
                    @click.away="userMenu = false">
                    <div class="flex items-center gap-3 w-full" @click="userMenu = !userMenu">
                        <div class="relative w-9 h-9 rounded-xl flex-shrink-0"
                            style="box-shadow: 0 0 0 2px rgba(74,222,128,0.3);">
                            <img src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=Farm&background=166534&color=fff&bold=true' }}"
                                class="w-full h-full rounded-xl object-cover">
                            <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full"
                                style="background: #22c55e; border: 2px solid rgba(15,45,20,0.8);"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[13px] font-semibold truncate" style="color: #ffffff;">
                                {{ auth()->user()->name ?? 'Farm Admin' }}
                            </div>
                            <div class="text-[10px] truncate" style="color: rgba(255,255,255,0.35);">System
                                Administrator</div>
                        </div>
                        <i data-lucide="chevrons-up-down" class="w-3.5 h-3.5 flex-shrink-0"
                            style="color: rgba(255,255,255,0.3);"></i>
                    </div>

                    {{-- User Menu Dropdown --}}
                    <div x-show="userMenu" x-cloak x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="absolute bottom-16 left-3 right-3 backdrop-blur-xl rounded-xl shadow-2xl p-2 z-50"
                        style="background: rgba(10,25,13,0.95); border: 1px solid rgba(34,197,94,0.12);">
                        <a href="{{ route('settings.index') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors"
                            style="color: rgba(255,255,255,0.65); font-size: 0.8rem;"
                            onmouseover="this.style.background='rgba(255,255,255,0.05)'; this.style.color='white';"
                            onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.65)';">
                            <i data-lucide="settings" class="w-4 h-4"></i> Preferences
                        </a>
                        <div class="h-px my-1 mx-2" style="background: rgba(255,255,255,0.06);"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2 text-left px-3 py-2 rounded-lg transition-colors"
                                style="color: #f87171; font-size: 0.8rem;"
                                onmouseover="this.style.background='rgba(239,68,68,0.08)';"
                                onmouseout="this.style.background='transparent';">
                                <i data-lucide="log-out" class="w-4 h-4"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        </div>
    </aside>

    {{-- Mobile Sidebar Overlay --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false"
        class="fixed inset-0 z-40 bg-stone-900/50 backdrop-blur-sm md:hidden"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    {{-- ═══════════════════════════════════════════════
    MAIN CONTENT AREA
    ═══════════════════════════════════════════════ --}}
    <main class="flex-1 flex flex-col h-full overflow-hidden min-w-0">

        {{-- TOP HEADER — Glass, light/dark aware --}}
        <header class="glass-header h-[68px] flex items-center gap-3 px-4 sm:px-6 flex-shrink-0 z-40">

            {{-- Mobile menu button --}}
            <button @click="sidebarOpen=true" class="md:hidden flex-shrink-0 p-2 rounded-xl transition-colors"
                style="color: #4d7c5a;" onmouseover="this.style.background='rgba(22,163,74,0.1)';"
                onmouseout="this.style.background='transparent';">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>

            {{-- Smart Search --}}
            <div class="flex-1 max-w-lg hidden sm:block">
                <button @click="searchOpen=true"
                    class="w-full flex items-center gap-3 px-4 py-2 rounded-xl text-sm transition-all duration-200 group"
                    :style="darkMode
                        ? 'background: rgba(22,101,52,0.15); border: 1px solid rgba(34,197,94,0.12); color: rgba(134,239,172,0.6);'
                        : 'background: rgba(22,163,74,0.06); border: 1px solid rgba(22,163,74,0.15); color: #6b9e7a;'">
                    <i data-lucide="search" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="flex-1 text-start text-[13px]">{{ __('Search animals, RFID, invoices...') }}</span>
                    <kbd class="kbd hidden lg:flex">⌘K</kbd>
                </button>
            </div>

            {{-- Right Controls --}}
            <div class="flex items-center gap-1.5 ml-auto">

                {{-- Mobile search --}}
                <button @click="searchOpen=true" class="sm:hidden flex-shrink-0 p-2 rounded-xl transition-colors"
                    :class="darkMode ? 'text-green-400 hover:bg-green-900/30' : 'text-green-700 hover:bg-green-100'">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>

                {{-- Network Status Pill --}}
                <div x-data="{ offline: false }" x-init="
                        offline = !navigator.onLine;
                        window.addEventListener('offline', () => offline = true);
                        window.addEventListener('online', () => offline = false);
                     "
                    class="flex-shrink-0 flex items-center gap-1.5 px-2.5 py-1.5 rounded-full border transition-all duration-300"
                    :style="offline
                        ? 'background: rgba(245,158,11,0.1); border-color: rgba(245,158,11,0.3);'
                        : (darkMode ? 'background: rgba(22,163,74,0.1); border-color: rgba(34,197,94,0.25);' : 'background: rgba(22,163,74,0.08); border-color: rgba(22,163,74,0.25);')">
                    <span class="relative flex h-2 w-2 flex-shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                            :class="offline ? 'bg-amber-400' : 'bg-green-400'"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2"
                            :class="offline ? 'bg-amber-500' : 'bg-green-500'"></span>
                    </span>
                    <span class="text-[10px] font-bold tracking-widest uppercase"
                        :class="offline ? 'text-amber-500' : (darkMode ? 'text-green-400' : 'text-green-700')"
                        x-text="offline ? '{{ __('Offline') }}' : '{{ __('Online') }}'">
                    </span>
                </div>

                {{-- Live Clock --}}
                <div class="flex-shrink-0 flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-mono font-semibold transition-colors"
                    :class="darkMode ? 'text-green-400/70 bg-green-900/20' : 'text-green-800 bg-green-50 border border-green-100'"
                    x-data="liveClock()">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    <span x-text="time" class="tracking-tight tabular-nums"></span>
                </div>

                {{-- Language Switcher --}}
                <div class="relative flex-shrink-0" x-data="{ langOpen: false, langTimer: null }" @mouseenter="clearTimeout(langTimer); langOpen = true" @mouseleave="langTimer = setTimeout(() => langOpen = false, 250)" @click.away="langOpen=false">
                    <button @click="langOpen=!langOpen" class="flex items-center gap-1.5 p-2 rounded-xl transition-all duration-200 uppercase font-bold text-xs"
                        :class="darkMode ? 'text-green-400/80 hover:text-green-400 hover:bg-green-900/20' : 'text-green-700 hover:bg-green-100'">
                        {{ app()->getLocale() }}
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </button>
                    <div x-show="langOpen" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        class="absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} top-full mt-2 w-36 glass-panel shadow-2xl z-50 overflow-hidden flex flex-col py-1"
                        :style="{ background: darkMode ? 'rgba(10,25,13,0.95)' : '#ffffff', border: darkMode ? '1px solid rgba(34,197,94,0.12)' : '1px solid rgba(22,163,74,0.2)' }" dir="ltr">
                        <a href="{{ route('lang.switch', 'en') }}" class="flex items-center gap-2 px-4 py-2 text-sm transition-colors" :class="darkMode ? 'text-stone-300 hover:bg-green-500/20 hover:text-white' : 'text-stone-700 hover:bg-green-50 hover:text-green-800'">
                            <span>🇬🇧</span> English
                        </a>
                        <a href="{{ route('lang.switch', 'fr') }}" class="flex items-center gap-2 px-4 py-2 text-sm transition-colors" :class="darkMode ? 'text-stone-300 hover:bg-green-500/20 hover:text-white' : 'text-stone-700 hover:bg-green-50 hover:text-green-800'">
                            <span>🇫🇷</span> Français
                        </a>
                        <a href="{{ route('lang.switch', 'ar') }}" class="flex items-center gap-2 px-4 py-2 text-sm transition-colors" :class="darkMode ? 'text-stone-300 hover:bg-green-500/20 hover:text-white' : 'text-stone-700 hover:bg-green-50 hover:text-green-800'">
                            <span class="mr-1">🇸🇦</span> العربية
                        </a>
                    </div>
                </div>

                {{-- Theme Toggle --}}
                <button @click="darkMode = !darkMode" class="flex-shrink-0 p-2 rounded-xl transition-all duration-200"
                    :class="darkMode ? 'text-amber-400 hover:bg-amber-400/10' : 'text-green-700 hover:bg-green-100'"
                    :title="darkMode ? 'Switch to Light Mode (النهار)' : 'Switch to Dark Mode (الليل)'">
                    <i x-show="!darkMode" data-lucide="moon" class="w-[18px] h-[18px]"></i>
                    <i x-show="darkMode" x-cloak data-lucide="sun" class="w-[18px] h-[18px]"></i>
                </button>

                {{-- Notifications --}}
                <div class="relative flex-shrink-0" x-data="{ notifOpen: false }" @click.away="notifOpen=false">
                    <button @click="notifOpen=!notifOpen" class="relative p-2 rounded-xl transition-all duration-200"
                        :class="darkMode ? 'text-green-400/70 hover:text-green-400 hover:bg-green-900/20' : 'text-green-700 hover:bg-green-100'">
                        <i data-lucide="bell" class="w-[18px] h-[18px]"></i>
                        <span class="absolute top-1.5 right-1.5 flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                        </span>
                    </button>

                    <div x-show="notifOpen" x-cloak x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        class="absolute right-0 top-full mt-2 w-80 glass-panel shadow-2xl z-50 overflow-hidden">
                        <div
                            class="flex items-center justify-between px-4 py-3 border-b border-stone-200/50 dark:border-white/[0.05]">
                            <h3 class="text-sm font-bold text-stone-900 dark:text-white">Notifications</h3>
                            <span class="badge badge-danger badge-dot">2 New</span>
                        </div>
                        <div
                            class="divide-y divide-stone-100 dark:divide-white/[0.04] max-h-72 overflow-y-auto custom-scroll">
                            <a href="{{ route('health.dashboard') }}"
                                class="flex gap-3 px-4 py-3 hover:bg-stone-50 dark:hover:bg-white/[0.03] transition-colors">
                                <div
                                    class="w-8 h-8 rounded-full gradient-rose flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i data-lucide="heart-pulse" class="w-4 h-4 text-white"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-stone-800 dark:text-stone-100">Health Alert</p>
                                    <p class="text-xs text-stone-600 truncate">2 animals require immediate attention</p>
                                    <p class="text-[10px] text-stone-400 mt-0.5">2 minutes ago</p>
                                </div>
                            </a>
                            <a href="{{ route('livestock.index') }}"
                                class="flex gap-3 px-4 py-3 hover:bg-stone-50 dark:hover:bg-white/[0.03] transition-colors">
                                <div
                                    class="w-8 h-8 rounded-full gradient-amber flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i data-lucide="wheat" class="w-4 h-4 text-white"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-stone-800 dark:text-stone-100">Feed Alert</p>
                                    <p class="text-xs text-stone-600 truncate">Hay stock below reorder level</p>
                                    <p class="text-[10px] text-stone-400 mt-0.5">18 minutes ago</p>
                                </div>
                            </a>
                        </div>
                        <div class="px-4 py-2.5 border-t border-stone-100 dark:border-white/[0.04]">
                            <a href="#"
                                class="text-xs font-semibold text-primary-500 hover:text-primary-600 transition-colors">View
                                all notifications →</a>
                        </div>
                    </div>
                </div>

                {{-- {{ __('Quick Action') }}s --}}
                <div class="relative" x-data="{ qaOpen: false }" @click.away="qaOpen=false">
                    <button @click="qaOpen=!qaOpen" class="hidden sm:flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold
                                   text-white gradient-emerald hover:opacity-90 transition-opacity shadow-md ml-1"
                        style="box-shadow: 0 4px 12px -2px rgba(16,185,129,0.4);">
                        <i data-lucide="zap" class="w-3.5 h-3.5"></i>{{ __('Quick') }}</button>
                    <div x-show="qaOpen" x-cloak x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        class="absolute right-0 top-full mt-2 w-56 glass-panel shadow-2xl z-50 overflow-hidden p-1.5">
                        <a href="{{ route('livestock.create') }}"
                            class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm text-stone-700 dark:text-stone-300 hover:bg-green-50 dark:hover:bg-green-500/10 hover:text-green-700 dark:hover:text-green-400 transition-colors">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i> Register Animal
                        </a>
                        <a href="{{ route('health.dashboard') }}"
                            class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm text-stone-700 dark:text-stone-300 hover:bg-rose-50 dark:hover:bg-rose-500/10 hover:text-rose-700 dark:hover:text-rose-400 transition-colors">
                            <i data-lucide="stethoscope" class="w-4 h-4"></i> Log Vet Visit
                        </a>
                        <a href="{{ route('reports.index') }}"
                            class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm text-stone-700 dark:text-stone-300 hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                            <i data-lucide="file-bar-chart" class="w-4 h-4"></i> View Reports
                        </a>
                        <div class="h-px bg-stone-200/60 dark:bg-white/[0.05] my-1"></div>
                        <a href="{{ route('ai.dashboard') }}"
                            class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm hover:bg-purple-50 dark:hover:bg-purple-500/10 hover:text-purple-700 dark:hover:text-purple-400 transition-colors">
                            <i data-lucide="brain-circuit" class="w-4 h-4 text-purple-500"></i>
                            <span class="text-gradient-ai font-semibold">AI Insights</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        {{-- Scrollable Content --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar" id="main-scroll"
            :style="darkMode ? 'background-color: #0A1409; color: #d1fae5;' : 'background-color: #EEF4EB; color: #1a2e1d;'">
            <div class="p-4 sm:p-6 lg:p-8 max-w-[1600px] mx-auto animate-fade-in">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-[-8px]"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" x-init="setTimeout(() => show = false, 4500)" class="flex items-center gap-3 mb-6 px-4 py-3.5 rounded-xl
                                            bg-green-50 dark:bg-green-500/10
                                            border border-green-200 dark:border-green-500/20
                                            text-green-800 dark:text-green-300 text-sm font-medium shadow-sm">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-green-500 flex-shrink-0"></i>
                        <span class="flex-1">{{ session('success') }}</span>
                        <button @click="show=false"
                            class="text-green-400 hover:text-green-600 transition-colors flex-shrink-0">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-cloak x-init="setTimeout(() => show = false, 5000)" class="flex items-center gap-3 mb-6 px-4 py-3.5 rounded-xl
                                            bg-red-50 dark:bg-red-500/10
                                            border border-red-200 dark:border-red-500/20
                                            text-red-800 dark:text-red-300 text-sm font-medium shadow-sm">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 flex-shrink-0"></i>
                        <span class="flex-1">{{ session('error') }}</span>
                        <button @click="show=false" class="text-red-400 hover:text-red-600 transition-colors flex-shrink-0">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>

            {{-- Footer --}}
            <footer class="px-6 lg:px-8 py-6 border-t border-stone-200/50 dark:border-white/[0.04] mt-8">
                <div
                    class="max-w-[1600px] mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-stone-400">
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-md gradient-emerald flex items-center justify-center">
                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                        </span>
                        <span>&copy; {{ date('Y') }} <strong class="text-stone-600 dark:text-stone-300">OviTech Smart
                                Farm</strong> Enterprise Edition</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            All systems operational
                        </span>
                        <span>v3.0.0</span>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    {{-- ═══════════════════════════════════════════════
    GLOBAL SEARCH MODAL (CMD/CTRL + K)
    ═══════════════════════════════════════════════ --}}
    <div x-show="searchOpen" x-cloak class="fixed inset-0 z-[100] flex items-start justify-center pt-[10vh] px-4"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-stone-900/60 backdrop-blur-sm" @click="searchOpen=false"></div>

        {{-- Panel --}}
        <div class="relative w-full max-w-2xl glass-panel shadow-2xl overflow-hidden z-10" @click.stop
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-[-12px]"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            <div class="flex items-center gap-3 px-5 py-4 border-b border-stone-200/50 dark:border-white/[0.05]">
                <i data-lucide="search" class="w-5 h-5 text-primary-500 flex-shrink-0"></i>
                <input type="text" id="search-input" placeholder="Search animals, RFID, health records, invoices..."
                    class="flex-1 bg-transparent border-0 outline-none text-base text-stone-900 dark:text-white placeholder-stone-400"
                    autofocus>
                <button @click="searchOpen=false"
                    class="text-xs bg-stone-100 dark:bg-stone-800 text-stone-600 px-2 py-1 rounded-lg font-bold flex-shrink-0">
                    ESC
                </button>
            </div>

            <div class="max-h-[420px] overflow-y-auto custom-scroll p-2">
                <p class="px-3 pt-2 pb-1 text-[10px] font-bold uppercase tracking-widest text-stone-400">Quick Links</p>
                @php
                    $quickLinks = [
                        ['route' => 'livestock.index', 'icon' => 'sheep', 'label' => 'Livestock Directory', 'sub' => 'Manage all animals', 'color' => 'sky'],
                        ['route' => 'health.dashboard', 'icon' => 'heart-pulse', 'label' => 'Health Dashboard', 'sub' => 'AI disease detection', 'color' => 'rose'],
                        ['route' => 'feed.dashboard', 'icon' => 'wheat', 'label' => 'Feed & Nutrition', 'sub' => 'Inventory & schedules', 'color' => 'amber'],
                        ['route' => 'finance.dashboard', 'icon' => 'trending-up', 'label' => 'Finance', 'sub' => 'Revenue & expenses', 'color' => 'emerald'],
                        ['route' => 'reports.index', 'icon' => 'file-bar-chart', 'label' => 'Reports & Analytics', 'sub' => 'Farm intelligence', 'color' => 'teal'],
                        ['route' => 'ai.dashboard', 'icon' => 'brain-circuit', 'label' => 'AI Hub', 'sub' => 'Predictions & insights', 'color' => 'purple'],
                    ];
                @endphp
                @foreach($quickLinks as $link)
                    <a href="{{ route($link['route']) }}" @click="searchOpen=false"
                        class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-stone-50 dark:hover:bg-white/[0.04] transition-colors group">
                        <div
                            class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0
                                                {{ "bg-{$link['color']}-100 dark:bg-{$link['color']}-500/10 text-{$link['color']}-600 dark:text-{$link['color']}-400" }}">
                            <i data-lucide="{{ $link['icon'] }}" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-stone-800 dark:text-stone-100">{{ $link['label'] }}</p>
                            <p class="text-xs text-stone-600">{{ $link['sub'] }}</p>
                        </div>
                        <i data-lucide="{{ app()->getLocale() == 'ar' ? 'chevron-left' : 'chevron-right' }}"
                            class="w-4 h-4 text-stone-300 dark:text-stone-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                @endforeach
            </div>

            <div
                class="px-5 py-3 border-t border-stone-200/50 dark:border-white/[0.05] flex items-center gap-3 text-[11px] text-stone-400">
                <span class="flex items-center gap-1"><kbd class="kbd">↑↓</kbd> Navigate</span>
                <span class="flex items-center gap-1"><kbd class="kbd">↵</kbd> Open</span>
                <span class="flex items-center gap-1"><kbd class="kbd">Esc</kbd> Close</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
    SCRIPTS
    ═══════════════════════════════════════════════ --}}
    @stack('scripts')

    <script>
        // Alpine Root Store
        document.addEventListener('alpine:init', () => {
            Alpine.store('app', {
                sidebarCollapsed: localStorage.getItem('ovi_sidebar') === 'true',
                darkMode: localStorage.getItem('ovi_dark') === 'true' ||
                    (!localStorage.getItem('ovi_dark') && window.matchMedia('(prefers-color-scheme: dark)').matches),
            });
        });

        function oviApp() {
            return {
                darkMode: localStorage.getItem('ovi_dark') === 'true' ||
                    (!localStorage.getItem('ovi_dark') && window.matchMedia('(prefers-color-scheme: dark)').matches),
                sidebarOpen: false,
                sidebarCollapsed: localStorage.getItem('ovi_sidebar') === 'true',
                searchOpen: false,
                commandOpen: false,

                init() {
                    this.$watch('darkMode', val => {
                        localStorage.setItem('ovi_dark', val);
                        document.documentElement.classList.toggle('dark', val);
                    });
                    this.$watch('sidebarCollapsed', val => localStorage.setItem('ovi_sidebar', val));
                    if (this.darkMode) document.documentElement.classList.add('dark');

                    // Keyboard: CMD+K / CTRL+K
                    document.addEventListener('keydown', (e) => {
                        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                            e.preventDefault();
                            this.searchOpen = true;
                            this.$nextTick(() => document.getElementById('search-input')?.focus());
                        }
                    });
                }
            };
        }

        function liveClock() {
            return {
                time: '',
                init() {
                    const update = () => {
                        this.time = new Date().toLocaleTimeString('en-US', {
                            hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
                        });
                    };
                    update();
                    setInterval(update, 1000);
                }
            };
        }

        // Lucide icons auto-init
        document.addEventListener('DOMContentLoaded', () => {
            const renderIcons = () => {
                if (typeof lucide === 'undefined') return;
                lucide.createIcons();
                // Remove data-lucide to prevent re-processing the same SVGs on future mutations
                document.querySelectorAll('svg[data-lucide]').forEach(el => el.removeAttribute('data-lucide'));
            };

            renderIcons();

            // Re-render icons when Alpine mutates the DOM, safely
            const observer = new MutationObserver((mutations) => {
                let hasNewIcons = false;
                for (let m of mutations) {
                    for (let node of m.addedNodes) {
                        if (node.nodeType === 1 && (node.hasAttribute('data-lucide') || node.querySelector('[data-lucide]'))) {
                            hasNewIcons = true;
                            break;
                        }
                    }
                    if (hasNewIcons) break;
                }

                if (hasNewIcons) {
                    observer.disconnect();
                    renderIcons();
                    observer.observe(document.body, { childList: true, subtree: true });
                }
            });
            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>

    <style>
        kbd.kbd {
            display: inline-flex;
            align-items: center;
            padding: 1px 5px;
            border-radius: 4px;
            border: 1px solid rgba(0, 0, 0, 0.12);
            background: white;
            font-size: 10px;
            font-family: monospace;
            font-weight: 700;
            color: #64748b;
        }

        .dark kbd.kbd {
            border-color: rgba(255, 255, 255, 0.1);
            background: rgba(15, 23, 42, 0.8);
            color: #94a3b8;
        }

        /* Fix missing Tailwind text colors in Light Mode across the entire platform */
        html:not(.dark) .text-slate-900 { color: #0f172a !important; }
        html:not(.dark) .text-slate-800 { color: #1e293b !important; }
        html:not(.dark) .text-slate-700 { color: #334155 !important; }
        html:not(.dark) .text-slate-600 { color: #475569 !important; }
        html:not(.dark) .text-slate-500 { color: #475569 !important; } /* mapped darker for contrast */
        html:not(.dark) .text-slate-400 { color: #64748b !important; } /* mapped darker for contrast */
        html:not(.dark) .text-slate-300 { color: #94a3b8 !important; } /* mapped darker for contrast */

        [x-cloak] {
            display: none !important;
        }
    </style>

    {{-- Service Worker Unregistration (Disabled for Dev) --}}
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.getRegistrations().then(function(registrations) {
                    for(let registration of registrations) {
                        registration.unregister();
                        console.log('SW unregistered successfully for development.');
                    }
                });
            });
        }
    </script>

    {{-- ═══ Lucide Icons Init ═══ --}}
    {{-- Re-runs createIcons on load, Alpine mutations, and modal opens --}}
    <script>
        function initLucide() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Run immediately when DOM is ready
        document.addEventListener('DOMContentLoaded', initLucide);

        // Also run on full page load (covers deferred scripts)
        window.addEventListener('load', initLucide);

        // Re-run whenever Alpine changes the DOM (x-show, x-if, modals, etc.)
        document.addEventListener('alpine:initialized', () => {
            initLucide();
            // Watch for DOM mutations caused by Alpine (x-if inserts, modals, etc.)
            const observer = new MutationObserver(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
            observer.observe(document.body, {
                childList: true,
                subtree: true,
                attributes: false,
            });
        });
    </script>
    <div class="hidden translate-x-full -translate-x-full start-4 end-4"></div>
</body>
</html>