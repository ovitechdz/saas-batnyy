@extends('layouts.app')
@section('title', __('Administration Dashboard'))

@section('content')
<div class="p-4 sm:p-8 space-y-6 max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                <i data-lucide="shield" class="w-8 h-8 text-red-500"></i>
                {{ __('Administration & Security') }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">{{ __('Multi-tenant management, Role-Based Access Control, and System Logs.') }}</p>
        </div>
        <div class="flex gap-2">
            <button onclick="alert('{{ __('This feature is under construction and will be available in the next update.') }}')" class="btn-primary flex items-center gap-2 bg-red-600 hover:bg-red-700 shadow-red-500/30 border-red-500 text-white">
                <i data-lucide="shield-alert" class="w-4 h-4"></i> {{ __('Security Scan') }}
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="glass-panel p-5 relative overflow-hidden group border-t-4 border-slate-500">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <i data-lucide="users" class="w-24 h-24 text-slate-500"></i>
            </div>
            <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider relative z-10">{{ __('Total Users') }}</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white relative z-10">45</div>
        </div>
        <div class="glass-panel p-5 relative overflow-hidden group border-t-4 border-red-500">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <i data-lucide="alert-triangle" class="w-24 h-24 text-red-500"></i>
            </div>
            <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider relative z-10">{{ __('Security Alerts') }}</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white relative z-10">0</div>
            <div class="text-xs text-red-500 mt-2 font-bold flex items-center gap-1 relative z-10">{{ __('System Secure') }}</div>
        </div>
        <div class="glass-panel p-5 relative overflow-hidden group border-t-4 border-blue-500">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <i data-lucide="server" class="w-24 h-24 text-blue-500"></i>
            </div>
            <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider relative z-10">{{ __('Server Uptime') }}</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white relative z-10">99.99%</div>
        </div>
    </div>

    <div class="glass-panel p-12 text-center mt-6">
        <i data-lucide="shield-check" class="w-16 h-16 text-slate-300 dark:text-slate-700 mx-auto mb-4"></i>
        <h3 class="text-xl font-bold text-slate-700 dark:text-slate-300">{{ __('Admin Hub Initialized') }}</h3>
        <p class="text-slate-500 mt-2">{{ __('The Administration module is online and ready for security configurations.') }}</p>
    </div>

</div>
@endsection
