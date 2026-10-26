@extends('layouts.app')

@section('title', 'Edit Water Source')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('water.show', $source->id) }}" class="btn-ghost btn-icon">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Edit Water Source</h1>
            <p class="text-sm text-slate-500">Update details for {{ $source->name }}.</p>
        </div>
    </div>

    <div class="glass-panel p-6 sm:p-8">
        <form action="{{ route('water.update', $source->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="label-field">Source Name</label>
                <input type="text" name="name" value="{{ $source->name }}" required class="input-field">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="label-field">Source Type</label>
                    <select name="type" required class="select-field">
                        <option value="Well" {{ $source->type === 'Well' ? 'selected' : '' }}>Well</option>
                        <option value="Tank" {{ $source->type === 'Tank' ? 'selected' : '' }}>Tank / Reservoir</option>
                        <option value="Municipal" {{ $source->type === 'Municipal' ? 'selected' : '' }}>Municipal Connection</option>
                        <option value="Rainwater" {{ $source->type === 'Rainwater' ? 'selected' : '' }}>Rainwater Harvesting</option>
                        <option value="River" {{ $source->type === 'River' ? 'selected' : '' }}>River / Stream</option>
                    </select>
                </div>
                
                <div>
                    <label class="label-field">Capacity (m³)</label>
                    <input type="number" step="0.01" name="capacity" value="{{ $source->capacity }}" class="input-field">
                </div>
            </div>

            <div>
                <label class="label-field">Status</label>
                <select name="status" required class="select-field">
                    <option value="Active" {{ $source->status === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Maintenance" {{ $source->status === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="Depleted" {{ $source->status === 'Depleted' ? 'selected' : '' }}>Depleted / Dry</option>
                    <option value="Offline" {{ $source->status === 'Offline' ? 'selected' : '' }}>Offline</option>
                </select>
            </div>

            <!-- Technical Specifications & Location -->
            <div class="border-t border-slate-200 dark:border-slate-800 pt-6">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Technical & Location Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="label-field">Assigned Zone</label>
                        <select name="zone_id" class="select-field">
                            <option value="">-- No Zone --</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}" {{ $source->zone_id === $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label-field">GPS Coordinates</label>
                        <input type="text" name="coordinates" value="{{ $source->coordinates }}" class="input-field" placeholder="e.g. 24.7136, 46.6753">
                    </div>
                    <div>
                        <label class="label-field">Pump Flow Rate (m³/h)</label>
                        <input type="number" step="0.01" name="flow_rate" value="{{ $source->flow_rate }}" class="input-field" placeholder="Optional">
                    </div>
                    <div>
                        <label class="label-field">Depth (meters)</label>
                        <input type="number" step="0.01" name="depth" value="{{ $source->depth }}" class="input-field" placeholder="Optional (for wells)">
                    </div>
                    <div>
                        <label class="label-field">Installation Date</label>
                        <input type="date" name="installation_date" value="{{ $source->installation_date ? $source->installation_date->format('Y-m-d') : '' }}" class="input-field">
                    </div>
                    <div>
                        <label class="label-field">Next Maintenance Date</label>
                        <input type="date" name="next_maintenance_date" value="{{ $source->next_maintenance_date ? $source->next_maintenance_date->format('Y-m-d') : '' }}" class="input-field">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3">
                <a href="{{ route('water.show', $source->id) }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Update Source</button>
            </div>
        </form>
    </div>
</div>
@endsection
