<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Animal;

class HealthDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'quarantine_count' => DB::table('quarantine_records')->where('status', 'active')->count(),
            'upcoming_vaccines' => DB::table('vaccination_records')
                                        ->where('next_due_date', '>=', now())
                                        ->where('next_due_date', '<=', now()->addDays(30))
                                        ->count(),
            'low_stock_medicines' => DB::table('medicine_stock')->where('quantity', '<=', 5)->count(),
            'critical_cases' => DB::table('diagnosis')->where('severity', 'Severe')->distinct('medical_record_id')->count('medical_record_id'),
        ];
        $animals = Animal::orderBy('created_at', 'desc')->get();
        $recentRecords = \App\Models\HealthRecord::with('animal')
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();

        return view('health.dashboard', compact('stats', 'animals', 'recentRecords'));
    }
}
