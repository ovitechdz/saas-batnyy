@props([
    'title',
    'icon' => 'info',
])

<div {{ $attributes->merge(['class' => 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-2xl p-5 relative overflow-hidden group']) }}>
    <div class="flex items-start gap-4">
        <div class="p-2.5 bg-blue-100 dark:bg-blue-800/50 rounded-xl text-blue-600 dark:text-blue-400 shrink-0">
            <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
        </div>
        <div>
            <h4 class="text-sm font-bold text-blue-900 dark:text-blue-100 mb-1">{{ $title }}</h4>
            <div class="text-sm text-blue-700 dark:text-blue-300/80 leading-relaxed">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
