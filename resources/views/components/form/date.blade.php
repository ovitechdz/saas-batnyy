@props([
    'disabled' => false,
    'label' => null,
    'error' => null,
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
        <div class="absolute left-4 text-neutral-400 group-focus-within:text-primary-500 transition-colors pointer-events-none z-10">
            <i data-lucide="calendar" class="w-5 h-5"></i>
        </div>

        <input 
            type="date"
            id="{{ $id }}"
            {{ $disabled ? 'disabled' : '' }}
            {!! $attributes->merge([
                'class' => 'block w-full pl-11 pr-4 py-3 rounded-xl border bg-neutral-50 dark:bg-neutral-900/50 text-neutral-900 dark:text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500/40 transition-all shadow-sm ' . 
                ($error ? 'border-danger focus:border-danger ring-danger/20' : 'border-neutral-200 dark:border-neutral-700 focus:border-primary-500')
            ]) !!}
        >
        
        <!-- Style the native date picker icon indicator (Webkit) -->
        <style>
            input[type="date"]::-webkit-calendar-picker-indicator {
                cursor: pointer;
                opacity: 0.6;
                transition: 0.2s;
                filter: invert(0.5); /* Adjust for dark mode if needed */
            }
            input[type="date"]::-webkit-calendar-picker-indicator:hover {
                opacity: 1;
            }
        </style>
    </div>

    @if($error)
        <p class="mt-1.5 text-xs text-danger animate-fade-up">
            {{ $error }}
        </p>
    @endif
</div>
