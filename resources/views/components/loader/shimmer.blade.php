<div {{ $attributes->merge(['class' => 'relative overflow-hidden bg-neutral-100 dark:bg-neutral-800/50 rounded-xl']) }}>
    <!-- Base content placeholder -->
    <div class="opacity-0">
        {{ $slot }}
    </div>
    
    <!-- Shimmer effect -->
    <div class="absolute inset-0 z-10 animate-[shimmer_2s_infinite] bg-gradient-to-r from-transparent via-white/40 dark:via-white/5 to-transparent skew-x-[-20deg]"></div>
    
    <!-- Actual content wrapper (if provided to shape the skeleton) -->
    @if($slot->isNotEmpty())
        <div class="absolute inset-0 bg-neutral-200 dark:bg-neutral-800 mask-image-content">
            {{ $slot }}
        </div>
    @endif
</div>
