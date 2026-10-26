@extends('layouts.app')
@section('title', 'Customers')

@section('content')
    <div class="space-y-6" x-data="{
        viewMode: localStorage.getItem('customersViewMode') || (window.innerWidth < 1024 ? 'grid' : 'table'),
        showAddModal: false,
        showProfileModal: false,
        selectedCustomer: null,
        revenue: 0,
        orders: 0,
        imagePreview: null,
        showSensitive: localStorage.getItem('customersSensitive') !== 'false',
        toggleSensitive() {
            this.showSensitive = !this.showSensitive;
            localStorage.setItem('customersSensitive', this.showSensitive);
        }
    }">

        {{-- ═══ PAGE HERO ═══ --}}
        <div class="page-hero animate-fade-up">
            <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full opacity-20 blur-3xl pointer-events-none"
                style="background: radial-gradient(circle, #10b981, transparent 70%)"></div>
            <div class="absolute -bottom-12 -left-16 w-48 h-48 rounded-full opacity-15 blur-3xl pointer-events-none"
                style="background: radial-gradient(circle, #0d9488, transparent 70%)"></div>

            <div class="page-hero-content flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div>
                    <nav class="breadcrumb mb-3">
                        <a href="{{ route('dashboard') }}" class="breadcrumb-item flex items-center gap-1">
                            <i data-lucide="home" class="w-3 h-3"></i> {{ __("Dashboard") }}
                        </a>
                        <span class="breadcrumb-sep">/</span>
                        <span class="breadcrumb-active text-white">{{ __("Customers") }}</span>
                    </nav>
                    <div class="flex items-center gap-3 mb-1">
                        <div class="p-2 rounded-xl"
                            style="background: rgba(16, 185, 129,0.2); border: 1px solid rgba(16, 185, 129,0.3);">
                            <i data-lucide="users-round" class="w-5 h-5 text-emerald-400"></i>
                        </div>
                        <span class="text-emerald-400 text-xs font-bold uppercase tracking-widest">{{ __("Logistics & Supply") }}</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">
                        {{ __("Customer") }} <span class="text-gradient-primary">{{ __("Directory") }}</span>
                    </h1>
                    <p class="text-slate-400 mt-1.5 text-sm">{{ __("Manage buyers, track purchases, and build strong client relationships.") }} {{ $customers->total() }} {{ __("registered.") }}</p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}"
                        class="btn-secondary flex items-center justify-center gap-2">
                        <i data-lucide="download" class="w-4 h-4"></i> {{ __("Export") }}
                    </a>
                    {{-- Privacy Toggle Button --}}
                    <button @click="toggleSensitive()"
                        :class="showSensitive
                            ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400 hover:bg-emerald-500/20'
                            : 'bg-rose-500/10 border-rose-500/30 text-rose-400 hover:bg-rose-500/20'"
                        class="flex items-center justify-center gap-2 px-4 py-2 rounded-xl border font-semibold text-sm transition-all duration-300 shadow-sm"
                        :title="showSensitive ? '{{ __("Hide Sensitive Info") }}' : '{{ __("Show Sensitive Info") }}'">
                        <span x-show="showSensitive" class="flex items-center gap-2">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">{{ __("Hide Info") }}</span>
                        </span>
                        <span x-show="!showSensitive" class="flex items-center gap-2">
                            <i data-lucide="eye-off" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">{{ __("Show Info") }}</span>
                        </span>
                    </button>
                    <button @click="showAddModal = true" class="btn-primary flex items-center justify-center gap-2">
                        <i data-lucide="user-plus" class="w-4 h-4"></i> {{ __("Add Customer") }}
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══ KPI STATS ═══ --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 animate-fade-up delay-100">

            <div
                class="modern-card p-5 border-t-[4px] border-emerald-500 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
                <div class="stat-card-orb bg-emerald-500"></div>
                <div class="flex items-start justify-between mb-3">
                    <div
                        class="stat-card-icon bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 mb-0">
                        <i data-lucide="users-round" class="w-5 h-5"></i>
                    </div>
                    <span class="badge badge-info">{{ __("Total") }}</span>
                </div>
                <p class="text-3xl font-black text-slate-900 dark:text-white tabular-nums">
                    {{ number_format($stats['total']) }}</p>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">{{ __("Total Customers") }}</p>
            </div>

            <div
                class="modern-card p-5 border-t-[4px] border-emerald-500 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
                <div class="stat-card-orb bg-emerald-500"></div>
                <div class="flex items-start justify-between mb-3">
                    <div
                        class="stat-card-icon bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 mb-0">
                        <i data-lucide="user-check" class="w-5 h-5"></i>
                    </div>
                    <span class="badge badge-success">{{ __("Active") }}</span>
                </div>
                <p class="text-3xl font-black text-slate-900 dark:text-white tabular-nums">{{ $stats['active'] }}</p>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">{{ __("Active Clients") }}</p>
            </div>

            <div
                class="modern-card p-5 border-t-[4px] border-amber-500 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
                <div class="stat-card-orb bg-amber-500"></div>
                <div class="flex items-start justify-between mb-3">
                    <div class="stat-card-icon bg-amber-100 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 mb-0">
                        <i data-lucide="trending-up" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-900 dark:text-white tabular-nums">
                    {{ number_format($stats['total_revenue'], 0) }}</p>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">{{ __("Total Revenue (MAD)") }}</p>
            </div>

            <div
                class="modern-card p-5 border-t-[4px] border-emerald-500 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
                <div class="stat-card-orb bg-emerald-500"></div>
                <div class="flex items-start justify-between mb-3">
                    <div class="stat-card-icon bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 mb-0">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-900 dark:text-white tabular-nums">{{ $stats['new_this_month'] }}
                </p>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">{{ __("New This Month") }}</p>
            </div>
        </div>

        {{-- ═══ TOOLBAR ═══ --}}
        <div
            class="modern-card p-4 flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between animate-fade-up delay-150">
            <form method="GET" action="{{ route('customers.index') }}" class="flex flex-col sm:flex-row gap-3 flex-1">
                <div class="relative flex-1 max-w-xs">
                    <i data-lucide="search"
                        class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __("Name, phone, city...") }}"
                        class="input-field pl-9">
                </div>

                <select name="type" class="select-field" onchange="this.form.submit()">
                    <option value="">{{ __("All Types") }}</option>
                    <option value="butcher" {{ request('type') == 'butcher' ? 'selected' : '' }}>🥩 {{ __("Butcher") }}</option>
                    <option value="wholesaler" {{ request('type') == 'wholesaler' ? 'selected' : '' }}>🏭 {{ __("Wholesaler") }}</option>
                    <option value="retailer" {{ request('type') == 'retailer' ? 'selected' : '' }}>🏪 {{ __("Retailer") }}</option>
                    <option value="individual" {{ request('type') == 'individual' ? 'selected' : '' }}>👤 {{ __("Individual") }}</option>
                </select>

                <select name="status" class="select-field" onchange="this.form.submit()">
                    <option value="">{{ __("All Status") }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __("Active") }}</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __("Inactive") }}</option>
                </select>

                <button type="submit" class="btn-primary">{{ __("Search") }}</button>
                @if(request()->hasAny(['search', 'type', 'status']))
                    <a href="{{ route('customers.index') }}" class="btn-ghost flex items-center gap-1">
                        <i data-lucide="x" class="w-4 h-4"></i> {{ __("Clear") }}
                    </a>
                @endif
            </form>

            <div class="text-xs text-slate-500 dark:text-slate-400 shrink-0">
                {{ $customers->total() }} {{ $customers->total() !== 1 ? __('customers') : __('customer') }}
            </div>
        </div>

        {{-- ═══ TABLE ═══ --}}
        <div class="modern-card overflow-hidden animate-fade-up delay-200">
            @if($customers->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 gap-4">
                    <div class="w-20 h-20 rounded-3xl bg-emerald-100 dark:bg-emerald-500/10 flex items-center justify-center">
                        <i data-lucide="users-round" class="w-10 h-10 text-emerald-400"></i>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-bold text-slate-700 dark:text-slate-300 mb-1">{{ __("No customers found") }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __("Add your first customer to get started.") }}</p>
                    </div>
                    <button @click="showAddModal = true" class="btn-primary flex items-center gap-2 mt-2">
                        <i data-lucide="user-plus" class="w-4 h-4"></i> {{ __("Add Customer") }}
                    </button>
                </div>
            @else
                {{-- Table View --}}
                <div x-show="viewMode === 'table'" class="overflow-x-auto" style="display: none;"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>{{ __("Customer") }}</th>
                                <th>{{ __("Contact") }}</th>
                                <th>{{ __("Type") }}</th>
                                <th>{{ __("Location") }}</th>
                                <th class="text-right">{{ __("Revenue (DZD)") }}</th>
                                <th class="text-center">{{ __("Orders") }}</th>
                                <th>{{ __("Last Purchase") }}</th>
                                <th>{{ __("Status") }}</th>
                                <th class="w-24 px-2 py-2 text-left">
                                    {{-- View Toggle --}}
                                    <div class="bg-slate-100 dark:bg-[#18291b] p-0.5 rounded-2xl inline-flex items-center border border-slate-200 dark:border-white/5 shadow-sm">
                                        <button @click="viewMode = 'grid'; localStorage.setItem('customersViewMode', 'grid')"
                                            :class="viewMode === 'grid' ? 'bg-white dark:bg-[#0f2312] text-emerald-600 dark:text-emerald-400 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:text-slate-500 dark:hover:text-slate-400'"
                                            class="p-2 rounded-xl transition-all duration-200" title="{{ __('Grid View') }}">
                                            <i data-lucide="layout-grid" class="w-4 h-4"></i>
                                        </button>
                                        <button @click="viewMode = 'table'; localStorage.setItem('customersViewMode', 'table')"
                                            :class="viewMode === 'table' ? 'bg-white dark:bg-[#0f2312] text-emerald-600 dark:text-emerald-400 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:text-slate-500 dark:hover:text-slate-400'"
                                            class="p-2 rounded-xl transition-all duration-200" title="{{ __('Table View') }}">
                                            <i data-lucide="layout-list" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                                @php
                                    $parts = explode(' ', trim($customer->name));
                                    $initials = mb_strtoupper(mb_substr($parts[0], 0, 1) . (count($parts) > 1 ? mb_substr(end($parts), 0, 1) : ''));
                                    $avatarColors = [
                                        'butcher' => 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400',
                                        'wholesaler' => 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400',
                                        'retailer' => 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400',
                                        'individual' => 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400',
                                    ];
                                    $typeLabels = [
                                        'butcher' => ['icon' => '🥩', 'label' => 'Butcher', 'badge' => 'badge-danger'],
                                        'wholesaler' => ['icon' => '🏭', 'label' => 'Wholesaler', 'badge' => 'badge-info'],
                                        'retailer' => ['icon' => '🏪', 'label' => 'Retailer', 'badge' => 'badge-warning'],
                                        'individual' => ['icon' => '👤', 'label' => 'Individual', 'badge' => 'badge-success'],
                                    ];
                                    $ac = $avatarColors[$customer->type] ?? $avatarColors['individual'];
                                    $tl = $typeLabels[$customer->type] ?? $typeLabels['individual'];
                                @endphp
                                <tr class="group">
                                    {{-- Customer Name & Company --}}
                                    <td>
                                        @php
                                            $customerData = [
                                                "name" => $customer->name,
                                                "company" => $customer->company,
                                                "type" => $customer->type,
                                                "photo_url" => $customer->photo_path ? $customer->photo_url : null,
                                                "initials" => $initials,
                                                "ac" => $ac,
                                                "tl" => $tl,
                                                "phone" => $customer->phone,
                                                "whatsapp" => $customer->whatsapp,
                                                "email" => $customer->email,
                                                "city" => $customer->city,
                                                "country" => $customer->country,
                                                "address" => $customer->address,
                                                "notes" => $customer->notes,
                                                "revenue" => number_format($customer->total_revenue, 0),
                                                "orders" => $customer->total_orders,
                                                "last_purchase" => $customer->last_purchase_at ? $customer->last_purchase_at->format("d M Y") : "—"
                                            ];
                                        @endphp
                                        <button type="button"
                                            @click="selectedCustomer = {{ \Illuminate\Support\Js::from($customerData) }}; showProfileModal = true"
                                            class="group/card relative flex items-center gap-3 p-2 pr-4 w-full min-w-[220px] rounded-2xl bg-white border border-slate-200 dark:bg-[#0f2312] dark:border-[#1e3d1e]/50 shadow-sm hover:shadow-md hover:-translate-y-1 hover:border-emerald-300 dark:hover:border-emerald-500/50 transition-all duration-300 text-start overflow-hidden">

                                            {{-- Background Glow on Hover --}}
                                            <div
                                                class="absolute inset-0 bg-gradient-to-r from-emerald-500/0 via-emerald-500/5 to-teal-500/0 opacity-0 group-hover/card:opacity-100 transition-opacity duration-500 pointer-events-none">
                                            </div>

                                            <div class="relative shrink-0 z-10">
                                                @if($customer->photo_path)
                                                    <img src="{{ $customer->photo_url }}"
                                                        onerror="this.outerHTML='<div class=\'w-11 h-11 rounded-xl {{ $ac }} flex items-center justify-center font-black text-sm shadow-sm ring-2 ring-white dark:ring-[#0f2312] transition-all\''>{{ $initials }}</div>'"
                                                        class="w-11 h-11 rounded-xl object-cover shadow-sm ring-2 ring-white dark:ring-[#0f2312] transition-all">
                                                @else
                                                    <div
                                                        class="w-11 h-11 rounded-xl {{ $ac }} flex items-center justify-center font-black text-sm shadow-sm ring-2 ring-white dark:ring-[#0f2312] transition-all">
                                                        {{ $initials }}
                                                    </div>
                                                @endif
                                                <div
                                                    class="absolute -bottom-1.5 -right-1.5 w-5 h-5 rounded-lg bg-white dark:bg-[#0f2312] flex items-center justify-center shadow-sm border border-slate-100 dark:border-[#1e3d1e]">
                                                    <span class="text-[10px] leading-none">{{ $tl['icon'] }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0 z-10">
                                                <p
                                                    class="font-bold text-slate-900 dark:text-white text-sm truncate group-hover/card:text-emerald-600 dark:group-hover/card:text-emerald-400 transition-colors">
                                                    {{ $customer->name }}</p>
                                                <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">
                                                    {{ $customer->company ?? '—' }}</p>
                                            </div>
                                            <div
                                                class="shrink-0 opacity-0 group-hover/card:opacity-100 transition-all duration-300 transform translate-x-2 group-hover/card:translate-x-0 z-10">
                                                <div
                                                    class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                                    <i data-lucide="{{ app()->getLocale() == 'ar' ? 'chevron-left' : 'chevron-right' }}"
                                                        class="w-3.5 h-3.5"></i>
                                                </div>
                                            </div>
                                        </button>
                                    </td>

                                    {{-- Contact --}}
                                    <td>
                                        <div class="flex flex-col gap-1">
                                            @if($customer->phone)
                                                <a href="tel:{{ $customer->phone }}"
                                                    :class="!showSensitive ? 'blur-sm select-none pointer-events-none' : ''"
                                                    class="inline-flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all"
                                                    @click.stop>
                                                    <i data-lucide="phone" class="w-3 h-3"></i> {{ $customer->phone }}
                                                </a>
                                            @endif
                                            @if($customer->whatsapp ?? $customer->phone)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->whatsapp ?? $customer->phone) }}"
                                                    target="_blank"
                                                    :class="!showSensitive ? 'blur-sm select-none pointer-events-none' : ''"
                                                    class="inline-flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 transition-all"
                                                    @click.stop>
                                                    <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24">
                                                        <path
                                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                                    </svg>
                                                    WhatsApp
                                                </a>
                                            @endif
                                            @if($customer->email)
                                                <a href="mailto:{{ $customer->email }}"
                                                    :class="!showSensitive ? 'blur-sm select-none pointer-events-none' : ''"
                                                    class="inline-flex items-center gap-1.5 text-xs text-blue-500 hover:text-blue-600 transition-all truncate max-w-[160px]"
                                                    @click.stop>
                                                    <i data-lucide="mail" class="w-3 h-3 shrink-0"></i> {{ $customer->email }}
                                                </a>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Type --}}
                                    <td>
                                        <span class="badge {{ $tl['badge'] }}">{{ $tl['icon'] }} {{ $tl['label'] }}</span>
                                    </td>

                                    {{-- Location --}}
                                    <td>
                                        <div class="flex items-center gap-1 text-sm text-slate-600 dark:text-slate-400">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                                            {{ $customer->city ?? '—' }}@if($customer->city && $customer->country),
                                            {{ $customer->country }}@endif
                                        </div>
                                    </td>

                                    {{-- Revenue --}}
                                    <td class="text-right">
                                        <p :class="!showSensitive ? 'blur-sm select-none' : ''"
                                            class="font-black text-slate-900 dark:text-white tabular-nums transition-all">
                                            {{ number_format($customer->total_revenue, 0) }}</p>
                                        <p class="text-xs text-slate-400">DZD</p>
                                    </td>

                                    {{-- Orders --}}
                                    <td class="text-center">
                                        <span :class="!showSensitive ? 'blur-sm select-none' : ''"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm transition-all">
                                            {{ $customer->total_orders }}
                                        </span>
                                    </td>

                                    {{-- Last Purchase --}}
                                    <td>
                                        @if($customer->last_purchase_at)
                                            <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">
                                                {{ $customer->last_purchase_at->format('d M Y') }}</p>
                                            <p class="text-xs text-slate-400">{{ $customer->last_purchase_at->diffForHumans() }}</p>
                                        @else
                                            <span class="text-slate-400 text-sm">—</span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        @if($customer->status === 'active')
                                            <span class="badge badge-success">{{ __("Active") }}</span>
                                        @else
                                            <span class="badge badge-muted">{{ __("Inactive") }}</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td @click.stop>
                                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            @if($customer->phone || $customer->whatsapp)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->whatsapp ?? $customer->phone) }}"
                                                    target="_blank"
                                                    class="p-1.5 text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 rounded-lg transition-colors"
                                                    title="WhatsApp">
                                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                        <path
                                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                                    </svg>
                                                </a>
                                            @endif
                                            <form method="POST" action="{{ route('customers.destroy', $customer->id) }}"
                                                onsubmit="return confirm('{{ __("Remove") }} {{ addslashes($customer->name) }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-colors"
                                                    title="Delete">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Grid View --}}
                <div x-show="viewMode === 'grid'" style="display: none;"
                     x-transition:enter="transition ease-out duration-300 delay-100"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">
                     
                    {{-- Grid Toolbar (Only visible in Grid View, aligns with Table Header) --}}
                    <div class="flex justify-end px-4 pt-4 pb-2">
                        <div class="bg-slate-100 dark:bg-[#18291b] p-0.5 rounded-2xl inline-flex items-center border border-slate-200 dark:border-white/5 shadow-sm">
                            <button @click="viewMode = 'grid'; localStorage.setItem('customersViewMode', 'grid')"
                                :class="viewMode === 'grid' ? 'bg-white dark:bg-[#0f2312] text-emerald-600 dark:text-emerald-400 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:text-slate-500 dark:hover:text-slate-400'"
                                class="p-2 rounded-xl transition-all duration-200" title="{{ __('Grid View') }}">
                                <i data-lucide="layout-grid" class="w-4 h-4"></i>
                            </button>
                            <button @click="viewMode = 'table'; localStorage.setItem('customersViewMode', 'table')"
                                :class="viewMode === 'table' ? 'bg-white dark:bg-[#0f2312] text-emerald-600 dark:text-emerald-400 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:text-slate-500 dark:hover:text-slate-400'"
                                class="p-2 rounded-xl transition-all duration-200" title="{{ __('Table View') }}">
                                <i data-lucide="layout-list" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <div class="p-4 pt-0 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($customers as $customer)
                        @php
                            $parts = explode(' ', trim($customer->name));
                            $initials = mb_strtoupper(mb_substr($parts[0], 0, 1) . (count($parts) > 1 ? mb_substr(end($parts), 0, 1) : ''));
                            $avatarColors = [
                                'butcher' => 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400',
                                'wholesaler' => 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400',
                                'retailer' => 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400',
                                'individual' => 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400',
                            ];
                            $typeLabels = [
                                'butcher' => ['icon' => '🥩', 'label' => 'Butcher', 'badge' => 'badge-danger'],
                                'wholesaler' => ['icon' => '🏭', 'label' => 'Wholesaler', 'badge' => 'badge-info'],
                                'retailer' => ['icon' => '🏪', 'label' => 'Retailer', 'badge' => 'badge-warning'],
                                'individual' => ['icon' => '👤', 'label' => 'Individual', 'badge' => 'badge-success'],
                            ];
                            $ac = $avatarColors[$customer->type] ?? $avatarColors['individual'];
                            $tl = $typeLabels[$customer->type] ?? $typeLabels['individual'];

                            $customerData = [
                                "name" => $customer->name,
                                "company" => $customer->company,
                                "type" => $customer->type,
                                "photo_url" => $customer->photo_path ? $customer->photo_url : null,
                                "initials" => $initials,
                                "ac" => $ac,
                                "tl" => $tl,
                                "phone" => $customer->phone,
                                "whatsapp" => $customer->whatsapp,
                                "email" => $customer->email,
                                "city" => $customer->city,
                                "country" => $customer->country,
                                "address" => $customer->address,
                                "notes" => $customer->notes,
                                "revenue" => number_format($customer->total_revenue, 0),
                                "orders" => $customer->total_orders,
                                "last_purchase" => $customer->last_purchase_at ? $customer->last_purchase_at->format("d M Y") : "—"
                            ];
                        @endphp

                        <div class="group relative flex flex-col p-4 rounded-2xl bg-white dark:bg-[#0f2312] border border-slate-200 dark:border-[#1e3d1e]/50 shadow-[0_4px_12px_-4px_rgba(0,0,0,0.1)] hover:shadow-[0_8px_20px_-6px_rgba(16, 185, 129,0.3)] hover:-translate-y-1 hover:border-emerald-300 dark:hover:border-emerald-500/50 transition-all duration-300 overflow-hidden cursor-pointer"
                             @click="selectedCustomer = {{ \Illuminate\Support\Js::from($customerData) }}; showProfileModal = true">

                            {{-- Background Glow on Hover --}}
                            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 via-emerald-500/5 to-teal-500/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

                            {{-- Top Section: Avatar & Badges --}}
                            <div class="relative flex items-start justify-between mb-4 z-10">
                                <div class="flex items-center gap-3">
                                    <div class="relative shrink-0">
                                        @if($customer->photo_path)
                                            <img src="{{ $customer->photo_url }}"
                                                onerror="this.outerHTML='<div class=\'w-12 h-12 rounded-xl {{ $ac }} flex items-center justify-center font-black text-sm shadow-sm ring-2 ring-white dark:ring-[#0f2312] group-hover:ring-emerald-100 dark:group-hover:ring-emerald-900/50 transition-all\'>{{ $initials }}</div>'"
                                                class="w-12 h-12 rounded-xl object-cover shadow-sm ring-2 ring-white dark:ring-[#0f2312] group-hover:ring-emerald-100 dark:group-hover:ring-emerald-900/50 transition-all">
                                        @else
                                            <div class="w-12 h-12 rounded-xl {{ $ac }} flex items-center justify-center font-black text-lg shadow-sm ring-2 ring-white dark:ring-[#0f2312] group-hover:ring-emerald-100 dark:group-hover:ring-emerald-900/50 transition-all">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                        <div class="absolute -bottom-1.5 -right-1.5 w-6 h-6 rounded-lg bg-white dark:bg-[#0f2312] flex items-center justify-center shadow-sm border border-slate-100 dark:border-[#1e3d1e]">
                                            <span class="text-xs leading-none">{{ $tl['icon'] }}</span>
                                        </div>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-slate-900 dark:text-white text-base truncate group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                            {{ $customer->name }}
                                        </h3>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                            {{ $customer->company ?? '—' }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    @if($customer->status === 'active')
                                        <span class="badge badge-success text-[10px] px-1.5 py-0.5">{{ __("Active") }}</span>
                                    @else
                                        <span class="badge badge-muted text-[10px] px-1.5 py-0.5">{{ __("Inactive") }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Mid Section: Contact & Location --}}
                            <div class="relative z-10 space-y-2 mb-4 mt-1 px-1">
                                <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i>
                                    <span class="truncate">{{ $customer->city ?? '—' }}@if($customer->city && $customer->country), {{ $customer->country }}@endif</span>
                                </div>
                                @if($customer->phone)
                                <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <i data-lucide="phone" class="w-4 h-4 text-slate-400 shrink-0"></i>
                                    <a href="tel:{{ $customer->phone }}"
                                        :class="!showSensitive ? 'blur-sm select-none pointer-events-none' : ''"
                                        class="hover:text-emerald-500 transition-all" @click.stop>{{ $customer->phone }}</a>
                                </div>
                                @endif
                                @if($customer->email)
                                <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <i data-lucide="mail" class="w-4 h-4 text-slate-400 shrink-0"></i>
                                    <a href="mailto:{{ $customer->email }}"
                                        :class="!showSensitive ? 'blur-sm select-none pointer-events-none' : ''"
                                        class="hover:text-emerald-500 truncate transition-all" @click.stop>{{ $customer->email }}</a>
                                </div>
                                @endif
                            </div>

                            {{-- Bottom Section: Stats --}}
                            <div class="relative z-10 mt-auto pt-3 border-t border-slate-100 dark:border-[#1e3d1e]/60 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">{{ __("Revenue") }}</p>
                                    <p :class="!showSensitive ? 'blur-sm select-none' : ''"
                                        class="font-black text-slate-800 dark:text-white transition-all">{{ number_format($customer->total_revenue, 0) }} <span class="text-xs font-normal text-slate-500">DZD</span></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">{{ __("Orders") }}</p>
                                    <p :class="!showSensitive ? 'blur-sm select-none' : ''"
                                        class="font-bold text-slate-800 dark:text-white transition-all">{{ $customer->total_orders }}</p>
                                </div>
                            </div>

                        </div>
                    @endforeach
                    </div>
                </div>

                {{-- Pagination --}}
                @if($customers->hasPages())
                    <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
                        {{ $customers->withQueryString()->links() }}
                    </div>
                @endif
            @endif
        </div>

        {{-- ═══ ADD CUSTOMER MODAL ═══ --}}
        <template x-teleport="body">
            <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-3 sm:p-6"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-end="opacity-0">

                <div class="absolute inset-0 bg-[#0A1409]/80 backdrop-blur-sm" @click="showAddModal = false"></div>

                <div class="relative w-full max-w-2xl flex flex-col rounded-2xl shadow-2xl border border-slate-200 dark:border-[#1e3d1e] bg-white dark:bg-[#0f2312]"
                    style="max-height: 90vh;" x-show="showAddModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                    {{-- Header --}}
                    <div
                        class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-[#1e3d1e] shrink-0">
                        <div class="flex items-center gap-3">
                            <template x-if="imagePreview">
                                <img :src="imagePreview"
                                    class="w-12 h-12 rounded-xl object-cover shrink-0 border border-slate-200 dark:border-[#1e3d1e] shadow-sm">
                            </template>
                            <template x-if="!imagePreview">
                                <div
                                    class="stat-card-icon bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 mb-0">
                                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                                </div>
                            </template>
                            <div>
                                <h2 class="text-lg font-black text-slate-900 dark:text-white">Add Customer</h2>
                                <p class="text-xs text-slate-500 dark:text-emerald-200/50">{{ __("Register a new buyer or client.") }}</p>
                            </div>
                        </div>
                        <button @click="showAddModal = false"
                            class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-[#1e3d1e] text-slate-400 transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    {{-- Scrollable Body --}}
                    <div class="overflow-y-auto flex-1 px-6 py-5">
                        <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data"
                            id="addCustomerForm" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <div class="md:col-span-2 form-group">
                                    <label class="label-field">{{ __("Profile Photo") }}</label>
                                    <input
                                        @change="imagePreview = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : null"
                                        type="file" name="photo" accept="image/*"
                                        class="input-field p-2 text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-500/10 file:text-emerald-500 hover:file:bg-emerald-500/20">
                                </div>

                                <div class="md:col-span-2 form-group">
                                    <label class="label-field">{{ __("Full Name *") }}</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i
                                                data-lucide="user" class="w-4 h-4 text-slate-400"></i></div>
                                        <input type="text" name="name" required class="input-field pl-9"
                                            placeholder="{{ __("Customer full name") }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="label-field">{{ __("Phone") }}</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i
                                                data-lucide="phone" class="w-4 h-4 text-slate-400"></i></div>
                                        <input type="text" name="phone" id="customerPhone" class="input-field pl-9"
                                            placeholder="+213 ...">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="label-field flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-emerald-500 fill-current" viewBox="0 0 24 24">
                                            <path
                                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                        </svg>
                                        WhatsApp
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-emerald-500 fill-current" viewBox="0 0 24 24">
                                                <path
                                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="whatsapp" class="input-field pl-9 pr-14"
                                            placeholder="+213 ...">
                                        <button type="button"
                                            onclick="document.querySelector('[name=whatsapp]').value = document.getElementById('customerPhone').value"
                                            class="absolute inset-y-0 right-0 pr-3 text-xs font-bold text-emerald-500 hover:text-emerald-600 transition-colors">
                                            Copy
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="label-field">{{ __("Email") }}</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i
                                                data-lucide="mail" class="w-4 h-4 text-slate-400"></i></div>
                                        <input type="email" name="email" class="input-field pl-9"
                                            placeholder="email@example.com">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="label-field">{{ __("Customer Type *") }}</label>
                                    <select name="type" required class="select-field">
                                        <option value="butcher">🥩 {{ __("Butcher") }}</option>
                                        <option value="wholesaler">🏭 {{ __("Wholesaler") }}</option>
                                        <option value="retailer">🏪 {{ __("Retailer") }}</option>
                                        <option value="individual">👤 {{ __("Individual") }}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="label-field">{{ __("Company") }}</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i
                                                data-lucide="building-2" class="w-4 h-4 text-slate-400"></i></div>
                                        <input type="text" name="company" class="input-field pl-9"
                                            placeholder="{{ __("Company (optional)") }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="label-field">{{ __("City") }}</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i
                                                data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i></div>
                                        <input type="text" name="city" class="input-field pl-9" placeholder="{{ __("e.g. Algiers") }}">
                                    </div>
                                </div>


                                <div class="md-col-span-2 form-group">
                                    <label class="label-field">{{ __("Notes") }}</label>
                                    <textarea name="notes" rows="4" class="input-field"
                                        placeholder="{{ __("Any additional notes...") }}"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Sticky Footer --}}
                    <div
                        class="px-6 py-4 border-t border-slate-100 dark:border-[#1e3d1e] shrink-0 flex justify-end gap-3 bg-white dark:bg-[#0f2312] rounded-b-2xl">
                        <button type="button" @click="showAddModal = false" class="btn-secondary">{{ __("Cancel") }}</button>
                        <button type="submit" form="addCustomerForm" class="btn-primary flex items-center gap-2">
                            <i data-lucide="user-plus" class="w-4 h-4"></i> {{ __("Add Customer") }}
                        </button>
                    </div>
                </div>
            </div>
        </template>

        {{-- ═══ PROFILE MODAL ═══ --}}
        <template x-teleport="body">
            <div x-show="showProfileModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-3 sm:p-6"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-end="opacity-0">

                <div class="absolute inset-0 bg-[#0A1409]/80 backdrop-blur-sm" @click="showProfileModal = false"></div>

                <div class="relative w-full max-w-lg flex flex-col rounded-[24px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.4)] border border-white/10 bg-[#0f2312]/90 backdrop-blur-xl overflow-hidden"
                    style="max-height: 90vh;" x-show="showProfileModal" @click.stop
                    x-transition:enter="transition ease-out duration-300 delay-100"
                    x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                    {{-- Professional Neo-Minimalist Background --}}
                    <div class="absolute inset-0 pointer-events-none">
                        <div class="absolute top-0 left-0 w-full h-40 bg-gradient-to-b from-[#22C55E]/10 to-transparent">
                        </div>
                        <div class="absolute -top-32 -left-32 w-80 h-80 bg-[#22C55E]/5 rounded-full blur-[100px]"></div>
                    </div>

                    <button @click="showProfileModal = false"
                        class="absolute top-5 right-5 p-2 rounded-full bg-[#0C1117]/80 hover:bg-[#112211] border border-white/10 text-slate-300 hover:text-white transition-all backdrop-blur-md z-30 shadow-sm">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>

                    {{-- Added padding wrapper so the scrollbar doesn't hit the rounded edges --}}
                    <div class="p-2 relative flex-1 overflow-hidden z-10">
                        <div class="px-6 pb-6 pt-8 h-full overflow-y-auto rounded-xl">
                            <template x-if="selectedCustomer">
                                <div class="w-full">

                                    <div class="mb-4">
                                        {{-- Avatar --}}
                                        <div
                                            class="relative inline-block p-1.5 bg-[#1e3d1e]/80 rounded-[1.75rem] shadow-[0_8px_30px_-5px_rgba(0,0,0,0.5)] backdrop-blur-sm border border-[#2a4d2a]/50 shrink-0">
                                            <template x-if="selectedCustomer.photo_url">
                                                <img :src="selectedCustomer.photo_url"
                                                    class="w-28 h-28 rounded-2xl object-cover shadow-inner">
                                            </template>
                                            <template x-if="!selectedCustomer.photo_url">
                                                <div
                                                    :class="['w-28 h-28 rounded-2xl flex items-center justify-center font-black text-4xl shadow-inner border-2 border-current border-opacity-20', selectedCustomer.ac]">
                                                    <span x-text="selectedCustomer.initials"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    {{-- Name & Company (Left Aligned) --}}
                                    <div class="mb-8">
                                        <div class="flex flex-wrap items-center gap-3 mb-2">
                                            <h2 class="text-3xl font-black text-white tracking-tight"
                                                x-text="selectedCustomer.name"></h2>
                                            {{-- Type Badge --}}
                                            <span
                                                :class="['badge shadow-md border border-white/10 px-3 py-1 text-[11px]', selectedCustomer.tl.badge]">
                                                <span x-html="selectedCustomer.tl.icon"></span> <span
                                                    x-text="selectedCustomer.tl.label"
                                                    class="ml-1 font-bold tracking-wide"></span>
                                            </span>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-y-2 text-sm text-slate-400 font-medium">
                                            <span
                                                class="flex items-center gap-1.5 bg-slate-800/50 px-2.5 py-1 rounded-md border border-slate-700/50">
                                                <i data-lucide="briefcase" class="w-4 h-4 text-emerald-400"></i>
                                                <span x-text="selectedCustomer.company || '—'"></span>
                                            </span>
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-700 mx-2 hidden sm:block"></span>
                                            <span
                                                class="flex items-center gap-1.5 bg-slate-800/50 px-2.5 py-1 rounded-md border border-slate-700/50">
                                                <i data-lucide="map-pin" class="w-4 h-4 text-emerald-400"></i>
                                                <span>
                                                    <span x-text="selectedCustomer.city || '—'"></span><template
                                                        x-if="selectedCustomer.city && selectedCustomer.country"><span>,
                                                        </span></template><span
                                                        x-text="selectedCustomer.country || ''"></span>
                                                </span>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Action Buttons (WhatsApp & Call) --}}
                                    <div class="grid grid-cols-2 gap-4 w-full mb-8">
                                        <a :href="showSensitive && selectedCustomer.whatsapp ? 'https://wa.me/' + selectedCustomer.whatsapp.replace(/[^0-9]/g, '') : '#'"
                                            target="_blank"
                                            :class="['flex items-center justify-center gap-2 py-3.5 px-4 rounded-xl font-bold transition-all duration-300 shadow-lg border', (showSensitive && selectedCustomer.whatsapp) ? 'bg-[#25D366]/10 hover:bg-[#25D366]/20 border-[#25D366]/30 text-[#25D366]' : 'bg-slate-800/50 text-slate-600 cursor-not-allowed border-slate-700/50']"
                                            :disabled="!showSensitive || !selectedCustomer.whatsapp">
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                                <path
                                                    d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824z" />
                                            </svg>
                                            <span>{{ __("WhatsApp") }}</span>
                                        </a>
                                        <a :href="showSensitive && selectedCustomer.phone ? 'tel:' + selectedCustomer.phone : '#'"
                                            :class="['flex items-center justify-center gap-2 py-3.5 px-4 rounded-xl font-bold transition-all duration-300 shadow-lg border', (showSensitive && selectedCustomer.phone) ? 'bg-blue-500/10 hover:bg-blue-500/20 border-blue-500/30 text-blue-400' : 'bg-slate-800/50 text-slate-600 cursor-not-allowed border-slate-700/50']"
                                            :disabled="!showSensitive || !selectedCustomer.phone">
                                            <i data-lucide="phone" class="w-5 h-5"></i>
                                            <span>{{ __("Call") }}</span>
                                        </a>
                                    </div>

                                    {{-- Premium Stats --}}
                                    <div class="grid grid-cols-2 gap-4 mb-6 w-full">
                                        <div
                                            class="relative p-5 rounded-2xl bg-gradient-to-b from-slate-800 to-slate-800/50 border border-slate-700/50 shadow-[inset_0_1px_1px_rgba(255,255,255,0.05)] overflow-hidden group text-left">
                                            <div
                                                class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 opacity-50">
                                            </div>
                                            <div
                                                class="absolute -right-6 -bottom-6 opacity-5 group-hover:opacity-10 transition-opacity duration-500">
                                                <i data-lucide="coins" class="w-24 h-24 text-emerald-400"></i>
                                            </div>
                                            <p
                                                class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5 relative z-10">
                                                Total Revenue</p>
                                            <p :class="!showSensitive ? 'blur-md select-none' : ''"
                                                class="text-2xl font-black text-emerald-400 relative z-10 transition-all duration-300">
                                                <span x-text="showSensitive ? selectedCustomer.revenue : '••••'"></span> <span
                                                    class="text-[12px] font-bold text-emerald-500/70 ml-0.5">DZD</span>
                                            </p>
                                        </div>

                                        <div
                                            class="relative p-5 rounded-2xl bg-gradient-to-b from-slate-800 to-slate-800/50 border border-slate-700/50 shadow-[inset_0_1px_1px_rgba(255,255,255,0.05)] overflow-hidden group text-left">
                                            <div
                                                class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-violet-400 to-fuchsia-500 opacity-50">
                                            </div>
                                            <div
                                                class="absolute -right-6 -bottom-6 opacity-5 group-hover:opacity-10 transition-opacity duration-500">
                                                <i data-lucide="shopping-bag" class="w-24 h-24 text-emerald-400"></i>
                                            </div>
                                            <p
                                                class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5 relative z-10">
                                                Total Orders</p>
                                            <p :class="!showSensitive ? 'blur-md select-none' : ''"
                                                class="text-2xl font-black text-emerald-400 relative z-10 transition-all duration-300"
                                                x-text="showSensitive ? selectedCustomer.orders : '••'"></p>
                                        </div>
                                    </div>

                                    {{-- Notes & Bio (Expanded & Glowing) --}}
                                    <div
                                        class="relative p-6 rounded-2xl bg-slate-800/60 border border-slate-700/50 backdrop-blur-md shadow-inner w-full min-h-[160px] text-left group hover:bg-slate-800 transition-colors">
                                        <div
                                            class="absolute top-4 right-4 text-slate-700/30 group-hover:text-slate-600/40 transition-colors">
                                            <i data-lucide="quote" class="w-16 h-16"></i>
                                        </div>
                                        <div class="relative z-10 h-full flex flex-col">
                                            <h3
                                                class="text-xs font-bold uppercase tracking-widest text-slate-400 flex items-center gap-2 mb-4">
                                                <i data-lucide="file-text" class="w-4 h-4 text-amber-400"></i> {{ __("Notes & Bio") }}
                                            </h3>
                                            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                                                <p class="text-slate-300 leading-relaxed text-[15px] font-medium"
                                                    x-text="selectedCustomer.notes || '{{ __("No additional notes provided.") }}'"></p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </template>
                        </div>
                    </div>
                </div>
        </template>


        {{-- Success Alert --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="fixed bottom-6 right-6 z-50 bg-emerald-500 text-white px-5 py-3.5 rounded-xl shadow-2xl shadow-emerald-500/30 flex items-center gap-3 font-bold text-sm"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-end="opacity-0 translate-y-4">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
        @endif

    </div>
@endsection