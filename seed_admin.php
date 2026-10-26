<?php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$farmId = Str::uuid();
DB::table('farms')->insert([
    'id' => $farmId, 
    'name' => 'Main Ovitech Farm', 
    'created_at' => now(), 
    'updated_at' => now()
]); 

DB::table('users')->insert([
    'id' => Str::uuid(), 
    'farm_id' => $farmId, 
    'name' => 'Admin', 
    'email' => 'admin@ovitech.com', 
    'password' => Hash::make('password'), 
    'is_super_admin' => true, 
    'created_at' => now(), 
    'updated_at' => now()
]);

echo "Admin user created successfully.";
