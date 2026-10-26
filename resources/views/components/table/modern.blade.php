@props([
    'title' => null,
    'description' => null,
    'headers' => null,
    'actions' => null,
])

<div class="glass rounded-2xl overflow-hidden shadow-premium-light dark:shadow-premium-dark flex flex-col">
    @if($title || $actions)
        <div class="p-6 border-b border-neutral-200 dark:border-neutral-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                @if($title)
                    <h3 class="text-lg font-bold text-neutral-900 dark:text-white">{{ $title }}</h3>
                @endif
                @if($description)
                    <p class="text-xs text-neutral-500 mt-1">{{ $description }}</p>
                @endif
            </div>
            
            @if($actions)
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif
    
    <div class="overflow-x-auto custom-scrollbar flex-1 relative">
        <table class="w-full text-left border-collapse min-w-max">
            @if($headers)
                <thead class="bg-neutral-50/80 dark:bg-neutral-900/80 backdrop-blur sticky top-0 z-10 shadow-sm border-b border-neutral-200 dark:border-neutral-800">
                    <tr>
                        {{ $headers }}
                    </tr>
                </thead>
            @endif
            
            <tbody class="divide-y divide-neutral-200/50 dark:divide-neutral-800/50">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
