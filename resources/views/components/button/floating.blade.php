@props([
    'type' => 'button',
    'loading' => false,
    'disabled' => false,
    'icon' => 'plus',
])

<button 
    type="{{ $type }}" 
    {{ $disabled || $loading ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => 'fixed bottom-8 right-8 z-50 flex items-center justify-center w-14 h-14 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-full shadow-[0_10px_25px_rgba(16,185,129,0.5)] hover:shadow-[0_15px_35px_rgba(16,185,129,0.6)] hover:scale-110 active:scale-95 transition-all duration-300 disabled:opacity-60 disabled:pointer-events-none group']) }}
>
    @if($loading)
        <svg class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @else
        <i data-lucide="{{ $icon }}" class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300"></i>
    @endif
</button>
