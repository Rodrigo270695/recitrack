<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $admin = User::create([
            "name"=> "Rodrigo",
            "last_name"=> "Granja",
            "dni"=> "77344506",
            'license' => '12345688',
            "email"=> "rodrigo_06_27@hotmail.com",
            "password"=> Hash::make("admin"),
            "typeuser_id" => 1
        ]);
        $admin = User::create([
            "name"=> "Edinson",
            "last_name"=> "Palacios",
            "dni"=> "12345678",
            'license' => '12345677',
            "email"=> "jardani1103@gmail.com",
            "password"=> Hash::make("jardani1103"),
            "typeuser_id" => 1
        ]);
        $admin = User::create([
            "name"=> "Andres",
            "last_name"=> "Davila",
            "dni"=> "87654321",
            'license' => '12345676',
            "email"=> "jdn3287@gmail.com",
            "password"=> Hash::make("jdn3287"),
            "typeuser_id" => 1
        ]);
        $admin = User::create([
            "name"=> "Galvany",
            "last_name"=> "Bladimir",
            "dni"=> "23344567",
            'license' => '12345675',
            "email"=> "galvany@cosome.edu.pe",
            "password"=> Hash::make("12345678"),
            "typeuser_id" => 1
        ]);
    }
}
