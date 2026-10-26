@extends('layouts.app')

@section('title', 'Water Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="glass-header px-6 py-6 z-20 relative flex flex-col md:flex-row items-start md:items-center justify-between gap-4 md:gap-0 rounded-3xl">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                <i data-lucide="droplets" class="w-8 h-8 text-blue-500 dark:text-blue-400 drop-shadow-[0_0_10px_rgba(59,130,246,0.5)]"></i>
                {{ __('Water Resources') }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm font-medium">{{ __('Manage wells, tanks, and track consumption.') }}</p>
        </div>
        <div class="flex items-center gap-4 w-full md:w-auto">
            <a href="{{ route('water.create') }}" class="btn-primary ml-auto md:ml-0 flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> {{ __('Add Source') }}
            </a>
        </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="modern-card p-6 border-t-[4px] border-blue-500 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="stat-card-icon bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400">
                    <i data-lucide="droplets" class="w-6 h-6"></i>
                </div>
                <span class="badge badge-info">{{ __('Active') }}</span>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Total Sources') }}</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $sources->count() }}</h3>
            </div>
        </div>

        <div class="modern-card p-6 border-t-[4px] border-emerald-500 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="stat-card-icon bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400">
                    <i data-lucide="database" class="w-6 h-6"></i>
                </div>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Total Capacity') }}</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($totalCapacity, 2) }} <span class="text-sm font-normal text-slate-500">m³</span></h3>
            </div>
        </div>
    </div>

    <!-- Sources Table -->
    <div class="modern-card overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i data-lucide="list" class="w-5 h-5 text-emerald-500"></i> {{ __('Water Sources') }}
            </h3>
        </div>
        
        <div class="overflow-x-auto custom-scroll">
            <table class="w-full text-sm whitespace-nowrap {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                <thead class="bg-slate-50/50 dark:bg-black/20 border-b border-slate-200 dark:border-white/5">
                    <tr>
                        <th class="px-6 py-4 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-xs {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Source Name') }}</th>
                        <th class="px-6 py-4 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-xs {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Type') }}</th>
                        <th class="px-6 py-4 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-xs {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Capacity (m³)') }}</th>
                        <th class="px-6 py-4 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-xs {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">{{ __('Status') }}</th>
                        <th class="px-6 py-4 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-xs {{ app()->getLocale() == 'ar' ? 'text-left' : 'text-right' }}">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                    @forelse($sources as $source)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 dark:text-white">{{ $source->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge badge-neutral">{{ $source->type }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-700 dark:text-slate-300 font-medium">
                            {{ $source->capacity ? number_format($source->capacity, 2) : 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($source->status === 'Active')
                                <span class="badge badge-success">{{ $source->status }}</span>
                            @elseif($source->status === 'Maintenance')
                                <span class="badge badge-warning">{{ $source->status }}</span>
                            @else
                                <span class="badge badge-danger">{{ $source->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 {{ app()->getLocale() == 'ar' ? 'text-left' : 'text-right' }}">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('water.show', $source->id) }}" class="btn-icon bg-slate-100 hover:bg-sky-100 dark:bg-white/5 dark:hover:bg-sky-500/20 text-slate-500 hover:text-sky-600 dark:text-slate-400 dark:hover:text-sky-400 transition-colors" title="View">
                                    <i data-lucide="eye" class="w-4 h-4 mx-auto"></i>
                                </a>
                                <a href="{{ route('water.edit', $source->id) }}" class="btn-icon bg-slate-100 hover:bg-amber-100 dark:bg-white/5 dark:hover:bg-amber-500/20 text-slate-500 hover:text-amber-600 dark:text-slate-400 dark:hover:text-amber-400 transition-colors" title="Edit">
                                    <i data-lucide="edit-2" class="w-4 h-4 mx-auto"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 dark:bg-white/5 mb-4 text-slate-400">
                                <i data-lucide="droplets" class="w-10 h-10"></i>
                            </div>
                            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2">{{ __('No Water Sources') }}</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm max-w-sm mx-auto mb-4">{{ __('Add a well, tank, or reservoir to start tracking water.') }}</p>
                            <a href="{{ route('water.create') }}" class="btn-primary inline-flex items-center gap-2 mx-auto">
                                <i data-lucide="plus" class="w-4 h-4"></i> {{ __('Add Source') }}
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
