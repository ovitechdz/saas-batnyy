<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $farm_id = auth()->user()->farm_id ?? 1;

        // Core Livestock Stats
        $stats = [
            'total_sheep'   => 1204,
            'total_ewes'    => 850,
            'total_rams'    => 354,
            'healthy_sheep' => 1180,
            'sick_sheep'    => 24,
            'pregnant_sheep'=> 145,
            'mortality_risk'=> '1.2%',
            'growth_index'  => '+4.5%',
            'feed_efficiency'=> '89%',
            'breeding_success'=> '92%'
        ];

        $healthPercentage = round(($stats['healthy_sheep'] / max($stats['total_sheep'], 1)) * 100, 1);
        $lowFeedAlerts = 2;

        // Financial Data
        $financials = [
            'revenue' => 145200,
            'revenue_trend' => '+18.5%',
            'expenses' => 42400,
            'expenses_trend' => '-5.4%',
            'profit' => 102800,
            'profit_trend' => '+25.2%',
            'cash_flow' => 85000,
            'orders' => 342,
            'marketplace_visitors' => 5400,
            'conversion_rate' => '4.2%',
        ];

        // IoT, Tech & Resource Data
        $iotData = [
            'rfid_connected' => 1204,
            'rfid_trend' => '+12 Today',
            'solar_production' => '145.2 kW',
            'battery_level' => '92%',
            'power_consumption' => '85.4 kW',
            'energy_savings' => '$450',
            'water_usage' => '4,240 L',
            'offline_sync' => 'All Synced',
            'storage_usage' => '45%',
            'reader_status' => 'Online',
            'gate_activity' => 450,
        ];

        // Advanced AI Insights (Multiple predictions)
        $aiInsights = [
            'health_score' => 98,
            'disease_risk' => 'Low Risk (1.2%)',
            'feed_recommendation' => 'Increase protein by 5% in Sector B.',
            'confidence' => '98.5%',
            'predictions' => [
                [
                    'type' => 'Disease',
                    'priority' => 'high',
                    'text' => 'High risk of foot rot in Barn 3 due to humidity.',
                    'action' => 'Schedule hoof trimming and dry bedding.',
                    'impact' => 'Prevents 15 potential infections.',
                    'confidence' => 88
                ],
                [
                    'type' => 'Weight',
                    'priority' => 'medium',
                    'text' => 'Lamb group Alpha slightly underweight.',
                    'action' => 'Switch to Starter Formula X2.',
                    'impact' => '+2.5kg avg weight gain in 14 days.',
                    'confidence' => 92
                ],
                [
                    'type' => 'Breeding',
                    'priority' => 'low',
                    'text' => '12 Ewes entering optimal fertility window.',
                    'action' => 'Introduce Ram 042.',
                    'impact' => '85% conception probability.',
                    'confidence' => 95
                ],
                [
                    'type' => 'Financial',
                    'priority' => 'medium',
                    'text' => 'Feed prices predicted to rise by 8% next month.',
                    'action' => 'Bulk order Alfalfa hay now.',
                    'impact' => 'Saves ~$1,200.',
                    'confidence' => 85
                ]
            ]
        ];

        // Daily Events & Weather (Mock)
        $dailyEvents = [
            'births_today' => 4,
            'deaths_today' => 0,
            'vaccinations_due' => 45,
            'employees_present' => 12,
        ];

        $weather = [
            'temp' => 24,
            'humidity' => '45%',
            'rain_prob' => '10%',
            'wind' => '12 km/h',
            'uv' => 'High (7)',
            'sunrise' => '06:24 AM',
            'sunset' => '19:45 PM',
        ];

        // Live Alerts Center
        $alerts = [
            ['type' => 'critical', 'message' => 'Water pressure low in Barn 2', 'time' => '10m ago'],
            ['type' => 'warning', 'message' => 'Feed silo 4 below 20%', 'time' => '1h ago'],
            ['type' => 'info', 'message' => 'System backup completed', 'time' => '3h ago'],
            ['type' => 'medical', 'message' => 'Vaccination batch #452 due tomorrow', 'time' => '5h ago'],
        ];

        // Mock Tasks
        $tasks = [
            ['title' => 'Morning Feeding', 'status' => 'completed', 'time' => '07:00 AM', 'priority' => 'high', 'progress' => 100],
            ['title' => 'Inspect Barn B', 'status' => 'pending', 'time' => '10:30 AM', 'priority' => 'medium', 'progress' => 45],
            ['title' => 'Vaccinate newborn lambs', 'status' => 'pending', 'time' => '02:00 PM', 'priority' => 'high', 'progress' => 0],
            ['title' => 'Solar panel maintenance', 'status' => 'upcoming', 'time' => '04:00 PM', 'priority' => 'low', 'progress' => 0],
            ['title' => 'Sync offline data', 'status' => 'upcoming', 'time' => '06:00 PM', 'priority' => 'medium', 'progress' => 0],
        ];

        // Comprehensive Mock Chart Data for Chart.js
        $charts = [
            'labels_monthly' => [__('Jan'), __('Feb'), __('Mar'), __('Apr'), __('May'), __('Jun'), __('Jul'), __('Aug'), __('Sep'), __('Oct'), __('Nov'), __('Dec')],
            'weight_curve' => [45, 48, 50, 52, 55, 59, 62, 65, 68, 70, 72, 75],
            'birth_rate' => [12, 15, 45, 10, 8, 12, 14, 50, 20, 15, 12, 10],
            'revenue_monthly' => [12000, 15000, 14000, 22000, 25000, 32000, 45200, 48000, 42000, 50000, 65000, 85000],
            'expenses_monthly' => [8000, 9000, 8500, 12000, 15000, 18000, 20000, 22000, 21000, 25000, 28000, 30000],
            'solar_daily' => [12, 15, 18, 22, 25, 24, 20, 18, 15, 10, 8, 5], // Time of day
            'water_daily' => [100, 150, 200, 300, 400, 450, 400, 350, 250, 150, 100, 50],
        ];

        // Farm Map Markers (Mock for Leaflet)
        $mapMarkers = [
            ['lat' => 34.0522, 'lng' => -118.2437, 'title' => 'Main Barn', 'type' => 'barn'],
            ['lat' => 34.0530, 'lng' => -118.2450, 'title' => 'Solar Array 1', 'type' => 'solar'],
            ['lat' => 34.0515, 'lng' => -118.2420, 'title' => 'Water Tank Alpha', 'type' => 'water'],
            ['lat' => 34.0540, 'lng' => -118.2440, 'title' => 'RFID Gate North', 'type' => 'rfid'],
            ['lat' => 34.0500, 'lng' => -118.2460, 'title' => 'Veterinary Room', 'type' => 'vet'],
        ];

        // Real Animals for table overview (Mocked to prevent DB crash)
        $animalsOverview = collect([
            (object)[
                'id' => '03ce97a1-edb7-43c6-ab9c-b86a11874bf9',
                'name' => 'Ewe 101',
                'gender' => 'F',
                'breed' => 'Merino',
                'rfid_tag' => 'RFID-88219',
                'status' => 'healthy',
                'weight_kg' => 65.2,
                'age' => '2 yrs',
                'ai_score' => 98,
                'last_scan' => '10m ago',
                'location' => 'Barn A',
                'photo' => null
            ],
            (object)[
                'id' => '13328473-80b3-46d4-97b6-4204fb161179',
                'name' => 'Ram 042',
                'gender' => 'M',
                'breed' => 'Suffolk',
                'rfid_tag' => 'RFID-99211',
                'status' => 'sick',
                'weight_kg' => 88.5,
                'age' => '3 yrs',
                'ai_score' => 45,
                'last_scan' => '1h ago',
                'location' => 'Vet Room',
                'photo' => null
            ],
            (object)[
                'id' => '180a571d-48ec-4042-937c-6d90e35fdc03',
                'name' => 'Lamb Alpha',
                'gender' => 'M',
                'breed' => 'Dorper',
                'rfid_tag' => 'RFID-77112',
                'status' => 'healthy',
                'weight_kg' => 15.4,
                'age' => '2 mo',
                'ai_score' => 95,
                'last_scan' => '5m ago',
                'location' => 'Barn B',
                'photo' => null
            ],
            (object)[
                'id' => '1bae0fcb-7139-42af-8941-ed423ec73b8b',
                'name' => 'Ewe 204',
                'gender' => 'F',
                'breed' => 'Merino',
                'rfid_tag' => 'RFID-33419',
                'status' => 'pregnant',
                'weight_kg' => 72.1,
                'age' => '4 yrs',
                'ai_score' => 92,
                'last_scan' => '2h ago',
                'location' => 'Barn A',
                'photo' => null
            ]
        ]);

        return view('dashboard', compact(
            'stats', 
            'healthPercentage',
            'lowFeedAlerts', 
            'financials',
            'iotData',
            'aiInsights',
            'dailyEvents',
            'weather',
            'alerts',
            'tasks',
            'charts',
            'mapMarkers',
            'animalsOverview'
        ));
    }
}
