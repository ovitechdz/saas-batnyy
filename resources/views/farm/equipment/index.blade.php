@extends('layouts.app')

@section('content')
<div x-data="{ showAddModal: false }" class="h-full flex flex-col relative lg:overflow-hidden">
    
    {{-- TOP GLASS NAV --}}
    <div class="glass-header px-4 md:px-8 py-6 z-20 relative flex flex-col md:flex-row items-start md:items-center justify-between gap-4 md:gap-0">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                <i data-lucide="tractor" class="w-8 h-8 text-amber-500 dark:text-amber-400 drop-shadow-[0_0_10px_rgba(251,191,36,0.5)]"></i>
                {{ __('Equipment & Fleet') }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm font-medium">{{ __('Manage tractors, drones, harvesters and monitor fuel/battery levels.') }}</p>
        </div>
        
        <div class="flex items-center gap-4 w-full md:w-auto">
            <button @click="showAddModal = true" class="btn-primary ml-auto md:ml-0 flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> {{ __('Add Equipment') }}
            </button>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col gap-6 px-4 md:px-8 py-8 h-full relative z-10 overflow-y-auto custom-scroll">
        
        @if(session('success'))
        <div class="bg-amber-500/10 border border-amber-500/30 text-amber-400 px-6 py-4 rounded-xl flex items-center gap-3">
            <i data-lucide="check-circle-2" class="w-5 h-5"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
        @endif

        {{-- DATA GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($equipmentList as $equipment)
                @php
                    $fuelColor = $equipment->fuel_level < 20 ? 'rose' : ($equipment->fuel_level < 50 ? 'amber' : 'emerald');
                    $icon = match($equipment->type) {
                        'Tractor' => 'tractor',
                        'Drone' => 'plane',
                        'Harvester' => 'truck',
                        default => 'truck'
                    };
                @endphp
                <div class="modern-card p-6 flex flex-col group cursor-pointer hover:border-amber-500/30">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full blur-3xl -mr-10 -mt-10 group-hover:bg-amber-500/20 transition-all"></div>
                    
                    <div class="flex justify-between items-start mb-6 relative z-10">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                                <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-lg">{{ $equipment->name }}</h3>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">{{ $equipment->type }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-1 transition-opacity">
                            <button class="btn-icon bg-slate-100 hover:bg-emerald-100 dark:bg-white/5 dark:hover:bg-emerald-500/20 text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 transition-colors">
                                <i data-lucide="edit-2" class="w-4 h-4 mx-auto"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-6 relative z-10">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest flex items-center gap-1">
                                <i data-lucide="battery-charging" class="w-3 h-3 text-{{ $fuelColor }}-500 dark:text-{{ $fuelColor }}-400"></i> {{ __('Energy Level') }}
                            </span>
                            <span class="text-sm font-black text-slate-900 dark:text-white">{{ $equipment->fuel_level ?? 0 }}%</span>
                        </div>
                        <div class="w-full h-2 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-{{ $fuelColor }}-500 rounded-full shadow-[0_0_10px_currentColor] transition-all duration-1000" style="width: {{ $equipment->fuel_level ?? 0 }}%"></div>
                        </div>
                    </div>

                    <div class="mt-auto relative z-10 flex items-center justify-between border-t border-slate-200 dark:border-white/5 pt-4">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full {{ $equipment->status == 'active' ? 'bg-emerald-500 dark:bg-emerald-400 animate-pulse' : 'bg-rose-500 dark:bg-rose-400' }}"></div>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase">{{ $equipment->status }}</span>
                        </div>
                        <span class="text-xs font-bold text-slate-500">{{ __('Maint: Next Month') }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 py-20 text-center modern-card">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 dark:bg-white/5 mb-4 text-slate-400">
                        <i data-lucide="tractor" class="w-10 h-10"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">{{ __('No Equipment Found') }}</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm max-w-sm mx-auto">{{ __('Add your first tractor, drone, or harvester to start monitoring your fleet.') }}</p>
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
                    <i data-lucide="plus-circle" class="w-5 h-5 text-amber-500"></i> {{ __('Add Equipment') }}
                </h2>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('farm.equipment.store') }}" method="POST" class="p-6 space-y-4 overflow-y-auto custom-scrollbar">
                @csrf
                
                <div>
                    <label class="label-field">{{ __('Equipment Name/Model') }}</label>
                    <input type="text" name="name" required placeholder="{{ __('e.g. John Deere 8R') }}" class="input-field">
                </div>

                {{-- Equipment Type --}}
                <div>
                    <label class="label-field">{{ __('Equipment Type') }}</label>
                    <select name="type" required class="select-field">
                        <option value="Tractor">{{ __('Tractor') }}</option>
                        <option value="Drone">{{ __('Drone') }}</option>
                        <option value="Harvester">{{ __('Harvester') }}</option>
                        <option value="IoT Sensor">{{ __('IoT Sensor') }}</option>
                        <option value="Other">{{ __('Other') }}</option>
                    </select>
                </div>

                {{-- Serial Number --}}
                <div>
                    <label class="label-field">{{ __('Serial Number / Asset ID') }}</label>
                    <input type="text" name="serial_number" placeholder="{{ __('Optional') }}" class="input-field">
                </div>

                {{-- Fuel Type --}}
                <div>
                    <label class="label-field">{{ __('Power Source / Fuel Type') }}</label>
                    <select name="fuel_type" class="select-field">
                        <option value="Diesel">{{ __('Diesel') }}</option>
                        <option value="Electric">{{ __('Electric (Battery)') }}</option>
                        <option value="Solar">{{ __('Solar Powered') }}</option>
                        <option value="Manual">{{ __('Manual / None') }}</option>
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="label-field">{{ __('Initial Status') }}</label>
                    <select name="status" required class="select-field">
                        <option value="Active">{{ __('Active') }}</option>
                        <option value="Idle">{{ __('Idle / Standby') }}</option>
                        <option value="Maintenance">{{ __('Maintenance') }}</option>
                    </select>
                </div>

                {{-- Assign to Zone --}}
                <div>
                    <label class="label-field">{{ __('Assigned Zone (Optional)') }}</label>
                    <select name="zone_id" class="select-field">
                        <option value="" selected>{{ __('None (Mobile/Floating)') }}</option>
                        @forelse($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" @click="showAddModal = false" class="btn-ghost">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn-primary">
                        {{ __('Add to Fleet') }}
                    </button>
                </div>
            </form>
        </div>
        </div>
    </template>
</div>
@endsection
