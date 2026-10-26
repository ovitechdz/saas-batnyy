@extends('layouts.app')
@section('title', __('Live IoT & RFID Dashboard'))

@section('content')
<div class="p-4 sm:p-8 space-y-6 max-w-7xl mx-auto" x-data="iotDashboard()">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                <i data-lucide="radio" class="w-8 h-8 text-emerald-500"></i>
                {{ __("IoT & Hardware Status") }}
                <span class="relative flex h-3 w-3 mt-1">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">{{ __("Real-time monitoring of Smart Gates, RFID Readers, and Environmental Sensors.") }}</p>
        </div>
        <div class="flex gap-2">
            <button @click="simulateRfidScan" class="btn-secondary text-xs flex items-center gap-2" title="{{ __('Developer tool to simulate hardware') }}">
                <i data-lucide="zap" class="w-4 h-4 text-amber-500"></i> {{ __("Simulate Scan") }}
            </button>
            <button @click="simulateTelemetry" class="btn-secondary text-xs flex items-center gap-2" title="{{ __('Developer tool to simulate telemetry') }}">
                <i data-lucide="thermometer" class="w-4 h-4 text-blue-500"></i> {{ __("Simulate Telemetry") }}
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="glass-panel p-5 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 text-emerald-500/10 group-hover:text-emerald-500/20 transition-colors">
                <i data-lucide="activity" class="w-32 h-32"></i>
            </div>
            <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider">{{ __("Online Devices") }}</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white flex items-center gap-2" x-text="stats.online">
                {{ $stats['online_devices'] }}
            </div>
        </div>
        <div class="glass-panel p-5 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 text-rose-500/10 group-hover:text-rose-500/20 transition-colors">
                <i data-lucide="wifi-off" class="w-32 h-32"></i>
            </div>
            <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider">{{ __("Offline / Error") }}</div>
            <div class="text-3xl font-black text-rose-600 dark:text-rose-400 flex items-center gap-2" x-text="stats.offline">
                {{ $stats['offline_devices'] }}
            </div>
        </div>
        <div class="glass-panel p-5 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 text-blue-500/10 group-hover:text-blue-500/20 transition-colors">
                <i data-lucide="scan-line" class="w-32 h-32"></i>
            </div>
            <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider">{{ __("Scans Today") }}</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white flex items-center gap-2" x-text="stats.scans">
                2,451
            </div>
        </div>
        <div class="glass-panel p-5 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 text-amber-500/10 group-hover:text-amber-500/20 transition-colors">
                <i data-lucide="siren" class="w-32 h-32"></i>
            </div>
            <div class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider">{{ __("Active Alerts") }}</div>
            <div class="text-3xl font-black text-amber-600 dark:text-amber-400 flex items-center gap-2" x-text="stats.alerts">
                0
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Live Map / Grid -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Smart Gates & RFID Readers -->
            <div class="glass-panel p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-800 pb-2 flex items-center gap-2">
                    <i data-lucide="door-open" class="w-5 h-5 text-emerald-500"></i> {{ __("Smart Gates & Checkpoints") }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($rfidDevices as $device)
                        <div class="glass-panel-sm p-4 flex justify-between items-center transition-all hover:shadow-md">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $device->status === 'online' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-rose-100 text-rose-600 dark:bg-rose-900/50 dark:text-rose-400' }}">
                                    <i data-lucide="{{ $device->status === 'online' ? 'radio-tower' : 'wifi-off' }}" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white">{{ $device->name }}</h4>
                                    <div class="text-xs text-slate-500 font-mono">{{ $device->mac_address }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold {{ $device->status === 'online' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400' }}">
                                    @if($device->status === 'online') <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> @endif
                                    {{ strtoupper($device->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-6 text-slate-500">
                            {{ __("No RFID readers registered.") }}
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Environmental Sensors -->
            <div class="glass-panel p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-800 pb-2 flex items-center gap-2">
                    <i data-lucide="cloud-rain" class="w-5 h-5 text-blue-500"></i> {{ __("Environmental Sensors") }}
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <!-- Dynamic rendering would go here. For UI presentation, we mock the layout -->
                    <div class="glass-panel-sm p-4 text-center">
                        <i data-lucide="thermometer" class="w-8 h-8 mx-auto text-amber-500 mb-2"></i>
                        <div class="text-xs text-slate-500 font-semibold mb-1">{{ __("Barn A Temp") }}</div>
                        <div class="text-xl font-black text-slate-900 dark:text-white" x-text="liveSensors.temp + ' °C'">22.4 °C</div>
                    </div>
                    <div class="glass-panel-sm p-4 text-center">
                        <i data-lucide="droplets" class="w-8 h-8 mx-auto text-blue-500 mb-2"></i>
                        <div class="text-xs text-slate-500 font-semibold mb-1">{{ __("Humidity") }}</div>
                        <div class="text-xl font-black text-slate-900 dark:text-white" x-text="liveSensors.humidity + ' %'">45 %</div>
                    </div>
                    <div class="glass-panel-sm p-4 text-center">
                        <i data-lucide="wind" class="w-8 h-8 mx-auto text-slate-400 mb-2"></i>
                        <div class="text-xs text-slate-500 font-semibold mb-1">{{ __("Ammonia") }}</div>
                        <div class="text-xl font-black text-emerald-600 dark:text-emerald-400" x-text="liveSensors.ammonia + ' ppm'">12 ppm</div>
                    </div>
                    <div class="glass-panel-sm p-4 text-center relative overflow-hidden">
                        <div class="absolute bottom-0 left-0 right-0 bg-blue-500/20 transition-all duration-1000" :style="'height: ' + liveSensors.waterLevel + '%'"></div>
                        <i data-lucide="glass-water" class="w-8 h-8 mx-auto text-blue-500 mb-2 relative z-10"></i>
                        <div class="text-xs text-slate-500 font-semibold mb-1 relative z-10">{{ __("Trough Level") }}</div>
                        <div class="text-xl font-black text-slate-900 dark:text-white relative z-10" x-text="liveSensors.waterLevel + ' %'">80 %</div>
                    </div>
                </div>
                
                <!-- Sensor Live Chart -->
                <div class="mt-6 h-48 w-full relative">
                    <canvas id="sensorChart"></canvas>
                </div>
            </div>

        </div>

        <!-- Live Feed Sidebar -->
        <div class="glass-panel flex flex-col h-full max-h-[800px]">
            <div class="p-4 border-b border-slate-200/50 rounded-t-xl">
                <h3 class="font-bold text-slate-900 dark:text-white flex items-center justify-between">
                    <span class="flex items-center gap-2"><i data-lucide="list" class="w-5 h-5 text-emerald-500"></i> {{ __("Live Scan Feed") }}</span>
                    <span class="flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                </h3>
            </div>
            
            <!-- Feed Logs -->
            <div class="p-4 flex-1 overflow-y-auto space-y-3 font-mono text-sm" id="logContainer">
                <template x-for="log in logs" :key="log.id">
                    <div class="p-3 rounded-lg shadow-sm animate-fade-in-down glass-panel-sm" :class="log.type === 'error' ? 'border-rose-500/50 bg-rose-50' : ''">
                        <div class="flex justify-between items-center mb-1 text-xs text-slate-500">
                            <span x-text="log.time"></span>
                            <span x-text="log.reader" class="font-semibold text-slate-700 dark:text-slate-300"></span>
                        </div>
                        <div class="font-bold" :class="log.type === 'error' ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400'">
                            <i data-lucide="tag" class="w-3 h-3 inline"></i> <span x-text="log.epc"></span>
                        </div>
                        <div x-show="log.message" class="text-xs mt-1 text-slate-600 dark:text-slate-400" x-text="log.message"></div>
                    </div>
                </template>
                
                <div x-show="logs.length === 0" class="text-center text-slate-500 py-8 italic">
                    {{ __("Waiting for events...") }}
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<style>
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down {
        animation: fadeInDown 0.3s ease-out forwards;
    }
</style>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('iotDashboard', () => ({
            stats: {
                online: {{ $stats['online_devices'] }},
                offline: {{ $stats['offline_devices'] }},
                scans: 2451,
                alerts: 0
            },
            liveSensors: {
                temp: 22.4,
                humidity: 45,
                ammonia: 12,
                waterLevel: 80
            },
            logs: [],
            chart: null,
            init() {
                // Initialize Chart
                const ctx = document.getElementById('sensorChart');
                if(ctx) {
                    this.chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ["{{ __('10m ago') }}", "{{ __('8m ago') }}", "{{ __('6m ago') }}", "{{ __('4m ago') }}", "{{ __('2m ago') }}", "{{ __('Now') }}"],
                            datasets: [{
                                label: "{{ __('Temperature (°C)') }}",
                                data: [21.5, 21.8, 22.1, 22.3, 22.4, 22.4],
                                borderColor: '#F59E0B',
                                tension: 0.4,
                                borderWidth: 2,
                                pointRadius: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } },
                                x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                            }
                        }
                    });
                }
                
                // Add some initial mock logs
                this.addLog("{{ __('Gate A - Entry') }}", 'E200001992010156', 'success', "{{ __('Animal registered entry.') }}");
                this.addLog("{{ __('Gate A - Entry') }}", 'E200001992010157', 'success', "{{ __('Animal registered entry.') }}");
            },
            addLog(reader, epc, type, message = null) {
                const now = new Date();
                this.logs.unshift({
                    id: Date.now() + Math.random(),
                    time: now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second:'2-digit' }),
                    reader,
                    epc,
                    type,
                    message
                });
                if(this.logs.length > 50) this.logs.pop();
                
                if(type === 'success') this.stats.scans++;
                if(type === 'error') this.stats.alerts++;
            },
            simulateRfidScan() {
                // Mock an API call to the RFID endpoint
                const mockEpc = 'E200' + Math.floor(100000000000 + Math.random() * 900000000000);
                this.addLog("{{ __('Main Gate Scanner') }}", mockEpc, 'success', "{{ __('Simulated scan received via WebSocket.') }}");
                if (typeof lucide !== 'undefined') lucide.createIcons();
            },
            simulateTelemetry() {
                // Mock telemetry fluctuation
                let currentTemp = parseFloat(this.liveSensors.temp);
                this.liveSensors.temp = parseFloat((currentTemp + (Math.random() > 0.5 ? 0.2 : -0.2)).toFixed(1));
                this.liveSensors.humidity = Math.min(100, Math.max(0, parseInt(this.liveSensors.humidity) + Math.floor(Math.random() * 5) - 2));
                this.liveSensors.waterLevel = Math.max(0, parseInt(this.liveSensors.waterLevel) - 5);
                if(this.liveSensors.waterLevel < 10) this.liveSensors.waterLevel = 100; // Refill
                
                // Update chart
                if(this.chart) {
                    this.chart.data.datasets[0].data.shift();
                    this.chart.data.datasets[0].data.push(this.liveSensors.temp);
                    this.chart.update();
                }
            }
        }));
    });
</script>
@endpush
@endsection
