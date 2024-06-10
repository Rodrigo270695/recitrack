<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsertypeSeeder::class,
            UserSeeder::class,
            BrandSeeder::class,
            BrandmodelSeeder::class,
            VehiclecolorSeeder::class,
            VehicletypeSeeder::class,
        ]);
    }
}
