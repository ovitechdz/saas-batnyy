@extends('layouts.app')

@section('title', 'Water Source Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('water.index') }}" class="btn-ghost btn-icon">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $source->name }}</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">{{ $source->type }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('water.edit', $source->id) }}" class="btn-secondary">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Edit
            </a>
            <form action="{{ route('water.destroy', $source->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this water source?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Stats & Information -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-panel p-6">
            <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4">Status</h3>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                    <i data-lucide="activity" class="w-5 h-5 text-slate-500"></i>
                </div>
                <div>
                    <div class="font-bold text-slate-900 dark:text-white">{{ $source->status }}</div>
                    <div class="text-xs text-slate-500 mt-0.5">Current operational state</div>
                </div>
            </div>
        </div>
        
        <div class="glass-panel p-6">
            <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4">Max Capacity</h3>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i data-lucide="database" class="w-5 h-5 text-blue-500"></i>
                </div>
                <div>
                    <div class="font-bold text-slate-900 dark:text-white">{{ $source->capacity ? number_format($source->capacity, 2) . ' m³' : 'Unlimited / N/A' }}</div>
                    <div class="text-xs text-slate-500 mt-0.5">Total volume limit</div>
                </div>
            </div>
        </div>

        <div class="glass-panel p-6">
            <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4">Total Logged Consumption</h3>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <i data-lucide="droplet" class="w-5 h-5 text-emerald-500"></i>
                </div>
                <div>
                    <div class="font-bold text-slate-900 dark:text-white">{{ number_format($source->readings->sum('consumption'), 2) }} m³</div>
                    <div class="text-xs text-slate-500 mt-0.5">Across all readings</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Technical Details -->
    <div class="glass-panel p-6 mt-6">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white border-b border-slate-200/50 dark:border-slate-800/50 pb-4 mb-4">Technical & Location Details</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Assigned Zone</p>
                <p class="font-medium text-slate-900 dark:text-white mt-1">
                    @if($source->zone)
                        <a href="{{ route('farm.zones.index') }}" class="text-blue-500 hover:underline">{{ $source->zone->name }}</a>
                    @else
                        <span class="text-slate-500">Unassigned</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">GPS Coordinates</p>
                <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $source->coordinates ?: 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pump Flow Rate</p>
                <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $source->flow_rate ? $source->flow_rate . ' m³/h' : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Depth</p>
                <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $source->depth ? $source->depth . ' meters' : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Installation Date</p>
                <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $source->installation_date ? $source->installation_date->format('M d, Y') : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Next Maintenance</p>
                <p class="font-medium {{ $source->next_maintenance_date && $source->next_maintenance_date < now() ? 'text-red-500' : 'text-slate-900 dark:text-white' }} mt-1">
                    {{ $source->next_maintenance_date ? $source->next_maintenance_date->format('M d, Y') : 'N/A' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Readings Log -->
    <div class="glass-panel overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-slate-200/50 dark:border-slate-800/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50 dark:bg-slate-800/20">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Consumption & Quality Logs</h3>
            <button onclick="document.getElementById('add-reading-form').classList.toggle('hidden')" class="btn-primary btn-sm">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Reading
            </button>
        </div>
        
        <!-- Add Reading Form (Hidden by default) -->
        <div id="add-reading-form" class="hidden p-6 bg-slate-50 dark:bg-slate-900/40 border-b border-slate-200/50 dark:border-slate-800/50">
            <form action="{{ route('water.readings.store', $source->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                @csrf
                <div>
                    <label class="label-field">Date</label>
                    <input type="datetime-local" name="reading_date" required class="input-field" value="{{ now()->format('Y-m-d\TH:i') }}">
                </div>
                <div>
                    <label class="label-field">Consumption (m³)</label>
                    <input type="number" step="0.01" name="consumption" required class="input-field" placeholder="Volume used">
                </div>
                <div>
                    <label class="label-field">pH Level (0-14)</label>
                    <input type="number" step="0.1" name="ph_level" class="input-field" placeholder="Optional">
                </div>
                <div>
                    <label class="label-field">Salinity (ppm)</label>
                    <input type="number" step="1" name="salinity" class="input-field" placeholder="Optional">
                </div>
                <div class="lg:col-span-3">
                    <label class="label-field">Notes</label>
                    <input type="text" name="notes" class="input-field" placeholder="Any observations...">
                </div>
                <div>
                    <button type="submit" class="btn-primary w-full h-[42px]">Save Log</button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto custom-scroll">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Consumption</th>
                        <th>pH Level</th>
                        <th>Salinity</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($source->readings as $reading)
                    <tr>
                        <td>
                            <div class="font-bold text-slate-900 dark:text-white">{{ $reading->reading_date->format('M d, Y') }}</div>
                            <div class="text-xs text-slate-500">{{ $reading->reading_date->format('H:i') }}</div>
                        </td>
                        <td>
                            <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ number_format($reading->consumption, 2) }} m³</span>
                        </td>
                        <td>
                            {{ $reading->ph_level ?? '-' }}
                        </td>
                        <td>
                            {{ $reading->salinity ? $reading->salinity . ' ppm' : '-' }}
                        </td>
                        <td class="text-sm text-slate-500">
                            {{ $reading->notes ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500">
                            No readings logged yet. Click "Add Reading" to start tracking.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
