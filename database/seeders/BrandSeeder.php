<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {

        $marcas = [
            "Hino", "CAT", "Mercedes Benz", "Volvo", "Scania",
            "Isizu", "Heil", "Kenworth", "Freightliner",
            "Peterbilt", "Mack Trucks"
        ];

        foreach ($marcas as $marca) {
            Brand::create([
                "name" => $marca
            ]);
        }

    }
}
