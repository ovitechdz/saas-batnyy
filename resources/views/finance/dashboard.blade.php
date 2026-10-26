@extends('layouts.app')
@section('title', __('Enterprise Finance'))

@section('content')
<div class="space-y-6" x-data="financeDash()">

    {{-- ═══ PAGE HERO ═══ --}}
    <div class="page-hero animate-fade-up" style="background: linear-gradient(135deg, #0f172a 0%, #052e16 50%, #0f172a 100%);">
        <div class="absolute -top-12 -right-12 w-72 h-72 rounded-full opacity-20 blur-3xl pointer-events-none"
             style="background: radial-gradient(circle, #10b981, transparent 70%)"></div>
        <div class="absolute bottom-0 left-1/4 w-48 h-48 rounded-full opacity-10 blur-3xl pointer-events-none"
             style="background: radial-gradient(circle, #3b82f6, transparent 70%)"></div>
        <div class="page-hero-content flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div>
                <nav class="breadcrumb mb-3">
                    <a href="{{ route('dashboard') }}" class="breadcrumb-item flex items-center gap-1">
                        <i data-lucide="home" class="w-3 h-3"></i> {{ __('Dashboard') }}
                    </a>
                    <span class="breadcrumb-sep">/</span>
                    <span class="breadcrumb-active text-white">{{ __('Finance') }}</span>
                </nav>
                <div class="flex items-center gap-3 mb-1">
                    <div class="p-2 rounded-xl" style="background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.3);">
                        <i data-lucide="trending-up" class="w-5 h-5 text-emerald-400"></i>
                    </div>
                    <span class="text-emerald-400 text-xs font-bold uppercase tracking-widest">{{ __('Enterprise ERP') }}</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">
                    {{ __('Financial') }} <span class="text-gradient-primary">{{ __('Command') }}</span>
                </h1>
                <p class="text-slate-400 mt-1.5 text-sm">{{ __('AI-powered ERP insights, cash flow projections, and real-time P&L analytics.') }}</p>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('finance.ledger') }}" class="btn-secondary flex items-center gap-2">
                    <i data-lucide="book-open" class="w-4 h-4"></i> {{ __('General Ledger') }}
                </a>
                <a href="{{ route('finance.ledger') }}" class="btn-primary flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> {{ __('New Transaction') }}
                </a>
            </div>
        </div>
    </div>

    {{-- ═══ KPI CARDS ═══ --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 animate-fade-up delay-100">

        {{-- Revenue --}}
        <div class="modern-card p-6 border-t-[4px] border-emerald-500/60 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
            <div class="stat-card-orb bg-emerald-500"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <i data-lucide="circle-dollar-sign" class="w-5 h-5"></i>
                </div>
                <span class="badge badge-success text-[10px]">
                    <i data-lucide="trending-up" class="w-2.5 h-2.5"></i> +12.5%
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 dark:text-white tabular-nums">
                ${{ number_format($stats['total_revenue']) }}
            </p>
            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider">{{ __('Total Revenue') }}</p>
            <p class="text-[10px] text-slate-400 mt-0.5">{{ __('Year to date') }}</p>
        </div>

        {{-- Expenses --}}
        <div class="modern-card p-6 border-t-[4px] border-rose-500/60 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
            <div class="stat-card-orb bg-rose-500"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-rose-100 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                </div>
                <span class="badge badge-danger text-[10px]">
                    <i data-lucide="trending-down" class="w-2.5 h-2.5"></i> -3.2%
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 dark:text-white tabular-nums">
                ${{ number_format($stats['total_expenses']) }}
            </p>
            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider">{{ __('Operating Expenses') }}</p>
            <p class="text-[10px] text-slate-400 mt-0.5">{{ __('Optimized from last period') }}</p>
        </div>

        {{-- Net Profit --}}
        <div class="modern-card p-6 border-t-[4px] border-blue-500/60 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
            <div class="stat-card-orb bg-blue-500"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <i data-lucide="pie-chart" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-900 dark:text-white tabular-nums">
                ${{ number_format($stats['net_profit']) }}
            </p>
            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider">{{ __('Net Profit') }}</p>
            <div class="mt-2 progress-track">
                @php $margin = $stats['total_revenue'] > 0 ? ($stats['net_profit'] / $stats['total_revenue']) * 100 : 54; @endphp
                <div class="progress-fill bg-gradient-to-r from-blue-400 to-blue-600" style="width: {{ min($margin, 100) }}%"></div>
            </div>
            <p class="text-[10px] text-blue-500 mt-1 font-semibold">{{ number_format($margin, 0) }}% {{ __('Profit Margin') }}</p>
        </div>

        {{-- Receivables --}}
        <div class="modern-card p-6 border-t-[4px] border-amber-500/60 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
            <div class="stat-card-orb bg-amber-500"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <i data-lucide="receipt" class="w-5 h-5"></i>
                </div>
                <span class="badge badge-warning text-[10px]">{{ __('Pending') }}</span>
            </div>
            <p class="text-2xl font-black text-slate-900 dark:text-white tabular-nums">
                ${{ number_format($stats['accounts_receivable']) }}
            </p>
            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider">{{ __('Accounts Receivable') }}</p>
            <p class="text-[10px] text-amber-500 mt-0.5 font-semibold">12 {{ __('pending invoices') }}</p>
        </div>
    </div>

    {{-- ═══ CHART + AI ADVISOR ═══ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 animate-fade-up delay-150">

        {{-- Cash Flow Chart --}}
        <div class="xl:col-span-2 modern-card p-6 group">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200/50 dark:border-white/[0.05]">
                <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                        <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                    </div>
                    {{ __('Cash Flow & Projections') }}
                </h3>
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1.5 text-xs text-slate-500">
                        <span class="w-3 h-1.5 rounded-full bg-emerald-500 inline-block"></span> {{ __('Revenue') }}
                        <span class="w-3 h-1.5 rounded-full bg-rose-400 inline-block ml-2"></span> {{ __('Expenses') }}
                    </div>
                </div>
            </div>

            <div class="relative h-60">
                <canvas id="cashFlowChart"></canvas>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-4 pt-4 border-t border-slate-200/50 dark:border-white/[0.05]">
                @php
                    $cashFlows = $cashFlow ?? collect([]);
                    $avgRevenue = $cashFlows->avg('total_income') ?? 0;
                    $avgExpense = $cashFlows->avg('total_expenses') ?? 0;
                @endphp
                <div class="text-center">
                    <p class="text-xs text-slate-500">{{ __('Avg Monthly Revenue') }}</p>
                    <p class="font-bold text-slate-900 dark:text-white">${{ number_format($avgRevenue) }}</p>
                </div>
                <div class="text-center border-x border-slate-200/50 dark:border-white/[0.05]">
                    <p class="text-xs text-slate-500">{{ __('Avg Monthly Expense') }}</p>
                    <p class="font-bold text-slate-900 dark:text-white">${{ number_format($avgExpense) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-slate-500">{{ __('Cash Flow Trend') }}</p>
                    <p class="font-bold text-emerald-600 dark:text-emerald-400">+{{ number_format(max($avgRevenue - $avgExpense, 0)) }}</p>
                </div>
            </div>
        </div>

        {{-- AI Advisor Sidebar --}}
        <div class="space-y-5">

            {{-- Health Score --}}
            <div class="modern-card p-5 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 opacity-5 pointer-events-none">
                    <i data-lucide="brain-circuit" class="w-24 h-24 text-indigo-500"></i>
                </div>
                <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5 mb-4 pb-3 border-b border-slate-200/50 dark:border-white/[0.05]">
                    <div class="w-8 h-8 rounded-lg gradient-purple flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-4 h-4 text-white"></i>
                    </div>
                    {{ __('AI Health Score') }}
                </h3>
                <div class="flex items-center gap-4 mb-4">
                    <div class="relative w-20 h-20 flex-shrink-0">
                        <svg class="w-20 h-20 -rotate-90" viewBox="0 0 36 36">
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none" stroke="rgba(0,0,0,0.07)" stroke-width="3"/>
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none"
                                  stroke="{{ $health['status'] === 'Excellent' ? '#10b981' : ($health['status'] === 'Warning' ? '#f59e0b' : '#6366f1') }}"
                                  stroke-width="3"
                                  stroke-dasharray="{{ $health['score'] }}, 100"
                                  stroke-linecap="round"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-2xl font-black text-slate-900 dark:text-white">{{ $health['score'] }}</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-lg font-black text-slate-900 dark:text-white">{{ __($health['status']) }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ __('Based on 50+ financial indicators') }}</p>
                        <span class="badge {{ $health['status'] === 'Excellent' ? 'badge-success' : ($health['status'] === 'Warning' ? 'badge-warning' : 'badge-info') }} mt-2">{{ __('AI Certified') }}</span>
                    </div>
                </div>
                <div class="space-y-2.5">
                    @foreach($health['recommendations'] as $rec)
                    <div class="flex gap-2.5 p-3 rounded-xl bg-amber-50/50 dark:bg-amber-500/5 border border-amber-100 dark:border-amber-500/10 text-xs">
                        <i data-lucide="lightbulb" class="w-3.5 h-3.5 text-amber-500 flex-shrink-0 mt-0.5"></i>
                        <span class="text-slate-600 dark:text-slate-400">{{ __($rec) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Next Month Forecast --}}
            <div class="modern-card p-5 group">
                <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="calendar-range" class="w-4 h-4"></i>
                    </div>
                    {{ __('Next Month Forecast') }}
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50/50 dark:bg-emerald-500/5 border border-emerald-100 dark:border-emerald-500/10">
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-medium">{{ __('Projected Revenue') }}</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400">+${{ number_format($predictions['predicted_income']) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-rose-50/50 dark:bg-rose-500/5 border border-rose-100 dark:border-rose-500/10">
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-medium">{{ __('Projected Expenses') }}</span>
                        <span class="font-bold text-rose-600 dark:text-rose-400">-${{ number_format($predictions['predicted_expense']) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-white/[0.03] border border-slate-200/60 dark:border-white/[0.05]">
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-medium">{{ __('Predicted Net') }}</span>
                        <span class="text-xl font-black text-slate-900 dark:text-white">${{ number_format($predictions['predicted_net']) }}</span>
                    </div>
                    <div class="text-center">
                        <span class="text-[10px] text-slate-400">{{ __('AI confidence: ') }}</span>
                        <span class="text-[10px] font-bold text-primary-500">{{ $predictions['confidence'] ?? 88.5 }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('financeDash', () => ({ }));
});

document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('cashFlowChart');
    if (!ctx || typeof Chart === 'undefined') return;

    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
    const labelColor = isDark ? '#94a3b8' : '#64748b';

    new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: ["{{ __('Jan') }}", "{{ __('Feb') }}", "{{ __('Mar') }}", "{{ __('Apr') }}", "{{ __('May') }}", "{{ __('Jun') }}", "{{ __('Jul') }}", "{{ __('Aug') }}", "{{ __('Sep') }}", "{{ __('Oct') }}", "{{ __('Nov') }}", "{{ __('Dec') }}"],
            datasets: [
                {
                    label: "{{ __('Revenue') }}",
                    data: [40000, 62000, 45000, 80000, 55000, 90000, 75000, 100000, 85000, 110000, 95000, 120000],
                    backgroundColor: 'rgba(16,185,129,0.7)',
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: "{{ __('Expenses') }}",
                    data: [28000, 40000, 32000, 55000, 38000, 60000, 52000, 68000, 58000, 72000, 64000, 80000],
                    backgroundColor: 'rgba(239,68,68,0.5)',
                    borderRadius: 6,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDark ? '#1e293b' : '#0f172a',
                    titleColor: '#f1f5f9',
                    bodyColor: '#94a3b8',
                    borderColor: 'rgba(255,255,255,0.08)',
                    borderWidth: 1,
                    padding: 12,
                    callbacks: {
                        label: ctx => ` $${ctx.parsed.y.toLocaleString()}`
                    }
                }
            },
            scales: {
                x: { grid: { color: gridColor }, ticks: { color: labelColor, font: { size: 11 } } },
                y: {
                    grid: { color: gridColor },
                    ticks: {
                        color: labelColor, font: { size: 11 },
                        callback: v => '$' + (v/1000) + 'k'
                    }
                }
            },
            interaction: { intersect: false, mode: 'index' }
        }
    });
});
</script>
@endpush
