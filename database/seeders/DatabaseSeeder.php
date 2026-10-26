<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\Farm;
use App\Models\Sheep;
use App\Models\FeedInventory;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create an admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@ovitech.com'],
            [
                'name' => 'Farm Admin',
                'password' => bcrypt('password'),
            ]
        );

        // Create a farm
        $farm = Farm::firstOrCreate(
            ['name' => 'Main Ovitech Farm'],
            [
                'location' => 'Riyadh, SA',
                'subscription_tier' => 'enterprise',
            ]
        );

        // Seed Feed Inventory
        FeedInventory::firstOrCreate(
            ['farm_id' => $farm->id, 'feed_type' => 'Alfalfa Hay'],
            [
                'quantity_kg' => 4250,
                'reorder_level_kg' => 1000,
            ]
        );

        // Seed some Sheep
        if (Sheep::count() === 0) {
            for ($i = 1; $i <= 10; $i++) {
                Sheep::create([
                    'farm_id' => $farm->id,
                    'rfid_tag' => 'RFID' . str_pad($i, 5, '0', STR_PAD_LEFT),
                    'name' => 'Sheep ' . $i,
                    'gender' => $i % 2 == 0 ? 'ewe' : 'ram',
                    'birth_date' => now()->subYears(rand(1, 4)),
                    'status' => $i === 3 ? 'sick' : ($i === 4 ? 'pregnant' : 'healthy'),
                ]);
            }
        }
    }
}
