@props([
    'disabled' => false,
    'label' => null,
    'icon' => null,
    'error' => null,
    'options' => [] // Expected format: ['value' => 'Label']
])

@php
    $id = $attributes->get('id') ?? Str::random(8);
@endphp

<div class="relative w-full">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1.5 transition-colors">
            {{ $label }}
        </label>
    @endif

    <div class="relative flex items-center group">
        @if($icon)
            <div class="absolute left-4 text-neutral-400 group-focus-within:text-primary-500 transition-colors pointer-events-none z-10">
                <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
            </div>
        @endif

        <select 
            id="{{ $id }}"
            {{ $disabled ? 'disabled' : '' }}
            {!! $attributes->merge([
                'class' => 'block w-full appearance-none rounded-xl border bg-neutral-50 dark:bg-neutral-900/50 text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500/40 transition-all shadow-sm cursor-pointer ' . 
                ($icon ? 'pl-11 pr-10 py-3' : 'pl-4 pr-10 py-3') . ' ' .
                ($error ? 'border-danger focus:border-danger ring-danger/20' : 'border-neutral-200 dark:border-neutral-700 focus:border-primary-500')
            ]) !!}
        >
            @if($slot->isNotEmpty())
                {{ $slot }}
            @else
                @foreach($options as $value => $text)
                    <option value="{{ $value }}">{{ $text }}</option>
                @endforeach
            @endif
        </select>

        <div class="absolute right-4 text-neutral-400 pointer-events-none transition-transform group-hover:text-neutral-600 dark:group-hover:text-neutral-300">
            <i data-lucide="chevron-down" class="w-5 h-5"></i>
        </div>
    </div>

    @if($error)
        <p class="mt-1.5 text-xs text-danger animate-fade-up">
            {{ $error }}
        </p>
    @endif
</div>
