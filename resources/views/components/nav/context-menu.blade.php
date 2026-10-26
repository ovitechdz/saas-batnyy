@props(['targetId'])

<div x-data="{ 
        open: false, 
        top: '0px', 
        left: '0px',
        openMenu(e) {
            this.open = true;
            this.left = e.clientX + 'px';
            this.top = e.clientY + 'px';
            e.preventDefault();
        }
    }" 
    @contextmenu.window="if($event.target.closest('#{{ $targetId }}')) openMenu($event)"
    @click.away="open = false" 
    class="relative z-50"
>
    <div 
        x-show="open" 
        x-transition.opacity.duration.200ms
        :style="`top: ${top}; left: ${left}; position: fixed;`"
        class="w-48 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-premium-light dark:shadow-premium-dark py-2 flex flex-col"
        style="display: none;"
    >
        {{ $slot }}
    </div>
</div>
