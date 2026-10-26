@extends('layouts.app')

@section('title', 'Enterprise AI Dashboard')

@push('head')
    {{-- Leaflet Map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
@endpush

@section('content')

    <div class="p-4 sm:p-8 space-y-8 animate-fade-in pb-32">

        <!-- 1. WELCOME AREA -->
        <div class="relative overflow-hidden rounded-2xl p-8 lg:p-10"
             :style="darkMode
                ? 'background: linear-gradient(135deg, #0d3320 0%, #166534 50%, #1a4731 100%); border: 1px solid rgba(34,197,94,0.2); box-shadow: 0 20px 60px rgba(0,0,0,0.4);'
                : 'background: linear-gradient(135deg, #166534 0%, #15803d 45%, #1a4731 100%); border: 1px solid rgba(74,222,128,0.2); box-shadow: 0 8px 40px rgba(22,101,52,0.25);'">

            {{-- Decorative orbs --}}
            <div class="absolute top-0 right-0 w-[400px] h-[400px] rounded-full pointer-events-none -mt-20 -mr-20"
                 style="background: radial-gradient(circle, rgba(74,222,128,0.2) 0%, transparent 70%); filter: blur(40px);"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 rounded-full pointer-events-none -mb-10 -ml-10"
                 style="background: radial-gradient(circle, rgba(251,191,36,0.15) 0%, transparent 70%); filter: blur(50px);"></div>
            <div class="absolute top-1/2 left-1/2 w-64 h-64 rounded-full pointer-events-none -translate-x-1/2 -translate-y-1/2"
                 style="background: radial-gradient(circle, rgba(34,197,94,0.08) 0%, transparent 70%);"></div>

            <div class="relative z-10 flex flex-col xl:flex-row items-start xl:items-center justify-between gap-8">
                <div class="flex-1">
                    {{-- Badges --}}
                    <div class="flex flex-wrap items-center gap-2.5 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-widest"
                              style="background: rgba(74,222,128,0.15); border: 1px solid rgba(74,222,128,0.3); color: #86efac;">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                            {{ __(auth()->user()->farm->name ?? 'Ovitech Farm') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-medium"
                              style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); color: rgba(255,255,255,0.75);">
                            <i data-lucide="sun" class="w-3 h-3" style="color: #fbbf24;"></i>
                            {{ __('Morning') }} • {{ $weather['temp'] }}°C • {{ $weather['humidity'] }} {{ __('Humidity') }}
                        </span>
                    </div>

                    {{-- Main Title --}}
                    <h1 class="text-4xl lg:text-5xl font-black tracking-tight mb-3 leading-tight" style="color: #ffffff;">
                        {{ __('Welcome back,') }} <span style="color: #86efac;">{{ auth()->user()->name ?? 'Farmer' }}</span> 👋
                    </h1>

                    <p class="max-w-2xl text-base leading-relaxed mb-8" style="color: rgba(209,250,229,0.8);">
                        {{ __('AI Systems are operational. Your farm health is exceptional today.') }}
                        <strong style="color: #ffffff;">{{ __(collect($aiInsights['predictions'])->firstWhere('priority', 'high')['text'] ?? 'No critical alerts.') }}</strong>
                    </p>

                    {{-- Action Buttons --}}
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('livestock.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-200 hover:-translate-y-0.5"
                                style="background: rgba(255,255,255,0.95); color: #15803d; box-shadow: 0 4px 20px rgba(0,0,0,0.15);"
                                onmouseover="this.style.background='#ffffff'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.2)';"
                                onmouseout="this.style.background='rgba(255,255,255,0.95)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.15)';">
                            <i data-lucide="plus" class="w-4 h-4"></i> {{ __('Quick Action') }}
                        </a>
                        <a href="{{ route('ai.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 hover:-translate-y-0.5"
                                style="background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.9); border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(10px);"
                                onmouseover="this.style.background='rgba(255,255,255,0.18)';"
                                onmouseout="this.style.background='rgba(255,255,255,0.12)';">
                            <i data-lucide="file-text" class="w-4 h-4"></i> {{ __('Generate AI Report') }}
                        </a>
                    </div>
                </div>

                {{-- Health Score Ring --}}
                <div class="flex items-center gap-6 xl:border-l xl:pl-10 flex-shrink-0"
                     style="border-color: rgba(255,255,255,0.15);">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color: rgba(134,239,172,0.7);">{{ __('AI Health Index') }}</p>
                        <div class="flex items-end justify-end gap-1">
                            <span class="text-6xl font-black leading-none" style="color: #ffffff; text-shadow: 0 0 30px rgba(74,222,128,0.4);">{{ $aiInsights['health_score'] }}</span>
                            <span class="text-xl font-bold mb-1" style="color: rgba(74,222,128,0.7);">/100</span>
                        </div>
                        <p class="text-xs mt-2 flex items-center justify-end gap-1 font-semibold" style="color: #86efac;">
                            <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> {{ __('Excellent Condition') }}
                        </p>
                    </div>
                    {{-- Circular Progress Ring --}}
                    <div class="w-28 h-28 rounded-full relative flex items-center justify-center flex-shrink-0"
                         style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.15); backdrop-filter: blur(10px);">
                        <svg class="absolute inset-0 w-full h-full" style="transform: rotate(-90deg);">
                            <circle cx="56" cy="56" r="48" stroke="rgba(255,255,255,0.1)" stroke-width="7" fill="none"/>
                            <circle cx="56" cy="56" r="48" stroke="#4ade80" stroke-width="7" fill="none"
                                stroke-dasharray="301"
                                stroke-dashoffset="{{ 301 - (301 * $aiInsights['health_score'] / 100) }}"
                                stroke-linecap="round"
                                style="transition: stroke-dashoffset 2s ease-out; filter: drop-shadow(0 0 8px rgba(74,222,128,0.6));"/>
                        </svg>
                        <i data-lucide="activity" class="w-8 h-8 animate-pulse" style="color: #4ade80;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. SMART KPI CARDS GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5">

            <!-- Total Livestock -->
            <div class="modern-card p-6 group cursor-pointer hover:-translate-y-2 hover:shadow-xl transition-all duration-300 border-t-[4px] border-t-sky-500"
                 :style="darkMode ? 'background: rgba(15,35,18,0.7); border: 1px solid rgba(34,197,94,0.1);' : 'background: #ffffff; border: 1px solid #d4e8d8; box-shadow: 0 4px 24px rgba(0,0,0,0.06);'">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-sky-500/10 rounded-full blur-lg group-hover:bg-sky-500/20 transition-all"></div>
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider mb-1"
                           :style="darkMode ? 'color: rgba(148,163,184,0.8);' : 'color: #5a7c6f;'">{{ __('Total Livestock') }}</p>
                        <h3 class="text-3xl font-black tracking-tight"
                            :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ number_format($stats['total_sheep']) }}</h3>
                    </div>
                    <div class="w-11 h-11 flex items-center justify-center rounded-xl transition-all duration-300 group-hover:scale-110 group-hover:-rotate-6"
                         :style="darkMode ? 'background: linear-gradient(135deg, #38bdf8, #0284c7); box-shadow: 0 4px 12px rgba(56,189,248,0.4);' : 'background: #e0f2fe; color: #0284c7;'">
                        <i data-lucide="layers" class="w-5 h-5" :style="darkMode ? 'color: white;' : 'color: #0284c7;'"></i>
                    </div>
                </div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-bold"
                          style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">
                        <i data-lucide="arrow-up-right" class="w-3 h-3"></i> {{ $stats['growth_index'] }}
                    </span>
                    <span class="text-xs font-medium" :style="darkMode ? 'color: rgba(148,163,184,0.7);' : 'color: #6b9e7a;'">{{ __('Growth Index') }}</span>
                </div>
                <div class="h-10 w-full"><canvas id="kpiChart1"></canvas></div>
            </div>

            <!-- Breeding Success -->
            <div class="modern-card p-6 group cursor-pointer hover:-translate-y-2 hover:shadow-xl transition-all duration-300 border-t-[4px] border-t-rose-500"
                 :style="darkMode ? 'background: rgba(15,35,18,0.7); border: 1px solid rgba(34,197,94,0.1);' : 'background: #ffffff; border: 1px solid #d4e8d8; box-shadow: 0 4px 24px rgba(0,0,0,0.06);'">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-rose-500/10 rounded-full blur-lg group-hover:bg-rose-500/20 transition-all"></div>
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider mb-1"
                           :style="darkMode ? 'color: rgba(148,163,184,0.8);' : 'color: #5a7c6f;'">{{ __('Breeding Success') }}</p>
                        <h3 class="text-3xl font-black tracking-tight"
                            :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ $stats['breeding_success'] }}</h3>
                    </div>
                    <div class="w-11 h-11 flex items-center justify-center rounded-xl transition-all duration-300 group-hover:scale-110 group-hover:rotate-6"
                         :style="darkMode ? 'background: linear-gradient(135deg, #fb7185, #e11d48); box-shadow: 0 4px 12px rgba(251,113,133,0.4);' : 'background: #fce7f3; color: #be185d;'">
                        <i data-lucide="heart" class="w-5 h-5" :style="darkMode ? 'color: white;' : 'color: #be185d;'"></i>
                    </div>
                </div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-bold"
                          style="background: #fce7f3; color: #be185d; border: 1px solid #fbcfe8;">
                        <i data-lucide="activity" class="w-3 h-3"></i> {{ $stats['pregnant_sheep'] }}
                    </span>
                    <span class="text-xs font-medium" :style="darkMode ? 'color: rgba(148,163,184,0.7);' : 'color: #6b9e7a;'">{{ __('Pregnant') }}</span>
                </div>
                <div class="w-full rounded-full h-1.5 overflow-hidden"
                     :style="darkMode ? 'background: rgba(255,255,255,0.06);' : 'background: #fce7f3;'">
                    <div class="h-1.5 rounded-full" style="width: 92%; background: linear-gradient(90deg, #fb7185, #f472b6);"></div>
                </div>
            </div>

            <!-- Cash Flow -->
            <div class="modern-card p-6 group cursor-pointer hover:-translate-y-2 hover:shadow-xl transition-all duration-300 border-t-[4px] border-t-green-500"
                 :style="darkMode ? 'background: rgba(15,35,18,0.7); border: 1px solid rgba(34,197,94,0.1);' : 'background: #ffffff; border: 1px solid #d4e8d8; box-shadow: 0 4px 24px rgba(0,0,0,0.06);'">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-green-500/10 rounded-full blur-lg group-hover:bg-green-500/20 transition-all"></div>
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider mb-1"
                           :style="darkMode ? 'color: rgba(148,163,184,0.8);' : 'color: #5a7c6f;'">{{ __('Cash Flow') }}</p>
                        <h3 class="text-3xl font-black tracking-tight"
                            :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">${{ number_format($financials['cash_flow']) }}</h3>
                    </div>
                    <div class="w-11 h-11 flex items-center justify-center rounded-xl transition-all duration-300 group-hover:scale-110 group-hover:-rotate-6"
                         :style="darkMode ? 'background: linear-gradient(135deg, #4ade80, #16a34a); box-shadow: 0 4px 12px rgba(74,222,128,0.4);' : 'background: #dcfce7; color: #15803d;'">
                        <i data-lucide="dollar-sign" class="w-5 h-5" :style="darkMode ? 'color: white;' : 'color: #15803d;'"></i>
                    </div>
                </div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-bold"
                          style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">
                        <i data-lucide="arrow-up-right" class="w-3 h-3"></i> {{ $financials['revenue_trend'] }}
                    </span>
                    <span class="text-xs font-medium" :style="darkMode ? 'color: rgba(148,163,184,0.7);' : 'color: #6b9e7a;'">{{ __('Revenue') }}</span>
                </div>
                <div class="h-10 w-full"><canvas id="kpiChart2"></canvas></div>
            </div>

            <!-- Solar Power -->
            <div class="modern-card p-6 group cursor-pointer hover:-translate-y-2 hover:shadow-xl transition-all duration-300 border-t-[4px] border-t-amber-500 hidden sm:block"
                 :style="darkMode ? 'background: rgba(15,35,18,0.7); border: 1px solid rgba(34,197,94,0.1);' : 'background: #ffffff; border: 1px solid #d4e8d8; box-shadow: 0 4px 24px rgba(0,0,0,0.06);'">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-500/10 rounded-full blur-lg group-hover:bg-amber-500/20 transition-all"></div>
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider mb-1"
                           :style="darkMode ? 'color: rgba(148,163,184,0.8);' : 'color: #5a7c6f;'">{{ __('Solar Power') }}</p>
                        <h3 class="text-3xl font-black tracking-tight"
                            :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ $iotData['solar_production'] }}</h3>
                    </div>
                    <div class="w-11 h-11 flex items-center justify-center rounded-xl transition-all duration-300 group-hover:scale-110 group-hover:rotate-6"
                         :style="darkMode ? 'background: linear-gradient(135deg, #fbbf24, #d97706); box-shadow: 0 4px 12px rgba(251,191,36,0.4);' : 'background: #fef3c7; color: #d97706;'">
                        <i data-lucide="sun" class="w-5 h-5" :style="darkMode ? 'color: white;' : 'color: #d97706;'"></i>
                    </div>
                </div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-bold"
                          style="background: #fef3c7; color: #d97706; border: 1px solid #fde68a;">
                        <i data-lucide="battery-charging" class="w-3 h-3"></i> {{ $iotData['battery_level'] }}
                    </span>
                    <span class="text-xs font-medium" :style="darkMode ? 'color: rgba(148,163,184,0.7);' : 'color: #6b9e7a;'">{{ __('Storage') }}</span>
                </div>
                <div class="w-full rounded-full h-1.5 overflow-hidden"
                     :style="darkMode ? 'background: rgba(255,255,255,0.06);' : 'background: #fef3c7;'">
                    <div class="h-1.5 rounded-full" style="width: 92%; background: linear-gradient(90deg, #fbbf24, #fde68a);"></div>
                </div>
            </div>

            <!-- RFID Scans -->
            <div class="modern-card p-6 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300 border-t-4 border-t-indigo-500 hidden xl:block"
                 :style="darkMode ? 'background: rgba(15,35,18,0.7); border: 1px solid rgba(34,197,94,0.1);' : 'background: #ffffff; border: 1px solid #d4e8d8; box-shadow: 0 4px 24px rgba(0,0,0,0.06);'">
                <div
                    class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-xl group-hover:bg-indigo-500/20 transition-all">
                </div>
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-xs font-bold text-stone-600 dark:text-stone-400 uppercase tracking-wider">{{ __('RFID Activity') }}</p>
                        <h3 class="text-3xl font-black text-stone-900 dark:text-white mt-1 tracking-tight">
                            {{ number_format($iotData['gate_activity']) }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 flex items-center justify-center rounded-2xl text-indigo-600 dark:text-white bg-indigo-100 dark:bg-gradient-to-br dark:from-indigo-400 dark:to-indigo-600 dark:shadow-[inset_0_2px_4px_rgba(255,255,255,0.4),_0_5px_15px_rgba(99,102,241,0.4)] group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300">
                        <i data-lucide="radio" class="w-6 h-6 dark:drop-shadow-md"></i>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-sm mb-4">
                    <span
                        class="text-indigo-600 dark:text-indigo-400 flex items-center font-bold bg-indigo-100 dark:bg-indigo-500/10 px-2 py-0.5 rounded border border-indigo-200 dark:border-indigo-500/20 whitespace-nowrap">
                        <i data-lucide="check-circle" class="w-3 h-3 me-1.5"></i> {{ __('Online') }}
                    </span>
                    <span class="text-stone-600 font-medium leading-tight">{{ __('All Gates Active') }}</span>
                </div>
                <div class="h-10 w-full">
                    <canvas id="kpiChart3"></canvas>
                </div>
            </div>
        </div>

        <!-- 3. AI PREDICTIONS & CHARTS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left: AI Insights (1 Col) -->
            <div class="space-y-6">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-sm"
                         :style="darkMode ? 'background: rgba(168,85,247,0.2);' : 'background: #f3e8ff;'">
                        <i data-lucide="sparkles" class="w-4 h-4" :style="darkMode ? 'color: #d8b4fe;' : 'color: #9333ea;'"></i>
                    </div>
                    <h2 class="text-lg font-bold tracking-tight" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ __('AI Recommendations') }}</h2>
                </div>

                @foreach($aiInsights['predictions'] as $prediction)
                    @php
                        $colorData = match($prediction['priority']) {
                            'high' => ['lightBorder' => '#fecaca', 'lightBg' => '#fef2f2', 'darkBg' => 'rgba(239,68,68,0.08)', 'accent' => '#ef4444', 'text' => '#991b1b'],
                            'medium' => ['lightBorder' => '#fde68a', 'lightBg' => '#fffbeb', 'darkBg' => 'rgba(245,158,11,0.08)', 'accent' => '#f59e0b', 'text' => '#92400e'],
                            default => ['lightBorder' => '#bfdbfe', 'lightBg' => '#eff6ff', 'darkBg' => 'rgba(59,130,246,0.08)', 'accent' => '#3b82f6', 'text' => '#1e40af'],
                        };
                    @endphp
                    <div class="rounded-2xl p-5 border-l-4 transition-all duration-300 hover:-translate-y-1"
                         :style="darkMode
                            ? 'background: {{ $colorData['darkBg'] }}; border-color: rgba(255,255,255,0.05); border-left-color: {{ $colorData['accent'] }}; box-shadow: 0 4px 15px rgba(0,0,0,0.2);'
                            : 'background: {{ $colorData['lightBg'] }}; border-color: {{ $colorData['lightBorder'] }}; border-left-color: {{ $colorData['accent'] }}; box-shadow: 0 2px 10px rgba(0,0,0,0.03);'">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold text-sm" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ __($prediction['type'] . ' Prediction') }}</h4>
                            <span class="text-[10px] font-bold px-2 py-1 rounded shadow-sm"
                                  :style="darkMode
                                    ? 'background: rgba(0,0,0,0.4); color: rgba(255,255,255,0.8); border: 1px solid rgba(255,255,255,0.1);'
                                    : 'background: #ffffff; color: {{ $colorData['text'] }}; border: 1px solid {{ $colorData['lightBorder'] }};'">
                                {{ $prediction['confidence'] }}% {{ __('Conf.') }}
                            </span>
                        </div>
                        <p class="text-sm mb-4 leading-relaxed" :style="darkMode ? 'color: rgba(255,255,255,0.7);' : 'color: #4a5d50;'">{{ __($prediction['text']) }}</p>
                        <div class="p-3.5 rounded-xl shadow-inner"
                             :style="darkMode
                                ? 'background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.05);'
                                : 'background: rgba(255,255,255,0.6); border: 1px solid rgba(0,0,0,0.04);'">
                            <p class="text-[10px] font-bold uppercase tracking-wider mb-1.5" :style="darkMode ? 'color: rgba(255,255,255,0.4);' : 'color: #7a8d80;'">{{ __('Suggested Action') }}</p>
                            <p class="text-sm font-bold" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ __($prediction['action']) }}</p>
                            <p class="text-xs mt-2 font-semibold flex items-center gap-1" :style="'color: ' + (darkMode ? '#86efac' : '#16a34a')">
                                <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> {{ __('Impact:') }} {{ __($prediction['impact']) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Right: Real-time Charts (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-sm"
                             :style="darkMode ? 'background: rgba(56,189,248,0.2);' : 'background: #e0f2fe;'">
                            <i data-lucide="bar-chart-2" class="w-4 h-4" :style="darkMode ? 'color: #7dd3fc;' : 'color: #0284c7;'"></i>
                        </div>
                        <h2 class="text-lg font-bold tracking-tight" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ __('Financial & Growth Analytics') }}</h2>
                    </div>
                    <div class="flex gap-1.5 p-1 rounded-lg" :style="darkMode ? 'background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.05);' : 'background: #e4efe7; border: 1px solid #d1e3d6;'">
                        <button class="px-4 py-1.5 text-xs font-bold rounded-md transition-all"
                                :style="darkMode ? 'color: rgba(255,255,255,0.5); hover:color: white;' : 'color: #5a7c6f; hover:color: #1a2e1d;'">{{ __('1W') }}</button>
                        <button class="px-4 py-1.5 text-xs font-bold rounded-md shadow-sm transition-all"
                                :style="darkMode ? 'background: #16a34a; color: white;' : 'background: #ffffff; color: #16a34a; box-shadow: 0 1px 3px rgba(0,0,0,0.1);'">{{ __('1M') }}</button>
                        <button class="px-4 py-1.5 text-xs font-bold rounded-md transition-all"
                                :style="darkMode ? 'color: rgba(255,255,255,0.5); hover:color: white;' : 'color: #5a7c6f; hover:color: #1a2e1d;'">{{ __('1Y') }}</button>
                    </div>
                </div>

                <div class="modern-card p-6"
                     :style="darkMode ? 'background: rgba(15,35,18,0.7); border: 1px solid rgba(34,197,94,0.1);' : 'background: #ffffff; border: 1px solid #d4e8d8; box-shadow: 0 2px 12px rgba(0,0,0,0.04);'">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-4" :style="darkMode ? 'color: rgba(255,255,255,0.4);' : 'color: #7a8d80;'">{{ __('Revenue vs Expenses') }}</h3>
                    <div class="chart-container"><canvas id="mainChart"></canvas></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="modern-card p-6"
                         :style="darkMode ? 'background: rgba(15,35,18,0.7); border: 1px solid rgba(34,197,94,0.1);' : 'background: #ffffff; border: 1px solid #d4e8d8; box-shadow: 0 2px 12px rgba(0,0,0,0.04);'">
                        <h3 class="text-xs font-bold uppercase tracking-widest mb-4" :style="darkMode ? 'color: rgba(255,255,255,0.4);' : 'color: #7a8d80;'">{{ __('Average Weight Growth (kg)') }}</h3>
                        <div class="chart-container" style="height: 200px;"><canvas id="weightChart"></canvas></div>
                    </div>
                    <div class="modern-card p-6"
                         :style="darkMode ? 'background: rgba(15,35,18,0.7); border: 1px solid rgba(34,197,94,0.1);' : 'background: #ffffff; border: 1px solid #d4e8d8; box-shadow: 0 2px 12px rgba(0,0,0,0.04);'">
                        <h3 class="text-xs font-bold uppercase tracking-widest mb-4" :style="darkMode ? 'color: rgba(255,255,255,0.4);' : 'color: #7a8d80;'">{{ __('Resource Usage') }}</h3>
                        <div class="chart-container" style="height: 200px;"><canvas id="resourceChart"></canvas></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. INTERACTIVE MAP & TASK CENTER -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Interactive Leaflet Map (2 Cols) -->
            <div class="lg:col-span-2 modern-card flex flex-col p-2"
                 :style="darkMode ? 'background: rgba(15,35,18,0.7); border: 1px solid rgba(34,197,94,0.1);' : 'background: #ffffff; border: 1px solid #d4e8d8; box-shadow: 0 4px 24px rgba(0,0,0,0.06);'">
                <div class="p-4 flex justify-between items-center rounded-t-xl"
                     :style="darkMode ? 'background: rgba(0,0,0,0.2); border-bottom: 1px solid rgba(255,255,255,0.05);' : 'background: #f8fbf9; border-bottom: 1px solid #eef4f0;'">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-sm"
                             :style="darkMode ? 'background: rgba(34,197,94,0.2);' : 'background: #dcfce7;'">
                            <i data-lucide="map" class="w-4 h-4" :style="darkMode ? 'color: #4ade80;' : 'color: #16a34a;'"></i>
                        </div>
                        <h3 class="text-lg font-bold tracking-tight" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ __('Interactive Farm Map') }}</h3>
                    </div>
                    <span class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 shadow-sm rounded-lg"
                          :style="darkMode ? 'background: rgba(0,0,0,0.4); border: 1px solid rgba(34,197,94,0.2); color: #4ade80;' : 'background: #ffffff; border: 1px solid #bbf7d0; color: #16a34a;'">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.8)]"></span> {{ __('GPS Live Tracking') }}
                    </span>
                </div>
                <!-- Farm Aerial View & Coordinates -->
                <div class="relative flex-1 overflow-hidden rounded-b-xl rounded-t-sm group min-h-[400px]">
                    <img src="{{ asset('img/farm-aerial.jpg') }}" alt="Ovitech Tawrira"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-105"
                        onerror="this.src='https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1600&auto=format&fit=crop';">

                    <!-- Overlay Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent pointer-events-none"></div>

                    <!-- Geographical Data Overlay -->
                    <div class="absolute bottom-0 left-0 right-0 p-6 flex flex-col md:flex-row justify-between items-end gap-4 pointer-events-none">
                        <div>
                            <h2 class="text-3xl font-black text-white tracking-tight drop-shadow-lg mb-3">{{ __('OVITECH TAWRIRA') }}</h2>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1.5 rounded-lg bg-black/50 backdrop-blur-md border border-white/20 text-white text-xs font-bold tracking-wide flex items-center gap-1.5 shadow-lg">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-green-400"></i> {{ __('Sidi Bel Abbes') }}
                                </span>
                                <span class="px-3 py-1.5 rounded-lg bg-black/50 backdrop-blur-md border border-white/20 text-white text-xs font-bold tracking-wide flex items-center gap-1.5 shadow-lg">
                                    <i data-lucide="maximize" class="w-3.5 h-3.5 text-sky-400"></i> {{ __('12.66 Hectares') }}
                                </span>
                                <span class="px-3 py-1.5 rounded-lg bg-black/50 backdrop-blur-md border border-white/20 text-white text-xs font-bold tracking-wide flex items-center gap-1.5 shadow-lg">
                                    <i data-lucide="wheat" class="w-3.5 h-3.5 text-amber-400"></i> {{ __('Blé Tendre') }}
                                </span>
                            </div>
                        </div>

                        <div class="bg-black/50 backdrop-blur-md border border-white/20 p-4 rounded-xl text-right shadow-xl">
                            <p class="text-[10px] font-bold text-stone-300 uppercase tracking-widest mb-2 opacity-80">{{ __('UTM Coordinates') }}</p>
                            <p class="text-sm font-mono text-green-300 font-bold">X = 748144.91 mE</p>
                            <p class="text-sm font-mono text-green-300 font-bold mb-1">Y = 3826956.03 mN</p>
                            <p class="text-[10px] text-stone-300 uppercase tracking-wider opacity-60">{{ __('Section 01, Ilot 03') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Center & Alerts -->
            <div class="modern-card flex flex-col"
                 :style="darkMode ? 'background: rgba(15,35,18,0.7); border: 1px solid rgba(34,197,94,0.1);' : 'background: #ffffff; border: 1px solid #d4e8d8; box-shadow: 0 4px 24px rgba(0,0,0,0.06);'">
                <div class="p-6 border-b" :style="darkMode ? 'border-color: rgba(255,255,255,0.05);' : 'border-color: #eef4f0;'">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-sm"
                             :style="darkMode ? 'background: rgba(14,165,233,0.2);' : 'background: #e0f2fe;'">
                            <i data-lucide="check-square" class="w-4 h-4" :style="darkMode ? 'color: #38bdf8;' : 'color: #0284c7;'"></i>
                        </div>
                        <h3 class="text-lg font-bold tracking-tight" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ __('Task Center') }}</h3>
                    </div>
                </div>

                <div class="p-5 flex-1 overflow-y-auto custom-scroll space-y-3" style="max-height: 400px;">
                    @foreach($tasks as $task)
                        <div class="p-4 rounded-xl border transition-all duration-300 hover:-translate-y-1 hover:shadow-md cursor-pointer group"
                             :style="darkMode
                                ? 'background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.05);'
                                : 'background: #fcfdfc; border-color: #e4efe7;'">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex gap-3">
                                    <div class="mt-0.5">
                                        @if($task['status'] == 'completed')
                                            <div class="w-5 h-5 rounded-full flex items-center justify-center border"
                                                 :style="darkMode ? 'background: rgba(34,197,94,0.2); border-color: rgba(34,197,94,0.3); color: #4ade80;' : 'background: #dcfce7; border-color: #bbf7d0; color: #16a34a;'">
                                                <i data-lucide="check" class="w-3 h-3"></i>
                                            </div>
                                        @else
                                            <div class="w-5 h-5 rounded-full border-2 transition-colors"
                                                 :style="darkMode ? 'border-color: rgba(255,255,255,0.2);' : 'border-color: #cbd5e1;'">
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold {{ $task['status'] == 'completed' ? 'line-through opacity-50' : '' }}"
                                           :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">
                                            {{ __($task['title']) }}
                                        </p>
                                        <p class="text-xs mt-1 flex items-center font-medium"
                                           :style="darkMode ? 'color: rgba(255,255,255,0.5);' : 'color: #6b9e7a;'">
                                            <i data-lucide="clock" class="w-3 h-3 mr-1"></i> {{ __($task['time']) }}
                                        </p>
                                    </div>
                                </div>
                                @if($task['priority'] == 'high')
                                    <span class="px-2 py-1 text-[9px] font-bold uppercase tracking-wider rounded"
                                          :style="darkMode ? 'background: rgba(239,68,68,0.2); color: #fca5a5;' : 'background: #fee2e2; color: #b91c1c;'">{{ __('HIGH') }}</span>
                                @endif
                            </div>
                            @if($task['status'] != 'completed' && isset($task['progress']))
                                <div class="w-full rounded-full h-1.5 mt-4 overflow-hidden"
                                     :style="darkMode ? 'background: rgba(255,255,255,0.1);' : 'background: #e2e8f0;'">
                                    <div class="h-1.5 rounded-full" style="width: {{ $task['progress'] }}%; background: linear-gradient(90deg, #38bdf8, #0ea5e9);"></div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="p-5 border-t" :style="darkMode ? 'border-color: rgba(255,255,255,0.05); background: rgba(0,0,0,0.2);' : 'border-color: #eef4f0; background: #f8fbf9;'">
                    <button onclick="window.location.href='{{ route('tasks.index') }}'" class="w-full py-3 text-sm font-bold rounded-xl transition-all shadow-sm"
                            :style="darkMode ? 'background: #16a34a; color: white; hover:background: #15803d;' : 'background: #166534; color: white; hover:background: #14532d;'">
                        {{ __('Add New Task') }}
                    </button>
                </div>
            </div>

        </div>

        <!-- 5. ANIMAL DATA GRID -->
        <div class="modern-card flex flex-col overflow-hidden"
             :style="darkMode ? 'background: rgba(15,35,18,0.7); border: 1px solid rgba(34,197,94,0.1);' : 'background: #ffffff; border: 1px solid #d4e8d8; box-shadow: 0 4px 24px rgba(0,0,0,0.06);'">
            <div class="p-6 border-b flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
                 :style="darkMode ? 'border-color: rgba(255,255,255,0.05); background: rgba(0,0,0,0.2);' : 'border-color: #eef4f0; background: #f8fbf9;'">
                <div>
                    <h3 class="text-lg font-bold flex items-center gap-2.5" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-sm"
                             :style="darkMode ? 'background: rgba(34,197,94,0.2);' : 'background: #dcfce7;'">
                            <i data-lucide="list" class="w-4 h-4" :style="darkMode ? 'color: #4ade80;' : 'color: #16a34a;'"></i>
                        </div>
                        {{ __('Real-time Animal Directory') }}
                    </h3>
                    <p class="text-[11px] font-bold uppercase tracking-widest mt-2" :style="darkMode ? 'color: rgba(255,255,255,0.4);' : 'color: #7a8d80;'">{{ __('Synchronized with RFID scanners 10s ago.') }}</p>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <div class="relative flex-1 sm:w-64">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2" :style="darkMode ? 'color: rgba(255,255,255,0.4);' : 'color: #94a3b8;'"></i>
                        <input type="text" placeholder="{{ __('Search ID, Breed, RFID...') }}"
                            class="text-sm rounded-xl pl-9 pr-4 py-2 focus:ring-2 focus:ring-green-500 outline-none w-full shadow-sm transition-all"
                            :style="darkMode ? 'background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.1); color: white;' : 'background: #ffffff; border: 1px solid #d1e3d6; color: #1a2e1d;'">
                    </div>
                    <div class="flex items-center gap-1 p-1 rounded-xl flex-shrink-0" :style="darkMode ? 'background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.1);' : 'background: #ffffff; border: 1px solid #d1e3d6;'">
                        <button @click="viewMode='grid'" :class="viewMode==='grid' ? (darkMode ? 'bg-white/10 shadow-sm text-white' : 'bg-slate-100 shadow-sm text-slate-800') : (darkMode ? 'text-white/40 hover:text-white' : 'text-slate-400 hover:text-slate-600')"
                                class="p-1.5 rounded-lg transition-all duration-200">
                            <i data-lucide="layout-grid" class="w-4 h-4"></i>
                        </button>
                        <button @click="viewMode='list'" :class="viewMode==='list' ? (darkMode ? 'bg-white/10 shadow-sm text-white' : 'bg-slate-100 shadow-sm text-slate-800') : (darkMode ? 'text-white/40 hover:text-white' : 'text-slate-400 hover:text-slate-600')"
                                class="p-1.5 rounded-lg transition-all duration-200">
                            <i data-lucide="table" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <button class="p-2 rounded-xl shadow-sm transition-all flex-shrink-0"
                            :style="darkMode ? 'background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); hover:background: rgba(255,255,255,0.05);' : 'background: #ffffff; border: 1px solid #d1e3d6; color: #5a7c6f; hover:background: #f8fbf9;'">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <div x-show="viewMode === 'list'" class="overflow-x-auto custom-scroll" :style="darkMode ? 'background: rgba(0,0,0,0.1);' : 'background: #ffffff;'">
                <table class="w-full {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} border-collapse">
                    <thead>
                        <tr class="text-[10px] font-bold uppercase tracking-widest border-b"
                            :style="darkMode ? 'background: rgba(0,0,0,0.3); color: rgba(255,255,255,0.4); border-color: rgba(255,255,255,0.05);' : 'background: #f8fbf9; color: #7a8d80; border-color: #eef4f0;'">
                            <th class="px-6 py-4 whitespace-nowrap">{{ __('Animal Profile') }}</th>
                            <th class="px-6 py-4 whitespace-nowrap">{{ __('RFID Tag') }}</th>
                            <th class="px-6 py-4 whitespace-nowrap">{{ __('Health Status') }}</th>
                            <th class="px-6 py-4 whitespace-nowrap">{{ __('Metrics') }}</th>
                            <th class="px-6 py-4 whitespace-nowrap">{{ __('AI Score') }}</th>
                            <th class="px-6 py-4 whitespace-nowrap {{ app()->getLocale() == 'ar' ? 'text-left' : 'text-right' }}">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" :style="darkMode ? 'divide-color: rgba(255,255,255,0.03);' : 'divide-color: #f1f5f9;'">
                        @foreach($animalsOverview as $animal)
                            <tr class="transition-colors group" :style="darkMode ? 'hover:background: rgba(255,255,255,0.02);' : 'hover:background: #f8fbf9;'">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl border flex items-center justify-center overflow-hidden shrink-0 shadow-sm transition-shadow group-hover:shadow-md"
                                             :style="darkMode ? 'background: rgba(0,0,0,0.3); border-color: rgba(255,255,255,0.1);' : 'background: #f8fbf9; border-color: #e4efe7;'">
                                            <i data-lucide="image" class="w-4 h-4 opacity-50" :style="darkMode ? 'color: white;' : 'color: #1a2e1d;'"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold whitespace-nowrap" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ __($animal->name) }}</p>
                                            <p class="text-xs font-medium whitespace-nowrap" :style="darkMode ? 'color: rgba(255,255,255,0.5);' : 'color: #6b9e7a;'">{{ __($animal->gender) }} • {{ __($animal->breed) }} • {{ __($animal->age) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[11px] font-mono font-bold px-2.5 py-1 rounded shadow-sm border"
                                          :style="darkMode ? 'background: rgba(0,0,0,0.3); color: #94a3b8; border-color: rgba(255,255,255,0.1);' : 'background: #f8fbf9; color: #475569; border-color: #e2e8f0;'">
                                        {{ $animal->rfid_tag }}
                                    </span>
                                    <p class="text-[10px] font-bold uppercase tracking-wider mt-2" :style="darkMode ? 'color: rgba(255,255,255,0.3);' : 'color: #94a3b8;'">
                                        <i data-lucide="map-pin" class="w-3 h-3 inline"></i> {{ __($animal->location) }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($animal->status == 'healthy')
                                        <span class="px-2.5 py-1.5 text-[10px] uppercase tracking-wider font-bold rounded-lg border shadow-sm"
                                              :style="darkMode ? 'background: rgba(34,197,94,0.1); color: #4ade80; border-color: rgba(34,197,94,0.2);' : 'background: #dcfce7; color: #16a34a; border-color: #bbf7d0;'">{{ __('HEALTHY') }}</span>
                                    @elseif($animal->status == 'sick')
                                        <span class="px-2.5 py-1.5 text-[10px] uppercase tracking-wider font-bold rounded-lg border shadow-sm animate-pulse"
                                              :style="darkMode ? 'background: rgba(239,68,68,0.1); color: #f87171; border-color: rgba(239,68,68,0.2);' : 'background: #fee2e2; color: #dc2626; border-color: #fecaca;'">{{ __('SICK') }}</span>
                                    @elseif($animal->status == 'pregnant')
                                        <span class="px-2.5 py-1.5 text-[10px] uppercase tracking-wider font-bold rounded-lg border shadow-sm"
                                              :style="darkMode ? 'background: rgba(168,85,247,0.1); color: #c084fc; border-color: rgba(168,85,247,0.2);' : 'background: #f3e8ff; color: #9333ea; border-color: #e9d5ff;'">{{ __('Pregnant') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold whitespace-nowrap" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ $animal->weight_kg }} {{ __('kg') }}</p>
                                    <p class="text-[10px] font-bold uppercase tracking-wider mt-1" :style="darkMode ? 'color: rgba(255,255,255,0.4);' : 'color: #94a3b8;'">{{ __('Last scan:') }} {{ __($animal->last_scan) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 rounded-full h-1.5 overflow-hidden" :style="darkMode ? 'background: rgba(255,255,255,0.1);' : 'background: #e2e8f0;'">
                                            <div class="h-1.5 rounded-full" style="width: {{ $animal->ai_score }}%; background: linear-gradient(90deg, #38bdf8, #0ea5e9);"></div>
                                        </div>
                                        <span class="text-xs font-bold" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ $animal->ai_score }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 {{ app()->getLocale() == 'ar' ? 'text-left' : 'text-right' }}">
                                    <a href="{{ route('livestock.show', $animal->id) }}" class="p-2 rounded-xl transition-all shadow-sm border group-hover:scale-105 inline-block"
                                            :style="darkMode ? 'background: rgba(0,0,0,0.3); border-color: rgba(255,255,255,0.1); color: rgba(255,255,255,0.5); hover:color: white; hover:background: rgba(255,255,255,0.05);' : 'background: #ffffff; border-color: #e2e8f0; color: #64748b; hover:color: #1a2e1d; hover:background: #f8fbf9;'">
                                        <i data-lucide="{{ app()->getLocale() == 'ar' ? 'chevron-left' : 'chevron-right' }}" class="w-4 h-4"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>

            <!-- Grid View Option -->
            <div x-show="viewMode === 'grid'" x-cloak class="p-6 custom-scroll" :style="darkMode ? 'background: rgba(0,0,0,0.1);' : 'background: #ffffff;'">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($animalsOverview as $animal)
                        <div class="rounded-2xl border p-5 shadow-sm hover:shadow-md transition-all relative group"
                             :style="darkMode ? 'background: rgba(0,0,0,0.3); border-color: rgba(255,255,255,0.1);' : 'background: #f8fbf9; border-color: #e4efe7;'">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 border"
                                         :style="darkMode ? 'background: rgba(0,0,0,0.4); border-color: rgba(255,255,255,0.1);' : 'background: #ffffff; border-color: #e4efe7;'">
                                        <i data-lucide="image" class="w-5 h-5 opacity-50" :style="darkMode ? 'color: white;' : 'color: #1a2e1d;'"></i>
                                    </div>
                                    <div class="{{ app()->getLocale() == 'ar' ? 'pr-2' : 'pl-2' }}">
                                        <p class="font-bold truncate" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ __($animal->name) }}</p>
                                        <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded shadow-sm border mt-1 inline-block"
                                              :style="darkMode ? 'background: rgba(0,0,0,0.3); color: #94a3b8; border-color: rgba(255,255,255,0.1);' : 'background: #ffffff; color: #475569; border-color: #e2e8f0;'">
                                            {{ $animal->rfid_tag }}
                                        </span>
                                    </div>
                                </div>
                                <div class="absolute top-5 {{ app()->getLocale() == 'ar' ? 'left-5' : 'right-5' }}">
                                    @if($animal->status == 'healthy')
                                        <span class="w-3 h-3 rounded-full inline-block shadow-[0_0_8px_rgba(34,197,94,0.6)]" style="background: #4ade80;"></span>
                                    @elseif($animal->status == 'sick')
                                        <span class="w-3 h-3 rounded-full inline-block shadow-[0_0_8px_rgba(239,68,68,0.6)] animate-pulse" style="background: #ef4444;"></span>
                                    @elseif($animal->status == 'pregnant')
                                        <span class="w-3 h-3 rounded-full inline-block shadow-[0_0_8px_rgba(168,85,247,0.6)]" style="background: #c084fc;"></span>
                                    @endif
                                </div>
                            </div>
                            <div class="space-y-3 text-sm mt-5 pt-4 border-t" :style="darkMode ? 'border-color: rgba(255,255,255,0.05);' : 'border-color: rgba(0,0,0,0.05);'">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-[10px] uppercase tracking-wider" :style="darkMode ? 'color: rgba(255,255,255,0.4);' : 'color: #94a3b8;'">{{ __('Category') }}</span>
                                    <span class="font-bold text-xs" :style="darkMode ? 'color: rgba(255,255,255,0.7);' : 'color: #1a2e1d;'">{{ __($animal->gender) }} • {{ __($animal->breed) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-[10px] uppercase tracking-wider" :style="darkMode ? 'color: rgba(255,255,255,0.4);' : 'color: #94a3b8;'">{{ __('Weight') }}</span>
                                    <span class="font-bold text-xs" :style="darkMode ? 'color: rgba(255,255,255,0.7);' : 'color: #1a2e1d;'">{{ $animal->weight_kg }} {{ __('kg') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-[10px] uppercase tracking-wider" :style="darkMode ? 'color: rgba(255,255,255,0.4);' : 'color: #94a3b8;'">{{ __('Location') }}</span>
                                    <span class="font-bold text-xs flex items-center" :style="darkMode ? 'color: rgba(255,255,255,0.7);' : 'color: #1a2e1d;'"><i data-lucide="map-pin" class="w-3 h-3 inline mr-1 opacity-70"></i>{{ __($animal->location) }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-dashed" :style="darkMode ? 'border-color: rgba(255,255,255,0.1);' : 'border-color: rgba(0,0,0,0.1);'">
                                    <span class="font-bold text-[10px] uppercase tracking-wider" :style="darkMode ? 'color: rgba(255,255,255,0.4);' : 'color: #94a3b8;'">{{ __('AI Score') }}</span>
                                    <div class="flex items-center gap-2 flex-1 mx-3">
                                        <div class="w-full rounded-full h-1.5 overflow-hidden" :style="darkMode ? 'background: rgba(255,255,255,0.1);' : 'background: #e2e8f0;'">
                                            <div class="h-1.5 rounded-full" style="width: {{ $animal->ai_score }}%; background: linear-gradient(90deg, #38bdf8, #0ea5e9);"></div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ $animal->ai_score }}</span>
                                </div>
                            </div>
                            <a href="{{ route('livestock.show', $animal->id) }}" class="mt-5 w-full py-2.5 rounded-xl font-bold text-xs transition-colors border shadow-sm flex items-center justify-center gap-1.5"
                                    :style="darkMode ? 'background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: white; hover:background: rgba(255,255,255,0.1);' : 'background: #ffffff; border-color: #d1e3d6; color: #1a2e1d; hover:background: #f1f5f9;'">
                                {{ __('View Details') }} <i data-lucide="{{ app()->getLocale() == 'ar' ? 'chevron-left' : 'chevron-right' }}" class="w-3.5 h-3.5 opacity-70"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- FLOATING WHATSAPP BUTTON
             ✏️  تحكم بالموضع: غير قيمة bottom و right/left أدناه
             bottom : المسافة من أسفل الشاشة  (مثال: 5rem = 80px)
             right  : المسافة من اليمين  /  left: للعربية
        -->
        <a href="https://wa.me/213XXXXXXXXX"
            target="_blank"
            rel="noopener noreferrer"
            title="{{ __('Contact us on WhatsApp') }}"
            class="fixed z-[999] group"
            style="{{ app()->getLocale() == 'ar' ? 'left:1.75rem;' : 'right:1.75rem;' }} bottom:5.5rem;">

            {{-- Pulse ring --}}
            <span class="absolute inset-0 rounded-full animate-ping opacity-40 pointer-events-none"
                  style="background:#25D366;"></span>

            {{-- Main button --}}
            <span class="relative flex items-center justify-center w-16 h-16 rounded-full transition-all duration-200 active:scale-95 border-2"
                  style="background:#25D366; border-color:rgba(255,255,255,0.35); box-shadow:0 8px 30px rgba(37,211,102,0.55);"
                  onmouseover="this.style.background='#1da851';this.style.transform='scale(1.1)';"
                  onmouseout="this.style.background='#25D366';this.style.transform='scale(1)';">
                {{-- Official WhatsApp SVG --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"
                     style="width:2rem;height:2rem;fill:white;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.3));">
                    <path d="M16.003 2.667C8.639 2.667 2.667 8.639 2.667 16c0 2.364.637 4.694 1.845 6.729L2.667 29.333l6.784-1.778A13.254 13.254 0 0 0 16.003 29.333C23.364 29.333 29.333 23.361 29.333 16S23.364 2.667 16.003 2.667zm0 2.4c5.93 0 10.93 5 10.93 10.933s-5 10.933-10.93 10.933a10.9 10.9 0 0 1-5.565-1.527l-.399-.236-4.028 1.057 1.073-3.918-.258-.413A10.9 10.9 0 0 1 5.072 16c0-5.933 5-10.933 10.931-10.933zm-3.2 5.6c-.267 0-.7.1-1.067.5-.367.4-1.4 1.367-1.4 3.333 0 1.967 1.433 3.867 1.633 4.133.2.267 2.8 4.267 6.8 5.8 3.996 1.533 3.996.933 4.663.867.667-.067 2.133-.867 2.4-1.7.267-.833.267-1.533.2-1.7-.067-.133-.267-.2-.567-.333-.3-.133-1.8-.9-2.08-.993-.28-.1-.48-.134-.667.133-.2.267-.767.967-.933 1.167-.167.2-.334.233-.634.067-.3-.167-1.267-.467-2.4-1.5-.9-.8-1.5-1.8-1.667-2.1-.167-.3-.017-.467.133-.6.133-.133.3-.333.45-.5.15-.167.2-.267.3-.467.1-.2.05-.367-.017-.5-.067-.133-.633-1.567-.887-2.133-.233-.533-.467-.433-.633-.433h-.534z"/>
                </svg>
            </span>

            {{-- Tooltip --}}
            <span class="absolute bottom-1/2 translate-y-1/2 text-white text-xs font-bold px-3 py-1.5 rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none transition-all duration-200 shadow-xl"
                  style="{{ app()->getLocale() == 'ar' ? 'right:calc(100% + 0.75rem);' : 'left:calc(100% + 0.75rem);' }} background:#111827; border:1px solid #374151;">
                {{ __('Chat on WhatsApp') }}
            </span>
        </a>

    </div>

@endsection

@push('scripts')
    <script>
        // Dashboard Charts & Map — runs after DOM ready
        document.addEventListener('DOMContentLoaded', function () {

            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

            if (typeof Chart !== 'undefined') {
                Chart.defaults.color = textColor;
                Chart.defaults.font.family = "'Inter', sans-serif";

                try { new Chart(document.getElementById('kpiChart1'), { type: 'bar', data: { labels: ['M','T','W','T','F','S','S'], datasets: [{ data: [12,19,15,25,22,30,28], backgroundColor: '#0ea5e9', borderRadius: 4 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { enabled: false } }, scales: { x: { display: false }, y: { display: false } } } }); } catch(e) {}
                try { new Chart(document.getElementById('kpiChart2'), { type: 'line', data: { labels: ['M','T','W','T','F','S','S'], datasets: [{ data: [65,59,80,81,56,55,90], borderColor: '#22c55e', borderWidth: 2, tension: 0.4, pointRadius: 0 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { enabled: false } }, scales: { x: { display: false }, y: { display: false } } } }); } catch(e) {}
                try { new Chart(document.getElementById('kpiChart3'), { type: 'bar', data: { labels: ['M','T','W','T','F','S','S'], datasets: [{ data: [400,350,450,420,500,480,450], backgroundColor: '#6366f1', borderRadius: 4 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { enabled: false } }, scales: { x: { display: false }, y: { display: false } } } }); } catch(e) {}

                try {
                    const ctxMain = document.getElementById('mainChart').getContext('2d');
                    const gradRev = ctxMain.createLinearGradient(0,0,0,400);
                    gradRev.addColorStop(0,'rgba(16,185,129,0.5)'); gradRev.addColorStop(1,'rgba(16,185,129,0)');
                    const gradExp = ctxMain.createLinearGradient(0,0,0,400);
                    gradExp.addColorStop(0,'rgba(239,68,68,0.5)'); gradExp.addColorStop(1,'rgba(239,68,68,0)');
                    new Chart(ctxMain, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($charts['labels_monthly']) !!},
                            datasets: [
                                { label: '{{ __('Revenue ($)') }}', data: {!! json_encode($charts['revenue_monthly']) !!}, borderColor: '#22c55e', backgroundColor: gradRev, borderWidth: 3, tension: 0.4, fill: true, pointBackgroundColor: '#22c55e', pointBorderColor: '#fff', pointHoverRadius: 6 },
                                { label: '{{ __('Expenses ($)') }}', data: {!! json_encode($charts['expenses_monthly']) !!}, borderColor: '#ef4444', backgroundColor: gradExp, borderWidth: 3, tension: 0.4, borderDash: [5,5], fill: true, pointBackgroundColor: '#ef4444', pointBorderColor: '#fff', pointHoverRadius: 6 }
                            ]
                        },
                        options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, plugins: { legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8, font: { weight: 'bold' } } }, tooltip: { backgroundColor: 'rgba(15,23,42,0.9)', titleColor: '#fff', bodyColor: '#cbd5e1', padding: 12, cornerRadius: 8 } }, scales: { x: { grid: { display: false, drawBorder: false } }, y: { grid: { color: gridColor, drawBorder: false }, beginAtZero: true } } }
                    });
                } catch(e) {}

                try { new Chart(document.getElementById('weightChart'), { type: 'line', data: { labels: {!! json_encode($charts['labels_monthly']) !!}, datasets: [{ label: '{{ __('Avg Weight (kg)') }}', data: {!! json_encode($charts['weight_curve']) !!}, borderColor: '#3b82f6', borderWidth: 3, tension: 0.4, pointRadius: 0, pointHoverRadius: 6 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { display: false }, y: { grid: { color: gridColor, drawBorder: false } } } } }); } catch(e) {}

                try { new Chart(document.getElementById('resourceChart'), { type: 'bar', data: { labels: ['6AM','8AM','10AM','12PM','2PM','4PM','6PM'], datasets: [{ label: '{{ __('Solar kW') }}', data: [5,12,18,25,22,15,5], backgroundColor: '#f59e0b', borderRadius: 4 }, { label: '{{ __('Water L') }}', data: [100,150,300,450,400,200,100], backgroundColor: '#0ea5e9', borderRadius: 4 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 6 } } }, scales: { x: { grid: { display: false } }, y: { grid: { color: gridColor } } } } }); } catch(e) {}
            }

            // Leaflet Map
            if (typeof L !== 'undefined' && document.getElementById('farmMap')) {
                try {
                    const map = L.map('farmMap', { zoomControl: false }).setView([34.0522, -118.2437], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map);
                    L.control.zoom({ position: 'bottomright' }).addTo(map);
                    const markersData = {!! json_encode($mapMarkers) !!};
                    const colors = { barn: '#22c55e', solar: '#f59e0b', water: '#0ea5e9', rfid: '#6366f1', vet: '#ef4444' };
                    markersData.forEach(m => {
                        const icon = L.divIcon({ className: 'custom-div-icon', html: `<div style="background-color:${colors[m.type]||'#fff'};width:16px;height:16px;border-radius:50%;border:2px solid white;box-shadow:0 0 10px ${colors[m.type]||'#fff'};"></div>`, iconSize: [16,16], iconAnchor: [8,8] });
                        L.marker([m.lat, m.lng], { icon }).bindPopup(`<b>${m.title}</b><br><span style="color:#666;font-size:10px;text-transform:uppercase;">${m.type}</span>`).addTo(map);
                    });
                    if (isDark) { const layer = document.querySelector('.leaflet-layer'); if(layer) layer.style.filter = 'invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%)'; }
                } catch(e) { console.error('Map init failed:', e); }
            }
        });
    </script>
@endpush






