@extends('layouts.app')

@section('title', 'Farm Reports & Analytics')

@section('content')
<style>
    .card-3d {
        transform-style: preserve-3d;
        perspective: 1000px;
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s ease;
    }
    .card-3d:hover {
        transform: translateY(-8px) rotateX(4deg) rotateY(-2deg);
        box-shadow: 0 30px 60px -10px rgba(0,0,0,0.25), 0 15px 30px -5px rgba(16,185,129,0.15);
    }
    .card-3d-blue:hover {
        box-shadow: 0 30px 60px -10px rgba(0,0,0,0.25), 0 15px 30px -5px rgba(59,130,246,0.15);
    }
    .card-3d-pink:hover {
        box-shadow: 0 30px 60px -10px rgba(0,0,0,0.25), 0 15px 30px -5px rgba(236,72,153,0.15);
    }
    .card-3d-amber:hover {
        box-shadow: 0 30px 60px -10px rgba(0,0,0,0.25), 0 15px 30px -5px rgba(245,158,11,0.15);
    }
    .card-3d-red:hover {
        box-shadow: 0 30px 60px -10px rgba(0,0,0,0.25), 0 15px 30px -5px rgba(239,68,68,0.15);
    }

    .stat-orb {
        background: conic-gradient(from 180deg at 50% 50%, #10b981 0deg, #3b82f6 120deg, #8b5cf6 240deg, #10b981 360deg);
        animation: spin-slow 10s linear infinite;
    }
    @keyframes spin-slow {
        to { transform: rotate(360deg); }
    }

    .progress-bar-3d {
        position: relative;
        height: 12px;
        border-radius: 99px;
        background: rgba(255,255,255,0.08);
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.2), 0 1px 0 rgba(255,255,255,0.1);
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 99px;
        position: relative;
        transition: width 1.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    .progress-bar-fill::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 6px;
        right: 6px;
        height: 3px;
        background: rgba(255,255,255,0.35);
        border-radius: 99px;
    }

    .number-ring {
        position: relative;
        width: 90px;
        height: 90px;
        flex-shrink: 0;
    }
    .number-ring svg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        transform: rotate(-90deg);
    }
    .number-ring-value {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        font-weight: 800;
    }

    .glow-emerald { filter: drop-shadow(0 0 8px rgba(16,185,129,0.6)); }
    .glow-blue    { filter: drop-shadow(0 0 8px rgba(59,130,246,0.6)); }
    .glow-pink    { filter: drop-shadow(0 0 8px rgba(236,72,153,0.6)); }
    .glow-amber   { filter: drop-shadow(0 0 8px rgba(245,158,11,0.6)); }
    .glow-red     { filter: drop-shadow(0 0 8px rgba(239,68,68,0.6)); }
    .glow-purple  { filter: drop-shadow(0 0 8px rgba(139,92,246,0.6)); }

    @keyframes float-up {
        from { opacity:0; transform: translateY(24px); }
        to   { opacity:1; transform: translateY(0); }
    }
    .animate-float-up { animation: float-up 0.6s ease forwards; }
    .delay-100 { animation-delay: 0.1s; opacity: 0; }
    .delay-200 { animation-delay: 0.2s; opacity: 0; }
    .delay-300 { animation-delay: 0.3s; opacity: 0; }
    .delay-400 { animation-delay: 0.4s; opacity: 0; }
    .delay-500 { animation-delay: 0.5s; opacity: 0; }

    .segmented-bar {
        display: flex;
        height: 20px;
        border-radius: 99px;
        overflow: hidden;
        gap: 2px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15), inset 0 2px 4px rgba(0,0,0,0.1);
    }
    .seg { transition: flex 0.8s cubic-bezier(0.34,1.56,0.64,1); position: relative; overflow: hidden; }
    .seg::after {
        content: '';
        position: absolute;
        top: 3px; left: 8px; right: 8px; height: 4px;
        background: rgba(255,255,255,0.3);
        border-radius: 99px;
    }

    .badge-upcoming {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        box-shadow: 0 4px 12px rgba(245,158,11,0.4);
    }
    .badge-due {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        box-shadow: 0 4px 12px rgba(239,68,68,0.4);
    }
    .badge-ok {
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 4px 12px rgba(16,185,129,0.4);
    }

    .glass-deep {
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(32px);
        -webkit-backdrop-filter: blur(32px);
        border: 1px solid rgba(255,255,255,0.08);
    }
    .glass-light {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,0.6);
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
    }
    @media (prefers-color-scheme: dark) {
        .glass-light {
            background: rgba(15, 23, 42, 0.55);
            border: 1px solid rgba(255,255,255,0.08);
        }
    }
</style>

<div class="space-y-8 animate-fade-in">

    <!-- ===== HERO HEADER ===== -->
    <div class="animate-float-up relative overflow-hidden rounded-3xl p-8 text-white"
         style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #0f172a 100%); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
        <!-- Floating orbs in background -->
        <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full opacity-20 blur-3xl" style="background: radial-gradient(circle, #10b981, transparent 70%)"></div>
        <div class="absolute -bottom-12 -left-12 w-48 h-48 rounded-full opacity-20 blur-3xl" style="background: radial-gradient(circle, #8b5cf6, transparent 70%)"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full opacity-5 blur-3xl" style="background: radial-gradient(circle, #3b82f6, transparent 70%)"></div>

        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 rounded-xl" style="background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.3);">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="text-emerald-400 text-sm font-semibold tracking-widest uppercase">{{ __('Ovitech Analytics') }}</span>
                </div>
                <h2 class="text-4xl font-black tracking-tight mb-1">{{ __('Farm Intelligence') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-blue-400">{{ __('Report') }}</span></h2>
                <p class="text-gray-400 text-sm">{{ __('Real-time insights across all operations') }}</p>
            </div>
            <div class="flex flex-col items-end gap-3 no-print">
                <button onclick="window.print()" class="flex items-center gap-2 text-sm px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-medium backdrop-blur-md transition-colors border border-white/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    {{ __('Print Report') }}
                </button>
                <div class="flex items-center gap-2 text-sm px-4 py-2 rounded-xl" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span></span>
                    <span class="text-gray-300">{{ __('Live Data') }}</span>
                </div>
                <p class="text-gray-500 text-xs">{{ now()->format('l, M d Y — H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- ===== TOP 4 KPI CARDS ===== -->
    @php
        $total = max($flock['total'], 1);
        $healthRate = round(($flock['healthy'] / $total) * 100);
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <!-- Total Flock -->
        <div class="card-3d glass-panel rounded-2xl p-6 animate-float-up delay-100 cursor-default">
            <div class="flex items-start justify-between mb-4">
                <div class="p-3 rounded-xl glow-emerald" style="background: linear-gradient(135deg,#059669,#10b981);">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <span class="text-xs font-bold text-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-1 rounded-full">+Flock</span>
            </div>
            <p class="text-5xl font-black text-gray-900 dark:text-white leading-none">{{ $flock['total'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">{{ __('Total Animals') }}</p>
            <div class="mt-4 flex gap-3 text-xs font-semibold">
                <span class="text-blue-500">🐏 {{ $flock['rams'] }} {{ __('Rams') }}</span>
                <span class="text-pink-500">🐑 {{ $flock['ewes'] }} {{ __('Ewes') }}</span>
                <span class="text-gray-400">+{{ $flock['wethers'] }} W</span>
            </div>
        </div>

        <!-- Health Rate -->
        <div class="card-3d card-3d-blue glass-panel rounded-2xl p-6 animate-float-up delay-200 cursor-default">
            <div class="flex items-start justify-between mb-4">
                <div class="p-3 rounded-xl glow-blue" style="background: linear-gradient(135deg,#1d4ed8,#3b82f6);">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-bold {{ $healthRate >= 80 ? 'text-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'text-amber-500 bg-amber-50 dark:bg-amber-900/20' }} px-2 py-1 rounded-full">{{ $healthRate >= 80 ? __('Good') : __('Fair') }}</span>
            </div>
            <p class="text-5xl font-black text-gray-900 dark:text-white leading-none">{{ $healthRate }}<span class="text-2xl text-blue-500">%</span></p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">{{ __('Health Rate') }}</p>
            <div class="mt-4 progress-bar-3d" style="background: rgba(59,130,246,0.15);">
                <div class="progress-bar-fill" style="width: {{ $healthRate }}%; background: linear-gradient(90deg, #3b82f6, #60a5fa);"></div>
            </div>
        </div>

        <!-- Sick Alerts -->
        <div class="card-3d card-3d-red glass-panel rounded-2xl p-6 animate-float-up delay-300 cursor-default">
            <div class="flex items-start justify-between mb-4">
                <div class="p-3 rounded-xl {{ $flock['sick'] > 0 ? 'glow-red' : '' }}" style="background: {{ $flock['sick'] > 0 ? 'linear-gradient(135deg,#dc2626,#ef4444)' : 'linear-gradient(135deg,#374151,#4b5563)' }};">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                @if($flock['sick'] > 0)
                    <span class="text-xs font-bold text-red-500 bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded-full animate-pulse">{{ __('Alert') }}</span>
                @endif
            </div>
            <p class="text-5xl font-black {{ $flock['sick'] > 0 ? 'text-red-500' : 'text-gray-900 dark:text-white' }} leading-none">{{ $flock['sick'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">{{ __('Under Treatment') }}</p>
            <div class="mt-4 flex gap-3 text-xs font-semibold">
                <span class="text-amber-500">⚠️ {{ $flock['quarantined'] }} {{ __('Quarantined') }}</span>
                <span class="text-gray-400">{{ $flock['deceased'] }} {{ __('Deceased') }}</span>
            </div>
        </div>

        <!-- Pregnant -->
        <div class="card-3d card-3d-pink glass-panel rounded-2xl p-6 animate-float-up delay-400 cursor-default">
            <div class="flex items-start justify-between mb-4">
                <div class="p-3 rounded-xl glow-purple" style="background: linear-gradient(135deg,#7c3aed,#8b5cf6);">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                @if($flock['pregnant'] > 0)
                    <span class="text-xs font-bold text-purple-500 bg-purple-50 dark:bg-purple-900/20 px-2 py-1 rounded-full">{{ __('Active') }}</span>
                @endif
            </div>
            <p class="text-5xl font-black text-gray-900 dark:text-white leading-none">{{ $flock['pregnant'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">{{ __('Pregnant Ewes') }}</p>
            <div class="mt-4 flex gap-3 text-xs font-semibold">
                <span class="text-pink-500">🐣 {{ $completed }} {{ __('Births Done') }}</span>
                <span class="text-gray-400">{{ $totalCycles }} {{ __('Cycles') }}</span>
            </div>
        </div>
    </div>

    <!-- ===== FLOCK DISTRIBUTION + HEALTH RECORDS ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 animate-float-up delay-200">

        <!-- Flock Distribution (3 cols wide) -->
        <div class="lg:col-span-3 card-3d glass-panel rounded-2xl p-6 cursor-default">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold">{{ __('Flock Distribution') }}</h3>
                <span class="text-xs text-gray-400">{{ $flock['total'] }} {{ __('total') }}</span>
            </div>

            <!-- Segmented Bar -->
            @if($flock['total'] > 0)
            <div class="segmented-bar mb-5">
                @if($flock['healthy'] > 0)
                <div class="seg bg-emerald-500 rounded-l-full" style="flex: {{ $flock['healthy'] }}" title="Healthy: {{ $flock['healthy'] }}"></div>
                @endif
                @if($flock['pregnant'] > 0)
                <div class="seg bg-purple-500" style="flex: {{ $flock['pregnant'] }}" title="Pregnant: {{ $flock['pregnant'] }}"></div>
                @endif
                @if($flock['sick'] > 0)
                <div class="seg bg-red-500" style="flex: {{ $flock['sick'] }}" title="Sick: {{ $flock['sick'] }}"></div>
                @endif
                @if($flock['quarantined'] > 0)
                <div class="seg bg-amber-500" style="flex: {{ $flock['quarantined'] }}" title="Quarantined: {{ $flock['quarantined'] }}"></div>
                @endif
                @if($flock['sold'] > 0)
                <div class="seg bg-blue-400" style="flex: {{ $flock['sold'] }}" title="Sold: {{ $flock['sold'] }}"></div>
                @endif
                @if($flock['deceased'] > 0)
                <div class="seg bg-gray-500 rounded-r-full" style="flex: {{ $flock['deceased'] }}" title="Deceased: {{ $flock['deceased'] }}"></div>
                @endif
                @if($flock['total'] == 0)
                <div class="seg bg-gray-300 dark:bg-gray-700 rounded-full w-full"></div>
                @endif
            </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @php
                $items = [
                    ['label'=>__('Healthy'),     'val'=>$flock['healthy'],     'c'=>'#10b981', 'bg'=>'rgba(16,185,129,0.1)'],
                    ['label'=>__('Pregnant'),    'val'=>$flock['pregnant'],    'c'=>'#8b5cf6', 'bg'=>'rgba(139,92,246,0.1)'],
                    ['label'=>__('Sick'),        'val'=>$flock['sick'],        'c'=>'#ef4444', 'bg'=>'rgba(239,68,68,0.1)'],
                    ['label'=>__('Quarantined'), 'val'=>$flock['quarantined'], 'c'=>'#f59e0b', 'bg'=>'rgba(245,158,11,0.1)'],
                    ['label'=>__('Sold'),        'val'=>$flock['sold'],        'c'=>'#3b82f6', 'bg'=>'rgba(59,130,246,0.1)'],
                    ['label'=>__('Deceased'),    'val'=>$flock['deceased'],    'c'=>'#6b7280', 'bg'=>'rgba(107,114,128,0.1)'],
                ];
                @endphp
                @foreach($items as $item)
                <div class="flex items-center gap-3 p-3 rounded-xl" style="background: {{ $item['bg'] }}; border: 1px solid {{ $item['c'] }}22;">
                    <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background: {{ $item['c'] }}; box-shadow: 0 0 6px {{ $item['c'] }};"></div>
                    <div>
                        <p class="font-bold text-gray-900 dark:text-white">{{ $item['val'] }}</p>
                        <p class="text-xs text-gray-500">{{ $item['label'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Health Records Summary (2 cols wide) -->
        <div class="lg:col-span-2 card-3d card-3d-red glass-panel rounded-2xl p-6 cursor-default">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold">{{ __('Health Records') }}</h3>
                <div class="p-2 rounded-xl glow-red" style="background: linear-gradient(135deg,#dc2626,#ef4444);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>

            <!-- Big Stat -->
            <div class="text-center mb-6 p-4 rounded-2xl" style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15);">
                <p class="text-5xl font-black text-red-500 leading-none">{{ $recentHealthCount }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ __('Cases — last 30 days') }}</p>
            </div>

            <!-- Top Diagnoses -->
            @if($topDiagnoses->count() > 0)
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">{{ __('Top Diagnoses') }}</h4>
                <div class="space-y-2.5">
                    @foreach($topDiagnoses as $i => $diag)
                    @php $maxVal = $topDiagnoses->first()->total; @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-gray-700 dark:text-gray-300 font-medium truncate mr-2">{{ $diag->diagnosis }}</span>
                            <span class="text-xs font-bold text-gray-900 dark:text-white flex-shrink-0">{{ $diag->total }}</span>
                        </div>
                        <div class="progress-bar-3d" style="background: rgba(239,68,68,0.12);">
                            <div class="progress-bar-fill" style="width: {{ ($diag->total / max($maxVal,1)) * 100 }}%; background: linear-gradient(90deg, {{ ['#ef4444','#f97316','#f59e0b','#84cc16','#22d3ee'][$i] }}, {{ ['#fca5a5','#fdba74','#fcd34d','#bef264','#67e8f9'][$i] }});"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="text-sm">{{ __('No records yet') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ===== VACCINATIONS + BREEDING ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 animate-float-up delay-300">

        <!-- Vaccinations -->
        <div class="card-3d card-3d-blue glass-panel rounded-2xl p-6 cursor-default">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold">{{ __('Vaccination Tracker') }}</h3>
                <div class="p-2 rounded-xl glow-blue" style="background: linear-gradient(135deg,#1d4ed8,#3b82f6);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-6">
                <div class="p-4 rounded-2xl text-center" style="background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.2);">
                    <p class="text-3xl font-black text-blue-500">{{ $totalVaccinations }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ __('Total Logged') }}</p>
                </div>
                <div class="p-4 rounded-2xl text-center" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2);">
                    <p class="text-3xl font-black text-amber-500">{{ $upcomingVaccinations->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ __('Due in 30 days') }}</p>
                </div>
            </div>

            @if($upcomingVaccinations->count() > 0)
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">{{ __('Upcoming Schedule') }}</h4>
                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                    @foreach($upcomingVaccinations as $vax)
                    @php
                        $daysUntil = now()->diffInDays(\Carbon\Carbon::parse($vax->next_due_date), false);
                        $urgency = $daysUntil <= 7 ? 'badge-due' : ($daysUntil <= 14 ? 'badge-upcoming' : 'badge-ok');
                    @endphp
                    <div class="flex items-center justify-between p-3 rounded-xl" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-500 text-xs font-bold flex-shrink-0">
                                {{ mb_strtoupper(mb_substr($vax->sheep->name ?? $vax->sheep->rfid_tag ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $vax->sheep->name ?? $vax->sheep->rfid_tag ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">{{ $vax->vaccine_name }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-white px-2.5 py-1 rounded-full {{ $urgency }}">
                            {{ \Carbon\Carbon::parse($vax->next_due_date)->format('M d') }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-gray-400">
                    <div class="text-3xl mb-2">🎉</div>
                    <p class="text-sm font-medium">{{ __('All vaccinations up to date!') }}</p>
                </div>
            @endif
        </div>

        <!-- Breeding Report -->
        <div class="card-3d card-3d-pink glass-panel rounded-2xl p-6 cursor-default">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold">{{ __('Breeding Overview') }}</h3>
                <div class="p-2 rounded-xl glow-pink" style="background: linear-gradient(135deg,#be185d,#ec4899);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 mb-6">
                <div class="p-4 rounded-2xl text-center" style="background: rgba(236,72,153,0.1); border: 1px solid rgba(236,72,153,0.2);">
                    <p class="text-3xl font-black text-pink-500">{{ $pregnant }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ __('Pregnant') }}</p>
                </div>
                <div class="p-4 rounded-2xl text-center" style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2);">
                    <p class="text-3xl font-black text-emerald-500">{{ $completed }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ __('Completed') }}</p>
                </div>
                <div class="p-4 rounded-2xl text-center" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);">
                    <p class="text-3xl font-black text-red-500">{{ $failedCycles }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ __('Failed') }}</p>
                </div>
            </div>

            @if($expectedBirths->count() > 0)
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">{{ __('Expected Births') }}</h4>
                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                    @foreach($expectedBirths as $cycle)
                    @php
                        $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($cycle->expected_lambing_date), false);
                    @endphp
                    <div class="flex items-center justify-between p-3 rounded-xl" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0" style="background: linear-gradient(135deg,#be185d,#ec4899);">
                                🐑
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $cycle->ewe->name ?? $cycle->ewe->rfid_tag ?? 'Unknown Ewe' }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($cycle->expected_lambing_date)->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full text-white" style="background: linear-gradient(135deg,#7c3aed,#8b5cf6); box-shadow: 0 4px 12px rgba(139,92,246,0.4);">
                            {{ $daysLeft > 0 ? __('in') . ' ' . $daysLeft . ' ' . __('d') : __('Soon') }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-gray-400">
                    <div class="text-3xl mb-2">🌱</div>
                    <p class="text-sm font-medium">{{ __('No upcoming births recorded') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ===== FEED INVENTORY ===== -->
    <div class="card-3d card-3d-amber glass-panel rounded-2xl p-6 animate-float-up delay-400 cursor-default">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold flex items-center gap-3">
                <div class="p-2 rounded-xl glow-amber" style="background: linear-gradient(135deg,#b45309,#f59e0b);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                {{ __('Feed Inventory Status') }}
            </h3>
            @if($lowFeedAlerts > 0)
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full text-white text-xs font-bold animate-pulse" style="background: linear-gradient(135deg,#dc2626,#ef4444); box-shadow: 0 4px 12px rgba(239,68,68,0.4);">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path></svg>
                {{ $lowFeedAlerts }} {{ __('Low Stock') }}
            </div>
            @endif
        </div>

        @if($feedItems->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($feedItems as $item)
                @php
                    $pct = min(100, ($item->quantity_kg / max($item->reorder_level_kg * 3, 1)) * 100);
                    $isLow = $item->quantity_kg <= $item->reorder_level_kg;
                    $barColor = $isLow ? 'linear-gradient(90deg,#dc2626,#ef4444)' : 'linear-gradient(90deg,#d97706,#f59e0b)';
                    $glowColor = $isLow ? 'rgba(239,68,68,0.3)' : 'rgba(245,158,11,0.3)';
                @endphp
                <div class="p-4 rounded-2xl relative overflow-hidden" style="background: rgba(255,255,255,0.04); border: 1px solid {{ $isLow ? 'rgba(239,68,68,0.3)' : 'rgba(255,255,255,0.08)' }};">
                    @if($isLow)
                    <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-20" style="background: radial-gradient(circle, #ef4444, transparent)"></div>
                    @endif
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $item->feed_type }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ __('Reorder:') }} {{ number_format($item->reorder_level_kg, 0) }} kg</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-black {{ $isLow ? 'text-red-500' : 'text-amber-500' }}">{{ number_format($item->quantity_kg, 0) }}</p>
                            <p class="text-xs text-gray-400">kg</p>
                        </div>
                    </div>
                    <div class="progress-bar-3d" style="background: rgba(0,0,0,0.15);">
                        <div class="progress-bar-fill" style="width: {{ $pct }}%; background: {{ $barColor }}; box-shadow: 0 2px 8px {{ $glowColor }};"></div>
                    </div>
                    @if($isLow)
                    <p class="text-xs text-red-400 font-semibold mt-2 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path></svg>
                        {{ __('Reorder Required') }}
                    </p>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <p class="font-medium">{{ __('No inventory items found.') }}</p>
                <a href="{{ route('inventory.index') }}" class="text-amber-500 hover:text-amber-400 text-sm mt-1 inline-block transition-colors">{{ __('Add feed stock →') }}</a>
            </div>
        @endif
    </div>

</div>
@endsection
