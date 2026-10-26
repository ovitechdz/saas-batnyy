@extends('layouts.app')
@section('title', 'Livestock Management')

@section('content')
<div class="space-y-6" x-data="livestockGrid()">

    {{-- ═══ PAGE HERO ═══ --}}
    <div class="page-hero animate-fade-up">
        {{-- Background orbs --}}
        <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full opacity-20 blur-3xl pointer-events-none"
             style="background: radial-gradient(circle, #10b981, transparent 70%)"></div>
        <div class="absolute -bottom-12 -left-16 w-48 h-48 rounded-full opacity-15 blur-3xl pointer-events-none"
             style="background: radial-gradient(circle, #3b82f6, transparent 70%)"></div>

        <div class="page-hero-content flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div>
                {{-- Breadcrumb --}}
                <nav class="breadcrumb mb-3">
                    <a href="{{ route('dashboard') }}" class="breadcrumb-item flex items-center gap-1">
                        <i data-lucide="home" class="w-3 h-3"></i> {{ __('Dashboard') }}
                    </a>
                    <span class="breadcrumb-sep">/</span>
                    <span class="breadcrumb-active text-white">{{ __('Livestock') }}</span>
                </nav>
                <div class="flex items-center gap-3 mb-1">
                    <div class="p-2 rounded-xl" style="background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.3);">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10c0-2.5 1.5-4 3.5-4 .5-1.5 2-2.5 3.5-2.5S13 4.5 13.5 6c2 0 3.5 1.5 3.5 4 0 1.5-.5 2.8-1.4 3.7L15 18h-2l-.3-2H9.3L9 18H7l-.1-4.3C6.1 12.8 3 11.5 3 10z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 18v2M15 18v2"/>
                        </svg>
                    </div>
                    <span class="text-emerald-400 text-xs font-bold uppercase tracking-widest">{{ __('Farm Management') }}</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">
                    {{ __('Livestock') }} <span class="text-gradient-primary">{{ __('Directory') }}</span>
                </h1>
                <p class="text-slate-400 mt-1.5 text-sm">{{ __('Manage and monitor all') }} {{ $livestock->total() }} {{ __('animals across every barn and zone.') }}</p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto no-print">
                <button onclick="window.print()"
                        class="btn-secondary flex items-center justify-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i> {{ __('Export') }}
                </button>
                <a href="{{ route('livestock.create') }}"
                   class="btn-primary flex items-center justify-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> {{ __('Register Animal') }}
                </a>
            </div>
        </div>
    </div>

    {{-- ═══ KPI STATS ═══ --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 animate-fade-up delay-100">

        {{-- Total --}}
        <div class="modern-card p-5 border-t-[4px] border-emerald-500 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
            <div class="stat-card-orb bg-emerald-500"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="stat-card-icon bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 mb-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10c0-2.5 1.5-4 3.5-4 .5-1.5 2-2.5 3.5-2.5S13 4.5 13.5 6c2 0 3.5 1.5 3.5 4 0 1.5-.5 2.8-1.4 3.7L15 18h-2l-.3-2H9.3L9 18H7l-.1-4.3C6.1 12.8 3 11.5 3 10z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 18v2M15 18v2"/>
                    </svg>
                </div>
                <span class="badge badge-success">Live</span>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white tabular-nums">{{ number_format($livestock->total()) }}</p>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">{{ __('Total Animals') }}</p>
        </div>

        {{-- Healthy --}}
        <div class="modern-card p-5 border-t-[4px] border-sky-500 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
            <div class="stat-card-orb bg-sky-500"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="stat-card-icon bg-sky-100 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400 mb-0">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white tabular-nums">
                {{ $livestock->where('status.name', 'Healthy')->count() ?: '—' }}
            </p>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">{{ __('Healthy Animals') }}</p>
        </div>

        {{-- Under Treatment --}}
        <div class="modern-card p-5 border-t-[4px] border-rose-500 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
            <div class="stat-card-orb bg-rose-500"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="stat-card-icon bg-rose-100 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 mb-0">
                    <i data-lucide="thermometer" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white tabular-nums">
                {{ $livestock->where('status.name', 'Treatment')->count() ?: '—' }}
            </p>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">{{ __('Under Treatment') }}</p>
        </div>

        {{-- Pregnant --}}
        <div class="modern-card p-5 border-t-[4px] border-pink-500 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
            <div class="stat-card-orb bg-pink-500"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="stat-card-icon bg-pink-100 dark:bg-pink-500/10 text-pink-600 dark:text-pink-400 mb-0">
                    <i data-lucide="heart" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white tabular-nums">
                {{ $livestock->where('status.name', 'Pregnant')->count() ?: '—' }}
            </p>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">{{ __('Pregnant Ewes') }}</p>
        </div>
    </div>

    {{-- ═══ TOOLBAR: SEARCH + FILTERS + BULK ═══ --}}
    <div class="modern-card p-4 flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between animate-fade-up delay-150">

        <div class="flex flex-col sm:flex-row gap-3 flex-1">
            {{-- Search --}}
            <form method="GET" action="{{ route('livestock.index') }}" class="relative flex-1 max-w-xs">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="{{ __('ID, RFID, Name, Breed...') }}"
                       class="input-field pl-9">
            </form>

            {{-- Filter Toggle --}}
            <button @click="filterOpen = !filterOpen"
                    :class="filterOpen ? 'btn-primary' : 'btn-secondary'"
                    class="flex items-center justify-center gap-2 flex-shrink-0">
                <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                {{ __('Filters') }}
                <span x-show="filterOpen" class="text-xs">✕</span>
            </button>

            {{-- View Toggle --}}
            <div class="flex items-center gap-1 p-1 rounded-xl bg-slate-100 dark:bg-slate-800 flex-shrink-0">
                <button @click="viewMode='table'" :class="viewMode==='table' ? 'bg-white dark:bg-slate-700 shadow-sm text-slate-800 dark:text-white' : 'text-slate-400'"
                        class="p-1.5 rounded-lg transition-all duration-200">
                    <i data-lucide="table" class="w-4 h-4"></i>
                </button>
                <button @click="viewMode='grid'" :class="viewMode==='grid' ? 'bg-white dark:bg-slate-700 shadow-sm text-slate-800 dark:text-white' : 'text-slate-400'"
                        class="p-1.5 rounded-lg transition-all duration-200">
                    <i data-lucide="layout-grid" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        {{-- Bulk Actions --}}
        <div x-show="selected.length > 0"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             class="flex items-center gap-3 px-4 py-2 rounded-xl
                    bg-emerald-50 dark:bg-emerald-500/10
                    border border-emerald-200 dark:border-emerald-500/20
                    text-emerald-700 dark:text-emerald-400">
            <span class="text-sm font-semibold" x-text="selected.length + ' {{ __('selected') }}'"></span>
            <div class="h-4 w-px bg-emerald-200 dark:bg-emerald-500/30"></div>
            <form action="{{ route('livestock.bulk') }}" method="POST" class="flex items-center gap-2">
                @csrf
                <input type="hidden" name="animal_ids" :value="selected.join(',')">
                <select name="action" class="text-sm bg-transparent border-none outline-none focus:ring-0 cursor-pointer font-semibold" required>
                    <option value="" disabled selected>{{ __('Action...') }}</option>
                    <option value="vaccinate">{{ __('Log Vaccination') }}</option>
                    <option value="transfer">{{ __('Transfer Location') }}</option>
                    <option value="change_status">{{ __('Change Status') }}</option>
                    <option value="delete">{{ __('Delete Selected') }}</option>
                </select>
                <button type="submit" class="p-1.5 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 rounded-lg transition-colors">
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>
            <button @click="selected=[]" class="text-slate-400 hover:text-slate-600 transition-colors ml-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    {{-- ═══ FILTER PANEL ═══ --}}
    <div x-show="filterOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-[-8px]"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="modern-card p-5">
        <form method="GET" action="{{ route('livestock.index') }}" class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <input type="hidden" name="search" value="{{ request('search') }}">

            <div class="form-group">
                <label class="label-field">{{ __('Gender') }}</label>
                <select name="gender" class="select-field">
                    <option value="">{{ __('All Genders') }}</option>
                    <option value="male"   {{ request('gender')==='male'  ?'selected':'' }}>♂ {{ __('Male (Ram)') }}</option>
                    <option value="female" {{ request('gender')==='female'?'selected':'' }}>♀ {{ __('Female (Ewe)') }}</option>
                    <option value="wether" {{ request('gender')==='wether'?'selected':'' }}>{{ __('Wether') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label class="label-field">{{ __('Status') }}</label>
                <select name="status" class="select-field">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="Healthy"    {{ request('status')==='Healthy'   ?'selected':'' }}>{{ __('Healthy') }}</option>
                    <option value="Treatment"  {{ request('status')==='Treatment' ?'selected':'' }}>{{ __('Treatment') }}</option>
                    <option value="Pregnant"   {{ request('status')==='Pregnant'  ?'selected':'' }}>{{ __('Pregnant') }}</option>
                    <option value="Quarantine" {{ request('status')==='Quarantine'?'selected':'' }}>{{ __('Quarantine') }}</option>
                    <option value="Sold"       {{ request('status')==='Sold'      ?'selected':'' }}>{{ __('Sold') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label class="label-field">{{ __('Sort By') }}</label>
                <select name="sort" class="select-field">
                    <option value="created_at" {{ request('sort','created_at')==='created_at'?'selected':'' }}>{{ __('Date Added') }}</option>
                    <option value="internal_id" {{ request('sort')==='internal_id'?'selected':'' }}>{{ __('Animal ID') }}</option>
                    <option value="birth_date"  {{ request('sort')==='birth_date' ?'selected':'' }}>{{ __('Date of Birth') }}</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary flex-1 justify-center">{{ __('Apply Filters') }}</button>
                <a href="{{ route('livestock.index') }}" class="btn-ghost p-2.5">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- ═══ TABLE VIEW ═══ --}}
    <div x-show="viewMode === 'table'" class="modern-card overflow-hidden animate-fade-up delay-200">
        <div class="overflow-x-auto custom-scroll">
            <table class="w-full border-collapse text-sm whitespace-nowrap {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                <thead class="bg-slate-50/50 dark:bg-black/20 border-b border-slate-200 dark:border-white/5">
                    <tr class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4 w-12">
                            <input type="checkbox"
                                   @change="toggleAll"
                                   :checked="selected.length === {{ $livestock->count() }} && {{ $livestock->count() }} > 0"
                                   class="rounded border-slate-300 dark:border-slate-700 text-primary-500 focus:ring-primary-500/50 w-4 h-4 cursor-pointer">
                        </th>
                        <th class="px-6 py-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Animal') }}</th>
                        <th class="px-6 py-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Identifiers') }}</th>
                        <th class="px-6 py-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Breed / Category') }}</th>
                        <th class="px-6 py-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Weight') }}</th>
                        <th class="px-6 py-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Location') }}</th>
                        <th class="px-6 py-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Status') }}</th>
                        <th class="px-6 py-4 w-12"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                    @forelse($livestock as $animal)
                    <tr @click.self="window.location.href='{{ route('livestock.show', $animal->id) }}'"
                        class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors cursor-pointer">
                        <td class="px-6 py-4" @click.stop>
                            <input type="checkbox" value="{{ $animal->id }}" x-model="selected"
                                   class="rounded border-slate-300 dark:border-slate-700 text-primary-500 focus:ring-primary-500/50 w-4 h-4 cursor-pointer">
                        </td>

                        {{-- Animal --}}
                        <td class="px-6 py-4" @click="window.location.href='{{ route('livestock.show', $animal->id) }}'">
                            <div class="flex items-center gap-3">
                                <div class="relative flex-shrink-0">
                                    <img src="{{ $animal->primary_photo_url }}"
                                         alt="{{ $animal->internal_id }}"
                                         class="w-10 h-10 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shadow-sm">
                                    @if(($animal->gender ?? '') === 'male')
                                        <span class="absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full bg-sky-100 dark:bg-sky-900/60 text-sky-600 dark:text-sky-400 flex items-center justify-center text-[9px] font-bold border border-white dark:border-slate-800">♂</span>
                                    @elseif(($animal->gender ?? '') === 'female')
                                        <span class="absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full bg-pink-100 dark:bg-pink-900/60 text-pink-600 dark:text-pink-400 flex items-center justify-center text-[9px] font-bold border border-white dark:border-slate-800">♀</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $animal->internal_id ?? __('No ID') }}</p>
                                    <p class="text-xs text-slate-500">{{ $animal->name ?? __('Unnamed') }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Identifiers --}}
                        <td class="px-6 py-4" @click="window.location.href='{{ route('livestock.show', $animal->id) }}'">
                            <div class="flex flex-col gap-1">
                                @if($animal->electronic_tag)
                                <span class="inline-flex items-center gap-1 text-[11px] font-mono px-2 py-0.5 rounded-md w-max
                                             bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400
                                             border border-slate-200 dark:border-slate-700">
                                    <i data-lucide="radio" class="w-2.5 h-2.5"></i> {{ $animal->electronic_tag }}
                                </span>
                                @endif
                                @if($animal->visual_tag)
                                <span class="inline-flex items-center gap-1 text-[11px] font-mono px-2 py-0.5 rounded-md w-max
                                             bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400
                                             border border-amber-200 dark:border-amber-500/20">
                                    <i data-lucide="tag" class="w-2.5 h-2.5"></i> {{ $animal->visual_tag }}
                                </span>
                                @endif
                                @if(!$animal->electronic_tag && !$animal->visual_tag)
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </div>
                        </td>

                        {{-- Breed --}}
                        <td class="px-6 py-4" @click="window.location.href='{{ route('livestock.show', $animal->id) }}'">
                            <p class="font-semibold text-slate-800 dark:text-slate-200 text-sm">{{ $animal->breed->name ?? __('Mixed') }}</p>
                            <p class="text-xs text-slate-500">{{ $animal->category->name ?? __('Standard') }} • {{ $animal->age ?? '—' }}</p>
                        </td>

                        {{-- Weight --}}
                        <td class="px-6 py-4" @click="window.location.href='{{ route('livestock.show', $animal->id) }}'">
                            @php $lastWeight = $animal->weights?->last(); @endphp
                            @if($lastWeight)
                                <span class="font-bold text-slate-900 dark:text-white text-sm">
                                    {{ number_format($lastWeight->weight_kg, 1) }}
                                    <span class="text-xs font-normal text-slate-500">kg</span>
                                </span>
                            @elseif($animal->birth_weight)
                                <span class="font-bold text-slate-900 dark:text-white text-sm">
                                    {{ number_format($animal->birth_weight, 1) }}
                                    <span class="text-xs font-normal text-slate-500">kg (birth)</span>
                                </span>
                            @else
                                <span class="text-slate-400 text-sm">—</span>
                            @endif
                            @if($animal->body_condition_score)
                                <div class="text-[10px] text-slate-400 mt-0.5">BCS {{ $animal->body_condition_score }}</div>
                            @endif
                        </td>

                        {{-- Location --}}
                        <td class="px-6 py-4" @click="window.location.href='{{ route('livestock.show', $animal->id) }}'">
                            <div class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400">
                                <i data-lucide="map-pin" class="w-3 h-3 text-slate-400 flex-shrink-0"></i>
                                {{ $animal->barn->name ?? __('Unassigned') }}
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4" @click="window.location.href='{{ route('livestock.show', $animal->id) }}'">
                            @php
                                $status = $animal->status->name ?? __('Unknown');
                                $statusClass = match($status) {
                                    'Healthy'    => 'badge-success',
                                    'Treatment'  => 'badge-danger',
                                    'Pregnant'   => 'badge-purple',
                                    'Quarantine' => 'badge-warning',
                                    'Sold'       => 'badge-info',
                                    default      => 'badge-neutral',
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $status }}</span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 relative" @click.stop>
                            <button @click="openMenu = openMenu === '{{ $animal->id }}' ? null : '{{ $animal->id }}'"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <i data-lucide="more-vertical" class="w-4 h-4"></i>
                            </button>
                            <div x-show="openMenu === '{{ $animal->id }}'"
                                 @click.away="openMenu=null"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-8 top-8 w-48 modern-card shadow-xl z-50 overflow-hidden p-1">
                                <a href="{{ route('livestock.show', $animal->id) }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                    <i data-lucide="eye" class="w-4 h-4 text-slate-400"></i> {{ __('View Profile') }}
                                </a>
                                <a href="{{ route('livestock.edit', $animal->id) }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                    <i data-lucide="pencil" class="w-4 h-4 text-slate-400"></i> {{ __('Edit Details') }}
                                </a>
                                <div class="h-px bg-slate-200/60 dark:bg-white/[0.05] my-1"></div>
                                <form action="{{ route('livestock.destroy', $animal->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete {{ $animal->internal_id }}? This cannot be undone.')"
                                            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i> {{ __('Delete Animal') }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-0">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i data-lucide="paw-print" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-slate-700 dark:text-slate-300">{{ __('No animals found') }}</p>
                                    <p class="text-sm text-slate-500 mt-1">
                                        {{ request('search') ? __('Try adjusting your search or filters.') : __('Register your first animal to get started.') }}
                                    </p>
                                </div>
                                @if(!request('search'))
                                <a href="{{ route('livestock.create') }}" class="btn-primary mt-2">
                                    <i data-lucide="plus" class="w-4 h-4"></i> {{ __('Register First Animal') }}
                                </a>
                                @else
                                <a href="{{ route('livestock.index') }}" class="btn-secondary mt-2">{{ __('Clear Search') }}</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($livestock->hasPages())
        <div class="flex items-center justify-between px-5 py-4 border-t border-slate-200/50 dark:border-white/[0.05]">
            <p class="text-sm text-slate-500">
                {{ __('Showing') }} <strong class="text-slate-700 dark:text-slate-300">{{ $livestock->firstItem() }}</strong>–<strong class="text-slate-700 dark:text-slate-300">{{ $livestock->lastItem() }}</strong>
                {{ __('of') }} <strong class="text-slate-700 dark:text-slate-300">{{ number_format($livestock->total()) }}</strong> {{ __('animals') }}
            </p>
            {{ $livestock->withQueryString()->links() }}
        </div>
        @endif
    </div>

    {{-- ═══ GRID VIEW ═══ --}}
    <div x-show="viewMode === 'grid'" x-cloak
         class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 animate-fade-up delay-200">
        @forelse($livestock as $animal)
        <a href="{{ route('livestock.show', $animal->id) }}"
           class="modern-card p-5 hover-lift block group">
            <div class="flex items-start gap-4">
                <img src="{{ $animal->primary_photo_url }}"
                     alt="{{ $animal->internal_id }}"
                     class="w-14 h-14 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shadow-sm group-hover:shadow-md transition-shadow flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <p class="font-bold text-slate-900 dark:text-white text-sm truncate">{{ $animal->internal_id ?? __('No ID') }}</p>
                        @php
                            $st = $animal->status->name ?? __('Unknown');
                            $sc = match($st){ 'Healthy'=>'badge-success','Treatment'=>'badge-danger','Pregnant'=>'badge-purple','Quarantine'=>'badge-warning', default=>'badge-neutral' };
                        @endphp
                        <span class="badge {{ $sc }} flex-shrink-0 text-[10px]">{{ $st }}</span>
                    </div>
                    <p class="text-xs text-slate-500 truncate">{{ $animal->breed->name ?? __('Mixed') }}</p>
                    <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                        <i data-lucide="map-pin" class="w-2.5 h-2.5"></i>
                        {{ $animal->barn->name ?? __('Unassigned') }}
                    </p>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full">
            <div class="empty-state modern-card py-16">
                <div class="empty-state-icon"><i data-lucide="paw-print" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i></div>
                <p class="font-bold text-slate-700 dark:text-slate-300">{{ __('No animals found') }}</p>
                <a href="{{ route('livestock.create') }}" class="btn-primary">{{ __('Register Animal') }}</a>
            </div>
        </div>
        @endforelse
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('livestockGrid', () => ({
        filterOpen: false,
        openMenu: null,
        viewMode: localStorage.getItem('ovi_livestock_view') ?? 'table',
        selected: [],
        animalIds: @json($livestock->pluck('id')),

        init() {
            this.$watch('viewMode', v => localStorage.setItem('ovi_livestock_view', v));
        },

        toggleAll(e) {
            this.selected = e.target.checked ? [...this.animalIds] : [];
        }
    }));
});
</script>
@endpush
