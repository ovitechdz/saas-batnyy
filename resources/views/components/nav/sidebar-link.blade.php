@props([
    'route',
    'icon'    => 'circle',
    'label'   => '',
    'active'  => false,
    'color'   => 'emerald',
    'badge'   => null,
    'count'   => null,
])

@php
    $colorMap = [
        'emerald' => 'color: #4ade80;',
        'sky'     => 'color: #38bdf8;',
        'indigo'  => 'color: #818cf8;',
        'rose'    => 'color: #fb7185;',
        'amber'   => 'color: #fbbf24;',
        'purple'  => 'color: #c084fc;',
        'slate'   => 'color: rgba(255,255,255,0.5);',
        'red'     => 'color: #f87171;',
    ];
    $iconStyle = $active ? ($colorMap[$color] ?? $colorMap['emerald']) : 'color: rgba(255,255,255,0.4);';
@endphp

<a href="{{ route($route) }}"
   class="agri-link {{ $active ? 'agri-link-active' : '' }} group flex items-center justify-between">

    <div class="flex items-center gap-2.5 min-w-0">
        {{-- Icon --}}
        <i data-lucide="{{ $icon }}"
           class="w-[14px] h-[14px] flex-shrink-0 transition-transform duration-200 group-hover:scale-105"
           style="{{ $iconStyle }}"></i>

        {{-- Label --}}
        <span class="truncate text-[12px]">
            {{ $label }}
        </span>
    </div>

    <div class="flex items-center gap-1.5 flex-shrink-0">
        {{-- Count Badge --}}
        @if($count)
        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md"
              style="background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.35);">
            {{ $count }}
        </span>
        @endif

        {{-- Alert Badge --}}
        @if($badge)
        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded"
              style="background: rgba(239,68,68,0.8); color: #fff; box-shadow: 0 0 8px rgba(239,68,68,0.4);">
            {{ $badge }}
        </span>
        @endif

        {{-- Chevron --}}
        <i data-lucide="{{ app()->getLocale() == 'ar' ? 'chevron-left' : 'chevron-right' }}"
           class="w-3 h-3 transition-transform group-hover:{{ app()->getLocale() == 'ar' ? '-translate-x-0.5' : 'translate-x-0.5' }}"
           style="color: rgba(255,255,255,0.2);"></i>
    </div>
</a>
