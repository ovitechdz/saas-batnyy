@props([
    'id' => 'chart-' . Str::random(8),
    'type' => 'area', // area, line, bar
    'series' => '[]',
    'categories' => '[]',
    'height' => '300',
    'color' => '#10b981'
])

<div class="relative w-full" style="min-height: {{ $height }}px">
    <div id="{{ $id }}" class="w-full h-full"></div>
</div>

<!-- Load ApexCharts if not loaded -->
@once
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endonce

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var options = {
            series: {!! $series !!},
            chart: {
                type: '{{ $type }}',
                height: {{ $height }},
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            colors: ['{{ $color }}'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            xaxis: {
                categories: {!! $categories !!},
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#94a3b8' }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#94a3b8' }
                }
            },
            grid: {
                borderColor: 'rgba(148, 163, 184, 0.1)',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            tooltip: {
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                y: { formatter: function (val) { return val } }
            }
        };

        var chart = new ApexCharts(document.querySelector("#{{ $id }}"), options);
        chart.render();
    });
</script>
