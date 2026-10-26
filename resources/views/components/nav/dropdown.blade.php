@props(['align' => 'right', 'trigger'])

<div x-data="{ open: false }" @click.away="open = false" class="relative inline-block text-left z-50">
    <div @click="open = !open" class="cursor-pointer">
        {{ $trigger }}
    </div>

    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
        class="absolute mt-2 w-56 rounded-xl shadow-premium-light dark:shadow-premium-dark bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 focus:outline-none origin-top-{{ $align }} {{ $align === 'right' ? 'right-0' : 'left-0' }}"
        style="display: none;"
    >
        <div class="py-2" role="menu" aria-orientation="vertical">
            {{ $slot }}
        </div>
    </div>
</div>
