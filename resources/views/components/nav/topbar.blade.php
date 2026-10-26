<header class="h-20 bg-surface-light dark:bg-surface-dark border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between px-8 sticky top-0 z-30 transition-colors">
    <div class="flex items-center flex-1">
        <!-- Optional Mobile Menu Trigger here -->
        {{ $left ?? '' }}
    </div>

    <div class="flex items-center gap-6">
        <!-- Global Search Trigger -->
        <button class="flex items-center gap-2 text-sm text-neutral-500 hover:text-neutral-900 dark:hover:text-white transition-colors bg-neutral-100 dark:bg-neutral-800/50 px-4 py-2 rounded-full border border-neutral-200 dark:border-neutral-700">
            <i data-lucide="search" class="w-4 h-4"></i>
            <span class="hidden sm:inline-block">Quick Search...</span>
            <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-[10px] font-mono bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 rounded ml-2">Ctrl+K</kbd>
        </button>

        <!-- Notification Bell -->
        <button class="relative text-neutral-400 hover:text-primary-500 transition-colors">
            <i data-lucide="bell" class="w-6 h-6 animate-pulse-ring"></i>
            <span class="absolute top-0 right-0 w-2 h-2 bg-danger rounded-full border-2 border-surface-light dark:border-surface-dark"></span>
        </button>

        <!-- Right slot for additional actions/profile -->
        {{ $right ?? '' }}
    </div>
</header>
