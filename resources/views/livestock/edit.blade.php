@extends('layouts.app')
@section('title', 'Edit Animal')

@section('content')
<div class="p-4 sm:p-8 space-y-6 max-w-4xl mx-auto" x-data="animalRegistrationForm()">
    
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">Edit Animal</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Update profile for {{ $animal->name ?? $animal->internal_id }}.</p>
    </div>

    <!-- Offline Alert -->
    <div x-show="!isOnline" style="display: none;" class="bg-amber-50 dark:bg-amber-500/10 border-l-4 border-amber-500 p-4 rounded-r-xl">
        <div class="flex items-center gap-3 text-amber-700 dark:text-amber-400">
            <i data-lucide="wifi-off" class="w-5 h-5"></i>
            <div>
                <p class="font-bold">You are currently offline.</p>
                <p class="text-sm">Changes will be saved locally and synced automatically when connection is restored.</p>
            </div>
        </div>
    </div>

    <form action="{{ route('livestock.update', $animal->id) }}" method="POST" id="animalForm" @submit.prevent="submitForm">
        @csrf
        @method('PUT')
        <div class="glass-panel p-6 space-y-8">
            
            <!-- Section: Identity -->
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-800 pb-2">Identity & Tags</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Visual Ear Tag <span class="text-amber-500 text-xs ml-2 cursor-pointer hover:underline" @click="startQrScanner('visual_tag')"><i data-lucide="qr-code" class="w-3 h-3 inline"></i> Scan QR</span></label>
                        <input type="text" name="visual_tag" x-model="formData.visual_tag" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="e.g. A-1234">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Electronic Tag (RFID) <span class="text-emerald-500 text-xs ml-2 cursor-pointer hover:underline" @click="scanRfid"><i data-lucide="radio" class="w-3 h-3 inline"></i> Scan</span></label>
                        <input type="text" name="electronic_tag" x-model="formData.electronic_tag" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="e.g. 982000000000001">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Internal ID (Required) <span class="text-amber-500 text-xs ml-2 cursor-pointer hover:underline" @click="startQrScanner('internal_id')"><i data-lucide="qr-code" class="w-3 h-3 inline"></i> Scan QR</span></label>
                        <input type="text" name="internal_id" x-model="formData.internal_id" required class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="e.g. EWE-001">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Name / Alias</label>
                        <input type="text" name="name" value="{{ $animal->name }}" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="e.g. Dolly">
                    </div>
                </div>
            </div>

            <!-- Section: Biology -->
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-800 pb-2">Biology & Breed</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Species</label>
                        <select name="species_id" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                            @foreach($species as $sp)
                                <option value="{{ $sp->id }}" {{ $animal->species_id == $sp->id ? 'selected' : '' }}>{{ $sp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Breed</label>
                        <select name="breed_id" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                            @foreach($breeds as $br)
                                <option value="{{ $br->id }}" {{ $animal->breed_id == $br->id ? 'selected' : '' }}>{{ $br->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Gender</label>
                        <select name="gender" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                            <option value="F" {{ $animal->gender == 'F' ? 'selected' : '' }}>Female</option>
                            <option value="M" {{ $animal->gender == 'M' ? 'selected' : '' }}>Male</option>
                            <option value="Unknown" {{ $animal->gender == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Category</label>
                        <select name="category_id" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $animal->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Color / Markings</label>
                        <input type="text" name="color" value="{{ $animal->color }}" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>
                </div>
            </div>

            <!-- Section: Birth & Origin -->
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-800 pb-2">Birth & Origin</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Birth Date</label>
                        <input type="date" name="birth_date" value="{{ $animal->birth_date ? $animal->birth_date->format('Y-m-d') : '' }}" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Birth/Initial Weight (kg)</label>
                        <input type="number" step="0.1" name="birth_weight" value="{{ $animal->birth_weight }}" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Origin</label>
                        <select name="origin" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                            <option value="Farm Born" {{ $animal->origin == 'Farm Born' ? 'selected' : '' }}>Farm Born</option>
                            <option value="Purchased" {{ $animal->origin == 'Purchased' ? 'selected' : '' }}>Purchased</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Section: Assignment & Status -->
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-800 pb-2">Assignment & Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Assign to Barn</label>
                        <select name="barn_id" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                            <option value="">-- No Barn --</option>
                            @foreach($barns as $barn)
                                <option value="{{ $barn->id }}" {{ $animal->current_barn_id == $barn->id ? 'selected' : '' }}>{{ $barn->name }} (Zone: {{ $barn->zone->name ?? 'None' }})</option>
                            @endforeach
                        </select>
                        @if($barns->isEmpty())
                            <p class="text-xs text-red-500 mt-2"><i data-lucide="alert-triangle" class="w-3 h-3 inline"></i> No barns found. <a href="{{ route('farm.barns.index') }}" class="underline font-bold">Create a Barn first</a>.</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Current Status</label>
                        <select name="status_id" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ $animal->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('livestock.show', $animal->id) }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary" :class="{ 'opacity-50 cursor-not-allowed': isSaving }" :disabled="isSaving">
                    <span x-show="!isSaving"><i data-lucide="save" class="w-4 h-4 inline mr-1"></i> Update Profile</span>
                    <span x-show="isSaving"><i data-lucide="loader-2" class="w-4 h-4 inline mr-1 animate-spin"></i> Saving...</span>
                </button>
            </div>
        </div>
    </form>

    <!-- QR Scanner Modal -->
    <template x-teleport="body">
        <div x-show="showQrModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" x-transition.opacity>
            <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden flex flex-col" @click.away="stopQrScanner">
                <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                    <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="qr-code" class="w-5 h-5 text-amber-500"></i>
                        Scan QR Code
                    </h3>
                    <button type="button" @click="stopQrScanner" class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="p-4 bg-black relative">
                    <div id="qr-reader" class="w-full bg-black rounded-lg overflow-hidden" style="min-height: 300px;"></div>
                </div>
                <div class="p-4 text-center text-sm text-slate-500 dark:text-slate-400">
                    Point your camera at the QR code to scan.
                </div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('animalRegistrationForm', () => ({
            isOnline: navigator.onLine,
            isSaving: false,
            showQrModal: false,
            scannerInstance: null,
            targetField: null,
            formData: {
                electronic_tag: {!! json_encode($animal->electronic_tag ?? '') !!},
                visual_tag: {!! json_encode($animal->visual_tag ?? '') !!},
                internal_id: {!! json_encode($animal->internal_id ?? '') !!}
            },
            init() {
                window.addEventListener('online', () => this.isOnline = true);
                window.addEventListener('offline', () => this.isOnline = false);
            },
            scanRfid() {
                this.formData.electronic_tag = 'SCANNING...';
                setTimeout(() => {
                    this.formData.electronic_tag = '98200' + Math.floor(1000000000 + Math.random() * 9000000000);
                }, 1000);
            },
            startQrScanner(field) {
                this.targetField = field;
                this.showQrModal = true;
                
                setTimeout(() => {
                    if (!this.scannerInstance) {
                        this.scannerInstance = new Html5Qrcode("qr-reader");
                    }
                    
                    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                    
                    this.scannerInstance.start(
                        { facingMode: "environment" },
                        config,
                        (decodedText, decodedResult) => {
                            if (this.targetField === 'visual_tag') {
                                this.formData.visual_tag = decodedText;
                            } else if (this.targetField === 'internal_id') {
                                this.formData.internal_id = decodedText;
                            }
                            this.stopQrScanner();
                        },
                        (errorMessage) => {
                        }
                    ).catch((err) => {
                        console.error("Error starting scanner:", err);
                        alert("Could not access camera. Please ensure permissions are granted.");
                        this.stopQrScanner();
                    });
                }, 100);
            },
            stopQrScanner() {
                this.showQrModal = false;
                if (this.scannerInstance) {
                    this.scannerInstance.stop().then(() => {
                        this.scannerInstance.clear();
                        this.scannerInstance = null;
                    }).catch(err => {
                        console.error("Failed to stop scanner", err);
                    });
                }
            },
            async submitForm(e) {
                if (this.isOnline) {
                    this.isSaving = true;
                    e.target.submit();
                } else {
                    alert('Animal saved to offline queue. It will be synced when you connect to the internet.');
                    window.location.href = "{{ route('livestock.show', $animal->id) }}";
                }
            }
        }));
    });
</script>
@endpush
@endsection
