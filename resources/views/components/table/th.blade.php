@props([
    'sortable' => false,
    'direction' => null, // 'asc' or 'desc'
    'align' => 'left' // 'left', 'center', 'right'
])

<th {{ $attributes->merge(['class' => "px-6 py-4 text-xs font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400 whitespace-nowrap text-$align"]) }}>
    @if($sortable)
        <button class="flex items-center gap-1 hover:text-primary-600 dark:hover:text-primary-400 transition-colors focus:outline-none group">
            {{ $slot }}
            <span class="flex flex-col opacity-50 group-hover:opacity-100 transition-opacity">
                <i data-lucide="chevron-up" class="w-3 h-3 -mb-1 {{ $direction === 'asc' ? 'text-primary-500 opacity-100' : '' }}"></i>
                <i data-lucide="chevron-down" class="w-3 h-3 {{ $direction === 'desc' ? 'text-primary-500 opacity-100' : '' }}"></i>
            </span>
        </button>
    @else
        {{ $slot }}
    @endif
</th>
