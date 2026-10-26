@extends('layouts.app')

@section('content')
<div x-data="{ scanQrModal: false, scanRfidModal: false }" class="h-full lg:h-screen flex flex-col relative lg:overflow-hidden"
     :style="darkMode ? 'background: rgba(0,0,0,0);' : 'background: transparent;'">
    
    {{-- TOP GLASS NAV --}}
    <div class="px-4 md:px-8 py-6 z-20 relative flex flex-col md:flex-row items-start md:items-center justify-between gap-4 md:gap-0 border-b"
         :style="darkMode ? 'background: rgba(15,35,18,0.4); backdrop-filter: blur(20px); border-color: rgba(255,255,255,0.05);' : 'background: rgba(255,255,255,0.7); backdrop-filter: blur(20px); border-color: rgba(34,197,94,0.1);'">
        <div>
            <h1 class="text-3xl font-black tracking-tight flex items-center gap-3" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm"
                     :style="darkMode ? 'background: rgba(34,197,94,0.2);' : 'background: #dcfce7;'">
                    <i data-lucide="tractor" class="w-6 h-6" :style="darkMode ? 'color: #4ade80; filter: drop-shadow(0 0 5px rgba(74,222,128,0.5));' : 'color: #16a34a; filter: drop-shadow(0 0 5px rgba(22,163,74,0.3));'"></i>
                </div>
                {{ __('Farm Operations Command Center') }}
            </h1>
            <p class="mt-2 text-sm font-bold uppercase tracking-widest" :style="darkMode ? 'color: rgba(255,255,255,0.4);' : 'color: #7a8d80;'">{{ __('Real-time telemetry, 3D zone mapping, and fleet management.') }}</p>
        </div>
        
        <div class="flex items-center gap-4 w-full md:w-auto">
            <div x-data="{ open: false }" class="relative z-50">
                <button @click="open = !open" @click.outside="open = false" class="px-5 py-2.5 rounded-xl shadow-sm text-sm font-bold transition-all flex items-center gap-2 border"
                        :style="darkMode ? 'background: rgba(0,0,0,0.3); border-color: rgba(255,255,255,0.1); color: #ffffff;' : 'background: #ffffff; border-color: #d4e8d8; color: #1a2e1d;'">
                    <i data-lucide="scan" class="w-4 h-4" :style="darkMode ? 'color: #4ade80;' : 'color: #16a34a;'"></i> {{ __('Scan Action') }} <i data-lucide="chevron-down" class="w-4 h-4 opacity-50"></i>
                </button>
                <div x-show="open" x-transition class="absolute top-full mt-2 left-0 w-48 border rounded-xl shadow-2xl overflow-hidden z-50" style="display: none;"
                     :style="darkMode ? 'background: rgba(15,35,18,0.95); border-color: rgba(255,255,255,0.1); backdrop-filter: blur(20px);' : 'background: rgba(255,255,255,0.95); border-color: #d4e8d8; backdrop-filter: blur(20px);'">
                    <a href="#" @click.prevent="open = false; scanRfidModal = true" class="flex items-center gap-3 px-4 py-3 text-sm font-bold transition-colors"
                       :style="darkMode ? 'color: #ffffff; hover:background: rgba(255,255,255,0.05);' : 'color: #1a2e1d; hover:background: #f8fbf9;'">
                        <i data-lucide="scan-line" class="w-4 h-4" :style="darkMode ? 'color: #4ade80;' : 'color: #16a34a;'"></i> {{ __('Scan RFID') }}
                    </a>
                    <a href="#" @click.prevent="open = false; scanQrModal = true" class="flex items-center gap-3 px-4 py-3 text-sm font-bold transition-colors"
                       :style="darkMode ? 'color: #ffffff; hover:background: rgba(255,255,255,0.05);' : 'color: #1a2e1d; hover:background: #f8fbf9;'">
                        <i data-lucide="qr-code" class="w-4 h-4 text-blue-500"></i> {{ __('Scan QR') }}
                    </a>
                </div>
            </div>
            <a href="{{ route('farm.zones.index') }}" class="px-5 py-2.5 rounded-xl shadow-lg text-sm font-bold transition-all ml-auto md:ml-0 inline-flex items-center text-white"
               style="background: linear-gradient(135deg, #10b981, #059669);">
                <i data-lucide="plus" class="w-4 h-4 mr-1 drop-shadow-sm"></i> {{ __('New Zone') }}
            </a>
        </div>
    </div>

    {{-- MAIN DASHBOARD LAYOUT --}}
    <div class="flex-1 flex flex-col lg:flex-row gap-6 px-4 md:px-8 py-6 h-full relative z-10 lg:overflow-hidden">
        
        {{-- MAIN 3D MAP AREA --}}
        <div x-data="farmMap" 
             @mousedown="startDrag" 
             @mousemove.window="doDrag" 
             @mouseup.window="stopDrag"
             class="flex-[2] rounded-3xl border shadow-xl overflow-hidden relative group min-h-[400px] lg:min-h-0 cursor-grab transition-colors"
             :class="{ 'cursor-grabbing': isDragging }"
             :style="darkMode ? 'background: rgba(15,35,18,0.7); border-color: rgba(34,197,94,0.1);' : 'background: #ffffff; border-color: #d4e8d8; box-shadow: 0 4px 24px rgba(0,0,0,0.06);'">
            
            {{-- Map Overlay Header --}}
            <div class="absolute top-6 left-6 z-10 rounded-full px-4 py-2 flex items-center gap-3 border shadow-sm backdrop-blur-md"
                 :style="darkMode ? 'background: rgba(0,0,0,0.4); border-color: rgba(255,255,255,0.1);' : 'background: rgba(255,255,255,0.8); border-color: #d4e8d8;'">
                <div class="w-2 h-2 rounded-full animate-ping" :style="darkMode ? 'background: #4ade80;' : 'background: #16a34a;'"></div>
                <span class="text-xs font-bold uppercase tracking-widest" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ __('Live Telemetry') }}</span>
            </div>

            {{-- 3D ISOMETRIC GRID CONTAINER --}}
            <div class="absolute inset-0 flex items-center justify-center transform-gpu preserve-3d transition-transform duration-100 ease-out"
                 :style="`transform: translate(${panX}px, ${panY}px) rotateX(60deg) rotateZ(-45deg) scale(${zoom})`">
                <div class="w-full h-full relative" style="transform: rotateX(60deg) rotateZ(-45deg) scale(0.8); transform-style: preserve-3d; transition: all 0.5s ease;">
                    
                    {{-- Grid Background --}}
                    <div class="absolute inset-0"
                         :style="darkMode ? 'background: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 50px 50px;' : 'background: linear-gradient(rgba(0,0,0,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,0.03) 1px, transparent 1px); background-size: 50px 50px;'"></div>

                    {{-- ZONE 1: Main Grazing --}}
                    <div class="absolute top-[20%] left-[20%] w-[250px] h-[250px] border-2 rounded-xl cursor-pointer transition-colors shadow-[0_0_30px_rgba(16,185,129,0.1)] group/zone" 
                         style="transform: translateZ(10px);"
                         :style="darkMode ? 'background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.3);' : 'background: rgba(34,197,94,0.1); border-color: rgba(34,197,94,0.3);'">
                        <div class="absolute -top-10 left-1/2 -translate-x-1/2 px-3 py-1.5 rounded-lg border text-xs font-bold whitespace-nowrap opacity-0 group-hover/zone:opacity-100 transition-opacity backdrop-blur-md shadow-lg" 
                             style="transform: rotateZ(45deg) rotateX(-60deg) translateY(-20px);"
                             :style="darkMode ? 'background: rgba(0,0,0,0.6); border-color: rgba(16,185,129,0.5); color: white;' : 'background: rgba(255,255,255,0.9); border-color: #bbf7d0; color: #166534;'">
                            {{ __('Alpha Grazing (Zone 1)') }}
                        </div>
                        {{-- 3D Barn in Zone 1 --}}
                        <div class="absolute top-[40%] left-[40%] w-16 h-20 shadow-[20px_20px_15px_rgba(0,0,0,0.2)] border" 
                             style="transform: translateZ(40px);"
                             :style="darkMode ? 'background: #1e293b; border-color: #334155;' : 'background: #e2e8f0; border-color: #cbd5e1;'">
                            <div class="absolute inset-0 bg-gradient-to-tr from-black/20 to-transparent"></div>
                            {{-- Roof --}}
                            <div class="absolute -top-4 -left-1 w-[72px] h-[72px] border-b-2 shadow-inner" 
                                 style="transform: rotateX(60deg) translateZ(10px);"
                                 :style="darkMode ? 'background: rgba(6,78,59,0.9); border-color: #10b981;' : 'background: #166534; border-color: #22c55e;'"></div>
                        </div>
                    </div>

                    {{-- ZONE 2: Quarantine --}}
                    <div class="absolute top-[60%] left-[50%] w-[150px] h-[200px] border-2 rounded-xl cursor-pointer transition-colors shadow-[0_0_30px_rgba(244,63,94,0.1)] group/zone" 
                         style="transform: translateZ(10px);"
                         :style="darkMode ? 'background: rgba(244,63,94,0.1); border-color: rgba(244,63,94,0.3); hover:background: rgba(244,63,94,0.2);' : 'background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.3); hover:background: rgba(239,68,68,0.2);'">
                        <div class="absolute -top-10 left-1/2 -translate-x-1/2 px-3 py-1.5 rounded-lg border text-xs font-bold whitespace-nowrap opacity-0 group-hover/zone:opacity-100 transition-opacity backdrop-blur-md shadow-lg" 
                             style="transform: rotateZ(45deg) rotateX(-60deg) translateY(-20px);"
                             :style="darkMode ? 'background: rgba(0,0,0,0.6); border-color: rgba(244,63,94,0.5); color: white;' : 'background: rgba(255,255,255,0.9); border-color: #fca5a5; color: #b91c1c;'">
                            {{ __('Quarantine (Zone 2)') }}
                        </div>
                    </div>

                    {{-- TRACTOR ASSET --}}
                    <div class="absolute top-[40%] left-[60%] w-8 h-12 rounded border shadow-[10px_10px_10px_rgba(0,0,0,0.2)]" 
                         style="transform: translateZ(20px);"
                         :style="darkMode ? 'background: #f59e0b; border-color: #fbbf24;' : 'background: #d97706; border-color: #f59e0b;'">
                         <div class="absolute -top-10 left-1/2 -translate-x-1/2 px-2 py-1 rounded border text-[10px] font-bold whitespace-nowrap backdrop-blur-md shadow-lg" 
                              style="transform: rotateZ(45deg) rotateX(-60deg) translateY(-20px);"
                              :style="darkMode ? 'background: rgba(0,0,0,0.6); border-color: rgba(245,158,11,0.5); color: white;' : 'background: rgba(255,255,255,0.9); border-color: #fcd34d; color: #b45309;'">
                            {{ __('Tractor TR-01') }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- Map Controls --}}
            <div class="absolute bottom-6 right-6 z-10 flex flex-col gap-2">
                <button @click="zoomIn" class="w-10 h-10 border rounded-xl flex items-center justify-center transition-colors shadow-lg"
                        :style="darkMode ? 'background: rgba(0,0,0,0.4); border-color: rgba(255,255,255,0.1); color: #94a3b8; hover:color: white; hover:background: rgba(255,255,255,0.05);' : 'background: #ffffff; border-color: #d4e8d8; color: #5a7c6f; hover:background: #f8fbf9; hover:color: #1a2e1d;'">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                </button>
                <button @click="zoomOut" class="w-10 h-10 border rounded-xl flex items-center justify-center transition-colors shadow-lg"
                        :style="darkMode ? 'background: rgba(0,0,0,0.4); border-color: rgba(255,255,255,0.1); color: #94a3b8; hover:color: white; hover:background: rgba(255,255,255,0.05);' : 'background: #ffffff; border-color: #d4e8d8; color: #5a7c6f; hover:background: #f8fbf9; hover:color: #1a2e1d;'">
                    <i data-lucide="minus" class="w-5 h-5"></i>
                </button>
                <button @click="reset" class="w-10 h-10 border rounded-xl flex items-center justify-center transition-colors shadow-lg mt-2"
                        :style="darkMode ? 'background: rgba(0,0,0,0.4); border-color: rgba(255,255,255,0.1); color: #94a3b8; hover:color: white; hover:background: rgba(255,255,255,0.05);' : 'background: #ffffff; border-color: #d4e8d8; color: #5a7c6f; hover:background: #f8fbf9; hover:color: #1a2e1d;'">
                    <i data-lucide="compass" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        {{-- RIGHT COLUMN: METRICS & PANELS (Flex: 1) --}}
        <div class="flex-1 flex flex-col gap-6 lg:overflow-y-auto custom-scroll pr-0 lg:pr-2 pb-10 lg:pb-0">
            
            {{-- Metric Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Zones Card --}}
                <div class="border rounded-2xl p-5 relative overflow-hidden group transition-all cursor-pointer shadow-sm"
                     :style="darkMode ? 'background: rgba(15,35,18,0.7); border-color: rgba(34,197,94,0.1); hover:border-color: rgba(34,197,94,0.3);' : 'background: #ffffff; border-color: #d4e8d8; hover:border-color: #86efac; hover:shadow-md;'">
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-full blur-3xl -mr-10 -mt-10 transition-all"
                         :style="darkMode ? 'background: rgba(16,185,129,0.1); group-hover:background: rgba(16,185,129,0.2);' : 'background: rgba(34,197,94,0.1); group-hover:background: rgba(34,197,94,0.2);'"></div>
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <div class="w-10 h-10 rounded-xl border flex items-center justify-center"
                             :style="darkMode ? 'background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.2); color: #34d399;' : 'background: #dcfce7; border-color: #bbf7d0; color: #16a34a;'">
                            <i data-lucide="map" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xs font-bold px-2 py-1 rounded-lg"
                              :style="darkMode ? 'background: rgba(16,185,129,0.1); color: #34d399;' : 'background: #dcfce7; color: #16a34a;'">{{ __('Active') }}</span>
                    </div>
                    <h3 class="text-3xl font-black relative z-10" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ $zonesCount ?? 4 }}</h3>
                    <p class="text-sm font-medium relative z-10" :style="darkMode ? 'color: #94a3b8;' : 'color: #64748b;'">{{ __('Total Zones') }}</p>
                </div>

                {{-- Barns Card --}}
                <div class="border rounded-2xl p-5 relative overflow-hidden group transition-all cursor-pointer shadow-sm"
                     :style="darkMode ? 'background: rgba(15,35,18,0.7); border-color: rgba(34,197,94,0.1); hover:border-color: rgba(56,189,248,0.3);' : 'background: #ffffff; border-color: #d4e8d8; hover:border-color: #7dd3fc; hover:shadow-md;'">
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-full blur-3xl -mr-10 -mt-10 transition-all"
                         :style="darkMode ? 'background: rgba(14,165,233,0.1); group-hover:background: rgba(14,165,233,0.2);' : 'background: rgba(56,189,248,0.1); group-hover:background: rgba(56,189,248,0.2);'"></div>
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <div class="w-10 h-10 rounded-xl border flex items-center justify-center"
                             :style="darkMode ? 'background: rgba(14,165,233,0.1); border-color: rgba(14,165,233,0.2); color: #38bdf8;' : 'background: #e0f2fe; border-color: #bae6fd; color: #0284c7;'">
                            <i data-lucide="warehouse" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xs font-bold px-2 py-1 rounded-lg"
                              :style="darkMode ? 'background: rgba(14,165,233,0.1); color: #38bdf8;' : 'background: #e0f2fe; color: #0284c7;'">{{ __('94% Cap') }}</span>
                    </div>
                    <h3 class="text-3xl font-black relative z-10" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ $barnsCount ?? 12 }}</h3>
                    <p class="text-sm font-medium relative z-10" :style="darkMode ? 'color: #94a3b8;' : 'color: #64748b;'">{{ __('Active Barns') }}</p>
                </div>

                {{-- Equipment Card --}}
                <div class="border rounded-2xl p-5 relative overflow-hidden group transition-all cursor-pointer shadow-sm"
                     :style="darkMode ? 'background: rgba(15,35,18,0.7); border-color: rgba(34,197,94,0.1); hover:border-color: rgba(245,158,11,0.3);' : 'background: #ffffff; border-color: #d4e8d8; hover:border-color: #fcd34d; hover:shadow-md;'">
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-full blur-3xl -mr-10 -mt-10 transition-all"
                         :style="darkMode ? 'background: rgba(245,158,11,0.1); group-hover:background: rgba(245,158,11,0.2);' : 'background: rgba(251,191,36,0.1); group-hover:background: rgba(251,191,36,0.2);'"></div>
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <div class="w-10 h-10 rounded-xl border flex items-center justify-center"
                             :style="darkMode ? 'background: rgba(245,158,11,0.1); border-color: rgba(245,158,11,0.2); color: #fbbf24;' : 'background: #fef3c7; border-color: #fde68a; color: #d97706;'">
                            <i data-lucide="tractor" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xs font-bold px-2 py-1 rounded-lg"
                              :style="darkMode ? 'background: rgba(244,63,94,0.1); color: #fb7185;' : 'background: #ffe4e6; color: #e11d48;'">{{ __('1 Maint.') }}</span>
                    </div>
                    <h3 class="text-3xl font-black relative z-10" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ $activeEquipment ?? 8 }}</h3>
                    <p class="text-sm font-medium relative z-10" :style="darkMode ? 'color: #94a3b8;' : 'color: #64748b;'">{{ __('Fleet Vehicles') }}</p>
                </div>

                {{-- Water Card --}}
                <div class="border rounded-2xl p-5 relative overflow-hidden group transition-all cursor-pointer shadow-sm"
                     :style="darkMode ? 'background: rgba(15,35,18,0.7); border-color: rgba(34,197,94,0.1); hover:border-color: rgba(59,130,246,0.3);' : 'background: #ffffff; border-color: #d4e8d8; hover:border-color: #93c5fd; hover:shadow-md;'">
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-full blur-3xl -mr-10 -mt-10 transition-all"
                         :style="darkMode ? 'background: rgba(59,130,246,0.1); group-hover:background: rgba(59,130,246,0.2);' : 'background: rgba(96,165,250,0.1); group-hover:background: rgba(96,165,250,0.2);'"></div>
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <div class="w-10 h-10 rounded-xl border flex items-center justify-center"
                             :style="darkMode ? 'background: rgba(59,130,246,0.1); border-color: rgba(59,130,246,0.2); color: #60a5fa;' : 'background: #dbeafe; border-color: #bfdbfe; color: #2563eb;'">
                            <i data-lucide="droplets" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black relative z-10" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">1.2M L</h3>
                    <p class="text-sm font-medium relative z-10" :style="darkMode ? 'color: #94a3b8;' : 'color: #64748b;'">{{ __('Monthly Water') }}</p>
                </div>
            </div>

            {{-- Equipment Fleet List --}}
            <div class="border rounded-2xl p-6 flex-1 flex flex-col shadow-sm"
                 :style="darkMode ? 'background: rgba(15,35,18,0.7); border-color: rgba(34,197,94,0.1);' : 'background: #ffffff; border-color: #d4e8d8;'">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold flex items-center gap-2" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">
                        <i data-lucide="activity" class="w-5 h-5" :style="darkMode ? 'color: #4ade80;' : 'color: #16a34a;'"></i> {{ __('Fleet Status') }}
                    </h2>
                    <a href="#" class="text-sm font-semibold transition-colors"
                       :style="darkMode ? 'color: #4ade80; hover:color: #86efac;' : 'color: #16a34a; hover:color: #15803d;'">{{ __('View All &rarr;') }}</a>
                </div>

                <div class="space-y-4 flex-1 overflow-y-auto custom-scroll">
                    @forelse($equipmentList as $equipment)
                        @php
                            $fuelColor = $equipment->fuel_level < 20 ? 'rose' : ($equipment->fuel_level < 50 ? 'amber' : 'emerald');
                            $iconColor = $equipment->status == 'active' ? 'emerald' : ($equipment->status == 'idle' ? 'amber' : 'rose');
                            $icon = match($equipment->type) {
                                'Tractor' => 'tractor',
                                'Drone' => 'plane',
                                'Harvester' => 'truck',
                                default => 'truck'
                            };
                            
                            $lightBgColors = ['emerald' => '#dcfce7', 'amber' => '#fef3c7', 'rose' => '#fee2e2'];
                            $lightBorderColors = ['emerald' => '#bbf7d0', 'amber' => '#fde68a', 'rose' => '#fecaca'];
                            $lightTextColors = ['emerald' => '#16a34a', 'amber' => '#d97706', 'rose' => '#dc2626'];
                        @endphp
                        <div class="flex items-center justify-between p-4 rounded-xl border transition-colors cursor-pointer group hover:scale-[1.01] shadow-sm"
                             :class="darkMode ? 'hover:border-{{ $iconColor }}-500/30' : 'hover:border-{{ $iconColor }}-400'"
                             :style="darkMode ? 'background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.05);' : 'background: #f8fbf9; border-color: #e2e8f0;'">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg border flex items-center justify-center group-hover:scale-110 transition-transform"
                                     :style="darkMode ? 'background: rgba(var(--color-{{ $iconColor }}-500), 0.1); border-color: rgba(var(--color-{{ $iconColor }}-500), 0.2); color: rgba(var(--color-{{ $iconColor }}-400), 1);' : 'background: {{ $lightBgColors[$iconColor] }}; border-color: {{ $lightBorderColors[$iconColor] }}; color: {{ $lightTextColors[$iconColor] }};'">
                                    <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm" :style="darkMode ? 'color: #e2e8f0;' : 'color: #1a2e1d;'">{{ $equipment->name }}</h4>
                                    <p class="text-xs mt-0.5 font-medium" :style="darkMode ? 'color: #64748b;' : 'color: #6b9e7a;'">{{ $equipment->type }} • {{ ucfirst($equipment->status) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-bold mb-1" 
                                     :style="darkMode ? 'color: rgba(var(--color-{{ $fuelColor }}-400), 1);' : 'color: {{ $lightTextColors[$fuelColor] }};'">{{ __('Energy: ') }}{{ $equipment->fuel_level }}%</div>
                                <div class="w-16 h-1.5 rounded-full overflow-hidden" :style="darkMode ? 'background: rgba(255,255,255,0.1);' : 'background: #e2e8f0;'">
                                    <div class="h-full rounded-full" 
                                         :style="darkMode ? 'width: {{ $equipment->fuel_level }}%; background: rgba(var(--color-{{ $fuelColor }}-400), 1); box-shadow: 0 0 10px rgba(var(--color-{{ $fuelColor }}-400), 0.5);' : 'width: {{ $equipment->fuel_level }}%; background: {{ $lightTextColors[$fuelColor] }};'"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-sm font-bold uppercase tracking-widest" :style="darkMode ? 'color: #64748b;' : 'color: #94a3b8;'">{{ __('No active fleet vehicles.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- SCAN QR MODAL --}}
    <div x-show="scanQrModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-0" style="display: none;" @keydown.escape.window="scanQrModal = false">
        <div x-show="scanQrModal" x-transition.opacity class="absolute inset-0 backdrop-blur-md" 
             :style="darkMode ? 'background: rgba(0,0,0,0.8);' : 'background: rgba(255,255,255,0.7);'" @click="scanQrModal = false"></div>
        <div x-show="scanQrModal" 
             x-init="
                $watch('scanQrModal', value => {
                    if (value) {
                        setTimeout(() => {
                            if (typeof Html5QrcodeScanner !== 'undefined') {
                                window.html5QrcodeScanner = new Html5QrcodeScanner('qr-reader', { fps: 10, qrbox: {width: 250, height: 250} }, false);
                                window.html5QrcodeScanner.render(
                                    (decodedText) => {
                                        alert('QR Scanned Successfully: ' + decodedText);
                                        scanQrModal = false;
                                    },
                                    (error) => { /* ignore */ }
                                );
                            } else {
                                alert('Scanner library is still loading. Please try again.');
                                scanQrModal = false;
                            }
                        }, 200);
                    } else {
                        if (window.html5QrcodeScanner) {
                            window.html5QrcodeScanner.clear();
                            window.html5QrcodeScanner = null;
                        }
                    }
                })
             "
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative shadow-2xl rounded-3xl w-full max-w-sm overflow-hidden flex flex-col items-center p-8 border"
             :style="darkMode ? 'background: rgba(15,35,18,0.95); border-color: rgba(255,255,255,0.1);' : 'background: #ffffff; border-color: #d4e8d8; box-shadow: 0 20px 50px rgba(0,0,0,0.1);'">
            <button @click="scanQrModal = false" class="absolute top-4 right-4 rounded-full p-2 transition-colors z-50 border shadow-sm"
                    :style="darkMode ? 'background: rgba(0,0,0,0.4); border-color: rgba(255,255,255,0.1); color: rgba(255,255,255,0.5); hover:color: white;' : 'background: #ffffff; border-color: #e2e8f0; color: #64748b; hover:color: #1a2e1d;'">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="w-16 h-16 rounded-2xl border flex items-center justify-center mb-6 shadow-sm"
                 :style="darkMode ? 'background: rgba(59,130,246,0.1); border-color: rgba(59,130,246,0.2); color: #60a5fa;' : 'background: #dbeafe; border-color: #bfdbfe; color: #2563eb;'">
                <i data-lucide="qr-code" class="w-8 h-8"></i>
            </div>
            <h2 class="text-xl font-bold mb-2" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ __('Scan QR Code') }}</h2>
            <p class="text-sm text-center mb-6 font-medium" :style="darkMode ? 'color: #94a3b8;' : 'color: #64748b;'">{{ __('Position the QR code inside the frame to scan the animal or equipment tag.') }}</p>
            
            {{-- Real Camera Scanner Div --}}
            <div id="qr-reader" class="w-full border rounded-2xl overflow-hidden relative shadow-inner"
                 :style="darkMode ? 'background: rgba(0,0,0,0.5); border-color: rgba(255,255,255,0.05);' : 'background: #f8fbf9; border-color: #e2e8f0;'"></div>
            
            <p class="text-xs font-bold uppercase tracking-widest mt-6"
               :style="darkMode ? 'color: #64748b;' : 'color: #94a3b8;'">{{ __('Camera Active') }}</p>
        </div>
    </div>

    {{-- SCAN RFID MODAL --}}
    <div x-show="scanRfidModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-0" style="display: none;" @keydown.escape.window="scanRfidModal = false">
        <div x-show="scanRfidModal" x-transition.opacity class="absolute inset-0 backdrop-blur-md" 
             :style="darkMode ? 'background: rgba(0,0,0,0.8);' : 'background: rgba(255,255,255,0.7);'" @click="scanRfidModal = false"></div>
        <div x-show="scanRfidModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative shadow-2xl rounded-3xl w-full max-w-sm overflow-hidden flex flex-col items-center p-8 border"
             :style="darkMode ? 'background: rgba(15,35,18,0.95); border-color: rgba(255,255,255,0.1);' : 'background: #ffffff; border-color: #d4e8d8; box-shadow: 0 20px 50px rgba(0,0,0,0.1);'">
            <button @click="scanRfidModal = false" class="absolute top-4 right-4 rounded-full p-2 transition-colors border shadow-sm"
                    :style="darkMode ? 'background: rgba(0,0,0,0.4); border-color: rgba(255,255,255,0.1); color: rgba(255,255,255,0.5); hover:color: white;' : 'background: #ffffff; border-color: #e2e8f0; color: #64748b; hover:color: #1a2e1d;'">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="w-16 h-16 rounded-2xl border flex items-center justify-center mb-6 shadow-sm"
                 :style="darkMode ? 'background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.2); color: #34d399;' : 'background: #dcfce7; border-color: #bbf7d0; color: #16a34a;'">
                <i data-lucide="radio" class="w-8 h-8 animate-pulse"></i>
            </div>
            <h2 class="text-xl font-bold mb-2" :style="darkMode ? 'color: #ffffff;' : 'color: #1a2e1d;'">{{ __('RFID Scanner') }}</h2>
            <p class="text-sm text-center mb-8 font-medium" :style="darkMode ? 'color: #94a3b8;' : 'color: #64748b;'">{{ __('Bring the RFID scanner near the animal\'s ear tag to automatically read the data.') }}</p>
            
            <div class="relative w-full aspect-square border rounded-2xl overflow-hidden flex items-center justify-center shadow-inner"
                 :style="darkMode ? 'background: rgba(0,0,0,0.5); border-color: rgba(255,255,255,0.05);' : 'background: #f8fbf9; border-color: #e2e8f0;'">
                {{-- Radar animation --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-16 h-16 border-2 rounded-full animate-[ping_2s_cubic-bezier(0,0,0.2,1)_infinite]"
                         :style="darkMode ? 'border-color: rgba(16,185,129,0.4);' : 'border-color: rgba(22,163,74,0.4);'"></div>
                    <div class="absolute w-32 h-32 border-2 rounded-full animate-[ping_2.5s_cubic-bezier(0,0,0.2,1)_infinite]"
                         :style="darkMode ? 'border-color: rgba(16,185,129,0.2);' : 'border-color: rgba(22,163,74,0.2);'"></div>
                    <div class="absolute w-48 h-48 border-2 rounded-full animate-[ping_3s_cubic-bezier(0,0,0.2,1)_infinite]"
                         :style="darkMode ? 'border-color: rgba(16,185,129,0.05);' : 'border-color: rgba(22,163,74,0.05);'"></div>
                </div>
                <i data-lucide="smartphone-nfc" class="w-10 h-10 relative z-10" :style="darkMode ? 'color: #34d399;' : 'color: #16a34a;'"></i>
                
                {{-- Hidden input to capture RFID keyboard wedge input --}}
                <input type="text" id="rfid-input" class="opacity-0 absolute inset-0 w-full h-full cursor-default" x-effect="if(scanRfidModal) { setTimeout(() => document.getElementById('rfid-input').focus(), 100) }" @keydown.enter="alert('RFID Tag Scanned: ' + $event.target.value); scanRfidModal = false; $event.target.value = '';">
            </div>
            <p class="text-xs font-bold uppercase tracking-widest mt-6 animate-pulse"
               :style="darkMode ? 'color: rgba(16,185,129,0.7);' : 'color: #16a34a;'">{{ __('Ready to scan...') }}</p>
        </div>
    </div>
</div>

{{-- QR Code Scanner Library --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript" defer></script>
<style>
    /* Force dark theme for the scanner injected UI */
    #qr-reader { border: none !important; }
    #qr-reader__dashboard_section_csr button {
        background: #2563eb !important;
        color: white !important;
        border-radius: 0.5rem !important;
        padding: 0.5rem 1rem !important;
        border: none !important;
        margin-top: 10px;
        cursor: pointer;
    }
    #qr-reader__dashboard_section_swaplink {
        color: #60a5fa !important;
        text-decoration: none !important;
    }
</style>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('farmMap', () => ({
            zoom: 0.8,
            panX: 0,
            panY: 0,
            isDragging: false,
            startX: 0,
            startY: 0,
            
            zoomIn() { this.zoom = Math.min(this.zoom + 0.2, 3); },
            zoomOut() { this.zoom = Math.max(this.zoom - 0.2, 0.4); },
            reset() { this.zoom = 0.8; this.panX = 0; this.panY = 0; },
            
            startDrag(e) {
                this.isDragging = true;
                this.startX = e.clientX - this.panX;
                this.startY = e.clientY - this.panY;
            },
            doDrag(e) {
                if(!this.isDragging) return;
                this.panX = e.clientX - this.startX;
                this.panY = e.clientY - this.startY;
            },
            stopDrag() {
                this.isDragging = false;
            }
        }));
    });
</script>
@endsection
