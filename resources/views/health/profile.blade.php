@extends('layouts.app')
@section('title', __('Medical Profile') . ' - ' . ($animal->internal_id ?? __('Unknown')))

@section('content')
<div class="p-4 sm:p-8 space-y-6 max-w-7xl mx-auto" x-data="{ showMedicalModal: false, showVaccinationModal: false, showLabModal: false }">
    <!-- Header -->
    <div class="glass-panel p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-6">
                <div class="relative">
                    <img src="{{ $animal->primary_photo_url }}" class="w-24 h-24 rounded-2xl border-4 border-white shadow-lg object-cover">
                    @if($animal->health_status === 'Sick')
                        <div class="absolute -bottom-2 -right-2 bg-rose-500 text-white w-8 h-8 rounded-full flex items-center justify-center shadow border-2 border-white">
                            <i data-lucide="activity" class="w-4 h-4"></i>
                        </div>
                    @else
                        <div class="absolute -bottom-2 -right-2 bg-emerald-500 text-white w-8 h-8 rounded-full flex items-center justify-center shadow border-2 border-white">
                            <i data-lucide="check" class="w-4 h-4"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <h1 class="text-3xl font-black text-slate-900 dark:text-white">{{ $animal->internal_id ?? __('Unknown ID') }}</h1>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded text-xs font-bold">{{ $animal->breed->name ?? __('Unknown Breed') }}</span>
                        <span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded text-xs font-bold">{{ ucfirst($animal->gender) ?? 'N/A' }}</span>
                        <span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded text-xs font-bold">{{ $animal->birth_weight ?? '--' }} kg</span>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button @click="showMedicalModal = true" class="btn-primary flex items-center gap-2">
                    <i data-lucide="clipboard-plus" class="w-4 h-4"></i> {{ __('Add Medical Record') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Active Treatments & Quarantine -->
    @if($animal->quarantineRecords->where('status', 'active')->count() > 0)
        <div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 p-4 rounded-r-xl flex items-start gap-4">
            <i data-lucide="alert-triangle" class="w-6 h-6 text-amber-500 shrink-0"></i>
            <div>
                <h4 class="font-bold text-amber-800 dark:text-amber-400">{{ __('Animal is currently in Quarantine') }}</h4>
                <p class="text-sm text-amber-700 dark:text-amber-500 mt-1">{{ __('Reason') }}: {{ $animal->quarantineRecords->where('status', 'active')->first()->reason }} | {{ __('Started') }}: {{ $animal->quarantineRecords->where('status', 'active')->first()->start_date }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Medical Timeline -->
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-panel p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 border-b border-slate-200 dark:border-slate-800 pb-2">{{ __('Medical History & Timeline') }}</h3>
                
                <div class="relative border-l-2 border-slate-200 dark:border-slate-700 ml-3 space-y-8">
                    
                    @forelse($animal->medicalRecords as $record)
                        <div class="relative pl-6">
                            <div class="absolute -left-[9px] bg-white dark:bg-slate-900 w-4 h-4 rounded-full border-2 border-emerald-500"></div>
                            <div class="text-xs text-slate-500 mb-1">{{ $record->examination_date }}</div>
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-100 dark:border-slate-800">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="font-bold text-slate-900 dark:text-white">{{ __('Veterinary Visit') }}</h4>
                                    <span class="text-xs text-slate-500">{{ __('By') }}: {{ $record->veterinaryVisit->veterinarian->name ?? __('System') }}</span>
                                </div>
                                
                                <div class="grid grid-cols-3 gap-4 mb-4 p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-100 dark:border-slate-800">
                                    <div class="text-center">
                                        <div class="text-xs text-slate-500">{{ __('Temp') }}</div>
                                        <div class="font-bold {{ $record->temperature > 39.5 ? 'text-rose-500' : 'text-slate-700 dark:text-slate-300' }}">{{ $record->temperature ?? '--' }}°C</div>
                                    </div>
                                    <div class="text-center border-l border-r border-slate-100 dark:border-slate-800">
                                        <div class="text-xs text-slate-500">{{ __('Heart') }}</div>
                                        <div class="font-bold text-slate-700 dark:text-slate-300">{{ $record->heart_rate ?? '--' }} bpm</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-xs text-slate-500">{{ __('Resp') }}</div>
                                        <div class="font-bold text-slate-700 dark:text-slate-300">{{ $record->respiratory_rate ?? '--' }} /min</div>
                                    </div>
                                </div>

                                @if($record->diagnoses->count() > 0)
                                    <div class="mb-3">
                                        <div class="text-xs font-bold text-slate-500 uppercase mb-1">{{ __('Diagnoses') }}</div>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($record->diagnoses as $diagnosis)
                                                <span class="px-2 py-1 bg-rose-100 text-rose-700 rounded text-xs font-bold">{{ $diagnosis->disease->name ?? __('Unknown') }} ({{ $diagnosis->severity }})</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($record->treatments->count() > 0)
                                    <div>
                                        <div class="text-xs font-bold text-slate-500 uppercase mb-1">{{ __('Treatments & Prescriptions') }}</div>
                                        <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                            @foreach($record->treatments as $treatment)
                                                <li class="flex items-center gap-2">
                                                    <i data-lucide="pill" class="w-3 h-3 text-blue-500"></i>
                                                    {{ $treatment->description }} 
                                                    <span class="text-xs px-1.5 bg-blue-100 text-blue-700 rounded">{{ $treatment->status }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                @if($record->notes)
                                    <div class="mt-3 text-sm text-slate-600 dark:text-slate-400 bg-amber-50 dark:bg-amber-900/10 p-2 rounded border border-amber-100 dark:border-amber-800">
                                        <strong>{{ __('Notes') }}:</strong> {{ $record->notes }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="pl-6 text-slate-500 italic text-sm">{{ __('No medical records found for this animal.') }}</div>
                    @endforelse

                </div>
            </div>
        </div>

        <!-- Sidebar Widgets -->
        <div class="space-y-6">
            <!-- Vaccinations -->
            <div class="glass-panel p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-800 pb-2 flex items-center justify-between">
                    <span class="flex items-center gap-2"><i data-lucide="syringe" class="w-5 h-5 text-blue-500"></i> {{ __('Vaccinations') }}</span>
                    <button @click="showVaccinationModal = true" class="text-xs font-bold text-blue-500 hover:text-blue-600">{{ __('Add') }}</button>
                </h3>
                <ul class="space-y-3">
                    @forelse($animal->vaccinationRecords as $vac)
                        <li class="flex justify-between items-center text-sm border-b border-slate-100 dark:border-slate-800 pb-2 last:border-0 last:pb-0">
                            <div>
                                <div class="font-bold text-slate-900 dark:text-white">{{ $vac->vaccine->name ?? __('Unknown Vaccine') }}</div>
                                <div class="text-xs text-slate-500">{{ __('Given') }}: {{ $vac->administered_date }}</div>
                            </div>
                            @if($vac->next_due_date && $vac->next_due_date < now())
                                <span class="bg-rose-100 text-rose-700 px-2 py-0.5 rounded text-xs font-bold">{{ __('Overdue') }}</span>
                            @elseif($vac->next_due_date)
                                <div class="text-xs text-slate-500 text-right">{{ __('Due') }}:<br>{{ $vac->next_due_date }}</div>
                            @endif
                        </li>
                    @empty
                        <li class="text-slate-500 italic text-sm">{{ __('No vaccination records.') }}</li>
                    @endforelse
                </ul>
            </div>

            <!-- Lab Results -->
            <div class="glass-panel p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-800 pb-2 flex items-center justify-between">
                    <span class="flex items-center gap-2"><i data-lucide="flask-conical" class="w-5 h-5 text-purple-500"></i> {{ __('Lab Results') }}</span>
                    <button @click="showLabModal = true" class="text-xs font-bold text-purple-500 hover:text-purple-600">{{ __('Add') }}</button>
                </h3>
                <ul class="space-y-3">
                    @forelse($animal->labResults as $lab)
                        <li class="flex justify-between items-center text-sm border-b border-slate-100 dark:border-slate-800 pb-2 last:border-0 last:pb-0">
                            <div>
                                <div class="font-bold text-slate-900 dark:text-white">{{ $lab->test_type }} {{ __('Test') }}</div>
                                <div class="text-xs text-slate-500">{{ $lab->test_date }}</div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-xs font-bold 
                                {{ $lab->result_status === 'Positive' ? 'bg-rose-100 text-rose-700' : ($lab->result_status === 'Negative' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700') }}">
                                {{ __($lab->result_status) }}
                            </span>
                        </li>
                    @empty
                        <li class="text-slate-500 italic text-sm">{{ __('No lab results recorded.') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>

    <!-- Medical Record Modal -->
    <template x-teleport="body">
        <div x-show="showMedicalModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" x-transition.opacity>
            <div class="bg-white dark:bg-[#0f2d1e] rounded-2xl w-full max-w-md shadow-2xl border border-slate-200 dark:border-emerald-900/50" @click.away="showMedicalModal = false" x-transition>
                <div class="p-6 border-b border-slate-200 dark:border-emerald-900/40 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ __('Add Medical Record') }}</h3>
                    <button @click="showMedicalModal = false" class="text-slate-400 hover:text-slate-500 dark:text-emerald-400 dark:hover:text-white">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-emerald-300 mb-1">{{ __('Examination Date') }}</label>
                        <input type="date" class="input-field w-full" value="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-emerald-300 mb-1">{{ __('Veterinarian') }}</label>
                        <select class="select-field w-full">
                            <option>Dr. Sarah Jenkins</option>
                            <option>Dr. Mark Ruffalo</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-emerald-300 mb-1">{{ __('Diagnosis') }}</label>
                        <input type="text" class="input-field w-full" placeholder="{{ __('e.g. Mastitis') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-emerald-300 mb-1">{{ __('Notes') }}</label>
                        <textarea class="input-field w-full" rows="3" placeholder="{{ __('Add examination notes...') }}"></textarea>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-200 dark:border-emerald-900/40 flex justify-end gap-3 bg-slate-50 dark:bg-[#0a1f14] rounded-b-2xl">
                    <button @click="showMedicalModal = false" class="btn-ghost">{{ __('Cancel') }}</button>
                    <button @click="showMedicalModal = false" class="btn-primary">{{ __('Save Record') }}</button>
                </div>
            </div>
        </div>
    </template>

    <!-- Vaccination Modal -->
    <template x-teleport="body">
        <div x-show="showVaccinationModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" x-transition.opacity>
            <div class="bg-white dark:bg-[#0f2d1e] rounded-2xl w-full max-w-md shadow-2xl border border-slate-200 dark:border-emerald-900/50" @click.away="showVaccinationModal = false" x-transition>
                <div class="p-6 border-b border-slate-200 dark:border-emerald-900/40 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ __('Add Vaccination') }}</h3>
                    <button @click="showVaccinationModal = false" class="text-slate-400 hover:text-slate-500 dark:text-emerald-400 dark:hover:text-white">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-emerald-300 mb-1">{{ __('Vaccine Name') }}</label>
                        <input type="text" class="input-field w-full" placeholder="{{ __('e.g. CDT, Foot Rot') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-emerald-300 mb-1">{{ __('Administered Date') }}</label>
                        <input type="date" class="input-field w-full" value="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-emerald-300 mb-1">{{ __('Next Due Date') }}</label>
                        <input type="date" class="input-field w-full">
                    </div>
                </div>
                <div class="p-6 border-t border-slate-200 dark:border-emerald-900/40 flex justify-end gap-3 bg-slate-50 dark:bg-[#0a1f14] rounded-b-2xl">
                    <button @click="showVaccinationModal = false" class="btn-ghost">{{ __('Cancel') }}</button>
                    <button @click="showVaccinationModal = false" class="btn-primary">{{ __('Save Vaccination') }}</button>
                </div>
            </div>
        </div>
    </template>

    <!-- Lab Result Modal -->
    <template x-teleport="body">
        <div x-show="showLabModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" x-transition.opacity>
            <div class="bg-white dark:bg-[#0f2d1e] rounded-2xl w-full max-w-md shadow-2xl border border-slate-200 dark:border-emerald-900/50" @click.away="showLabModal = false" x-transition>
                <div class="p-6 border-b border-slate-200 dark:border-emerald-900/40 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ __('Add Lab Result') }}</h3>
                    <button @click="showLabModal = false" class="text-slate-400 hover:text-slate-500 dark:text-emerald-400 dark:hover:text-white">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-emerald-300 mb-1">{{ __('Test Type') }}</label>
                        <select class="select-field w-full">
                            <option>{{ __('Blood Test') }}</option>
                            <option>{{ __('Fecal Egg Count') }}</option>
                            <option>{{ __('Tissue Biopsy') }}</option>
                            <option>{{ __('Milk Culture') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-emerald-300 mb-1">{{ __('Test Date') }}</label>
                        <input type="date" class="input-field w-full" value="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-emerald-300 mb-1">{{ __('Result Status') }}</label>
                        <select class="select-field w-full">
                            <option>{{ __('Pending') }}</option>
                            <option>{{ __('Negative') }}</option>
                            <option>{{ __('Positive') }}</option>
                            <option>{{ __('Inconclusive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-200 dark:border-emerald-900/40 flex justify-end gap-3 bg-slate-50 dark:bg-[#0a1f14] rounded-b-2xl">
                    <button @click="showLabModal = false" class="btn-ghost">{{ __('Cancel') }}</button>
                    <button @click="showLabModal = false" class="btn-primary">{{ __('Save Result') }}</button>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
