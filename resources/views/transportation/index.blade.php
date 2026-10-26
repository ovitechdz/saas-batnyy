@extends('layouts.app')
@section('title', 'Transportation')

@section('content')

<div class="p-4 sm:p-8 space-y-8 animate-fade-in pb-32" x-data="{
    showShipmentModal: false,
    showVehicleModal: false,
    activeShipment: null,
    showDetailPanel: false,
    openDetail(shipment) { this.activeShipment = shipment; this.showDetailPanel = true; }
}">

    {{-- ═══ PAGE HERO ═══ --}}
    <div class="relative overflow-hidden card-3d bg-gradient-to-br from-emerald-600 via-emerald-700 to-slate-900 border border-emerald-500/30 p-8 lg:p-10 shadow-2xl rounded-[2rem] animate-fade-up">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-400/20 rounded-full blur-[100px] -mt-32 -mr-32 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-teal-500/20 rounded-full blur-[80px] -mb-20 -ml-20 pointer-events-none"></div>

        <div class="page-hero-content relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div>
                {{-- Breadcrumb --}}
                <nav class="breadcrumb mb-3" style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                    <a href="{{ route('dashboard') }}" class="text-white/70 hover:text-white transition-colors flex items-center gap-1">
                        <i data-lucide="home" class="w-3 h-3"></i> {{ __('Dashboard') }}
                    </a>
                    <span class="text-white/40">/</span>
                    <span class="text-white font-medium">{{ __('Logistics') }}</span>
                </nav>
                
                <div class="flex items-center gap-3 mb-1">
                    <div class="p-2 rounded-xl" style="background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.3);">
                        <i data-lucide="truck" class="w-5 h-5 text-emerald-400"></i>
                    </div>
                    <span class="text-emerald-400 text-xs font-bold uppercase tracking-widest">{{ __('Supply Chain') }}</span>
                </div>
                
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">
                    {{ __('Transportation') }} <span class="text-gradient-primary">& {{ __('Fleet') }}</span>
                </h1>
                <p class="text-white/70 mt-1.5 text-sm max-w-xl">{{ __('Manage livestock shipments, track deliveries, and monitor your vehicle fleet in real time.') }}</p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto no-print">
                <button @click="showVehicleModal = true"
                        class="btn-secondary flex items-center justify-center gap-2">
                    <i data-lucide="truck" class="w-4 h-4"></i> {{ __('Add Vehicle') }}
                </button>
                <button @click="showShipmentModal = true"
                        class="btn-primary flex items-center justify-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> {{ __('New Shipment') }}
                </button>
            </div>
        </div>
    </div>

    <!-- ===== KPI STATS ===== -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
        $kpis = [
            ['label' => __('In Transit'), 'value' => $stats['active'], 'icon' => 'navigation', 'color' => 'blue', 'suffix' => ''],
            ['label' => __('Scheduled Today'), 'value' => $stats['today'], 'icon' => 'calendar-clock', 'color' => 'amber', 'suffix' => ''],
            ['label' => __('Delivered Total'), 'value' => $stats['delivered'], 'icon' => 'package-check', 'color' => 'emerald', 'suffix' => ''],
            ['label' => __('Fleet Available'), 'value' => $stats['vehicles_available'].'/'.$stats['vehicles_total'], 'icon' => 'truck', 'color' => 'purple', 'suffix' => ''],
        ];
        $colorMap = [
            'blue'   => ['bg' => 'bg-blue-500/10 dark:bg-blue-500/20', 'icon' => 'text-blue-600 dark:text-blue-400', 'glow' => 'shadow-blue-500/20'],
            'amber'  => ['bg' => 'bg-amber-500/10 dark:bg-amber-500/20', 'icon' => 'text-amber-600 dark:text-amber-400', 'glow' => 'shadow-amber-500/20'],
            'emerald'=> ['bg' => 'bg-emerald-500/10 dark:bg-emerald-500/20', 'icon' => 'text-emerald-600 dark:text-emerald-400', 'glow' => 'shadow-emerald-500/20'],
            'purple' => ['bg' => 'bg-purple-500/10 dark:bg-purple-500/20', 'icon' => 'text-purple-600 dark:text-purple-400', 'glow' => 'shadow-purple-500/20'],
        ];
        @endphp
        @foreach($kpis as $kpi)
        @php $c = $colorMap[$kpi['color']]; @endphp
        <div class="modern-card p-6 group cursor-pointer hover:-translate-y-2 hover:shadow-xl transition-all duration-300 border-t-4 border-t-{{ $kpi['color'] }}-500">
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 rounded-xl {{ $c['bg'] }} flex items-center justify-center shadow-lg {{ $c['glow'] }}">
                    <i data-lucide="{{ $kpi['icon'] }}" class="w-5 h-5 {{ $c['icon'] }}"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900 dark:text-white tabular-nums">{{ $kpi['value'] }}</p>
            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider">{{ $kpi['label'] }}</p>
        </div>
        @endforeach
    </div>

    <!-- ===== VEHICLE FLEET ===== -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                <i data-lucide="truck" class="w-5 h-5 text-emerald-500"></i> {{ __('Vehicle Fleet') }}
            </h2>
            <button @click="showVehicleModal = true" class="text-sm font-bold text-emerald-500 hover:text-emerald-600 flex items-center gap-1">
                <i data-lucide="plus" class="w-4 h-4"></i> {{ __('Add Vehicle') }}
            </button>
        </div>

        @if($vehicles->isEmpty())
        <div class="modern-card p-8 text-center">
            <i data-lucide="truck" class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-3"></i>
            <p class="text-slate-500 dark:text-slate-400 font-medium">{{ __('No vehicles registered yet.') }}</p>
            <button @click="showVehicleModal = true" class="mt-4 text-sm font-bold text-emerald-500 hover:underline">{{ __('+ Add your first vehicle') }}</button>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($vehicles as $vehicle)
            @php
                $statusColors = [
                    'available'   => ['front' => 'from-emerald-600 to-emerald-900', 'back' => 'from-emerald-700 to-emerald-950', 'badge' => __('Available')],
                    'in_transit'  => ['front' => 'from-sky-600 to-sky-900', 'back' => 'from-sky-700 to-sky-950', 'badge' => __('In Transit')],
                    'maintenance' => ['front' => 'from-amber-600 to-amber-900', 'back' => 'from-amber-700 to-amber-950', 'badge' => __('Maintenance')],
                ];
                $sc = $statusColors[$vehicle->status] ?? $statusColors['available'];
                $icons = ['truck' => 'truck', 'trailer' => 'package', 'van' => 'bus', 'pickup' => 'car'];
                $vIcon = $icons[$vehicle->type] ?? 'truck';
            @endphp
            <div class="vehicle-card" style="height: 200px;">
                <div class="vehicle-card-inner" style="height: 200px;">
                    <!-- Front -->
                    <div class="vehicle-card-front bg-gradient-to-br {{ $sc['front'] }} shadow-xl border border-white/10">
                        <div class="flex items-start justify-between">
                            <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center border border-white/30">
                                <i data-lucide="{{ $vIcon }}" class="w-6 h-6 text-white"></i>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-white/20 text-white border border-white/20">{{ $sc['badge'] }}</span>
                        </div>
                        <div>
                            <p class="text-white font-black text-xl leading-tight">{{ $vehicle->name }}</p>
                            <p class="text-white/70 text-sm mt-0.5">{{ $vehicle->plate_number ?? 'No plate' }} · {{ __($vehicle->type) }}</p>
                            <p class="text-white/50 text-xs mt-1 flex items-center gap-1">
                                <i data-lucide="users" class="w-3 h-3"></i> {{ $vehicle->capacity_animals ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <!-- Back -->
                    <div class="vehicle-card-back bg-gradient-to-br {{ $sc['back'] }} shadow-xl border border-white/10">
                        <p class="text-white/70 text-xs font-bold uppercase tracking-wider mb-3">{{ __('Driver Info') }}</p>
                        @if($vehicle->driver_name)
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center"><i data-lucide="user" class="w-4 h-4 text-white"></i></div>
                            <div>
                                <p class="text-white font-bold text-sm">{{ $vehicle->driver_name }}</p>
                                <p class="text-white/60 text-xs">{{ $vehicle->driver_phone ?? '—' }}</p>
                            </div>
                        </div>
                        @else
                        <p class="text-white/50 text-sm">{{ __('No driver assigned') }}</p>
                        @endif
                        <div class="mt-auto pt-3 border-t border-white/10">
                            <p class="text-white/50 text-xs">{{ __('Hover to flip • Click to manage') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- ===== SHIPMENTS TABLE ===== -->
    <div class="modern-card overflow-hidden">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <h2 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                <i data-lucide="package" class="w-5 h-5 text-emerald-500"></i> {{ __('Shipments') }}
            </h2>
            <form method="GET" class="flex items-center gap-3 flex-wrap">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search shipments...') }}" class="pl-9 pr-4 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none w-52">
                </div>
                <select name="status" class="py-2 px-3 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>{{ __('Scheduled') }}</option>
                    <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>{{ __('In Transit') }}</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                </select>
                <button type="submit" class="px-4 py-2 text-sm font-bold bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-colors">{{ __('Filter') }}</button>
            </form>
        </div>

        @if($shipments->isEmpty())
        <div class="p-16 text-center">
            <div class="w-20 h-20 rounded-3xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="package" class="w-10 h-10 text-emerald-500"></i>
            </div>
            <h3 class="text-lg font-black text-slate-700 dark:text-slate-300 mb-2">{{ __('No shipments yet') }}</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-6">{{ __('Schedule your first livestock shipment to get started.') }}</p>
            <button @click="showShipmentModal = true" class="inline-flex items-center gap-2 bg-emerald-500 text-white font-bold py-3 px-6 rounded-xl hover:bg-emerald-600 transition-colors">
                <i data-lucide="plus" class="w-5 h-5"></i> {{ __('New Shipment') }}
            </button>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                        <th class="{{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-6 py-3">{{ __('Reference') }}</th>
                        <th class="{{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-6 py-3">{{ __('Route') }}</th>
                        <th class="{{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-6 py-3">{{ __('Animals') }}</th>
                        <th class="{{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-6 py-3">{{ __('Vehicle') }}</th>
                        <th class="{{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-6 py-3">{{ __('Date') }}</th>
                        <th class="{{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-6 py-3">{{ __('Status') }}</th>
                        <th class="{{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-6 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($shipments as $shipment)
                    @php
                        $statusConfig = [
                            'scheduled'  => ['class' => 'badge-amber', 'icon' => 'clock', 'label' => __('Scheduled')],
                            'in_transit' => ['class' => 'badge-blue', 'icon' => 'navigation', 'label' => __('In Transit')],
                            'delivered'  => ['class' => 'badge-emerald', 'icon' => 'check-circle', 'label' => __('Delivered')],
                            'cancelled'  => ['class' => 'badge-red', 'icon' => 'x-circle', 'label' => __('Cancelled')],
                        ];
                        $sc = $statusConfig[$shipment->status] ?? $statusConfig['scheduled'];
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-sm text-emerald-600 dark:text-emerald-400">{{ $shipment->reference }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 text-sm">
                                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $shipment->origin }}</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 text-slate-400 shrink-0"></i>
                                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $shipment->destination }}</span>
                            </div>
                            @if($shipment->distance_km)
                            <p class="text-xs text-slate-400 mt-0.5">{{ number_format($shipment->distance_km) }} km</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10c0-2.5 1.5-4 3.5-4 .5-1.5 2-2.5 3.5-2.5S13 4.5 13.5 6c2 0 3.5 1.5 3.5 4 0 1.5-.5 2.8-1.4 3.7L15 18h-2l-.3-2H9.3L9 18H7l-.1-4.3C6.1 12.8 3 11.5 3 10z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 18v2M15 18v2"/>
                                </svg>
                                <span class="font-bold text-slate-900 dark:text-white">{{ $shipment->animal_count }}</span>
                                <span class="text-slate-500 text-sm">{{ __($shipment->animal_type) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-600 dark:text-slate-400">
                                {{ $shipment->vehicle?->name ?? '—' }}
                            </div>
                            @if($shipment->driver_name)
                            <p class="text-xs text-slate-400">{{ $shipment->driver_name }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ $shipment->scheduled_at?->format('d M Y') ?? '—' }}
                            </div>
                            <p class="text-xs text-slate-400">{{ $shipment->scheduled_at?->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge {{ $sc['class'] }}">
                                <i data-lucide="{{ $sc['icon'] }}" class="w-3 h-3"></i>
                                {{ $sc['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                @if($shipment->status === 'scheduled')
                                <form method="POST" action="{{ route('transportation.status', $shipment->id) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="in_transit">
                                    <button type="submit" class="text-xs font-bold px-3 py-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">{{ __('Depart') }}</button>
                                </form>
                                @elseif($shipment->status === 'in_transit')
                                <form method="POST" action="{{ route('transportation.status', $shipment->id) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="delivered">
                                    <button type="submit" class="text-xs font-bold px-3 py-1.5 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">{{ __('Mark Delivered') }}</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($shipments->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $shipments->links() }}
        </div>
        @endif
        @endif
    </div>

    <!-- ===== NEW SHIPMENT MODAL ===== -->
    <template x-teleport="body">
        <div x-show="showShipmentModal" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-3 sm:p-5"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm" @click="showShipmentModal = false"></div>
        <div class="relative w-full max-w-2xl flex flex-col glass-panel rounded-2xl shadow-2xl overflow-hidden"
             style="max-height: 90vh;"
             x-show="showShipmentModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-200 dark:border-emerald-500/30 shrink-0">
                        <i data-lucide="truck" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-slate-900 dark:text-white leading-tight">{{ __('New Shipment') }}</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Schedule a livestock transport.') }}</p>
                    </div>
                </div>
                <button @click="showShipmentModal = false" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 transition-colors shrink-0">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Scrollable Body -->
            <div class="overflow-y-auto flex-1 px-5 py-4">
                <form action="{{ route('transportation.store') }}" method="POST" id="shipmentForm">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="label-field">{{ __('Origin') }}</label>
                            <div class="relative"><div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i></div>
                            <input type="text" name="origin" required class="input-field pl-9" placeholder="{{ __('Origin') }}"></div>
                        </div>
                        <div class="form-group">
                            <label class="label-field">{{ __('Destination') }}</label>
                            <div class="relative"><div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i></div>
                            <input type="text" name="destination" required class="input-field pl-9" placeholder="{{ __('Destination') }}"></div>
                        </div>
                        <div class="form-group">
                            <label class="label-field">{{ __('Animal Count') }}</label>
                            <div class="relative"><div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i data-lucide="paw-print" class="w-4 h-4 text-slate-400"></i></div>
                            <input type="number" name="animal_count" required min="1" class="input-field pl-9" placeholder="25"></div>
                        </div>
                        <div class="form-group">
                            <label class="label-field">{{ __('Animal Type') }}</label>
                            <select name="animal_type" class="select-field">
                                <option value="Sheep">{{ __('Sheep') }}</option>
                                <option value="Cattle">{{ __('Cattle') }}</option>
                                <option value="Goat">{{ __('Goat') }}</option>
                                <option value="Mixed">{{ __('Mixed') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="label-field">{{ __('Driver Name') }}</label>
                            <div class="relative"><div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i data-lucide="user" class="w-4 h-4 text-slate-400"></i></div>
                            <input type="text" name="driver_name" class="input-field pl-9" placeholder="{{ __('Driver Name') }}"></div>
                        </div>
                        <div class="form-group">
                            <label class="label-field">{{ __('Vehicle') }}</label>
                            <select name="vehicle_id" class="select-field">
                                <option value="">{{ __('-- Select Vehicle --') }}</option>
                                @foreach($vehicles as $v)
                                <option value="{{ $v->id }}">{{ $v->name }} ({{ $v->plate_number }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="label-field">{{ __('Departure Date & Time') }}</label>
                            <div class="relative"><div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i></div>
                            <input type="datetime-local" name="scheduled_at" required class="input-field pl-9"></div>
                        </div>
                        <div class="form-group">
                            <label class="label-field">{{ __('Distance (km)') }}</label>
                            <div class="relative"><div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i data-lucide="route" class="w-4 h-4 text-slate-400"></i></div>
                            <input type="number" name="distance_km" step="0.1" class="input-field pl-9" placeholder="120"></div>
                        </div>
                        <div class="lg:col-span-2 form-group">
                            <label class="label-field">{{ __('Notes') }}</label>
                            <textarea name="notes" rows="2" class="input-field resize-none" placeholder="{{ __('Notes') }}"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800 shrink-0 flex justify-end gap-3 bg-white dark:bg-slate-900 rounded-b-2xl">
                <button type="button" @click="showShipmentModal = false" class="btn-secondary">{{ __('Cancel') }}</button>
                <button type="submit" form="shipmentForm" class="btn-primary flex items-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i> {{ __('Schedule Shipment') }}
                </button>
            </div>
        </div>
    </template>

    <!-- ===== ADD VEHICLE MODAL ===== -->
    <template x-teleport="body">
        <div x-show="showVehicleModal" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-3 sm:p-5"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm" @click="showVehicleModal = false"></div>
            <div class="relative w-full max-w-lg flex flex-col glass-panel rounded-2xl shadow-2xl overflow-hidden"
                 style="max-height: 90vh;"
                 x-show="showVehicleModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                <!-- Header -->
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-200 dark:border-emerald-500/30 shrink-0">
                            <i data-lucide="truck" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-black text-slate-900 dark:text-white leading-tight">{{ __('Add Vehicle') }}</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Register a new fleet vehicle.') }}</p>
                        </div>
                    </div>
                    <button @click="showVehicleModal = false" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 transition-colors shrink-0">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="overflow-y-auto flex-1 px-5 py-4">
                    <form action="{{ route('transportation.vehicles.store') }}" method="POST" id="vehicleForm">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div class="lg:col-span-2 form-group">
                                <label class="label-field">{{ __('Vehicle Name') }}</label>
                                <input type="text" name="name" required class="input-field" placeholder="{{ __('Vehicle Name') }}">
                            </div>
                            <div class="form-group">
                                <label class="label-field">{{ __('Plate Number') }}</label>
                                <input type="text" name="plate_number" class="input-field" placeholder="{{ __('Plate Number') }}">
                            </div>
                            <div class="form-group">
                                <label class="label-field">{{ __('Type') }}</label>
                                <select name="type" class="select-field">
                                    <option value="truck">{{ __('Truck') }}</option>
                                    <option value="trailer">{{ __('Trailer') }}</option>
                                    <option value="van">{{ __('Van') }}</option>
                                    <option value="pickup">{{ __('Pickup') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="label-field">{{ __('Capacity (animals)') }}</label>
                                <input type="text" name="capacity_animals" class="input-field" placeholder="30">
                            </div>
                            <div class="form-group">
                                <label class="label-field">{{ __('Driver Name') }}</label>
                                <input type="text" name="driver_name" class="input-field" placeholder="{{ __('Driver Name') }}">
                            </div>
                            <div class="lg:col-span-2 form-group">
                                <label class="label-field">{{ __('Driver Phone') }}</label>
                                <input type="text" name="driver_phone" class="input-field" placeholder="+212 ...">
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800 shrink-0 flex justify-end gap-3 bg-white dark:bg-slate-900 rounded-b-2xl">
                    <button type="button" @click="showVehicleModal = false" class="btn-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" form="vehicleForm" class="btn-primary flex items-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i> {{ __('Add Vehicle') }}
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
