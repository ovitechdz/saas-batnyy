@extends('layouts.app')
@section('title', 'Animal Profile: ' . ($animal->internal_id ?? 'Unknown'))

@section('content')
<div class="p-4 sm:p-8 space-y-6 max-w-7xl mx-auto" x-data="{ activeTab: 'overview' }">

    <!-- Header / Banner -->
    <div class="glass-panel overflow-hidden relative">
        <div class="h-32 bg-gradient-to-r from-emerald-500 to-teal-600 relative">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20 mix-blend-overlay"></div>
            @if($animal->status?->name === 'Deceased')
                <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center">
                    <span class="text-white font-black text-2xl tracking-widest uppercase opacity-50">Deceased</span>
                </div>
            @endif
        </div>
        <div class="px-6 pb-6 relative">
            <div class="flex flex-col md:flex-row gap-6 items-start">
                <div class="relative group shrink-0 -mt-12 md:-mt-16" x-data="{
                    openPhotoDialog() {
                        document.getElementById('photo-upload-input').click();
                    },
                    submitPhoto() {
                        document.getElementById('photo-upload-form').submit();
                    }
                }">
                    <img src="{{ $animal->primary_photo_url }}" alt="Profile" class="w-24 h-24 md:w-32 md:h-32 rounded-2xl object-cover border-4 border-white dark:border-slate-900 shadow-xl bg-white dark:bg-slate-800">
                    <button @click="openPhotoDialog()" class="absolute bottom-2 right-2 p-1.5 bg-slate-900/50 hover:bg-emerald-500 text-white rounded-lg backdrop-blur-md transition-colors opacity-0 group-hover:opacity-100">
                        <i data-lucide="camera" class="w-4 h-4"></i>
                    </button>
                    <form id="photo-upload-form" action="{{ route('livestock.photo', $animal->id) }}" method="POST" enctype="multipart/form-data" class="hidden">
                        @csrf
                        <input type="file" id="photo-upload-input" name="photo" accept="image/*" @change="submitPhoto()">
                    </form>
                </div>
                
                <div class="flex-1 w-full flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pt-4 md:pt-0">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h1 class="text-3xl font-black text-slate-900 dark:text-white">{{ $animal->internal_id ?? 'No ID' }}</h1>
                            @if($animal->gender === 'M')
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400 flex items-center justify-center" title="Male">
                                    <i data-lucide="mars" class="w-4 h-4"></i>
                                </div>
                            @elseif($animal->gender === 'F')
                                <div class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 dark:bg-pink-500/20 dark:text-pink-400 flex items-center justify-center" title="Female">
                                    <i data-lucide="venus" class="w-4 h-4"></i>
                                </div>
                            @endif
                        </div>
                        <p class="text-lg text-slate-500 dark:text-slate-400 font-medium">
                            {{ $animal->breed?->name ?? __('Mixed Breed') }} • {{ $animal->category?->name ?? __('Standard') }}
                        </p>
                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-sm font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                <i data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i>
                                {{ $animal->barn?->name ?? __('Unassigned Location') }}
                            </span>
                            @if($animal->electronic_tag)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-sm font-mono bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                <i data-lucide="radio" class="w-4 h-4"></i>
                                RFID: {{ $animal->electronic_tag }}
                            </span>
                            @endif
                            <div class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 {{ ($animal->status?->name ?? '') === 'Healthy' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400' }}">
                                <div class="w-2 h-2 rounded-full {{ ($animal->status?->name ?? '') === 'Healthy' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                                {{ $animal->status?->name ?? __('Unknown Status') }}
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 w-full md:w-auto">
                        <a href="{{ route('livestock.edit', $animal->id) }}" class="btn-secondary w-full md:w-auto flex justify-center items-center gap-2">
                            <i data-lucide="edit" class="w-4 h-4"></i> {{ __('Edit Profile') }}
                        </a>
                        <button class="btn-primary w-full md:w-auto flex justify-center items-center gap-2">
                            <i data-lucide="plus" class="w-4 h-4"></i> {{ __('Log Event') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="px-6 flex overflow-x-auto border-t border-slate-200 dark:border-slate-800 scrollbar-hide">
            <button @click="activeTab = 'overview'" :class="{'border-emerald-500 text-emerald-600 dark:text-emerald-400': activeTab === 'overview', 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300': activeTab !== 'overview'}" class="px-4 py-4 border-b-2 font-semibold text-sm whitespace-nowrap transition-colors flex items-center gap-2">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i> {{ __('Overview') }}
            </button>
            <button @click="activeTab = 'health'" :class="{'border-emerald-500 text-emerald-600 dark:text-emerald-400': activeTab === 'health', 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300': activeTab !== 'health'}" class="px-4 py-4 border-b-2 font-semibold text-sm whitespace-nowrap transition-colors flex items-center gap-2">
                <i data-lucide="activity" class="w-4 h-4"></i> {{ __('Health & Vet') }}
            </button>
            <button @click="activeTab = 'growth'" :class="{'border-emerald-500 text-emerald-600 dark:text-emerald-400': activeTab === 'growth', 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300': activeTab !== 'growth'}" class="px-4 py-4 border-b-2 font-semibold text-sm whitespace-nowrap transition-colors flex items-center gap-2">
                <i data-lucide="line-chart" class="w-4 h-4"></i> {{ __('Growth & Feed') }}
            </button>
            <button @click="activeTab = 'genetics'" :class="{'border-emerald-500 text-emerald-600 dark:text-emerald-400': activeTab === 'genetics', 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300': activeTab !== 'genetics'}" class="px-4 py-4 border-b-2 font-semibold text-sm whitespace-nowrap transition-colors flex items-center gap-2">
                <i data-lucide="dna" class="w-4 h-4"></i> {{ __('Genetics & Breeding') }}
            </button>
            <button @click="activeTab = 'timeline'" :class="{'border-emerald-500 text-emerald-600 dark:text-emerald-400': activeTab === 'timeline', 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300': activeTab !== 'timeline'}" class="px-4 py-4 border-b-2 font-semibold text-sm whitespace-nowrap transition-colors flex items-center gap-2">
                <i data-lucide="history" class="w-4 h-4"></i> {{ __('Timeline') }}
            </button>
        </div>
    </div>

    <!-- Tab Contents -->
    
    <!-- OVERVIEW TAB -->
    <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Key Metrics -->
        <div class="glass-panel p-6 col-span-1 md:col-span-3 grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="flex flex-col">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Current Age') }}</span>
                <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $animal->age }}</div>
                <span class="text-sm text-slate-400">{{ __('Born') }} {{ $animal->birth_date ? $animal->birth_date->format('M d, Y') : __('Unknown') }}</span>
            </div>
            <div class="flex flex-col border-l border-slate-200 dark:border-slate-800 pl-6">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Current Weight') }}</span>
                <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $animal->weights->last()->weight_kg ?? '--' }} <span class="text-sm font-medium text-slate-500">{{ __('kg') }}</span></div>
                @if($animal->weights->count() > 1)
                    @php
                        $last = $animal->weights->last()->weight_kg;
                        $prev = $animal->weights->get($animal->weights->count() - 2)->weight_kg;
                        $diff = $last - $prev;
                    @endphp
                    <span class="text-sm font-medium {{ $diff >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                        <i data-lucide="{{ $diff >= 0 ? 'trending-up' : 'trending-down' }}" class="w-3 h-3 inline"></i> {{ abs($diff) }} {{ __('kg') }}
                    </span>
                @else
                    <span class="text-sm text-slate-400">{{ __('Since birth') }} ({{ $animal->birth_weight ?? '--' }} {{ __('kg') }})</span>
                @endif
            </div>
            <div class="flex flex-col border-l border-slate-200 dark:border-slate-800 pl-6">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Body Condition (BCS)') }}</span>
                <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $animal->body_condition_score ?? '--' }} <span class="text-sm font-medium text-slate-500">/ 5.0</span></div>
                <span class="text-sm text-emerald-500 font-medium">{{ __('Optimal') }}</span>
            </div>
            <div class="flex flex-col border-l border-slate-200 dark:border-slate-800 pl-6">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Market Value') }}</span>
                <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400">${{ number_format($animal->current_market_value ?? 0, 2) }}</div>
                <span class="text-sm text-slate-400">{{ __('AI Estimated') }}</span>
            </div>
        </div>

        <!-- Identity Details -->
        <div class="glass-panel p-6 col-span-1 md:col-span-2">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="fingerprint" class="w-5 h-5 text-emerald-500"></i> {{ __('Identity Details') }}
            </h3>
            <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <div class="text-sm text-slate-500">{{ __('Visual Tag') }}</div>
                    <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $animal->visual_tag ?? __('N/A') }}</div>
                </div>
                <div>
                    <div class="text-sm text-slate-500">{{ __('Name / Alias') }}</div>
                    <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $animal->name ?? __('N/A') }}</div>
                </div>
                <div>
                    <div class="text-sm text-slate-500">{{ __('Color Pattern') }}</div>
                    <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $animal->color ?? __('N/A') }}</div>
                </div>
                <div>
                    <div class="text-sm text-slate-500">{{ __('Horn Status') }}</div>
                    <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $animal->horn_status ?? __('N/A') }}</div>
                </div>
                <div>
                    <div class="text-sm text-slate-500">{{ __('Wool Type') }}</div>
                    <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $animal->wool_type ?? __('N/A') }}</div>
                </div>
                <div>
                    <div class="text-sm text-slate-500">{{ __('Origin / Source') }}</div>
                    <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $animal->origin ?? __('Farm Born') }}</div>
                </div>
            </div>
        </div>

        <!-- QR Code -->
        <div class="glass-panel p-6 flex flex-col items-center justify-center text-center">
            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">{{ __('Quick Scan') }}</h3>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
                <!-- Placeholder for real QR Code, could use simple-qrcode package -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('livestock.show', $animal->id)) }}" alt="QR Code" class="w-32 h-32">
            </div>
            <p class="text-xs text-slate-400 mt-4">{{ __('Scan to open profile') }}</p>
            <button class="btn-secondary text-xs mt-3 w-full">{{ __('Print Label') }}</button>
        </div>
    </div>

    <!-- GROWTH TAB -->
    <div x-show="activeTab === 'growth'" style="display: none;" class="glass-panel p-6 relative">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
            <i data-lucide="line-chart" class="w-5 h-5 text-emerald-500"></i> {{ __('Growth Curve & Predictions') }}
        </h3>
        <div class="h-80 w-full relative">
            <canvas id="growthChart"></canvas>
        </div>
    </div>

    <!-- TIMELINE TAB -->
    <div x-show="activeTab === 'timeline'" style="display: none;" class="glass-panel p-6">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
            <i data-lucide="history" class="w-5 h-5 text-emerald-500"></i> {{ __('Chronological History') }}
        </h3>
        <div class="relative border-l border-slate-200 dark:border-slate-800 ml-4 space-y-8 pb-4">
            
            <!-- Dynamic Timeline Items (Mocked for layout) -->
            <div class="relative pl-8">
                <div class="absolute -left-3.5 top-1 w-7 h-7 bg-emerald-100 dark:bg-emerald-900/50 border-2 border-emerald-500 rounded-full flex items-center justify-center">
                    <i data-lucide="baby" class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <div class="font-bold text-slate-900 dark:text-white text-sm">{{ $animal->birth_date ? $animal->birth_date->format('F d, Y') : __('Unknown') }}</div>
                <div class="text-slate-600 dark:text-slate-400 text-sm mt-1">{{ __('Born at farm. Weight recorded:') }} <span class="font-semibold text-slate-900 dark:text-slate-200">{{ $animal->birth_weight ?? '--' }} {{ __('kg') }}</span>.</div>
            </div>

            @foreach($animal->movements as $movement)
            <div class="relative pl-8">
                <div class="absolute -left-3.5 top-1 w-7 h-7 bg-blue-100 dark:bg-blue-900/50 border-2 border-blue-500 rounded-full flex items-center justify-center">
                    <i data-lucide="arrow-right-left" class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="font-bold text-slate-900 dark:text-white text-sm">{{ $movement->moved_at->format('F d, Y H:i') }}</div>
                <div class="text-slate-600 dark:text-slate-400 text-sm mt-1">{{ __('Transferred to') }} <span class="font-semibold">{{ $movement->toBarn->name ?? __('Unknown Barn') }}</span>.</div>
            </div>
            @endforeach

        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('growthChart');
        if(ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['{{ __('Month 1') }}', '{{ __('Month 2') }}', '{{ __('Month 3') }}', '{{ __('Month 4') }}', '{{ __('Month 5') }}', '{{ __('Month 6') }}', '{{ __('Month 7 (Current)') }}'],
                    datasets: [
                        {
                            label: '{{ __('Actual Weight (kg)') }}',
                            data: [4.5, 12, 18.5, 26, 33, 41, 48],
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#10B981',
                        },
                        {
                            label: '{{ __('AI Prediction (kg)') }}',
                            data: [null, null, null, null, null, null, 48, 55, 61, 68],
                            borderColor: '#6366F1',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            tension: 0.4,
                            pointRadius: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { color: '#94a3b8', font: { family: 'Inter' } } }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(148, 163, 184, 0.1)' },
                            ticks: { color: '#94a3b8' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#94a3b8' }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
