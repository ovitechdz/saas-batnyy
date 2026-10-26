@extends('layouts.app')

@section('title', 'Feed Inventory')

@section('content')
<div class="space-y-6 animate-fade-in" x-data="{ addModalOpen: false, editModalOpen: false, editItem: {} }">
    <!-- Hero -->
    <div class="page-hero">
        <div class="page-hero-content flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <span class="text-amber-400 text-xs font-bold uppercase tracking-widest">{{ __('Farm Operations') }}</span>
                <h2 class="text-3xl font-black tracking-tight text-white mt-1">{{ __('Feed Inventory') }}</h2>
                <p class="text-gray-400 text-sm mt-1">{{ __('Monitor and manage your farm\'s feed stock.') }}</p>
            </div>
            <button @click="addModalOpen = true" class="premium-btn flex items-center space-x-2 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>{{ __('Add Stock') }}</span>
            </button>
        </div>
    </div>

    <!-- Inventory Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($inventory as $item)
            <div class="glass-panel p-6 relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-semibold">{{ $item->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Farm ID:') }} {{ substr($item->farm_id, 0, 8) }}...</p>
                    </div>
                    <div class="p-2 rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-end">
                        <span class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Current Stock') }}</span>
                        <span class="text-2xl font-bold {{ $item->total_quantity <= $item->minimum_threshold ? 'text-red-500' : 'text-gray-900 dark:text-white' }}">
                            {{ number_format($item->total_quantity, 2) }} {{ __('kg') }}
                        </span>
                    </div>
                    
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        @php
                            $percentage = min(100, ($item->total_quantity / max(1, $item->minimum_threshold * 3)) * 100);
                        @endphp
                        <div class="{{ $item->total_quantity <= $item->minimum_threshold ? 'bg-red-500' : 'bg-emerald-500' }} h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>

                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200/20 dark:border-gray-700/30">
                        <span class="text-sm text-gray-500">{{ __('Reorder Level') }}</span>
                        <span class="text-sm font-medium">{{ number_format($item->minimum_threshold, 2) }} {{ __('kg') }}</span>
                    </div>
                    
                    @if($item->total_quantity <= $item->minimum_threshold)
                    <div class="mt-2 text-xs font-medium text-red-500 bg-red-100/50 dark:bg-red-900/20 p-2 rounded-md flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ __('Stock is running low. Please reorder soon.') }}
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex gap-2 mt-4 pt-3 border-t border-gray-200/30 dark:border-gray-700/30">
                        <button
                            @click="editItem = { id: '{{ $item->id }}', name: '{{ addslashes($item->name) }}', total_quantity: {{ $item->total_quantity }}, minimum_threshold: {{ $item->minimum_threshold }} }; editModalOpen = true"
                            class="flex-1 flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            {{ __('Edit') }}
                        </button>
                        <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this feed item?') }}')" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 border border-red-200 dark:border-red-800/50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                {{ __('Delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full glass-panel p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('No Inventory') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('Get started by adding feed stock.') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Add Modal -->
    <template x-teleport="body">
        <div x-show="addModalOpen" class="fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="addModalOpen" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="addModalOpen" x-transition.scale.origin.bottom class="relative z-10 inline-block align-bottom glass-panel border border-emerald-900/30 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="modal-title">{{ __('Add / Update Feed Stock') }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Register new feed or add to an existing stock.') }}</p>
                                <div class="mt-6 space-y-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Feed Type (Name)') }}</label>
                                        <input type="text" name="name" required placeholder="{{ __('e.g. Alfalfa Hay') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300">
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Quantity Added (kg)') }}</label>
                                            <input type="number" step="0.01" name="total_quantity" required placeholder="e.g. 500" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Reorder Level (kg)') }}</label>
                                            <input type="number" step="0.01" name="minimum_threshold" required placeholder="e.g. 100" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Unit Price (per kg)') }} <span class="text-gray-400 font-normal">({{ __('Optional') }})</span></label>
                                            <input type="number" step="0.01" name="unit_price" placeholder="0.00" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Expiration Date') }} <span class="text-gray-400 font-normal">({{ __('Optional') }})</span></label>
                                            <input type="date" name="expiration_date" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300 cursor-pointer">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Supplier / Vendor') }} <span class="text-gray-400 font-normal">({{ __('Optional') }})</span></label>
                                        <input type="text" name="supplier" placeholder="{{ __('e.g. Farm Supplier Co.') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300">
                                    </div>
                                    <p class="text-sm text-gray-500 mt-2 bg-gray-50 dark:bg-emerald-900/10 p-3 rounded-xl border border-gray-100 dark:border-emerald-500/20 flex items-start shadow-inner">
                                        <svg class="w-5 h-5 flex-shrink-0 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>{{ __('If the feed type already exists, this quantity will be added to the current stock.') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100 dark:border-white/5">
                        <button type="submit" class="w-full sm:w-auto premium-btn sm:ml-3">
                            {{ __('Save Stock') }}
                        </button>
                        <button @click="addModalOpen = false" type="button" class="mt-3 w-full sm:w-auto sm:mt-0 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white/50 dark:bg-black/20 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors font-medium text-sm">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </template>
    <!-- Edit Inventory Modal -->
    <template x-teleport="body">
    <div x-show="editModalOpen" class="fixed z-[100] inset-0 overflow-y-auto" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="editModalOpen" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="editModalOpen" x-transition.scale.origin.bottom class="relative z-10 inline-block align-bottom glass-panel border border-emerald-900/30 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form :action="`/inventory/${editItem.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="modal-title">{{ __('Edit Feed Stock') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Update the inventory details for this feed.') }}</p>
                            <div class="mt-6 space-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Feed Type') }}</label>
                                    <input type="text" name="name" :value="editItem.name" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Current Quantity (kg)') }}</label>
                                        <input type="number" step="0.01" name="total_quantity" :value="editItem.total_quantity" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Reorder Level (kg)') }}</label>
                                        <input type="number" step="0.01" name="minimum_threshold" :value="editItem.minimum_threshold" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all duration-300">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100 dark:border-white/5">
                        <button type="submit" class="w-full sm:w-auto premium-btn sm:ml-3">
                            {{ __('Update') }}
                        </button>
                        <button @click="editModalOpen = false" type="button" class="mt-3 w-full sm:w-auto sm:mt-0 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white/50 dark:bg-black/20 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors font-medium text-sm">
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
