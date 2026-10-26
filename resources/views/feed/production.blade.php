@extends('layouts.app')
@section('title', 'Feed Production & Silage')

@section('content')
<div class="p-4 sm:p-8 space-y-6 max-w-7xl mx-auto" x-data="{ showMixModal: false, showSilageModal: false }">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                <i data-lucide="factory" class="w-8 h-8 text-blue-500"></i>
                {{ __('Feed Production') }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">{{ __('Manage silage pits, raw materials, and on-farm feed mixing.') }}</p>
        </div>
        <div class="flex gap-2">
            <button @click="showMixModal = true" class="btn-primary flex items-center gap-2">
                <i data-lucide="blender" class="w-4 h-4"></i> {{ __('Mix New Batch') }}
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Silage Pits -->
        <div class="glass-panel p-6">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-800 pb-2 flex items-center justify-between">
                <span class="flex items-center gap-2"><i data-lucide="shovel" class="w-5 h-5 text-amber-600"></i> {{ __('Silage Management') }}</span>
                <button @click="showSilageModal = true" class="text-xs font-bold text-amber-600 hover:text-amber-700">{{ __('Add Pit') }}</button>
            </h3>
            
            <div class="space-y-4">
                @forelse($silagePits as $pit)
                    <div class="glass-panel border border-emerald-500/10 dark:border-emerald-500/5 hover:border-emerald-500/30 transition-colors rounded-xl p-4 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white text-lg">{{ $pit->pit_name }}</h4>
                            <div class="text-sm text-slate-500 mt-1">
                                {{ __('Harvested:') }} <span class="text-emerald-600 dark:text-emerald-400 font-medium">{{ $pit->harvest_date }}</span> • {{ __('Est.') }} <span class="font-bold text-slate-700 dark:text-slate-300">{{ number_format($pit->estimated_tonnage, 0) }} {{ __('Tons') }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $pit->status === 'Fermenting' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                                {{ $pit->status === 'Open' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : '' }}
                                {{ $pit->status === 'Empty' ? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400' : '' }}
                            ">
                                {{ __($pit->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center p-6 border-2 border-dashed border-emerald-500/20 dark:border-emerald-500/10 rounded-xl">
                        <div class="text-slate-500 italic text-sm">{{ __('No silage pits recorded.') }}</div>
                        <button @click="showSilageModal = true" class="mt-2 text-sm text-emerald-600 font-bold hover:underline">{{ __('Log first harvest') }}</button>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Production Batches -->
        <div class="glass-panel p-6">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-800 pb-2 flex items-center justify-between">
                <span class="flex items-center gap-2"><i data-lucide="clipboard-check" class="w-5 h-5 text-emerald-500"></i> {{ __('Mixed Batches') }}</span>
            </h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-500 dark:text-slate-400">
                    <thead class="text-xs text-emerald-800 uppercase bg-emerald-50 dark:bg-emerald-900/20 dark:text-emerald-300">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg">{{ __('Batch #') }}</th>
                            <th class="px-4 py-3">{{ __('Concentrate') }}</th>
                            <th class="px-4 py-3">{{ __('Yield') }}</th>
                            <th class="px-4 py-3 rounded-tr-lg">{{ __('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBatches as $batch)
                            <tr class="border-b dark:border-slate-800 hover:bg-emerald-50 dark:hover:bg-emerald-900/10">
                                <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">{{ $batch->batch_number }}</td>
                                <td class="px-4 py-3">{{ $batch->concentrate->name ?? __('Custom Mix') }}</td>
                                <td class="px-4 py-3 text-emerald-600 dark:text-emerald-400 font-bold">+{{ number_format($batch->yield_quantity, 0) }} {{ $batch->unit ?? __('kg') }}</td>
                                <td class="px-4 py-3">{{ $batch->production_date }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-500 italic">{{ __('No recent production batches.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>

    <!-- Mix New Batch Modal -->
    <template x-teleport="body">
        <div x-show="showMixModal" class="fixed z-[100] inset-0 overflow-y-auto" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showMixModal" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showMixModal" x-transition.scale.origin.bottom class="relative z-10 inline-block align-bottom glass-panel border border-emerald-900/30 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form action="{{ route('feed.production.store') }}" method="POST">
                        @csrf
                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="modal-title">{{ __('Mix New Feed Batch') }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Record a newly produced feed batch.') }}</p>
                                <div class="mt-6 space-y-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Batch Number') }}</label>
                                        <input type="text" name="batch_number" required placeholder="e.g. BATCH-001" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300">
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Yield Quantity (kg)') }}</label>
                                            <input type="number" step="0.01" name="yield_quantity" required placeholder="e.g. 500" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Production Date') }}</label>
                                            <input type="date" name="production_date" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100 dark:border-white/5">
                            <button type="submit" class="w-full sm:w-auto btn-primary sm:ml-3">
                                {{ __('Save Batch') }}
                            </button>
                            <button @click="showMixModal = false" type="button" class="mt-3 w-full sm:w-auto sm:mt-0 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white/50 dark:bg-black/20 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors font-medium text-sm">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
    <!-- Silage Pit Modal -->
    <template x-teleport="body">
        <div x-show="showSilageModal" class="fixed z-[100] inset-0 overflow-y-auto" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showSilageModal" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showSilageModal" x-transition.scale.origin.bottom class="relative z-10 inline-block align-bottom glass-panel border border-emerald-900/30 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form action="{{ route('feed.production.silage.store') }}" method="POST">
                        @csrf
                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="modal-title">{{ __('Record Silage Harvest') }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Add a new silage pit to your farm.') }}</p>
                                <div class="mt-6 space-y-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Pit Name / Identifier') }}</label>
                                        <input type="text" name="pit_name" required placeholder="e.g. Pit A - Corn Silage" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300">
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Estimated Tonnage') }}</label>
                                            <input type="number" step="0.01" name="estimated_tonnage" required placeholder="e.g. 1500" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Harvest Date') }}</label>
                                            <input type="date" name="harvest_date" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Current Status') }}</label>
                                        <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300">
                                            <option value="Fermenting">{{ __('Fermenting') }}</option>
                                            <option value="Open">{{ __('Open') }}</option>
                                            <option value="Empty">{{ __('Empty') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100 dark:border-white/5">
                            <button type="submit" class="w-full sm:w-auto btn-primary sm:ml-3">
                                {{ __('Save Pit') }}
                            </button>
                            <button @click="showSilageModal = false" type="button" class="mt-3 w-full sm:w-auto sm:mt-0 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white/50 dark:bg-black/20 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors font-medium text-sm">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
