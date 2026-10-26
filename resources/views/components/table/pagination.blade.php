@props(['paginator'])

@if ($paginator->hasPages())
    <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-800 bg-neutral-50/50 dark:bg-neutral-900/30 flex items-center justify-between">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-neutral-700 dark:text-neutral-400">
                    Showing
                    <span class="font-medium text-neutral-900 dark:text-white">{{ $paginator->firstItem() }}</span>
                    to
                    <span class="font-medium text-neutral-900 dark:text-white">{{ $paginator->lastItem() }}</span>
                    of
                    <span class="font-medium text-neutral-900 dark:text-white">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>
            
            <div>
                {{ $paginator->links('pagination::tailwind') }}
            </div>
        </div>
        
        <!-- Mobile Simple Pagination -->
        <div class="flex items-center justify-between w-full sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-400 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 cursor-default rounded-md">
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-200 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                    Previous
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-200 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                    Next
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-400 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 cursor-default rounded-md">
                    Next
                </span>
            @endif
        </div>
    </div>
@endif
