@extends('layouts.app')
@section('title', __('Marketplace & Solar Dashboard'))

@section('content')
    <div class="p-4 sm:p-8 space-y-6 max-w-7xl mx-auto">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                    <i data-lucide="shopping-cart" class="w-8 h-8 text-purple-500"></i>
                    {{ __('Marketplace & Trading') }}
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">
                    {{ __('B2B Livestock Trading, Feed Commodities, and Solar Energy Contracts.') }}</p>
            </div>
            <div class="flex gap-2">
                <button class="btn-primary flex items-center gap-2 shadow-purple-500/30">
                    <i data-lucide="plus" class="w-4 h-4"></i> {{ __('New Listing') }}
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="glass-panel p-5 relative overflow-hidden group border-t-4 border-purple-500">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="shopping-bag" class="w-24 h-24 text-purple-500"></i>
                </div>
                <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider relative z-10">
                    {{ __('Active Listings') }}</div>
                <div class="text-3xl font-black text-slate-900 dark:text-white relative z-10">24</div>
            </div>
            <div class="glass-panel p-5 relative overflow-hidden group border-t-4 border-emerald-500">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="sun" class="w-24 h-24 text-emerald-500"></i>
                </div>
                <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider relative z-10">
                    {{ __('Solar Energy Grid') }}</div>
                <div class="text-3xl font-black text-slate-900 dark:text-white relative z-10">1.2 MW</div>
                <div class="text-xs text-emerald-500 mt-2 font-bold flex items-center gap-1 relative z-10">
                    {{ __('Exporting to Grid') }}</div>
            </div>
            <div class="glass-panel p-5 relative overflow-hidden group border-t-4 border-blue-500">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="trending-up" class="w-24 h-24 text-blue-500"></i>
                </div>
                <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider relative z-10">
                    {{ __('Market Revenue') }}</div>
                <div class="text-3xl font-black text-slate-900 dark:text-white relative z-10">$15.2k</div>
            </div>
        </div>

        <!-- Empty State for now -->
        <div class="glass-panel p-12 text-center mt-6">
            <i data-lucide="layout" class="w-16 h-16 text-slate-300 dark:text-slate-700 mx-auto mb-4"></i>
            <h3 class="text-xl font-bold text-slate-700 dark:text-slate-300">{{ __('Marketplace Initialized') }}</h3>
            <p class="text-slate-500 mt-2">{{ __('The Marketplace module is online and ready for active listings.') }}</p>
        </div>

    </div>
@endsection