<?php

namespace Database\Seeders;

use App\Models\Typeuser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsertypeSeeder extends Seeder
{
    public function run(): void
    {
        $tiposDeUsuarios = [
            "administrador",
            "chofer",
            "acompañante"
        ];

        foreach ($tiposDeUsuarios as $tipo) {
            Typeuser::create([
                'name' => $tipo
            ]);
        }
    }
}
