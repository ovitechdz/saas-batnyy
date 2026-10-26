@props([
    'title' => 'AI Insights',
    'confidence' => '90%',
    'icon' => 'sparkles'
])

<div {{ $attributes->merge(['class' => 'glass hover-lift border-t-2 border-t-purple-500 shadow-[0_10px_30px_-10px_rgba(168,85,247,0.2)] rounded-2xl p-6 relative overflow-hidden group']) }}>
    
    <div class="absolute -right-16 -top-16 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl pointer-events-none group-hover:bg-purple-500/20 transition-all duration-700"></div>

    <div class="flex items-center justify-between mb-5 relative z-10">
        <h3 class="text-lg font-bold text-neutral-900 dark:text-white flex items-center gap-2">
            <i data-lucide="{{ $icon }}" class="w-5 h-5 text-purple-500"></i> {{ $title }}
        </h3>
        @if($confidence)
            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400 border border-purple-200 dark:border-purple-500/30">
                {{ $confidence }} Conf.
            </span>
        @endif
    </div>
    
    <div class="space-y-3 relative z-10">
        {{ $slot }}
    </div>
</div>
