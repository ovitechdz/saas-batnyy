@props([
    'title',
    'value',
    'icon',
    'trend' => null, /* string e.g. '+12%' */
    'trendLabel' => 'vs last month',
    'color' => 'primary', /* primary, secondary, ai, warning, danger */
])

@php
    $colorClasses = [
        'primary' => 'bg-primary-500/10 text-primary-500 group-hover:bg-primary-500/20',
        'secondary' => 'bg-secondary-500/10 text-secondary-500 group-hover:bg-secondary-500/20',
        'ai' => 'bg-purple-500/10 text-purple-500 group-hover:bg-purple-500/20',
        'warning' => 'bg-warning/10 text-warning group-hover:bg-warning/20',
        'danger' => 'bg-danger/10 text-danger group-hover:bg-danger/20',
    ];
    $iconClass = $colorClasses[$color] ?? $colorClasses['primary'];
@endphp

<x-card.glass {{ $attributes }}>
    <div class="flex justify-between items-start mb-4">
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">{{ $title }}</p>
            <h3 class="text-3xl font-bold text-neutral-900 dark:text-white mt-1">{{ $value }}</h3>
        </div>
        <div class="p-3 rounded-xl transition-colors {{ $iconClass }}">
            <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
        </div>
    </div>
    
    @if($trend)
        <div class="flex items-center text-sm mt-4">
            @if(str_starts_with($trend, '+'))
                <span class="text-success flex items-center font-medium bg-success/10 px-2 py-0.5 rounded">
                    <i data-lucide="arrow-up-right" class="w-3 h-3 mr-1"></i> {{ $trend }}
                </span>
            @elseif(str_starts_with($trend, '-'))
                <span class="text-danger flex items-center font-medium bg-danger/10 px-2 py-0.5 rounded">
                    <i data-lucide="arrow-down-right" class="w-3 h-3 mr-1"></i> {{ $trend }}
                </span>
            @else
                <span class="text-neutral-500 flex items-center font-medium bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5 rounded">
                    <i data-lucide="minus" class="w-3 h-3 mr-1"></i> {{ $trend }}
                </span>
            @endif
            
            <span class="text-neutral-500 ml-2 text-xs">{{ $trendLabel }}</span>
        </div>
    @endif

    @isset($chart)
        <div class="mt-4 h-12 w-full opacity-60 group-hover:opacity-100 transition-opacity">
            {{ $chart }}
        </div>
    @endisset
</x-card.glass>
