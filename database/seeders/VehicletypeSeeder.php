<?php

namespace Database\Seeders;

use App\Models\Vehicletype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicletypeSeeder extends Seeder
{
    public function run(): void
    {
    $tiposDeVehiculos = [
        "Camión Frontal de Carga",
        "Camión de Carga Lateral",
        "Camión de Carga Trasera",
        "Camión de Reciclaje",
        "Camión Roll-Off",
        "Camión Grúa"
    ];

    foreach ($tiposDeVehiculos as $tipo) {
        Vehicletype::create([
            'name' => $tipo
        ]);
    }
    }
}
