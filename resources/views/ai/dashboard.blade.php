@extends('layouts.app')
@section('title', __('AI Command Hub'))

@section('content')
<div x-data="aiHub()" class="p-4 sm:p-8 space-y-6 max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                <i data-lucide="bot" class="w-8 h-8 text-blue-500"></i>
                {{ __('AI Command Hub') }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">{{ __('Central intelligence for disease prediction, feed optimization, and financial forecasting.') }}</p>
        </div>
        <div class="flex gap-2">
            <button @click="retrainModels()" :disabled="isRetraining" 
                    class="btn-primary flex items-center gap-2 disabled:opacity-75 disabled:cursor-wait transition-all duration-300">
                
                <div x-show="!isRetraining && !success" class="flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> {{ __('Retrain Models') }}
                </div>
                
                <div x-show="isRetraining" style="display: none;" class="flex items-center gap-2">
                    <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> {{ __('Retraining...') }}
                </div>
            
                <div x-show="success" style="display: none;" class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> {{ __('Models Updated') }}
                </div>
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="glass-panel p-5 relative overflow-hidden group border-t-4 border-rose-500">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <i data-lucide="activity" class="w-24 h-24 text-rose-500"></i>
            </div>
            <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider relative z-10">{{ __('Health Predictions') }}</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white relative z-10">94.2%</div>
            <div class="text-xs text-rose-500 mt-2 font-bold flex items-center gap-1 relative z-10">{{ __('Accuracy Rate') }}</div>
        </div>
        <div class="glass-panel p-5 relative overflow-hidden group border-t-4 border-amber-500">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <i data-lucide="wheat" class="w-24 h-24 text-amber-500"></i>
            </div>
            <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider relative z-10">{{ __('Feed Optimization') }}</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white relative z-10">12%</div>
            <div class="text-xs text-amber-500 mt-2 font-bold flex items-center gap-1 relative z-10">{{ __('Cost Reduction') }}</div>
        </div>
        <div class="glass-panel p-5 relative overflow-hidden group border-t-4 border-emerald-500">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <i data-lucide="pie-chart" class="w-24 h-24 text-emerald-500"></i>
            </div>
            <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider relative z-10">{{ __('Financial Forecasts') }}</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white relative z-10">{{ __('Active') }}</div>
        </div>
    </div>

    <div class="glass-panel p-12 text-center mt-6">
        <i data-lucide="cpu" class="w-16 h-16 text-slate-300 dark:text-slate-700 mx-auto mb-4"></i>
        <h3 class="text-xl font-bold text-slate-700 dark:text-slate-300">{{ __('AI Hub Initialized') }}</h3>
        <p class="text-slate-500 mt-2">{{ __('The AI models are running in the background processing farm telemetry data.') }}</p>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('aiHub', () => ({
            isRetraining: false,
            success: false,
            
            retrainModels() {
                if(this.isRetraining || this.success) return;
                
                this.isRetraining = true;
                
                // Simulate processing time
                setTimeout(() => {
                    this.isRetraining = false;
                    this.success = true;
                    
                    // Re-render lucide icons for the new check-circle icon
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                    
                    // Reset back to normal after 3 seconds
                    setTimeout(() => {
                        this.success = false;
                    }, 3000);
                    
                }, 2500); // 2.5 seconds fake loading
            }
        }));
    });
</script>
@endpush
@endsection
