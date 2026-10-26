@props([
    'title',
    'icon' => 'alert-triangle',
])

<div {{ $attributes->merge(['class' => 'bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-2xl p-5 relative overflow-hidden group']) }}>
    <div class="absolute -right-4 -top-4 opacity-5 pointer-events-none transform group-hover:scale-110 transition-transform">
        <i data-lucide="{{ $icon }}" class="w-24 h-24 text-amber-500"></i>
    </div>

    <div class="flex items-start gap-4 relative z-10">
        <div class="p-2.5 bg-amber-100 dark:bg-amber-800/50 rounded-xl text-amber-600 dark:text-amber-400 shrink-0">
            <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
        </div>
        <div>
            <h4 class="text-sm font-bold text-amber-900 dark:text-amber-100 mb-1">{{ $title }}</h4>
            <div class="text-sm text-amber-700 dark:text-amber-300/80 leading-relaxed">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
