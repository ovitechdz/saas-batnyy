@props([
    'align' => 'left'
])

<td {{ $attributes->merge(['class' => "px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300 whitespace-nowrap text-$align group-hover:bg-primary-50/30 dark:group-hover:bg-primary-900/10 transition-colors"]) }}>
    {{ $slot }}
</td>
