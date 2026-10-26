@props([
    'title',
    'amount',
    'trend' => null,
    'trendText' => 'vs last month',
    'type' => 'revenue' // revenue, expense, balance
])

@php
    $typeConfig = [
        'revenue' => ['icon' => 'trending-up', 'color' => 'primary', 'bg' => 'bg-primary-50 dark:bg-primary-900/10', 'text' => 'text-primary-600 dark:text-primary-400'],
        'expense' => ['icon' => 'trending-down', 'color' => 'danger', 'bg' => 'bg-red-50 dark:bg-red-900/10', 'text' => 'text-red-600 dark:text-red-400'],
        'balance' => ['icon' => 'dollar-sign', 'color' => 'secondary', 'bg' => 'bg-blue-50 dark:bg-blue-900/10', 'text' => 'text-blue-600 dark:text-blue-400'],
    ];
    $config = $typeConfig[$type] ?? $typeConfig['revenue'];
@endphp

<div {{ $attributes->merge(['class' => 'glass hover-lift rounded-2xl p-6 group']) }}>
    <div class="flex justify-between items-start mb-4">
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">{{ $title }}</p>
            <h3 class="text-3xl font-bold text-neutral-900 dark:text-white mt-1">{{ $amount }}</h3>
        </div>
        <div class="p-3 {{ $config['bg'] }} rounded-xl {{ $config['text'] }} group-hover:scale-110 transition-transform">
            <i data-lucide="{{ $config['icon'] }}" class="w-6 h-6"></i>
        </div>
    </div>

    <div class="flex items-center justify-between text-sm mt-4">
        @if($trend)
            <div class="flex items-center">
                @if(str_starts_with($trend, '+'))
                    <span class="text-success flex items-center font-bold bg-success/10 px-2 py-0.5 rounded">
                        <i data-lucide="arrow-up-right" class="w-3 h-3 mr-1"></i> {{ $trend }}
                    </span>
                @else
                    <span class="text-danger flex items-center font-bold bg-danger/10 px-2 py-0.5 rounded">
                        <i data-lucide="arrow-down-right" class="w-3 h-3 mr-1"></i> {{ $trend }}
                    </span>
                @endif
                <span class="text-neutral-500 ml-2 text-xs">{{ $trendText }}</span>
            </div>
        @endif
    </div>
    
    @isset($chart)
        <div class="mt-4 h-16 w-full relative">
            {{ $chart }}
        </div>
    @endisset
</div>
