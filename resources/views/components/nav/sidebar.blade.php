<aside x-data="{ expanded: true }" :class="expanded ? 'w-72' : 'w-24'" class="fixed inset-y-0 left-0 z-40 flex flex-col bg-sidebar-light dark:bg-sidebar-dark border-r border-neutral-200 dark:border-neutral-800 transition-all duration-300 shadow-premium-light dark:shadow-premium-dark">
    
    <!-- Logo Area -->
    <div class="flex items-center justify-between h-20 px-6 border-b border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shrink-0 shadow-glow-primary">
                <i data-lucide="leaf" class="w-6 h-6 text-white"></i>
            </div>
            <span x-show="expanded" x-transition.opacity.duration.300ms class="text-xl font-black text-neutral-900 dark:text-white tracking-tight">OVITECH</span>
        </div>
        <button @click="expanded = !expanded" class="text-neutral-400 hover:text-primary-500 transition-colors shrink-0">
            <i data-lucide="align-left" class="w-6 h-6"></i>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-2 custom-scrollbar">
        {{ $slot }}
    </div>

    <!-- User Profile Footer -->
    <div class="p-4 border-t border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap p-2 rounded-xl hover:bg-neutral-100 dark:hover:bg-neutral-800/50 cursor-pointer transition-colors">
            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/50 flex items-center justify-center shrink-0">
                <span class="text-primary-700 dark:text-primary-400 font-bold">JD</span>
            </div>
            <div x-show="expanded" x-transition.opacity.duration.300ms>
                <p class="text-sm font-semibold text-neutral-900 dark:text-white">John Doe</p>
                <p class="text-xs text-neutral-500">Farm Manager</p>
            </div>
        </div>
    </div>
</aside>
