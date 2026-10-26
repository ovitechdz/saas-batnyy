@extends('layouts.app')
@section('title', 'Veterinary Dashboard')

@section('content')
<div class="space-y-6" x-data="healthDash()">

    {{-- ═══ PAGE HERO ═══ --}}
    <div class="relative overflow-hidden card-3d bg-gradient-to-br from-rose-600 via-rose-700 to-slate-900 border border-rose-500/30 p-8 lg:p-10 shadow-2xl rounded-[2rem] animate-fade-up">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-rose-400/20 rounded-full blur-[100px] -mt-32 -mr-32 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-500/20 rounded-full blur-[80px] -mb-20 -ml-20 pointer-events-none"></div>

        <div class="page-hero-content relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div>
                <nav class="breadcrumb mb-3">
                    <a href="{{ route('dashboard') }}" class="breadcrumb-item flex items-center gap-1">
                        <i data-lucide="home" class="w-3 h-3"></i> {{ __('Dashboard') }}
                    </a>
                    <span class="breadcrumb-sep">/</span>
                    <span class="breadcrumb-active text-white">{{ __('Health') }}</span>
                </nav>
                <div class="flex items-center gap-3 mb-1">
                    <div class="p-2 rounded-xl" style="background: rgba(244,63,94,0.2); border: 1px solid rgba(244,63,94,0.3);">
                        <i data-lucide="heart-pulse" class="w-5 h-5 text-rose-400"></i>
                    </div>
                    <span class="text-rose-400 text-xs font-bold uppercase tracking-widest">{{ __('AI-Powered Veterinary') }}</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">
                    {{ __('Health') }} <span class="text-gradient-primary">{{ __('Command Center') }}</span>
                </h1>
                <p class="text-slate-400 mt-1.5 text-sm">{{ __('AI disease detection, vaccination tracking, and real-time health monitoring.') }}</p>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <button @click="vetVisitModal=true" class="btn-primary flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> {{ __('New Vet Visit') }}
                </button>
                <button @click="vaccinationModal=true" class="btn-secondary flex items-center gap-2">
                    <i data-lucide="syringe" class="w-4 h-4"></i> {{ __('Log Vaccination') }}
                </button>
            </div>
        </div>
    </div>

    {{-- ═══ KPI CARDS ═══ --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 animate-fade-up delay-100">

        {{-- Quarantine --}}
        <div class="modern-card p-6 group cursor-pointer hover:border-amber-500/30 hover:-translate-y-2 transition-all duration-300 border-t-4 border-t-amber-500">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/10 rounded-full blur-xl group-hover:bg-amber-500/20 transition-all"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i data-lucide="shield-alert" class="w-5 h-5"></i>
                </div>
                <span class="badge badge-warning badge-dot">{{ __('Alert') }}</span>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white tabular-nums">{{ $stats['quarantine_count'] }}</p>
            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider">{{ __('In Quarantine') }}</p>
            <div class="mt-3 progress-track">
                <div class="progress-fill bg-amber-400" style="width: {{ min(($stats['quarantine_count'] / max($stats['total_animals'] ?? 1, 1)) * 100, 100) }}%"></div>
            </div>
        </div>

        {{-- Critical --}}
        <div class="modern-card p-6 group cursor-pointer hover:border-rose-500/30 hover:-translate-y-2 transition-all duration-300 border-t-4 border-t-rose-500">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-500/10 rounded-full blur-xl group-hover:bg-rose-500/20 transition-all"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-rose-100 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i data-lucide="activity" class="w-5 h-5"></i>
                </div>
                @if($stats['critical_cases'] > 0)
                <span class="badge badge-danger badge-dot animate-pulse">{{ __('Critical') }}</span>
                @endif
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white tabular-nums">{{ $stats['critical_cases'] }}</p>
            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider">{{ __('Critical Cases') }}</p>
            <p class="text-xs text-rose-500 mt-2">{{ __('Severe diagnoses requiring urgent care') }}</p>
        </div>

        {{-- Upcoming Vaccines --}}
        <div class="modern-card p-6 group cursor-pointer hover:border-blue-500/30 hover:-translate-y-2 transition-all duration-300 border-t-4 border-t-blue-500">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-500/10 rounded-full blur-xl group-hover:bg-blue-500/20 transition-all"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i data-lucide="syringe" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white tabular-nums">{{ $stats['upcoming_vaccines'] }}</p>
            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider">{{ __('Upcoming Vaccines') }}</p>
            <p class="text-xs text-blue-500 mt-2">{{ __('Due in the next 30 days') }}</p>
        </div>

        {{-- Low Stock --}}
        <div class="modern-card p-6 group cursor-pointer hover:border-emerald-500/30 hover:-translate-y-2 transition-all duration-300 border-t-4 border-t-emerald-500">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl group-hover:bg-emerald-500/20 transition-all"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i data-lucide="pill" class="w-5 h-5"></i>
                </div>
                @if($stats['low_stock_medicines'] > 0)
                <span class="badge badge-warning">{{ __('Reorder') }}</span>
                @endif
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white tabular-nums">{{ $stats['low_stock_medicines'] }}</p>
            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider">{{ __('Low Stock Meds') }}</p>
            <p class="text-xs text-slate-400 mt-2">{{ __('Items below threshold') }}</p>
        </div>
    </div>

    {{-- ═══ MAIN CONTENT GRID ═══ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 animate-fade-up delay-200">

        {{-- LEFT: AI Predictions + Medical Records --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- AI Disease Predictions --}}
            <div class="modern-card p-6">
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-200/50 dark:border-white/[0.05]">
                    <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg gradient-purple flex items-center justify-center">
                            <i data-lucide="brain-circuit" class="w-4 h-4 text-white"></i>
                        </div>
                        {{ __('AI Disease Predictions') }}
                    </h3>
                    <span class="badge badge-purple badge-dot">{{ __('Live Model') }}</span>
                </div>

                <div class="space-y-4">
                    {{-- High Risk --}}
                    <div x-data="{ showWarning: true }" x-show="showWarning" x-transition.opacity
                         class="rounded-xl border border-rose-200 dark:border-rose-500/20
                                bg-rose-50/50 dark:bg-rose-500/5 p-4
                                hover:border-rose-300 dark:hover:border-rose-500/40 transition-colors">
                        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-500/10 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-500"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ __('Animal: EWE-045') }}</h4>
                                        <span class="badge badge-danger text-[10px]">{{ __('94% Risk') }}</span>
                                    </div>
                                    <p class="text-xs text-slate-600 dark:text-slate-400">
                                        {{ __('High risk of') }} <strong class="text-rose-600 dark:text-rose-400">{{ __('Pneumonia') }}</strong> {{ __('detected. Rapid weight loss (−2.5 kg) and elevated temp (40.2°C) via IoT sensors over 72h.') }}
                                    </p>
                                    <div class="mt-2 flex items-center gap-3 text-[10px] text-slate-400">
                                        <span class="flex items-center gap-1"><i data-lucide="thermometer" class="w-3 h-3"></i> 40.2°C</span>
                                        <span class="flex items-center gap-1"><i data-lucide="trending-down" class="w-3 h-3"></i> −2.5 kg</span>
                                        <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i> {{ __('Detected 2h ago') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2 flex-shrink-0 w-full sm:w-auto">
                                <button type="button" @click="alert('Animal EWE-045 has been moved to Quarantine Zone A.'); showWarning = false;" class="btn-danger btn-sm flex-1 sm:flex-none">{{ __('Isolate Now') }}</button>
                                <a href="{{ route('livestock.index') }}" class="btn-secondary btn-sm flex-1 sm:flex-none text-center">{{ __('View Profile') }}</a>
                            </div>
                        </div>
                    </div>

                    {{-- Medium Risk --}}
                    <div x-data="{ showWarning: true }" x-show="showWarning" x-transition.opacity
                         class="rounded-xl border border-amber-200 dark:border-amber-500/20
                                bg-amber-50/50 dark:bg-amber-500/5 p-4
                                hover:border-amber-300 dark:hover:border-amber-500/40 transition-colors">
                        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-500/10 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="footprints" class="w-5 h-5 text-amber-500"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ __('Animal: RAM-012') }}</h4>
                                        <span class="badge badge-warning text-[10px]">{{ __('78% Risk') }}</span>
                                    </div>
                                    <p class="text-xs text-slate-600 dark:text-slate-400">
                                        {{ __('Risk of') }} <strong class="text-amber-600 dark:text-amber-400">{{ __('Foot Rot') }}</strong> {{ __('detected. Movement dropped 60% via GPS over 48h.') }}
                                    </p>
                                    <div class="mt-2 flex items-center gap-3 text-[10px] text-slate-400">
                                        <span class="flex items-center gap-1"><i data-lucide="move" class="w-3 h-3"></i> {{ __('-60% mobility') }}</span>
                                        <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i> {{ __('48h trend') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2 flex-shrink-0 w-full sm:w-auto">
                                <button type="button" @click="vetVisitModal = true; showWarning = false;" class="btn-primary btn-sm flex-1 sm:flex-none">{{ __('Schedule Vet') }}</button>
                                <button type="button" @click="showWarning = false" class="btn-ghost btn-sm flex-1 sm:flex-none">{{ __('Dismiss') }}</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-200/50 dark:border-white/[0.05] flex items-center justify-between text-xs text-slate-500">
                    <span>{{ __('AI model last trained:') }} <strong class="text-slate-700 dark:text-slate-300">{{ __('Today 06:00 UTC') }}</strong></span>
                    <a href="{{ route('ai.dashboard') }}" class="text-primary-500 hover:text-primary-600 font-semibold transition-colors">
                        {{ __('View AI Hub →') }}
                    </a>
                </div>
            </div>

            {{-- Recent Medical Records --}}
            <div class="modern-card overflow-hidden" x-data="{ view: 'list' }">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200/50 dark:border-white/[0.05]">
                    <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                        </div>
                        {{ __('Recent Medical Logs') }}
                    </h3>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center p-1 bg-slate-100 dark:bg-slate-800 rounded-lg">
                            <button @click="view = 'grid'" :class="{'bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white': view === 'grid', 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300': view !== 'grid'}" class="p-1.5 rounded-md transition-all">
                                <i data-lucide="layout-grid" class="w-4 h-4"></i>
                            </button>
                            <button @click="view = 'list'" :class="{'bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white': view === 'list', 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300': view !== 'list'}" class="p-1.5 rounded-md transition-all">
                                <i data-lucide="table" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <a href="{{ route('livestock.index') }}" class="btn-ghost btn-sm">{{ __('View All →') }}</a>
                    </div>
                </div>

                {{-- List View --}}
                <div x-show="view === 'list'" class="overflow-x-auto custom-scroll">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-slate-50/50 dark:bg-black/20 border-b border-slate-200 dark:border-white/5">
                            <tr class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                                <th class="px-6 py-4">{{ __('Date') }}</th>
                                <th class="px-6 py-4">{{ __('Animal') }}</th>
                                <th class="px-6 py-4">{{ __('Diagnosis') }}</th>
                                <th class="px-6 py-4">{{ __('Veterinarian') }}</th>
                                <th class="px-6 py-4">{{ __('Treatment') }}</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                            @forelse($recentRecords as $record)
                                <tr>
                                    <td class="px-6 py-4 text-xs text-slate-500">{{ $record->created_at->diffForHumans() }}</td>
                                    <td class="px-6 py-4"><span class="font-bold text-slate-900 dark:text-white">{{ $record->animal->internal_id ?? __('Unknown') }}</span></td>
                                    <td class="px-6 py-4">
                                        @if($record->record_type === 'vaccination')
                                            <span class="badge badge-success">{{ __('Vaccination') }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ __('Treatment') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $record->veterinarian ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-xs truncate max-w-[150px]">{{ $record->treatment ?? 'N/A' }}</td>
                                    <td class="px-6 py-4"><a href="{{ $record->animal_id ? route('health.profile', $record->animal_id) : '#' }}" class="text-primary-500 hover:text-primary-600 text-xs font-semibold transition-colors">{{ __('View →') }}</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-4 text-xs text-slate-500">{{ __('Today, 09:30') }}</td>
                                    <td class="px-6 py-4"><span class="font-bold text-slate-900 dark:text-white">EWE-104</span></td>
                                    <td class="px-6 py-4"><span class="badge badge-danger">{{ __('Mastitis') }}</span></td>
                                    <td class="px-6 py-4">Dr. Sarah Jenkins</td>
                                    <td class="px-6 py-4 text-xs">{{ __('Antibiotics × 7d') }}</td>
                                    <td class="px-6 py-4"><a href="{{ isset($animals) && $animals->count() > 0 ? route('health.profile', $animals->first()->id) : route('livestock.index') }}" class="text-primary-500 hover:text-primary-600 text-xs font-semibold transition-colors">{{ __('View →') }}</a></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 text-xs text-slate-500">{{ __('Yesterday') }}</td>
                                    <td class="px-6 py-4"><span class="font-bold text-slate-900 dark:text-white">RAM-002</span></td>
                                    <td class="px-6 py-4"><span class="badge badge-neutral">{{ __('Routine Check') }}</span></td>
                                    <td class="px-6 py-4">Dr. Mark Ruffalo</td>
                                    <td class="px-6 py-4 text-xs">{{ __('None') }}</td>
                                    <td class="px-6 py-4"><a href="{{ isset($animals) && $animals->count() > 0 ? route('health.profile', $animals->first()->id) : route('livestock.index') }}" class="text-primary-500 hover:text-primary-600 text-xs font-semibold transition-colors">{{ __('View →') }}</a></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 text-xs text-slate-500">{{ __('2 days ago') }}</td>
                                    <td class="px-6 py-4"><span class="font-bold text-slate-900 dark:text-white">LAM-019</span></td>
                                    <td class="px-6 py-4"><span class="badge badge-warning">{{ __('Pneumonia') }}</span></td>
                                    <td class="px-6 py-4">Dr. Sarah Jenkins</td>
                                    <td class="px-6 py-4 text-xs">{{ __('Respiratory meds') }}</td>
                                    <td class="px-6 py-4"><a href="{{ isset($animals) && $animals->count() > 0 ? route('health.profile', $animals->first()->id) : route('livestock.index') }}" class="text-primary-500 hover:text-primary-600 text-xs font-semibold transition-colors">{{ __('View →') }}</a></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Grid View --}}
                <div x-show="view === 'grid'" style="display: none;" class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($recentRecords as $record)
                        <div class="border border-slate-200 dark:border-white/[0.05] rounded-xl p-4 bg-slate-50/50 dark:bg-black/20 hover:border-emerald-500/30 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-bold text-slate-900 dark:text-white">{{ $record->animal->internal_id ?? __('Unknown') }}</span>
                                <span class="text-[10px] font-medium text-slate-500 bg-white dark:bg-slate-800 px-2 py-1 rounded-md border border-slate-200 dark:border-white/10">{{ $record->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex items-center gap-2 mb-3">
                                @if($record->record_type === 'vaccination')
                                    <span class="badge badge-success">{{ __('Vaccination') }}</span>
                                @else
                                    <span class="badge badge-warning">{{ __('Treatment') }}</span>
                                @endif
                                <span class="text-xs text-slate-600 dark:text-slate-400 truncate max-w-[150px]" title="{{ $record->treatment ?? 'N/A' }}">{{ $record->treatment ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-slate-200/50 dark:border-white/[0.05] pt-3 mt-3">
                                <span class="text-xs text-slate-500 flex items-center gap-1"><i data-lucide="user" class="w-3 h-3"></i> {{ $record->veterinarian ?? 'N/A' }}</span>
                                <a href="{{ $record->animal_id ? route('health.profile', $record->animal_id) : '#' }}" class="text-primary-500 hover:text-primary-600 text-xs font-semibold flex items-center gap-1">{{ __('View →') }}</a>
                            </div>
                        </div>
                    @empty
                        <div class="border border-slate-200 dark:border-white/[0.05] rounded-xl p-4 bg-slate-50/50 dark:bg-black/20 hover:border-emerald-500/30 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-bold text-slate-900 dark:text-white">EWE-104</span>
                                <span class="text-[10px] font-medium text-slate-500 bg-white dark:bg-slate-800 px-2 py-1 rounded-md border border-slate-200 dark:border-white/10">{{ __('Today, 09:30') }}</span>
                            </div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="badge badge-danger">{{ __('Mastitis') }}</span>
                                <span class="text-xs text-slate-600 dark:text-slate-400 truncate">{{ __('Antibiotics × 7d') }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-slate-200/50 dark:border-white/[0.05] pt-3 mt-3">
                                <span class="text-xs text-slate-500 flex items-center gap-1"><i data-lucide="user" class="w-3 h-3"></i> Dr. Sarah Jenkins</span>
                                <a href="{{ isset($animals) && $animals->count() > 0 ? route('health.profile', $animals->first()->id) : route('livestock.index') }}" class="text-primary-500 hover:text-primary-600 text-xs font-semibold">{{ __('View →') }}</a>
                            </div>
                        </div>
                        <div class="border border-slate-200 dark:border-white/[0.05] rounded-xl p-4 bg-slate-50/50 dark:bg-black/20 hover:border-emerald-500/30 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-bold text-slate-900 dark:text-white">RAM-002</span>
                                <span class="text-[10px] font-medium text-slate-500 bg-white dark:bg-slate-800 px-2 py-1 rounded-md border border-slate-200 dark:border-white/10">{{ __('Yesterday') }}</span>
                            </div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="badge badge-neutral">{{ __('Routine Check') }}</span>
                                <span class="text-xs text-slate-600 dark:text-slate-400 truncate">{{ __('None') }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-slate-200/50 dark:border-white/[0.05] pt-3 mt-3">
                                <span class="text-xs text-slate-500 flex items-center gap-1"><i data-lucide="user" class="w-3 h-3"></i> Dr. Mark Ruffalo</span>
                                <a href="{{ isset($animals) && $animals->count() > 0 ? route('health.profile', $animals->first()->id) : route('livestock.index') }}" class="text-primary-500 hover:text-primary-600 text-xs font-semibold">{{ __('View →') }}</a>
                            </div>
                        </div>
                        <div class="border border-slate-200 dark:border-white/[0.05] rounded-xl p-4 bg-slate-50/50 dark:bg-black/20 hover:border-emerald-500/30 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-bold text-slate-900 dark:text-white">LAM-019</span>
                                <span class="text-[10px] font-medium text-slate-500 bg-white dark:bg-slate-800 px-2 py-1 rounded-md border border-slate-200 dark:border-white/10">{{ __('2 days ago') }}</span>
                            </div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="badge badge-warning">{{ __('Pneumonia') }}</span>
                                <span class="text-xs text-slate-600 dark:text-slate-400 truncate">{{ __('Respiratory meds') }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-slate-200/50 dark:border-white/[0.05] pt-3 mt-3">
                                <span class="text-xs text-slate-500 flex items-center gap-1"><i data-lucide="user" class="w-3 h-3"></i> Dr. Sarah Jenkins</span>
                                <a href="{{ isset($animals) && $animals->count() > 0 ? route('health.profile', $animals->first()->id) : route('livestock.index') }}" class="text-primary-500 hover:text-primary-600 text-xs font-semibold">{{ __('View →') }}</a>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="px-6 py-3 bg-slate-50/40 dark:bg-white/[0.02] border-t border-slate-200/50 dark:border-white/[0.05]">
                    <a href="{{ route('livestock.index') }}" class="text-xs text-primary-500 hover:text-primary-600 font-semibold transition-colors">
                        {{ __('View full medical history →') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="space-y-5">

            {{-- Medicine Pharmacy Alerts --}}
            <div class="modern-card p-5">
                <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5 mb-4 pb-3 border-b border-slate-200/50 dark:border-white/[0.05]">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="pill" class="w-4 h-4"></i>
                    </div>
                    {{ __('Pharmacy Alerts') }}
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-red-50/50 dark:bg-red-500/5 border border-red-100 dark:border-red-500/10">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ __('Penicillin G') }}</p>
                            <p class="text-xs text-red-500 mt-0.5 flex items-center gap-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i> {{ __('Only 2 units left') }}
                            </p>
                        </div>
                        <a href="{{ route('inventory.index') }}" class="btn-danger btn-sm ml-3 flex-shrink-0">{{ __('Reorder') }}</a>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-amber-50/50 dark:bg-amber-500/5 border border-amber-100 dark:border-amber-500/10">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ __('Ivermectin 1%') }}</p>
                            <p class="text-xs text-amber-500 mt-0.5 flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3"></i> {{ __('Expires in 14 days') }}
                            </p>
                        </div>
                        <a href="{{ route('inventory.index') }}" class="btn-secondary btn-sm ml-3 flex-shrink-0">{{ __('Check') }}</a>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-white/[0.05]">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ __('Tetanus Toxoid') }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ __('12 doses · Expires Jun 2026') }}</p>
                        </div>
                        <span class="badge badge-success flex-shrink-0">{{ __('OK') }}</span>
                    </div>
                </div>
                <a href="{{ route('inventory.index') }}" class="btn-ghost btn-sm mt-4 w-full justify-center">
                    {{ __('View Full Inventory →') }}
                </a>
            </div>

            {{-- Vaccination Schedule --}}
            <div class="modern-card p-5">
                <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5 mb-4 pb-3 border-b border-slate-200/50 dark:border-white/[0.05]">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <i data-lucide="calendar-check" class="w-4 h-4"></i>
                    </div>
                    {{ __('Vaccination Schedule') }}
                </h3>
                <div class="space-y-4">
                    <div class="relative pl-6 border-l-2 border-primary-500">
                        <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-primary-500 border-2 border-white dark:border-neutral-950"></div>
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Clostridial 8-Way') }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ __('Tomorrow • 150 Animals (Barn B)') }}</p>
                            </div>
                            <span class="badge badge-warning flex-shrink-0 text-[10px]">{{ __('Due') }}</span>
                        </div>
                    </div>
                    <div class="relative pl-6 border-l-2 border-slate-200 dark:border-slate-700">
                        <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-slate-300 dark:bg-slate-600 border-2 border-white dark:border-neutral-950"></div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Rabies Booster') }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ __('Next Week • 24 Guard Dogs') }}</p>
                        </div>
                    </div>
                    <div class="relative pl-6 border-l-2 border-slate-200 dark:border-slate-700">
                        <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-slate-300 dark:bg-slate-600 border-2 border-white dark:border-neutral-950"></div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Foot & Mouth Vaccine') }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ __('Aug 15 • Entire Flock') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Health Score Widget --}}
            <div class="modern-card p-5">
                <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 rounded-lg gradient-emerald flex items-center justify-center">
                        <i data-lucide="bar-chart-2" class="w-4 h-4 text-white"></i>
                    </div>
                    {{ __('Flock Health Score') }}
                </h3>
                <div class="flex items-center justify-center py-3">
                    <div class="relative w-32 h-32">
                        <svg class="w-32 h-32 -rotate-90" viewBox="0 0 36 36">
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none" stroke="rgba(0,0,0,0.06)" stroke-width="3"/>
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none" stroke="#10b981" stroke-width="3" stroke-dasharray="87, 100"
                                  stroke-linecap="round"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-black text-slate-900 dark:text-white">87</span>
                            <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider">{{ __('Good') }}</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-2 mt-2">
                    @foreach([__('Nutrition') => 92, __('Vaccination') => 88, __('Weight Trend') => 78] as $label => $val)
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-500 w-24 flex-shrink-0">{{ $label }}</span>
                        <div class="flex-1 progress-track">
                            <div class="progress-fill bg-gradient-to-r from-emerald-400 to-emerald-600" style="width: {{ $val }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 w-8 text-right">{{ $val }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ NEW VET VISIT MODAL ═══ --}}
    <template x-teleport="body">
        <div x-show="vetVisitModal" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-3 sm:p-5"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm" @click="vetVisitModal = false"></div>
            <div class="relative w-full max-w-2xl flex flex-col glass-panel rounded-2xl shadow-2xl overflow-hidden"
                 style="max-height: 90vh;"
                 x-show="vetVisitModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center border border-rose-200 dark:border-rose-500/30 shrink-0">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-black text-slate-900 dark:text-white leading-tight">{{ __('New Vet Visit') }}</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Log a new medical record.') }}</p>
                        </div>
                    </div>
                    <button @click="vetVisitModal = false" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 transition-colors shrink-0">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 px-5 py-4">
                    <form action="{{ route('health-records.store') }}" method="POST" id="vetVisitForm">
                        @csrf
                        <input type="hidden" name="record_type" value="treatment">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2 form-group">
                                <label class="label-field">{{ __('Select Animal') }}</label>
                                <select name="animal_id" required class="select-field">
                                    <option value="" disabled selected>{{ __('Select an animal...') }}</option>
                                    @foreach($animals as $animal)
                                        <option value="{{ $animal->id }}">{{ $animal->internal_id }} - {{ $animal->breed->name ?? 'Mixed' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="label-field">{{ __('Date of Visit') }}</label>
                                <input type="date" name="visit_date" required class="input-field" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label class="label-field">{{ __('Veterinarian Name') }}</label>
                                <input type="text" name="veterinarian" class="input-field" placeholder="{{ __('Dr. Name') }}">
                            </div>
                            <div class="md:col-span-2 form-group">
                                <label class="label-field">{{ __('Diagnosis / Symptoms') }}</label>
                                <textarea name="diagnosis" required class="input-field resize-none h-24" placeholder="{{ __('Describe symptoms and AI-assisted diagnosis...') }}"></textarea>
                            </div>
                            <div class="md:col-span-2 form-group">
                                <label class="label-field">{{ __('Treatment Prescribed') }}</label>
                                <input type="text" name="treatment" class="input-field" placeholder="{{ __('e.g. Antibiotics, rest, etc.') }}">
                            </div>
                            <div class="form-group">
                                <label class="label-field">{{ __('Follow-up Date') }}</label>
                                <input type="date" name="follow_up_date" class="input-field">
                            </div>
                            <div class="form-group">
                                <label class="label-field">{{ __('Cost (USD)') }}</label>
                                <input type="number" step="0.01" name="cost" class="input-field" placeholder="0.00">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800 shrink-0 flex justify-end gap-3 bg-white dark:bg-slate-900 rounded-b-2xl">
                    <button type="button" @click="vetVisitModal = false" class="btn-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" form="vetVisitForm" class="btn-primary flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4"></i> {{ __('Save Record') }}
                    </button>
                </div>
            </div>
        </div>
    </template>

    {{-- ═══ LOG VACCINATION MODAL ═══ --}}
    <template x-teleport="body">
        <div x-show="vaccinationModal" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-3 sm:p-5"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm" @click="vaccinationModal = false"></div>
            <div class="relative w-full max-w-lg flex flex-col glass-panel rounded-2xl shadow-2xl overflow-hidden"
                 style="max-height: 90vh;"
                 x-show="vaccinationModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-200 dark:border-emerald-500/30 shrink-0">
                            <i data-lucide="syringe" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-black text-slate-900 dark:text-white leading-tight">{{ __('Log Vaccination') }}</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Record an administered vaccine.') }}</p>
                        </div>
                    </div>
                    <button @click="vaccinationModal = false" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 transition-colors shrink-0">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 px-5 py-4">
                    <form action="{{ route('health-records.store') }}" method="POST" id="vaccinationForm">
                        @csrf
                        <input type="hidden" name="record_type" value="vaccination">
                        <div class="space-y-4">
                            <div class="form-group">
                                <label class="label-field">{{ __('Select Animal') }}</label>
                                <select name="animal_id" required class="select-field">
                                    <option value="" disabled selected>{{ __('Select an animal...') }}</option>
                                    @foreach($animals as $animal)
                                        <option value="{{ $animal->id }}">{{ $animal->internal_id }} - {{ $animal->breed->name ?? 'Mixed' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="label-field">{{ __('Vaccine Name / Type') }}</label>
                                <input type="text" name="treatment" required class="input-field" placeholder="{{ __('e.g. Rabies, Clostridial 8-Way') }}">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="label-field">{{ __('Date Administered') }}</label>
                                    <input type="date" name="visit_date" required class="input-field" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="form-group">
                                    <label class="label-field">{{ __('Next Due Date (Booster)') }}</label>
                                    <input type="date" name="follow_up_date" class="input-field">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="label-field">{{ __('Administered By') }}</label>
                                <input type="text" name="veterinarian" class="input-field" placeholder="{{ __('Vet or Staff Name') }}">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800 shrink-0 flex justify-end gap-3 bg-white dark:bg-slate-900 rounded-b-2xl">
                    <button type="button" @click="vaccinationModal = false" class="btn-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" form="vaccinationForm" class="btn-primary flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-4 h-4"></i> {{ __('Save Vaccination') }}
                    </button>
                </div>
            </div>
        </div>
    </template>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('healthDash', () => ({
        vetVisitModal: false,
        vaccinationModal: false,
    }));
});
</script>
@endpush
