@extends('layouts.app')

@section('title', 'Energy Management')

@section('content')
<div class="p-4 sm:p-8 space-y-8 pb-32" x-data="{ showLogModal: false }">
    
    <!-- Header -->
    <div class="relative overflow-hidden card-3d bg-gradient-to-br from-amber-600 via-amber-700 to-slate-900 border border-amber-500/30 p-8 lg:p-10 shadow-2xl rounded-[2rem]">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-yellow-400/20 rounded-full blur-[100px] -mt-32 -mr-32 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-emerald-500/20 rounded-full blur-[80px] -mb-20 -ml-20 pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col xl:flex-row items-start xl:items-center justify-between gap-8">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <span class="px-3 py-1 text-xs font-bold uppercase tracking-widest text-amber-300 bg-amber-900/40 rounded-full border border-amber-500/30 backdrop-blur-md">
                        {{ __('Farm Energy Grid') }}
                    </span>
                    <span class="flex items-center text-xs text-slate-300 bg-slate-900/50 px-3 py-1 rounded-full border border-slate-700/50">
                        <i data-lucide="sun" class="w-3 h-3 mr-2 text-yellow-400"></i>
                        {{ __('Solar Status: Optimal') }}
                    </span>
                </div>
                <h1 class="text-4xl lg:text-5xl font-black text-white tracking-tight mb-2">
                    {{ __('Energy') }} <span class="text-amber-400">{{ __('Management') }}</span>
                </h1>
                <p class="text-amber-100/80 max-w-2xl text-base leading-relaxed">
                    {{ __('Monitor solar production, farm consumption, and battery storage levels in real-time. Keep your farm sustainable.') }}
                </p>
                
                <div class="mt-8 flex flex-wrap gap-4">
                    <button @click="showLogModal = true" class="bg-white hover:bg-slate-100 text-slate-900 font-bold py-3 px-6 rounded-xl transition-all shadow-[0_0_20px_rgba(255,255,255,0.2)] flex items-center group">
                        <i data-lucide="plus" class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform"></i> {{ __('Log Consumption') }}
                    </button>
                    <button class="bg-slate-900/50 hover:bg-slate-900/80 text-white font-medium py-3 px-6 rounded-xl border border-white/10 transition-all flex items-center backdrop-blur-md">
                        <i data-lucide="download" class="w-5 h-5 mr-2"></i> {{ __('Export Report') }}
                    </button>
                </div>
            </div>
            
            <div class="flex items-center gap-8 xl:border-l xl:border-white/10 xl:pl-10">
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-amber-200 uppercase tracking-wider font-semibold mb-1">{{ __('Net Energy') }}</p>
                    <div class="flex items-end justify-end gap-1">
                        <span class="text-6xl font-black text-white leading-none tracking-tighter">{{ number_format($netEnergy, 1) }}</span>
                        <span class="text-amber-400 text-xl font-bold mb-1">kWh</span>
                    </div>
                    <p class="text-sm text-{{ $netEnergy >= 0 ? 'emerald' : 'red' }}-400 mt-2 flex items-center justify-end font-medium">
                        <i data-lucide="trending-{{ $netEnergy >= 0 ? 'up' : 'down' }}" class="w-4 h-4 mr-1"></i> {{ $netEnergy >= 0 ? __('Surplus') : __('Deficit') }}
                    </p>
                </div>
                <div class="w-32 h-32 rounded-full relative flex items-center justify-center bg-slate-900/40 border border-white/10 shadow-2xl backdrop-blur-xl shrink-0">
                    <i data-lucide="zap" class="w-12 h-12 text-amber-400 animate-pulse"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Production -->
        <div class="modern-card p-6 group cursor-pointer hover:border-amber-500/30 hover:-translate-y-2 transition-all duration-300 border-t-4 border-t-amber-500">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/10 rounded-full blur-xl group-hover:bg-amber-500/20 transition-all"></div>
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Today\'s Production') }}</p>
                    <h3 class="text-4xl font-black text-slate-900 dark:text-white mt-1 tracking-tight">{{ number_format($todayProduction, 1) }}</h3>
                </div>
                <div class="p-3 bg-amber-100 dark:bg-amber-500/10 rounded-2xl text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform shadow-sm">
                    <i data-lucide="sun" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="flex items-center text-sm mb-4">
                <span class="text-amber-600 dark:text-amber-400 font-bold">kWh</span>
                <span class="text-slate-500 ml-2 font-medium">{{ __('from Solar Panels') }}</span>
            </div>
        </div>

        <!-- Consumption -->
        <div class="modern-card p-6 group cursor-pointer hover:border-red-500/30 hover:-translate-y-2 transition-all duration-300 border-t-4 border-t-red-500">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-500/10 rounded-full blur-xl group-hover:bg-red-500/20 transition-all"></div>
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Today\'s Consumption') }}</p>
                    <h3 class="text-4xl font-black text-slate-900 dark:text-white mt-1 tracking-tight">{{ number_format($todayConsumption, 1) }}</h3>
                </div>
                <div class="p-3 bg-red-100 dark:bg-red-500/10 rounded-2xl text-red-600 dark:text-red-400 group-hover:scale-110 transition-transform shadow-sm">
                    <i data-lucide="power" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="flex items-center text-sm mb-4">
                <span class="text-red-600 dark:text-red-400 font-bold">kWh</span>
                <span class="text-slate-500 ml-2 font-medium">{{ __('Farm Infrastructure') }}</span>
            </div>
        </div>

        <!-- Battery Storage -->
        <div class="modern-card p-6 group cursor-pointer hover:border-emerald-500/30 hover:-translate-y-2 transition-all duration-300 border-t-4 border-t-emerald-500">
            @php
                $avgCharge = $batteries->avg('current_charge_percentage') ?? 0;
            @endphp
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl group-hover:bg-emerald-500/20 transition-all"></div>
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Avg Battery Level') }}</p>
                    <h3 class="text-4xl font-black text-slate-900 dark:text-white mt-1 tracking-tight">{{ number_format($avgCharge, 1) }}%</h3>
                </div>
                <div class="p-3 bg-emerald-100 dark:bg-emerald-500/10 rounded-2xl text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform shadow-sm">
                    <i data-lucide="battery-charging" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="flex items-center text-sm mb-4">
                <span class="text-emerald-600 dark:text-emerald-400 font-bold">{{ $batteries->count() }}</span>
                <span class="text-slate-500 ml-2 font-medium">{{ __('Active Batteries') }}</span>
            </div>
            <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2 border border-slate-300 dark:border-slate-700 overflow-hidden mt-6">
                <div class="bg-gradient-to-r from-emerald-500 to-green-300 h-2 rounded-full relative" style="width: {{ $avgCharge }}%"></div>
            </div>
        </div>
    </div>

    <!-- Infrastructure Lists -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Solar Panels -->
        <div class="modern-card flex flex-col overflow-hidden">
            <div class="p-6 border-b border-slate-200 dark:border-white/10 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="sun" class="w-5 h-5 text-amber-500"></i> {{ __('Solar Infrastructure') }}
                </h3>
            </div>
            <div class="p-0 overflow-x-auto custom-scroll">
                <table class="w-full border-collapse text-sm whitespace-nowrap {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                    <thead class="bg-slate-50/50 dark:bg-black/20 border-b border-slate-200 dark:border-white/5">
                        <tr class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                            <th class="p-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Panel Name') }}</th>
                            <th class="p-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Capacity (kW)') }}</th>
                            <th class="p-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                        @forelse($panels as $panel)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="p-4 font-bold text-slate-900 dark:text-white">{{ $panel->name }}</td>
                            <td class="p-4 text-slate-700 dark:text-slate-300 font-medium">{{ $panel->capacity_kw }}</td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-[10px] font-bold uppercase rounded-full {{ $panel->status == 'active' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400' }}">
                                    {{ $panel->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-slate-500">{{ __('No solar panels registered.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Batteries -->
        <div class="modern-card flex flex-col overflow-hidden">
            <div class="p-6 border-b border-slate-200 dark:border-white/10 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="battery" class="w-5 h-5 text-emerald-500"></i> {{ __('Battery Storage') }}
                </h3>
            </div>
            <div class="p-0 overflow-x-auto custom-scroll">
                <table class="w-full border-collapse text-sm whitespace-nowrap {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                    <thead class="bg-slate-50/50 dark:bg-black/20 border-b border-slate-200 dark:border-white/5">
                        <tr class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                            <th class="p-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Battery') }}</th>
                            <th class="p-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Capacity (kWh)') }}</th>
                            <th class="p-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Current Charge') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                        @forelse($batteries as $battery)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="p-4 font-bold text-slate-900 dark:text-white">{{ $battery->name }}</td>
                            <td class="p-4 text-slate-700 dark:text-slate-300 font-medium">{{ $battery->capacity_kwh }}</td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-24 bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                                        <div class="bg-emerald-500 h-2 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]" style="width: {{ $battery->current_charge_percentage }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $battery->current_charge_percentage }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-slate-500">{{ __('No batteries registered.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal for Logging Consumption -->
    <div x-show="showLogModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0" x-transition.opacity>
        <!-- Backdrop with Blur -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="showLogModal = false"></div>
        
        <!-- Modal Content -->
        <div class="relative glass-panel w-full max-w-xl shadow-2xl overflow-hidden" 
             x-show="showLogModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95">
             
            <!-- Decorative Backgrounds -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="p-8 relative z-10">
                <div class="flex justify-between items-start mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-200 dark:border-amber-500/30">
                            <i data-lucide="zap" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ __('Log Energy') }}</h2>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Record farm infrastructure consumption.') }}</p>
                        </div>
                    </div>
                    <button @click="showLogModal = false" class="text-slate-400 hover:text-slate-900 dark:hover:text-white p-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('energy.consumption.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Consumer Type -->
                        <div class="space-y-2">
                            <label class="label-field">{{ __('Consumer Category') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="grid" class="w-5 h-5 text-slate-400"></i>
                                </div>
                                <select name="consumer_type" required class="select-field pl-11 py-3">
                                    <option value="">{{ __('Select...') }}</option>
                                    <option value="Barn">{{ __('Barn Operations') }}</option>
                                    <option value="Pump">{{ __('Water Pumps') }}</option>
                                    <option value="Equipment">{{ __('Farm Equipment') }}</option>
                                    <option value="General">{{ __('General Facility') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Specific ID -->
                        <div class="space-y-2">
                            <label class="label-field">{{ __('Specific ID (Optional)') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="hash" class="w-5 h-5 text-slate-400 group-focus-within:text-amber-500 transition-colors"></i>
                                </div>
                                <input type="text" name="consumer_id" class="input-field pl-11 py-3" placeholder="{{ __('e.g. Pump-01') }}">
                            </div>
                        </div>

                        <!-- Energy Consumed -->
                        <div class="space-y-2">
                            <label class="label-field">{{ __('Energy Consumed') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="activity" class="w-5 h-5 text-slate-400 group-focus-within:text-red-500 transition-colors"></i>
                                </div>
                                <input type="number" step="0.01" name="energy_consumed_kwh" required class="input-field pl-11 pr-14 py-3" placeholder="0.00">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-slate-400 font-bold text-sm">kWh</span>
                                </div>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="space-y-2">
                            <label class="label-field">{{ __('Date Recorded') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="calendar" class="w-5 h-5 text-slate-400 group-focus-within:text-amber-500 transition-colors"></i>
                                </div>
                                <input type="date" name="recorded_at" required value="{{ date('Y-m-d') }}" class="input-field pl-11 py-3">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 mt-6 border-t border-slate-200 dark:border-white/10 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <button type="button" @click="showLogModal = false" class="btn-ghost w-full sm:w-auto">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn-primary w-full sm:w-auto flex items-center justify-center gap-2">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            {{ __('Save Record') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
