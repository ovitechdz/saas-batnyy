@extends('layouts.app')
@section('title', __('General Ledger'))

@section('content')
<div class="p-4 sm:p-8 space-y-6 max-w-7xl mx-auto" x-data="ledgerManager()">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                <i data-lucide="book-open" class="w-8 h-8 text-blue-500"></i>
                {{ __('General Ledger') }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">{{ __('Double-entry accounting, journal entries, and trial balance.') }}</p>
        </div>
        <div class="flex gap-2">
            <button @click="showModal = true" class="btn-primary flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> {{ __('Post Journal Entry') }}
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex space-x-1 bg-slate-100 dark:bg-slate-800/50 p-1 rounded-xl w-fit">
        <button @click="activeTab = 'trial_balance'" :class="{'bg-white dark:bg-slate-700 shadow text-slate-900 dark:text-white': activeTab === 'trial_balance', 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300': activeTab !== 'trial_balance'}" class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all">
            {{ __('Trial Balance') }}
        </button>
        <button @click="activeTab = 'journal_entries'" :class="{'bg-white dark:bg-slate-700 shadow text-slate-900 dark:text-white': activeTab === 'journal_entries', 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300': activeTab !== 'journal_entries'}" class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all">
            {{ __('Recent Journal Entries') }}
        </button>
    </div>

    <!-- Trial Balance Tab -->
    <div x-show="activeTab === 'trial_balance'" class="modern-card p-0 overflow-hidden" x-transition>
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Trial Balance (As of :date)', ['date' => now()->format('M d, Y')]) }}</h3>
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $trialBalance['is_balanced'] ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                {{ $trialBalance['is_balanced'] ? __('Balanced') : __('Unbalanced') }}
            </span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-500 dark:text-slate-400">
                <thead class="text-xs text-slate-700 uppercase bg-slate-100 dark:bg-slate-800 dark:text-slate-300">
                    <tr>
                        <th class="px-6 py-4">{{ __('Account Name') }}</th>
                        <th class="px-6 py-4 text-right">{{ __('Debit (DR)') }}</th>
                        <th class="px-6 py-4 text-right">{{ __('Credit (CR)') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($trialBalance['entries'] as $entry)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">{{ $entry['account'] }}</td>
                        <td class="px-6 py-4 text-right">{{ $entry['debit'] > 0 ? '$'.number_format($entry['debit'], 2) : '-' }}</td>
                        <td class="px-6 py-4 text-right">{{ $entry['credit'] > 0 ? '$'.number_format($entry['credit'], 2) : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-slate-500 italic">{{ __('No account balances found.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50 dark:bg-slate-800 font-black text-slate-900 dark:text-white border-t-2 border-slate-300 dark:border-slate-700">
                    <tr>
                        <td class="px-6 py-4">{{ __('TOTAL') }}</td>
                        <td class="px-6 py-4 text-right text-emerald-600 dark:text-emerald-400">${{ number_format($trialBalance['total_debit'], 2) }}</td>
                        <td class="px-6 py-4 text-right text-emerald-600 dark:text-emerald-400">${{ number_format($trialBalance['total_credit'], 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Journal Entries Tab -->
    <div x-show="activeTab === 'journal_entries'" class="space-y-6" style="display: none;" x-transition>
        @forelse($recentJournals as $journal)
        <div class="modern-card overflow-hidden">
            <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 flex justify-between items-center">
                <div>
                    <div class="font-bold text-slate-900 dark:text-white text-lg">{{ $journal->reference_number }}</div>
                    <div class="text-sm text-slate-500">{{ $journal->entry_date }} • {{ $journal->description }}</div>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                    {{ $journal->status }}
                </span>
            </div>
            
            <div class="p-4">
                <table class="w-full text-left text-sm text-slate-500 dark:text-slate-400">
                    <thead class="text-xs text-slate-700 uppercase border-b border-slate-200 dark:border-slate-800 dark:text-slate-300">
                        <tr>
                            <th class="pb-2 w-1/2">{{ __('Account') }}</th>
                            <th class="pb-2 text-right">{{ __('Debit') }}</th>
                            <th class="pb-2 text-right">{{ __('Credit') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @foreach($journal->lines as $line)
                        <tr>
                            <td class="py-3">
                                <div class="font-semibold {{ $line->credit > 0 ? 'pl-8' : '' }} text-slate-900 dark:text-white">{{ $line->account->name ?? __('Unknown Account') }}</div>
                                @if($line->costCenter)
                                <div class="text-xs text-indigo-500 mt-1 {{ $line->credit > 0 ? 'pl-8' : '' }}">{{ __('CC:') }} {{ $line->costCenter->name }}</div>
                                @endif
                            </td>
                            <td class="py-3 text-right">{{ $line->debit > 0 ? '$'.number_format($line->debit, 2) : '' }}</td>
                            <td class="py-3 text-right">{{ $line->credit > 0 ? '$'.number_format($line->credit, 2) : '' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="modern-card p-12 text-center">
            <div class="text-slate-500 italic">{{ __('No recent journal entries found.') }}</div>
        </div>
        @endforelse
    </div>

    <template x-teleport="body">
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-[#030712]/80 backdrop-blur-sm" @click="showModal = false"></div>
                
                <div x-show="showModal" x-transition class="relative glass-panel rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden border border-slate-200 dark:border-white/10">
                    <form action="{{ route('finance.ledger.store') }}" method="POST">
                        @csrf
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-white/10 flex justify-between items-center bg-slate-50 dark:bg-white/[0.02]">
                            <h2 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                                <i data-lucide="file-plus" class="w-5 h-5 text-blue-500"></i>
                                {{ __('Post Journal Entry') }}
                            </h2>
                            <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                        
                        <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Entry Date') }}</label>
                                    <input type="date" name="entry_date" x-model="entryDate" class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Description') }}</label>
                                    <input type="text" name="description" x-model="description" class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none" placeholder="{{ __('e.g. Purchased new tractor') }}" required>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-end mb-2">
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Lines') }}</label>
                                    <button type="button" @click="addLine()" class="text-xs font-bold text-blue-500 hover:text-blue-600 flex items-center gap-1">
                                        <i data-lucide="plus" class="w-3 h-3"></i> {{ __('Add Line') }}
                                    </button>
                                </div>
                                
                                <div class="space-y-3">
                                    <template x-for="(line, index) in lines" :key="line.id">
                                        <div class="flex gap-2 md:gap-3 items-start">
                                            <div class="flex-1 md:flex-[2] md:min-w-0">
                                                <select :name="'lines['+index+'][account_id]'" x-model="line.account_id" class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl px-2 md:px-4 py-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none text-xs md:text-sm" required>
                                                    <option value="" disabled>{{ __('Select') }}</option>
                                                    @foreach($accounts as $account)
                                                    <option value="{{ $account->id }}">{{ __($account->name) }} ({{ __(ucfirst($account->account_type)) }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="flex-1 md:flex-none md:w-32">
                                                <input type="number" :name="'lines['+index+'][debit]'" x-model.number="line.debit" step="0.01" min="0" class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl px-2 md:px-4 py-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none text-xs md:text-sm" placeholder="{{ __('Debit') }}">
                                            </div>
                                            <div class="flex-1 md:flex-none md:w-32">
                                                <input type="number" :name="'lines['+index+'][credit]'" x-model.number="line.credit" step="0.01" min="0" class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl px-2 md:px-4 py-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none text-xs md:text-sm" placeholder="{{ __('Credit') }}">
                                            </div>
                                            <button type="button" @click="removeLine(index)" class="p-2 md:p-2.5 text-slate-400 hover:text-rose-500 mt-0.5 md:mt-1 transition-colors shrink-0" :class="lines.length <= 2 ? 'opacity-50 cursor-not-allowed' : ''" :disabled="lines.length <= 2">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-slate-50 dark:bg-white/[0.02] border-t border-slate-200 dark:border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="flex items-center gap-6 font-mono text-sm w-full md:w-auto">
                                <div>
                                    <span class="text-slate-500">{{ __('Total Debit: ') }}</span>
                                    <span class="font-bold text-slate-900 dark:text-white" x-text="'$' + totalDebit.toFixed(2)"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500">{{ __('Total Credit: ') }}</span>
                                    <span class="font-bold text-slate-900 dark:text-white" x-text="'$' + totalCredit.toFixed(2)"></span>
                                </div>
                                <div>
                                    <span x-show="isBalanced" class="text-emerald-500 font-bold flex items-center gap-1">
                                        <i data-lucide="check" class="w-4 h-4"></i> {{ __('Balanced') }}
                                    </span>
                                    <span x-show="!isBalanced" class="text-rose-500 font-bold flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-4 h-4"></i> {{ __('Unbalanced') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-2 w-full md:w-auto justify-end">
                                <button type="button" @click="showModal = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors">{{ __('Cancel') }}</button>
                                <button type="submit" :disabled="!isBalanced" class="btn-primary disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i data-lucide="save" class="w-4 h-4"></i> {{ __('Post Entry') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('ledgerManager', () => ({
            activeTab: 'trial_balance',
            showModal: false,
            entryDate: new Date().toISOString().slice(0, 10),
            description: '',
            lines: [
                { id: 1, account_id: '', debit: 0, credit: 0 },
                { id: 2, account_id: '', debit: 0, credit: 0 }
            ],
            
            get totalDebit() {
                return this.lines.reduce((sum, line) => sum + (parseFloat(line.debit) || 0), 0);
            },
            
            get totalCredit() {
                return this.lines.reduce((sum, line) => sum + (parseFloat(line.credit) || 0), 0);
            },
            
            get isBalanced() {
                return this.totalDebit > 0 && Math.abs(this.totalDebit - this.totalCredit) < 0.01;
            },
            
            addLine() {
                this.lines.push({ id: Date.now(), account_id: '', debit: 0, credit: 0 });
                this.$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
            },
            
            removeLine(index) {
                if (this.lines.length > 2) {
                    this.lines.splice(index, 1);
                }
            }
        }));
    });
</script>
@endpush
@endsection
