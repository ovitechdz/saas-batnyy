@props([
    'type' => 'button',
    'loading' => false,
    'disabled' => false,
    'icon' => null,
])

<button 
    type="{{ $type }}" 
    {{ $disabled || $loading ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => 'relative overflow-hidden inline-flex items-center justify-center p-2.5 text-neutral-600 dark:text-neutral-300 hover:text-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-xl transition-all duration-300 hover:scale-110 active:scale-95 disabled:opacity-60 disabled:pointer-events-none disabled:transform-none']) }}
>
    @if($loading)
        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @elseif($icon)
        <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
    @else
        {{ $slot }}
    @endif
</button>
