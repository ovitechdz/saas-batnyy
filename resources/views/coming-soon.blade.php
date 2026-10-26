@extends('layouts.app')

@section('title', 'Coming Soon')

@section('content')
<div class="h-full flex items-center justify-center p-4 sm:p-6 overflow-hidden relative">
    
    <!-- Background Animations -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-emerald-500/10 dark:bg-emerald-500/20 rounded-full blur-[100px] animate-pulse-ring"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-500/10 dark:bg-blue-500/20 rounded-full blur-[100px] animate-pulse-ring delay-500"></div>
    </div>

    <!-- Main Card -->
    <div class="glass-panel p-8 sm:p-12 max-w-2xl w-full text-center relative z-10 overflow-hidden group animate-scale-in">
        
        <!-- Hover Glow Effect -->
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
        
        <!-- Animated Icon -->
        <div class="w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-lg border border-slate-200/50 dark:border-slate-700/50 relative z-10 animate-float">
            <i data-lucide="rocket" class="w-12 h-12 text-emerald-500 glow-emerald"></i>
            <div class="absolute inset-0 rounded-3xl border-2 border-emerald-500/20 animate-ping opacity-50"></div>
        </div>
        
        <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight mb-4 relative z-10">
            Innovation in <span class="text-gradient-primary">Progress</span>
        </h2>
        
        <p class="text-slate-600 dark:text-slate-400 mb-8 relative z-10 text-sm sm:text-base max-w-lg mx-auto leading-relaxed">
            We are building the next generation of Ovitech Smart Farm capabilities. This enterprise module is currently under heavy development to bring you unprecedented control and AI-driven insights.
        </p>

        <!-- Development Progress -->
        <div class="mb-10 text-left max-w-md mx-auto">
            <div class="flex justify-between items-end mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Development Phase</span>
                <span class="text-sm font-black text-emerald-600 dark:text-emerald-400">85%</span>
            </div>
            <div class="progress-track h-3 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="progress-fill h-full bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full relative" style="width: 85%;">
                    <div class="absolute inset-0 bg-white/20 animate-[shimmer_2s_infinite]"></div>
                </div>
            </div>
            <p class="text-xs text-slate-400 mt-2 text-right">Expected Release: Q4 2026</p>
        </div>
        
        <!-- Notification Form -->
        <div class="relative z-10 max-w-md mx-auto mb-10">
            <form action="#" method="GET" class="flex flex-col sm:flex-row gap-3" onsubmit="event.preventDefault(); alert('You will be notified once this module is ready!');">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="bell" class="w-4 h-4 text-slate-400"></i>
                    </div>
                    <input type="email" required placeholder="Enter your email for early access" class="input-premium pl-10 w-full" />
                </div>
                <button type="submit" class="premium-btn shrink-0">
                    Notify Me
                </button>
            </form>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-3">
                <i data-lucide="shield-check" class="w-3 h-3 inline mr-1 text-emerald-500"></i> No spam. Just exciting updates.
            </p>
        </div>
        
        <!-- Footer Actions -->
        <div class="flex justify-center border-t border-slate-200/50 dark:border-slate-800/50 pt-8 relative z-10">
            <a href="{{ route('dashboard') }}" class="btn-secondary">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Return to Dashboard
            </a>
        </div>
        
    </div>
</div>
@endsection
