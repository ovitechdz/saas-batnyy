<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DictionarySeeder extends Seeder
{
    public function run(): void
    {
        $sheepId = Str::uuid();
        $cattleId = Str::uuid();
        $goatId = Str::uuid();

        DB::table('animal_species')->insertOrIgnore([
            ['id' => $sheepId, 'name' => 'Sheep'],
            ['id' => $cattleId, 'name' => 'Cattle'],
            ['id' => $goatId, 'name' => 'Goat'],
        ]);

        DB::table('animal_breeds')->insertOrIgnore([
            ['id' => Str::uuid(), 'species_id' => $sheepId, 'name' => 'Awassi'],
            ['id' => Str::uuid(), 'species_id' => $sheepId, 'name' => 'Najdi'],
            ['id' => Str::uuid(), 'species_id' => $cattleId, 'name' => 'Holstein'],
            ['id' => Str::uuid(), 'species_id' => $cattleId, 'name' => 'Angus'],
            ['id' => Str::uuid(), 'species_id' => $goatId, 'name' => 'Boer'],
        ]);

        $farm = \App\Models\Farm::first();
        $farmId = $farm ? $farm->id : null;

        if ($farmId) {
            DB::table('animal_categories')->insertOrIgnore([
                ['id' => Str::uuid(), 'farm_id' => $farmId, 'name' => 'Breeder'],
                ['id' => Str::uuid(), 'farm_id' => $farmId, 'name' => 'Fattening'],
                ['id' => Str::uuid(), 'farm_id' => $farmId, 'name' => 'Dairy'],
                ['id' => Str::uuid(), 'farm_id' => $farmId, 'name' => 'Newborn'],
            ]);
        }

        DB::table('animal_status')->insertOrIgnore([
            ['id' => Str::uuid(), 'name' => 'Active'],
            ['id' => Str::uuid(), 'name' => 'Sick'],
            ['id' => Str::uuid(), 'name' => 'Quarantine'],
            ['id' => Str::uuid(), 'name' => 'Sold'],
            ['id' => Str::uuid(), 'name' => 'Deceased'],
        ]);
    }
}
