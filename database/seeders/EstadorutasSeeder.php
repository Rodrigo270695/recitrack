<?php

namespace Database\Seeders;

use App\Models\Statusroute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadorutasSeeder extends Seeder
{

    public function run(): void
    {
        Statusroute::create([
            'name' => 'Programado',
            'description' => 'Ruta programada',
        ]);
        Statusroute::create([
            'name' => 'En Curso',
            'description' => 'Ruta en curso',
        ]);
        Statusroute::create([
            'name' => 'Averiado',
            'description' => 'Ruta averiada',
        ]);
        Statusroute::create([
            'name' => 'Cancelado',
            'description' => 'Ruta cancelada',
        ]);
        Statusroute::create([
            'name' => 'Finalizado',
            'description' => 'Ruta finalizada',
        ]);
    }
}
