@extends('layouts.app')

@section('title', 'Breeding Management')

@section('content')
<div class="space-y-6 animate-fade-in" x-data="{ addModalOpen: false }">
    <!-- Hero -->
    <div class="page-hero">
        <div class="page-hero-content flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <span class="text-pink-400 text-xs font-bold uppercase tracking-widest">{{ __('Reproduction') }}</span>
                <h2 class="text-3xl font-black tracking-tight text-white mt-1">{{ __('Breeding Cycles') }}</h2>
                <p class="text-gray-400 text-sm mt-1">{{ __('Monitor pregnancies, track expected birth dates, and analyze breeding success rates.') }}</p>
            </div>
            <button @click="addModalOpen = true" class="premium-btn bg-pink-600 hover:bg-pink-500 shadow-pink-500/30 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('Log Breeding Activity') }}
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="glass-panel p-6 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-pink-500/20 rounded-full blur-xl group-hover:bg-pink-500/30 transition-colors"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('Pregnant Ewes') }}</p>
                    <p class="text-3xl font-black text-gray-900 dark:text-white mt-1">{{ $stats['pregnant'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-pink-50 dark:bg-pink-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>
        <div class="glass-panel p-6 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/20 rounded-full blur-xl group-hover:bg-emerald-500/30 transition-colors"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('Completed Births') }}</p>
                    <p class="text-3xl font-black text-gray-900 dark:text-white mt-1">{{ $stats['completed'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
        <div class="glass-panel p-6 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-500/20 rounded-full blur-xl group-hover:bg-red-500/30 transition-colors"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('Failed / Miscarriage') }}</p>
                    <p class="text-3xl font-black text-gray-900 dark:text-white mt-1">{{ $stats['failed'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="glass-panel overflow-hidden">
        <div class="p-6 border-b border-emerald-100 dark:border-gray-700/30 flex justify-between items-center bg-emerald-50 dark:bg-gray-800/40">
            <h3 class="text-lg font-bold text-emerald-900 dark:text-white">{{ __('Active & Past Breeding Cycles') }}</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white dark:bg-gray-800/30 border-b border-emerald-100 dark:border-gray-700/50">
                        <th class="px-6 py-4 text-xs font-bold text-emerald-700 dark:text-gray-400 uppercase tracking-wider">{{ __('Ewe Details') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-emerald-700 dark:text-gray-400 uppercase tracking-wider">{{ __('Mating Date') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-emerald-700 dark:text-gray-400 uppercase tracking-wider">{{ __('Expected Birth') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-emerald-700 dark:text-gray-400 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-emerald-700 dark:text-gray-400 uppercase tracking-wider text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/20 dark:divide-gray-700/30">
                    @forelse($cycles as $cycle)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors group" x-data="{ updateModalOpen: false }">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-pink-100 dark:bg-pink-900/40 text-pink-600 dark:text-pink-400 flex items-center justify-center font-bold text-xs mr-3">
                                    {{ mb_substr($cycle->ewe->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $cycle->ewe->name ?? __('Unnamed Ewe') }}</div>
                                    <div class="text-xs text-gray-500">{{ __('Tag:') }} {{ $cycle->ewe->rfid_tag ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($cycle->mating_date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                            @if($cycle->expected_lambing_date)
                                @php
                                    $daysRemaining = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($cycle->expected_lambing_date), false);
                                    $isPast = \Carbon\Carbon::parse($cycle->expected_lambing_date)->isPast();
                                @endphp
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($cycle->expected_lambing_date)->format('M d, Y') }}</span>
                                    @if($cycle->status === 'pregnant' || $cycle->status === 'mated')
                                        @if(!$isPast)
                                            <span class="text-xs text-emerald-500 mt-1">{{ sprintf(__('In %s days'), $daysRemaining) }}</span>
                                        @elseif($daysRemaining == 0)
                                            <span class="text-xs text-amber-500 font-bold mt-1">{{ __('Due Today!') }}</span>
                                        @else
                                            <span class="text-xs text-red-500 font-bold mt-1">{{ sprintf(__('Overdue by %s days'), abs($daysRemaining)) }}</span>
                                        @endif
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400">{{ __('Not Set') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($cycle->status === 'pregnant')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800 dark:bg-pink-900/40 dark:text-pink-400 border border-pink-200 dark:border-pink-800/30">{{ __('Pregnant') }}</span>
                            @elseif($cycle->status === 'lambed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/30">{{ __('Completed') }}</span>
                            @elseif($cycle->status === 'aborted')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400 border border-red-200 dark:border-red-800/30">{{ __('Failed') }}</span>
                            @elseif($cycle->status === 'open')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700">{{ __('Open (Not Pregnant)') }}</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-400 border border-blue-200 dark:border-blue-800/30">{{ __('Mated') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="updateModalOpen = true" class="text-emerald-500 hover:text-emerald-700 transition-colors" title="{{ __('Update Status') }}">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <a href="{{ route('livestock.show', $cycle->ewe_id) }}" class="text-blue-500 hover:text-blue-700 transition-colors" title="{{ __('View Ewe Profile') }}">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        </td>

                        <!-- Update Status Modal -->
                        <template x-teleport="body">
                            <div x-show="updateModalOpen" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
                                <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-left">
                                    <div x-show="updateModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="updateModalOpen = false"></div>
                                    <div x-show="updateModalOpen" x-transition.scale.origin.bottom class="relative glass-panel rounded-2xl shadow-2xl w-full max-w-lg border border-gray-200/50 dark:border-gray-700/50 my-8">
                                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/50 flex justify-between items-center bg-white/40 dark:bg-gray-800/40 sticky top-0 z-10 backdrop-blur-md">
                                            <h3 class="font-bold text-gray-900 dark:text-white">{{ sprintf(__('Update Status: %s'), $cycle->ewe->name ?? __('Ewe')) }}</h3>
                                            <button type="button" @click="updateModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                        <form action="{{ route('breeding-cycles.update', $cycle->id) }}" method="POST" class="p-6">
                                            @csrf
                                            @method('PUT')
                                            <div class="space-y-4" x-data="{ status: '{{ $cycle->status }}' }">
                                                <div>
                                                    <label class="label-premium">{{ __('New Status') }}</label>
                                                    <select name="status" x-model="status" required class="select-premium">
                                                        <option class="bg-white dark:bg-gray-800" value="mated">{{ __('Mated (Awaiting Ultrasound)') }}</option>
                                                        <option class="bg-white dark:bg-gray-800" value="pregnant">{{ __('Confirmed Pregnant') }}</option>
                                                        <option class="bg-white dark:bg-gray-800" value="open">{{ __('Open (Not Pregnant)') }}</option>
                                                        <option class="bg-white dark:bg-gray-800" value="lambed">{{ __('Completed / Lambed') }}</option>
                                                        <option class="bg-white dark:bg-gray-800" value="aborted">{{ __('Failed / Aborted') }}</option>
                                                    </select>
                                                </div>
                                                
                                                <div x-show="status === 'lambed'" x-cloak x-transition>
                                                    <label class="label-premium">{{ __('Actual Lambing Date') }}</label>
                                                    <input type="date" name="actual_lambing_date" class="input-premium" :required="status === 'lambed'" value="{{ $cycle->actual_lambing_date ?? date('Y-m-d') }}">
                                                </div>

                                                <div>
                                                    <label class="label-premium">{{ __('Notes / Observations') }}</label>
                                                    <textarea name="notes" rows="2" class="input-premium" placeholder="{{ __('Ultrasound results, health issues...') }}">{{ $cycle->notes }}</textarea>
                                                </div>
                                            </div>
                                            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700/50 flex justify-end gap-3">
                                                <button type="button" @click="updateModalOpen = false" class="px-4 py-2.5 rounded-xl font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">{{ __('Cancel') }}</button>
                                                <button type="submit" class="premium-btn">{{ __('Save Status') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <p class="text-lg font-medium">{{ __('No breeding cycles recorded yet.') }}</p>
                            <p class="mt-1">{{ __('Add breeding cycles from an individual ewe\'s profile.') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($cycles->hasPages())
        <div class="p-4 border-t border-gray-200/20 dark:border-gray-700/30">
            {{ $cycles->links() }}
        </div>
        @endif
    </div>

    <!-- Add Breeding Cycle Modal -->
    <template x-teleport="body">
        <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
                <div x-show="addModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="addModalOpen = false"></div>
                <div x-show="addModalOpen" x-transition.scale.origin.bottom class="relative glass-panel rounded-2xl shadow-2xl w-full max-w-lg border border-gray-200/50 dark:border-gray-700/50 my-8">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/50 flex justify-between items-center bg-white/40 dark:bg-gray-800/40 sticky top-0 z-10 backdrop-blur-md">
                        <h3 class="font-bold text-gray-900 dark:text-white">{{ __('Log Breeding Activity') }}</h3>
                        <button type="button" @click="addModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <form action="{{ route('breeding-cycles.store') }}" method="POST" class="p-6">
                        @csrf
                        <div class="space-y-4">
                            <!-- Ewe and Ram -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="label-premium">{{ __('Select Ewe (Female) *') }}</label>
                                    <select name="animal_id" required class="select-premium">
                                        <option value="">{{ __('Select Ewe...') }}</option>
                                        @foreach($ewes as $ewe)
                                            <option class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" value="{{ $ewe->id }}">{{ $ewe->name ?? __('Ewe') }} ({{ $ewe->visual_tag ?? $ewe->internal_id }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="label-premium">{{ __('Select Ram (Optional)') }}</label>
                                    <select name="ram_id" class="select-premium">
                                        <option value="">{{ __('Unknown/None...') }}</option>
                                        @foreach($rams as $ram)
                                            <option class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" value="{{ $ram->id }}">{{ $ram->name ?? __('Ram') }} ({{ $ram->visual_tag ?? $ram->internal_id }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Breeding Settings -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="label-premium">{{ __('Method') }}</label>
                                    <select name="breeding_method" class="select-premium">
                                        <option value="natural">{{ __('Natural Mating') }}</option>
                                        <option value="ai">{{ __('Artificial Insemination (AI)') }}</option>
                                        <option value="embryo_transfer">{{ __('Embryo Transfer (ET)') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="label-premium">{{ __('Mating Type') }}</label>
                                    <select name="mating_type" class="select-premium">
                                        <option value="pen">{{ __('Pen Mating') }}</option>
                                        <option value="paddock">{{ __('Paddock Mating') }}</option>
                                        <option value="hand">{{ __('Hand Mating') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="label-premium">{{ __('BCS at Mating') }}</label>
                                    <input type="number" name="bcs_at_mating" step="0.5" min="1" max="5" class="input-premium" placeholder="e.g. 3.0">
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="label-premium">{{ __('Mating Date *') }}</label>
                                    <input type="date" name="start_date" required class="input-premium" value="{{ date('Y-m-d') }}">
                                </div>
                                <div>
                                    <label class="label-premium">{{ __('Ultrasound Date (Optional)') }}</label>
                                    <input type="date" name="scan_date" class="input-premium">
                                </div>
                            </div>

                            <div>
                                <label class="label-premium">{{ __('Notes & Observations') }}</label>
                                <textarea name="notes" rows="3" class="input-premium" placeholder="{{ __('Any specific observations about this breeding cycle...') }}"></textarea>
                            </div>
                        </div>
                        <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700/50 flex justify-end gap-3">
                            <button type="button" @click="addModalOpen = false" class="px-4 py-2.5 rounded-xl font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">{{ __('Cancel') }}</button>
                            <button type="submit" class="premium-btn">{{ __('Save Record') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
