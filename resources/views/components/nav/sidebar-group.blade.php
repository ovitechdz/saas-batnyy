@props([
    'icon'    => 'folder',
    'label'   => '',
    'active'  => false,
    'color'   => 'slate',
    'count'   => null,
])

@php
    $colorMap = [
        'emerald' => ['icon' => 'color: #4ade80;', 'border' => 'border-l-[#4ade80]'],
        'sky'     => ['icon' => 'color: #38bdf8;', 'border' => 'border-l-[#38bdf8]'],
        'indigo'  => ['icon' => 'color: #818cf8;', 'border' => 'border-l-[#818cf8]'],
        'rose'    => ['icon' => 'color: #fb7185;', 'border' => 'border-l-[#fb7185]'],
        'amber'   => ['icon' => 'color: #fbbf24;', 'border' => 'border-l-[#fbbf24]'],
        'purple'  => ['icon' => 'color: #c084fc;', 'border' => 'border-l-[#c084fc]'],
        'slate'   => ['icon' => 'color: rgba(255,255,255,0.5);', 'border' => 'border-l-slate-500'],
    ];
    $theme = $colorMap[$color] ?? $colorMap['slate'];
    $iconStyle   = $active ? $theme['icon'] : 'color: rgba(255,255,255,0.45);';
    $borderClass = $active ? 'border-l-2 ' . $theme['border'] : 'border-l-2 border-l-transparent';
@endphp

<div x-data="{ groupOpen: {{ $active ? 'true' : 'false' }} }" class="agri-card {{ $borderClass }}">
    {{-- Group Header Button --}}
    <button @click="groupOpen = !groupOpen"
            class="agri-card-header group w-full flex items-center justify-between">
        
        <div class="flex items-center gap-2.5">
            <i data-lucide="{{ $icon }}"
               class="w-[15px] h-[15px] flex-shrink-0 transition-transform duration-300 group-hover:scale-110"
               style="{{ $iconStyle }}"></i>

            <span class="truncate text-[12.5px] tracking-wide" style="{{ $active ? 'color: #ffffff; font-weight: 600;' : '' }}">
                {{ $label }}
            </span>
        </div>

        <div class="flex items-center gap-2">
            @if($count)
            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md transition-colors"
                  style="background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.4); border: 1px solid rgba(255,255,255,0.06);">
                {{ $count }}
            </span>
            @endif

            <div class="flex-shrink-0 transition-transform duration-300" :class="groupOpen ? 'rotate-180' : ''">
                <i data-lucide="chevron-down" class="w-3.5 h-3.5" style="color: rgba(255,255,255,0.3);"></i>
            </div>
        </div>
    </button>

    {{-- Children --}}
    <div x-show="groupOpen"
         x-collapse.duration.300ms
         x-cloak>
        <div class="px-1.5 pb-2 pt-0.5 flex flex-col space-y-0.5">
            {{ $slot }}
        </div>
    </div>
</div>
