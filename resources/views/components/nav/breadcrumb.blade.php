@props(['items' => []])

<nav class="flex text-sm text-neutral-500 font-medium mb-4" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @foreach($items as $label => $url)
            <li class="inline-flex items-center">
                @if(!$loop->first)
                    <i data-lucide="chevron-right" class="w-4 h-4 mx-1 text-neutral-400"></i>
                @endif
                
                @if($url && !$loop->last)
                    <a href="{{ $url }}" class="inline-flex items-center hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        @if($loop->first)
                            <i data-lucide="home" class="w-4 h-4 mr-2"></i>
                        @endif
                        {{ $label }}
                    </a>
                @else
                    <span class="text-neutral-900 dark:text-neutral-300 font-semibold flex items-center">
                        @if($loop->first)
                            <i data-lucide="home" class="w-4 h-4 mr-2"></i>
                        @endif
                        {{ $label }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
