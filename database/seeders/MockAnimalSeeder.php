<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MockAnimalSeeder extends Seeder
{
    public function run(): void
    {
        $farm_id = \App\Models\User::first()->farm_id ?? 'e9ed396c-f1b7-49ae-8fca-507640ca6a27';

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        for ($i = 1; $i <= 10; $i++) {
            DB::table('animals')->insert([
                'id' => (string) Str::uuid(),
                'farm_id' => $farm_id,
                'internal_id' => 'EWE-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'visual_tag' => 'TAG-E' . $i,
                'gender' => 'F',
                'status_id' => 1,
                'species_id' => 1,
                'breed_id' => 1,
                'category_id' => 1
            ]);
        }

        for ($i = 1; $i <= 5; $i++) {
            DB::table('animals')->insert([
                'id' => (string) Str::uuid(),
                'farm_id' => $farm_id,
                'internal_id' => 'RAM-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'visual_tag' => 'TAG-R' . $i,
                'gender' => 'M',
                'status_id' => 1,
                'species_id' => 1,
                'breed_id' => 1,
                'category_id' => 1
            ]);
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
