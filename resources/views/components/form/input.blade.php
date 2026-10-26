@props([
    'disabled' => false,
    'label' => null,
    'icon' => null,
    'error' => null,
    'success' => false,
    'type' => 'text'
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
            <div class="absolute left-4 text-neutral-400 group-focus-within:text-primary-500 transition-colors pointer-events-none">
                <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
            </div>
        @endif

        <input 
            type="{{ $type }}"
            id="{{ $id }}"
            {{ $disabled ? 'disabled' : '' }}
            {!! $attributes->merge([
                'class' => 'block w-full rounded-xl border bg-neutral-50 dark:bg-neutral-900/50 text-neutral-900 dark:text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500/40 transition-all shadow-sm ' . 
                ($icon ? 'pl-11 pr-4 py-3' : 'px-4 py-3') . ' ' .
                ($error ? 'border-danger focus:border-danger ring-danger/20' : 
                ($success ? 'border-success focus:border-success ring-success/20' : 'border-neutral-200 dark:border-neutral-700 focus:border-primary-500'))
            ]) !!}
        >

        @if($error)
            <div class="absolute right-4 text-danger pointer-events-none">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
            </div>
        @elseif($success)
            <div class="absolute right-4 text-success pointer-events-none">
                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
            </div>
        @endif
        
        @if($type === 'password' && !$error && !$success)
            <button type="button" x-data @click="$el.previousElementSibling.type = $el.previousElementSibling.type === 'password' ? 'text' : 'password'" class="absolute right-4 text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 focus:outline-none">
                <i data-lucide="eye" class="w-5 h-5"></i>
            </button>
        @endif
    </div>

    @if($error)
        <p class="mt-1.5 text-xs text-danger flex items-center gap-1 animate-fade-up">
            {{ $error }}
        </p>
    @endif
</div>
