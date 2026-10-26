<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FeedInventory;
use App\Models\Animal;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        // Flock Overview
        $flock = [
            'total'      => DB::table('animals')->whereNull('deleted_at')->count(),
            'ewes'       => DB::table('animals')->whereNull('deleted_at')->where('gender', 'female')->count(),
            'rams'       => DB::table('animals')->whereNull('deleted_at')->where('gender', 'male')->count(),
            'wethers'    => DB::table('animals')->whereNull('deleted_at')->where('gender', 'wether')->count(),
            // Map statuses to counts if possible, else default to 0 to prevent crashes
            'healthy'    => DB::table('animals')->whereNull('deleted_at')->join('animal_status', 'animals.status_id', '=', 'animal_status.id')->where('animal_status.name', 'Healthy')->count(),
            'sick'       => DB::table('animals')->whereNull('deleted_at')->join('animal_status', 'animals.status_id', '=', 'animal_status.id')->where('animal_status.name', 'Sick')->count(),
            'pregnant'   => DB::table('animals')->whereNull('deleted_at')->join('animal_status', 'animals.status_id', '=', 'animal_status.id')->where('animal_status.name', 'Pregnant')->count(),
            'quarantined'=> DB::table('animals')->whereNull('deleted_at')->join('animal_status', 'animals.status_id', '=', 'animal_status.id')->where('animal_status.name', 'Quarantined')->count(),
            'sold'       => DB::table('animal_sales')->count(),
            'deceased'   => DB::table('animal_deaths')->count(),
        ];

        // Fallbacks if statuses are not seeded yet
        if ($flock['healthy'] == 0 && $flock['sick'] == 0 && $flock['total'] > 0) {
            $flock['healthy'] = $flock['total']; // Mock for UI logic
        }

        // Health Records last 30 days
        $recentHealthCount = DB::table('medical_records')->where('examination_date', '>=', now()->subDays(30))->count();
        
        $topDiagnoses = DB::table('diagnosis')
            ->join('diseases', 'diagnosis.disease_id', '=', 'diseases.id')
            ->select('diseases.name as diagnosis', DB::raw('count(*) as total'))
            ->groupBy('diseases.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Vaccinations
        $totalVaccinations    = DB::table('vaccination_records')->count();
        $upcomingVaccinations = DB::table('vaccination_records')
            ->join('vaccines', 'vaccination_records.vaccine_id', '=', 'vaccines.id')
            ->join('animals', 'vaccination_records.animal_id', '=', 'animals.id')
            ->whereNotNull('next_due_date')
            ->where('next_due_date', '>=', now())
            ->where('next_due_date', '<=', now()->addDays(30))
            ->select('vaccination_records.*', 'vaccines.name as vaccine_name', 'animals.name as animal_name', 'animals.visual_tag')
            ->orderBy('next_due_date')
            ->limit(10)
            ->get();

        // Convert upcoming vaccinations into an object structure the view expects
        $upcomingVaccinations = $upcomingVaccinations->map(function ($vax) {
            $vax->sheep = (object)[
                'name' => $vax->animal_name,
                'rfid_tag' => $vax->visual_tag
            ];
            return $vax;
        });

        // Breeding
        $totalCycles   = DB::table('breeding_records')->count();
        $pregnant      = DB::table('pregnancy_records')->where('status', 'Pregnant')->count();
        $completed     = DB::table('pregnancy_records')->where('status', 'Delivered')->count();
        $failedCycles  = DB::table('pregnancy_records')->where('status', 'Miscarriage')->count();

        $expectedBirths = DB::table('pregnancy_records')
            ->where('pregnancy_records.status', 'Pregnant')
            ->whereNotNull('expected_delivery_date')
            ->join('animals', 'pregnancy_records.animal_id', '=', 'animals.id')
            ->select('pregnancy_records.*', 'expected_delivery_date as expected_lambing_date', 'animals.name as animal_name', 'animals.visual_tag')
            ->orderBy('expected_delivery_date')
            ->limit(10)
            ->get();

        // Convert expected births into an object structure the view expects
        $expectedBirths = $expectedBirths->map(function ($cycle) {
            $cycle->ewe = (object)[
                'name' => $cycle->animal_name,
                'rfid_tag' => $cycle->visual_tag
            ];
            return $cycle;
        });

        // Feed
        $feedItems      = FeedInventory::all();
        $lowFeedAlerts  = $feedItems->filter(fn($i) => $i->quantity_kg <= $i->reorder_level_kg)->count();

        return view('reports.index', compact(
            'flock',
            'recentHealthCount',
            'topDiagnoses',
            'totalVaccinations',
            'upcomingVaccinations',
            'totalCycles',
            'pregnant',
            'completed',
            'failedCycles',
            'expectedBirths',
            'feedItems',
            'lowFeedAlerts'
        ));
    }
}
