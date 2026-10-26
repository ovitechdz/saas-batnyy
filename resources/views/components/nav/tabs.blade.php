@props(['tabs' => []])

<div x-data="{ activeTab: '{{ array_key_first($tabs) }}' }" class="w-full">
    <!-- Tab Headers -->
    <div class="flex space-x-1 p-1 bg-neutral-100 dark:bg-neutral-800/50 rounded-xl max-w-max border border-neutral-200 dark:border-neutral-700/50">
        @foreach($tabs as $key => $label)
            <button 
                @click="activeTab = '{{ $key }}'"
                :class="activeTab === '{{ $key }}' ? 'bg-white dark:bg-neutral-700 text-neutral-900 dark:text-white shadow-sm' : 'text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300 hover:bg-neutral-200/50 dark:hover:bg-neutral-700/30'"
                class="px-5 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- Tab Contents -->
    <div class="mt-6">
        {{ $slot }}
    </div>
</div>
