<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Property;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Property::create([
            'name' => 'Villa Sunshine',
            'description' => 'Une villa avec vue sur la mer.',
            'price_per_night' => 150.00,
        ]);
        Property::create([
            'name' => 'Cozy Appartement',
            'description' => 'Un appartement meublé avec une ambiance tropical.',
            'price_per_night' => 100.00,
        ]);
    }
}
