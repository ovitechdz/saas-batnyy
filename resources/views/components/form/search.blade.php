@props([
    'placeholder' => 'Search...',
    'shortcut' => 'Ctrl+K'
])

<div class="relative w-full group">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-neutral-400 group-focus-within:text-primary-500 transition-colors">
        <i data-lucide="search" class="w-5 h-5"></i>
    </div>
    
    <input 
        type="search" 
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'block w-full pl-10 pr-16 py-2.5 rounded-full border border-neutral-200 dark:border-neutral-700 bg-neutral-100 dark:bg-neutral-900/80 text-neutral-900 dark:text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 focus:bg-white dark:focus:bg-neutral-900 transition-all shadow-sm hover:border-neutral-300 dark:hover:border-neutral-600']) }}
    >
    
    @if($shortcut)
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <span class="hidden sm:inline-flex items-center px-2 py-0.5 border border-neutral-200 dark:border-neutral-700 rounded text-xs font-medium text-neutral-400 bg-white dark:bg-neutral-800">
                {{ $shortcut }}
            </span>
        </div>
    @endif
</div>
