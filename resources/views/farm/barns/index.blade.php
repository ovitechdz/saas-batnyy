@extends('layouts.app')

@section('content')
<div x-data="{ showAddModal: false }" class="h-full flex flex-col relative lg:overflow-hidden">
    
    {{-- TOP GLASS NAV --}}
    <div class="glass-header px-4 md:px-8 py-6 z-20 relative flex flex-col md:flex-row items-start md:items-center justify-between gap-4 md:gap-0">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                <i data-lucide="warehouse" class="w-8 h-8 text-sky-500 dark:text-sky-400 drop-shadow-[0_0_10px_rgba(56,189,248,0.5)]"></i>
                {{ __('Barns & Capacity') }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm font-medium">{{ __('Monitor physical structures, occupancy, and environmental data.') }}</p>
        </div>
        
        <div class="flex items-center gap-4 w-full md:w-auto">
            <button @click="showAddModal = true" class="btn-primary ml-auto md:ml-0 flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> {{ __('Add New Barn') }}
            </button>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col gap-6 px-4 md:px-8 py-8 h-full relative z-10 overflow-y-auto custom-scroll">
        
        @if(session('success'))
        <div class="bg-sky-500/10 border border-sky-500/30 text-sky-400 px-6 py-4 rounded-xl flex items-center gap-3">
            <i data-lucide="check-circle-2" class="w-5 h-5"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
        @endif

        {{-- DATA GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($barns as $barn)
                @php
                    $occupancyPercentage = $barn->capacity > 0 ? round(($barn->current_occupancy / $barn->capacity) * 100) : 0;
                    $occupancyColor = $occupancyPercentage > 90 ? 'rose' : ($occupancyPercentage > 70 ? 'amber' : 'sky');
                @endphp
                <div class="modern-card p-6 flex flex-col group cursor-pointer hover:border-sky-500/30">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-sky-500/10 rounded-full blur-3xl -mr-10 -mt-10 group-hover:bg-sky-500/20 transition-all"></div>
                    
                    <div class="flex justify-between items-start mb-6 relative z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/20 flex items-center justify-center text-sky-600 dark:text-sky-400 group-hover:scale-110 transition-transform">
                                <i data-lucide="warehouse" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-lg">{{ $barn->name }}</h3>
                                <p class="text-xs text-slate-500 flex items-center gap-1">
                                    <i data-lucide="map-pin" class="w-3 h-3"></i> {{ $barn->zone->name ?? __('Unassigned') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-1 transition-opacity">
                            <button class="btn-icon bg-slate-100 hover:bg-emerald-100 dark:bg-white/5 dark:hover:bg-emerald-500/20 text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 transition-colors">
                                <i data-lucide="edit-2" class="w-4 h-4 mx-auto"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6 relative z-10">
                        <div class="bg-slate-50 dark:bg-black/40 rounded-xl p-3 border border-slate-200 dark:border-white/5 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest flex items-center gap-1">
                                <i data-lucide="thermometer" class="w-3 h-3 text-rose-500 dark:text-rose-400"></i> {{ __('Temp') }}
                            </span>
                            <span class="text-lg font-black text-slate-900 dark:text-white">{{ $barn->temperature ?? '--' }}&deg;C</span>
                        </div>
                        <div class="bg-slate-50 dark:bg-black/40 rounded-xl p-3 border border-slate-200 dark:border-white/5 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest flex items-center gap-1">
                                <i data-lucide="droplets" class="w-3 h-3 text-blue-500 dark:text-blue-400"></i> {{ __('Humid') }}
                            </span>
                            <span class="text-lg font-black text-slate-900 dark:text-white">{{ $barn->humidity ?? '--' }}%</span>
                        </div>
                    </div>

                    <div class="mt-auto relative z-10">
                        <div class="flex justify-between text-xs font-bold mb-2">
                            <span class="text-slate-400 uppercase tracking-widest">{{ __('Occupancy') }}</span>
                            <span class="text-{{ $occupancyColor }}-400">{{ $barn->current_occupancy }} / {{ $barn->capacity }}</span>
                        </div>
                        <div class="w-full h-2 bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-{{ $occupancyColor }}-500 rounded-full shadow-[0_0_10px_currentColor] transition-all duration-1000" style="width: {{ $occupancyPercentage }}%"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 py-20 text-center modern-card">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 dark:bg-white/5 mb-4 text-slate-400">
                        <i data-lucide="warehouse" class="w-10 h-10"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">{{ __('No Barns Found') }}</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm max-w-sm mx-auto">{{ __('Create your first physical structure to manage livestock capacity and monitor environmental conditions.') }}</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ADD NEW MODAL (Alpine.js) --}}
    <template x-teleport="body">
        <div x-show="showAddModal" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Backdrop --}}
            <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="showAddModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal Content --}}
            <div x-show="showAddModal" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative z-10 inline-flex flex-col align-bottom glass-panel w-full max-w-lg text-left transform transition-all sm:my-8 sm:align-middle max-h-[calc(100vh-2rem)] sm:max-h-[calc(100vh-4rem)]">
            
            <div class="px-6 py-4 border-b border-slate-200 dark:border-white/10 flex items-center justify-between shrink-0">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-sky-500"></i> {{ __('Create New Barn') }}
                </h2>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('farm.barns.store') }}" method="POST" class="p-4 space-y-3 overflow-y-auto custom-scrollbar">
                @csrf
                
                <div>
                    <label class="label-field">{{ __('Barn Name') }}</label>
                    <input type="text" name="name" required placeholder="{{ __('e.g. Block A') }}" class="input-field">
                </div>

                <div>
                    <label class="label-field">{{ __('Assign to Zone') }}</label>
                    <select name="zone_id" required class="select-field">
                        <option value="" disabled selected>{{ __('Select a Zone...') }}</option>
                        @forelse($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }} ({{ $zone->type ?? 'General' }})</option>
                        @empty
                            <option value="" disabled>{{ __('⚠️ No zones found! Please create a Zone first.') }}</option>
                        @endforelse
                    </select>
                </div>

                <div>
                    <label class="label-field">{{ __('Barn Type') }}</label>
                    <select name="type" required class="select-field">
                        <option value="Nursery">{{ __('Nursery (Lambs/Calves)') }}</option>
                        <option value="Fattening">{{ __('Fattening / Feeding') }}</option>
                        <option value="Maternity">{{ __('Maternity / Breeding') }}</option>
                        <option value="Quarantine">{{ __('Quarantine') }}</option>
                        <option value="General" selected>{{ __('General Storage / Housing') }}</option>
                    </select>
                </div>

                <div>
                    <label class="label-field">{{ __('Max Capacity (Animals)') }}</label>
                    <input type="number" name="capacity" required min="1" placeholder="{{ __('e.g. 500') }}" class="input-field">
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" @click="showAddModal = false" class="btn-ghost">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn-primary">
                        {{ __('Create Barn') }}
                    </button>
                </div>
            </form>
        </div>
        </div>
    </template>
</div>
@endsection
