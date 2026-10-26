@props([
    'type' => 'text', // text, avatar, card, table-row
    'lines' => 1
])

<div {{ $attributes->merge(['class' => 'animate-skeleton w-full']) }}>
    @if($type === 'text')
        <div class="space-y-2">
            @for($i = 0; $i < $lines; $i++)
                <div class="h-4 bg-neutral-200 dark:bg-neutral-800 rounded {{ $i === $lines - 1 && $lines > 1 ? 'w-2/3' : 'w-full' }}"></div>
            @endfor
        </div>
    @elseif($type === 'avatar')
        <div class="w-12 h-12 bg-neutral-200 dark:bg-neutral-800 rounded-full"></div>
    @elseif($type === 'card')
        <div class="p-4 border border-neutral-200 dark:border-neutral-800 rounded-2xl bg-white dark:bg-neutral-900/50">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-neutral-200 dark:bg-neutral-800 rounded-full"></div>
                <div class="space-y-2 flex-1">
                    <div class="h-4 bg-neutral-200 dark:bg-neutral-800 rounded w-1/3"></div>
                    <div class="h-3 bg-neutral-200 dark:bg-neutral-800 rounded w-1/4"></div>
                </div>
            </div>
            <div class="space-y-2">
                <div class="h-3 bg-neutral-200 dark:bg-neutral-800 rounded w-full"></div>
                <div class="h-3 bg-neutral-200 dark:bg-neutral-800 rounded w-5/6"></div>
            </div>
        </div>
    @elseif($type === 'table-row')
        <div class="flex items-center justify-between p-4 border-b border-neutral-200 dark:border-neutral-800">
            <div class="h-4 bg-neutral-200 dark:bg-neutral-800 rounded w-1/4"></div>
            <div class="h-4 bg-neutral-200 dark:bg-neutral-800 rounded w-1/4"></div>
            <div class="h-4 bg-neutral-200 dark:bg-neutral-800 rounded w-1/6"></div>
            <div class="h-8 w-8 bg-neutral-200 dark:bg-neutral-800 rounded-lg"></div>
        </div>
    @endif
</div>
