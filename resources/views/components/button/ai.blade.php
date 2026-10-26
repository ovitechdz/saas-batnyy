@props([
    'type' => 'button',
    'loading' => false,
    'disabled' => false,
    'icon' => 'sparkles',
])

<button 
    type="{{ $type }}" 
    {{ $disabled || $loading ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => 'relative overflow-hidden inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-purple-500 to-blue-500 rounded-xl shadow-[0_0_20px_rgba(139,92,246,0.5)] hover:shadow-[0_0_30px_rgba(139,92,246,0.7)] hover:scale-105 active:scale-95 transition-all duration-300 disabled:opacity-60 disabled:pointer-events-none group']) }}
>
    <div class="absolute inset-0 bg-white/20 w-full animate-[shimmer_2s_infinite]"></div>
    
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white relative z-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @elseif($icon)
        <i data-lucide="{{ $icon }}" class="w-4 h-4 relative z-10 group-hover:animate-pulse"></i>
    @endif
    
    <span class="relative z-10">{{ $slot }}</span>
</button>
