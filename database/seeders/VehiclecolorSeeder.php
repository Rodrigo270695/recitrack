<?php

namespace Database\Seeders;

use App\Models\Vehiclecolor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehiclecolorSeeder extends Seeder
{

    public function run(): void
    {
        $colores = [
            "Rojo", "Azul", "Verde", "Negro", "Blanco", "Gris", "Amarillo", "Naranja", "Violeta", "Magenta",
            "Cian", "Turquesa", "Plata", "Oro", "Bronce", "Coral", "Salmon", "Chocolate", "Marfil", "Lavanda"
        ];

        foreach ($colores as $color) {
            Vehiclecolor::create([
                "name" => $color
            ]);
        }

    }
}
