@extends('layouts.app')
@section('title', 'Smart Feed Dashboard')

@section('content')
<div class="p-4 sm:p-8 space-y-6 max-w-7xl mx-auto" x-data="feedDashboard()">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                <i data-lucide="wheat" class="w-8 h-8 text-amber-500"></i>
                {{ __('Feed & Nutrition') }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">{{ __('AI-driven feed management and cost optimization.') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('feed.production') }}" class="btn-secondary flex items-center gap-2">
                <i data-lucide="factory" class="w-4 h-4"></i> {{ __('Production') }}
            </a>
            <a href="{{ route('inventory.index') }}" class="btn-primary flex items-center gap-2">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i> {{ __('Purchase Feed') }}
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="glass-panel p-5 relative overflow-hidden group border border-emerald-500/20 dark:border-emerald-500/10 hover:border-emerald-500/50 transition-colors">
            <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider">{{ __('Total Inventory') }}</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($stats['total_inventory_kg'], 0) }} <span class="text-lg">{{ __('kg') }}</span></div>
            <div class="text-xs text-emerald-500 mt-1 flex items-center gap-1"><i data-lucide="trending-up" class="w-3 h-3"></i> {{ __('Stable') }}</div>
        </div>
        <div class="glass-panel p-5 relative overflow-hidden group border border-emerald-500/20 dark:border-emerald-500/10 hover:border-emerald-500/50 transition-colors">
            <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider">{{ __('Low Stock Items') }}</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white">{{ $stats['low_stock_items'] }}</div>
            <div class="text-xs text-rose-500 mt-1 flex items-center gap-1"><i data-lucide="alert-triangle" class="w-3 h-3"></i> {{ __('Requires Reorder') }}</div>
        </div>
        <div class="glass-panel p-5 relative overflow-hidden group border border-emerald-500/20 dark:border-emerald-500/10 hover:border-emerald-500/50 transition-colors">
            <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider">{{ __('Active Plans') }}</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white">{{ $stats['active_feeding_plans'] }}</div>
            <div class="text-xs text-emerald-500 mt-1">{{ __('Daily Automated Rations') }}</div>
        </div>
        <div class="glass-panel p-5 relative overflow-hidden group border border-emerald-500/20 dark:border-emerald-500/10 hover:border-emerald-500/50 transition-colors">
            <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider">{{ __('Silo Utilization') }}</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white">{{ $stats['warehouse_utilization'] }}%</div>
            <div class="text-xs text-emerald-500 mt-1">{{ __('Average Capacity') }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- AI Nutrition Recommendations -->
            <div class="glass-panel p-6 border border-emerald-200 dark:border-emerald-900/50">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-800 pb-2 flex items-center justify-between">
                    <span class="flex items-center gap-2"><i data-lucide="brain-circuit" class="w-5 h-5 text-emerald-500"></i> {{ __('AI Formulation Engine') }}</span>
                    <span class="text-xs bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 px-2 py-1 rounded-full font-bold">{{ __('Lactating Ewes') }}</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm text-slate-500 mb-2">{{ __('Optimal Nutritional Targets') }}</div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ __('Crude Protein') }}</span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $aiRecommendation['recommended_formula']['protein_percentage'] }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-1.5">
                                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ min(100, $aiRecommendation['recommended_formula']['protein_percentage'] * 4) }}%"></div>
                            </div>
                            
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ __('Energy (Mcal/kg)') }}</span>
                                <span class="font-bold text-amber-600 dark:text-amber-400">{{ $aiRecommendation['recommended_formula']['energy_mcal'] }}</span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-1.5">
                                <div class="bg-amber-500 h-1.5 rounded-full" style="width: 70%"></div>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-100 dark:border-slate-700">
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-slate-500">{{ __('Est. Cost') }}</span>
                                <span class="font-bold text-slate-900 dark:text-white">${{ $aiRecommendation['estimated_cost_per_kg'] }} / {{ __('kg') }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500">{{ __('Expected FCR') }}</span>
                                <span class="font-bold text-slate-900 dark:text-white">{{ $aiRecommendation['expected_feed_efficiency'] }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="glass-panel border border-emerald-900/30 rounded-xl p-4 shadow-sm">
                        <div class="text-sm font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-emerald-500/10 pb-2 mb-3">{{ __('AI Suggested Mix') }}</div>
                        <ul class="space-y-2 text-sm">
                            @foreach($aiRecommendation['suggested_mix'] as $ingredient => $percentage)
                            <li class="flex justify-between items-center">
                                <span class="text-slate-600 dark:text-slate-400">{{ __($ingredient) }}</span>
                                <span class="font-bold text-emerald-800 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-0.5 rounded">{{ $percentage }}</span>
                            </li>
                            @endforeach
                        </ul>
                        <form action="{{ route('feed.apply_ai_plan') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full mt-4 btn-primary text-xs py-2">{{ __('Apply as New Feeding Plan') }}</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Inventory List -->
            <div class="glass-panel p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-800 pb-2 flex items-center justify-between">
                    <span class="flex items-center gap-2"><i data-lucide="boxes" class="w-5 h-5 text-emerald-500"></i> {{ __('Feed Inventory') }}</span>
                    <button class="text-xs font-bold text-emerald-500 hover:text-emerald-600">{{ __('View All') }}</button>
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500 dark:text-slate-400">
                        <thead class="text-xs text-emerald-800 uppercase bg-emerald-50 dark:bg-emerald-900/20 dark:text-emerald-300">
                            <tr>
                                <th class="px-4 py-3 rounded-tl-lg">{{ __('Feed Name') }}</th>
                                <th class="px-4 py-3">{{ __('Available') }}</th>
                                <th class="px-4 py-3">{{ __('Protein') }}</th>
                                <th class="px-4 py-3 rounded-tr-lg">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventory->take(5) as $item)
                            <tr class="border-b dark:border-slate-800 hover:bg-emerald-50 dark:hover:bg-emerald-900/10">
                                <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">{{ $item->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="{{ $item->total_quantity <= ($item->minimum_threshold ?? 0) ? 'text-rose-500 font-bold' : '' }}">
                                        {{ number_format($item->total_quantity, 0) }} {{ $item->unit ?? __('kg') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $item->nutritionProfile->protein_percentage ?? '--' }}%</td>
                                <td class="px-4 py-3">
                                    <button class="text-emerald-500 hover:underline">{{ __('Details') }}</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-500 italic">{{ __('No inventory available.') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Widgets -->
        <div class="space-y-6">
            
            <!-- Depletion Alerts -->
            <div class="glass-panel p-6 border border-rose-500/20 dark:border-rose-900/50">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-800 pb-2 flex items-center gap-2">
                    <i data-lucide="trending-down" class="w-5 h-5 text-rose-500"></i> {{ __('Depletion Forecast') }}
                </h3>
                <ul class="space-y-4">
                    @forelse($depletionAlerts as $alert)
                        <li class="bg-rose-50 dark:bg-rose-900/10 p-3 rounded-lg border border-rose-100 dark:border-rose-900/50">
                            <div class="flex justify-between items-start mb-1">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $alert['feed'] }}</div>
                                <span class="text-xs bg-rose-200 text-rose-800 dark:bg-rose-800/80 dark:text-rose-200 px-2 py-0.5 rounded font-bold">{{ $alert['days_remaining'] }} {{ __('Days Left') }}</span>
                            </div>
                            <div class="text-xs text-slate-600 dark:text-slate-400">{{ __('Order by:') }} <span class="font-bold">{{ $alert['optimal_purchase_date'] }}</span> {{ __('to avoid shortage.') }}</div>
                        </li>
                    @empty
                        <li class="text-sm text-emerald-600 dark:text-emerald-400 flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ __('No immediate shortages forecast.') }}
                        </li>
                    @endforelse
                </ul>
            </div>

            <!-- Silo Capacity Widget (Mock) -->
            <div class="glass-panel p-6 border border-emerald-500/20 dark:border-emerald-900/50">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-800 pb-2 flex items-center gap-2">
                    <i data-lucide="database" class="w-5 h-5 text-emerald-500"></i> {{ __('Silo Storage') }}
                </h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ __('Silo 1 (Corn)') }}</span>
                            <span class="text-slate-500">85%</span>
                        </div>
                        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ __('Silo 2 (Soybean)') }}</span>
                            <span class="text-slate-500">40%</span>
                        </div>
                        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full" style="width: 40%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ __('Silo 3 (Empty)') }}</span>
                            <span class="text-slate-500">0%</span>
                        </div>
                        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                            <div class="bg-slate-300 dark:bg-slate-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('feedDashboard', () => ({
            // Additional interactions can be handled here
        }));
    });
</script>
@endpush
@endsection
