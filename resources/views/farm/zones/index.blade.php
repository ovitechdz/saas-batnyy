@extends('layouts.app')

@section('content')
    <div x-data="{ showAddModal: false }" class="h-full flex flex-col relative lg:overflow-hidden">

        {{-- TOP GLASS NAV --}}
        <div
            class="glass-header px-4 md:px-8 py-6 z-20 relative flex flex-col md:flex-row items-start md:items-center justify-between gap-4 md:gap-0">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                    <i data-lucide="map-pin"
                        class="w-8 h-8 text-emerald-500 dark:text-emerald-400 drop-shadow-[0_0_10px_rgba(16,185,129,0.5)]"></i>
                    {{ __('Farms & Zones') }}
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm font-medium">
                    {{ __('Manage your operational areas, grazing fields, and croplands.') }}</p>
            </div>

            <div class="flex items-center gap-4 w-full md:w-auto">
                <button @click="showAddModal = true" class="btn-primary ml-auto md:ml-0 flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> {{ __('Add New Zone') }}
                </button>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col gap-6 px-4 md:px-8 py-8 h-full relative z-10 overflow-y-auto custom-scroll">

            @if(session('success'))
                <div
                    class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-6 py-4 rounded-xl flex items-center gap-3">
                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            {{-- DATA GRID --}}
            <div class="modern-card">
                <div class="overflow-x-auto">
                    <table class="table-premium w-full {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    {{ __('Zone Name') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    {{ __('Type') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    {{ __('Area Size (Acres)') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    {{ __('Barns') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    {{ __('Status') }}</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider {{ app()->getLocale() == 'ar' ? 'text-left' : 'text-right' }}">
                                    {{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($zones as $zone)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                                <i data-lucide="map" class="w-5 h-5"></i>
                                            </div>
                                            <div class="font-bold text-slate-900 dark:text-white">{{ $zone->name }}</div>
                                        </div>
                                    </td>
                                    <td class="font-medium">
                                        {{ $zone->type }}
                                    </td>
                                    <td class="font-medium tabular-nums">
                                        {{ $zone->area_size ?? 'N/A' }}
                                    </td>
                                    <td class="font-medium tabular-nums">
                                        {{ $zone->barns_count ?? 0 }}
                                    </td>
                                    <td>
                                        @if($zone->status == 'active')
                                            <span class="badge badge-success">
                                                <span class="badge-dot animate-pulse"></span> {{ __('Active') }}
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                {{ ucfirst($zone->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="{{ app()->getLocale() == 'ar' ? 'text-left' : 'text-right' }}">
                                        <div class="flex items-center justify-end gap-2 transition-opacity">
                                            <button
                                                class="btn-icon bg-slate-100 hover:bg-emerald-100 dark:bg-white/5 dark:hover:bg-emerald-500/20 text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 transition-colors">
                                                <i data-lucide="edit-2" class="w-4 h-4 mx-auto"></i>
                                            </button>
                                            <button
                                                class="btn-icon bg-slate-100 hover:bg-rose-100 dark:bg-white/5 dark:hover:bg-rose-500/20 text-slate-500 hover:text-rose-600 dark:text-slate-400 dark:hover:text-rose-400 transition-colors">
                                                <i data-lucide="trash-2" class="w-4 h-4 mx-auto"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div
                                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-white/5 mb-4 text-slate-400">
                                            <i data-lucide="map-pin" class="w-8 h-8"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-1">
                                            {{ __('No Zones Found') }}</h3>
                                        <p class="text-slate-500 dark:text-slate-400 text-sm">
                                            {{ __('Create your first operational zone to get started.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ADD NEW MODAL (Alpine.js) --}}
        <template x-teleport="body">
            <div x-show="showAddModal" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    {{-- Backdrop --}}
                    <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm"
                        @click="showAddModal = false"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    {{-- Modal Content --}}
                    <div x-show="showAddModal" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative z-10 inline-flex flex-col align-bottom glass-panel w-full max-w-lg text-left transform transition-all sm:my-8 sm:align-middle max-h-[calc(100vh-2rem)] sm:max-h-[calc(100vh-4rem)]">

                        <div
                            class="px-6 py-4 border-b border-slate-200 dark:border-white/10 flex items-center justify-between shrink-0">
                            <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                <i data-lucide="plus-circle" class="w-5 h-5 text-emerald-500"></i>
                                {{ __('Create New Zone') }}
                            </h2>
                            <button @click="showAddModal = false"
                                class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>

                        <form action="{{ route('farm.zones.store') }}" method="POST"
                            class="p-6 space-y-4 overflow-y-auto custom-scrollbar">
                            @csrf

                            <div>
                                <label class="label-field">{{ __('Zone Name') }}</label>
                                <input type="text" name="name" required placeholder="{{ __('e.g. Alpha Grazing') }}"
                                    class="input-field">
                            </div>

                            <div>
                                <label class="label-field">{{ __('Zone Type') }}</label>
                                <select name="type" required class="select-field">
                                    <option value="Grazing">{{ __('Grazing') }}</option>
                                    <option value="Cropland">{{ __('Cropland') }}</option>
                                    <option value="Quarantine">{{ __('Quarantine') }}</option>
                                    <option value="Facilities">{{ __('Facilities') }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="label-field">{{ __('Area Size (Acres/Hectares)') }}</label>
                                <input type="number" step="0.01" name="area_size" placeholder="{{ __('Optional') }}"
                                    class="input-field">
                            </div>

                            <div>
                                <label class="label-field">{{ __('Map Coordinates') }}</label>
                                <input type="text" name="coordinates" placeholder="{{ __('e.g. 45.123, -93.456') }}"
                                    class="input-field">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="label-field">{{ __('Soil Type') }}</label>
                                    <select name="soil_type" class="select-field">
                                        <option value="">{{ __('Select Soil...') }}</option>
                                        <option value="Clay">{{ __('Clay') }}</option>
                                        <option value="Sandy">{{ __('Sandy') }}</option>
                                        <option value="Loam">{{ __('Loam') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="label-field">{{ __('Initial Status') }}</label>
                                    <select name="status" class="select-field">
                                        <option value="active">{{ __('Active') }}</option>
                                        <option value="resting">{{ __('Resting (Fallow)') }}</option>
                                        <option value="maintenance">{{ __('Maintenance') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="pt-4 flex items-center justify-end gap-3">
                                <button type="button" @click="showAddModal = false" class="btn-ghost">
                                    {{ __('Cancel') }}
                                </button>
                                <button type="submit" class="btn-primary">
                                    {{ __('Create Zone') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
        </template>
    </div>
@endsection