@props([
    'animal',
    'actions' => null
])

<div {{ $attributes->merge(['class' => 'glass rounded-2xl p-5 hover-lift group border border-neutral-200 dark:border-neutral-800 flex flex-col justify-between']) }}>
    <div class="flex justify-between items-start mb-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-neutral-200 dark:bg-neutral-800 border-2 border-white dark:border-neutral-700 shadow-md flex items-center justify-center overflow-hidden shrink-0">
                @if(isset($animal->photo) && $animal->photo)
                    <img src="{{ Storage::url($animal->photo) }}" alt="{{ $animal->name }}" class="w-full h-full object-cover">
                @else
                    <i data-lucide="image" class="w-5 h-5 text-neutral-400"></i>
                @endif
            </div>
            <div>
                <h4 class="text-base font-bold text-neutral-900 dark:text-white flex items-center gap-2">
                    {{ $animal->name ?? 'Unnamed' }}
                    @if(isset($animal->gender) && $animal->gender == 'female')
                        <i data-lucide="venus" class="w-3 h-3 text-pink-500"></i>
                    @elseif(isset($animal->gender) && $animal->gender == 'male')
                        <i data-lucide="mars" class="w-3 h-3 text-blue-500"></i>
                    @endif
                </h4>
                <p class="text-xs text-neutral-500 font-mono mt-0.5">RFID: {{ $animal->rfid_tag ?? 'N/A' }}</p>
            </div>
        </div>
        <div>
            @if(isset($animal->status))
                @if($animal->status == 'healthy')
                    <span class="px-2.5 py-1 text-[10px] uppercase font-bold tracking-wider rounded-full bg-success/10 text-success border border-success/20">Healthy</span>
                @elseif($animal->status == 'sick')
                    <span class="px-2.5 py-1 text-[10px] uppercase font-bold tracking-wider rounded-full bg-danger/10 text-danger border border-danger/20">Sick</span>
                @else
                    <span class="px-2.5 py-1 text-[10px] uppercase font-bold tracking-wider rounded-full bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-300 border border-neutral-200 dark:border-neutral-700">{{ $animal->status }}</span>
                @endif
            @endif
        </div>
    </div>
    
    <div class="grid grid-cols-2 gap-3 mb-4 mt-2 border-y border-neutral-100 dark:border-neutral-800/50 py-3">
        <div>
            <p class="text-[10px] text-neutral-400 uppercase font-semibold">Breed</p>
            <p class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ ucfirst($animal->breed ?? 'Unknown') }}</p>
        </div>
        <div>
            <p class="text-[10px] text-neutral-400 uppercase font-semibold">Weight</p>
            <p class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ $animal->weight_kg ? $animal->weight_kg.' kg' : '-' }}</p>
        </div>
    </div>
    
    @if($actions)
        <div class="flex gap-2 mt-auto">
            {{ $actions }}
        </div>
    @else
        <div class="flex gap-2 mt-auto">
            <x-button.secondary class="w-full py-1.5 text-xs">View Details</x-button.secondary>
        </div>
    @endif
</div>
