<div {{ $attributes->merge(['class' => 'glass hover-lift rounded-2xl p-6 relative overflow-hidden group']) }}>
    <!-- Decorative background glows -->
    <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/5 rounded-full blur-2xl group-hover:bg-white/10 transition-all duration-500 pointer-events-none"></div>
    <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-white/5 rounded-full blur-2xl group-hover:bg-white/10 transition-all duration-500 pointer-events-none"></div>
    
    <div class="relative z-10">
        {{ $slot }}
    </div>
</div>
