@extends('layouts.app')

@section('title', 'Add Water Source')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('water.index') }}" class="btn-ghost btn-icon">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ __('Add Water Source') }}</h1>
            <p class="text-sm text-slate-500">{{ __('Register a new well, tank, or connection.') }}</p>
        </div>
    </div>

    <div class="glass-panel p-6 sm:p-8">
        <form action="{{ route('water.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="label-field">{{ __('Source Name') }}</label>
                <input type="text" name="name" required class="input-field" placeholder="{{ __('e.g., Main Well, North Tank') }}">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="label-field">{{ __('Source Type') }}</label>
                    <select name="type" required class="select-field">
                        <option value="Well">{{ __('Well') }}</option>
                        <option value="Tank">{{ __('Tank / Reservoir') }}</option>
                        <option value="Municipal">{{ __('Municipal Connection') }}</option>
                        <option value="Rainwater">{{ __('Rainwater Harvesting') }}</option>
                        <option value="River">{{ __('River / Stream') }}</option>
                    </select>
                </div>
                
                <div>
                    <label class="label-field">{{ __('Capacity (m³)') }}</label>
                    <input type="number" step="0.01" name="capacity" class="input-field" placeholder="{{ __('Optional max capacity') }}">
                </div>
            </div>

            <div>
                <label class="label-field">{{ __('Status') }}</label>
                <select name="status" required class="select-field">
                    <option value="Active">{{ __('Active') }}</option>
                    <option value="Maintenance">{{ __('Maintenance') }}</option>
                    <option value="Depleted">{{ __('Depleted / Dry') }}</option>
                    <option value="Offline">{{ __('Offline') }}</option>
                </select>
            </div>

            <!-- Technical Specifications & Location -->
            <div class="border-t border-slate-200 dark:border-slate-800 pt-6">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">{{ __('Technical & Location Details') }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="label-field">{{ __('Assigned Zone') }}</label>
                        <select name="zone_id" class="select-field">
                            <option value="">{{ __('-- No Zone --') }}</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label-field">{{ __('GPS Coordinates') }}</label>
                        <input type="text" name="coordinates" class="input-field" placeholder="{{ __('e.g. 24.7136, 46.6753') }}">
                    </div>
                    <div>
                        <label class="label-field">{{ __('Pump Flow Rate (m³/h)') }}</label>
                        <input type="number" step="0.01" name="flow_rate" class="input-field" placeholder="{{ __('Optional') }}">
                    </div>
                    <div>
                        <label class="label-field">{{ __('Depth (meters)') }}</label>
                        <input type="number" step="0.01" name="depth" class="input-field" placeholder="{{ __('Optional (for wells)') }}">
                    </div>
                    <div>
                        <label class="label-field">{{ __('Installation Date') }}</label>
                        <input type="date" name="installation_date" class="input-field">
                    </div>
                    <div>
                        <label class="label-field">{{ __('Next Maintenance Date') }}</label>
                        <input type="date" name="next_maintenance_date" class="input-field">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3">
                <a href="{{ route('water.index') }}" class="btn-secondary">{{ __('Cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('Save Source') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
