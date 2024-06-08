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
            "name"=> "admin",
            "email"=> "rodrigo_06_27@hotmail.com",
            "password"=> Hash::make("admin")
        ]);
        $admin->assignRole("admin");
        $admin = User::create([
            "name"=> "Edinson Palacios",
            "email"=> "jardani1103@gmail.com",
            "password"=> Hash::make("jardani1103")
        ]);
        $admin->assignRole("admin");
        $admin = User::create([
            "name"=> "Andres davila",
            "email"=> "jdn3287@gmail.com",
            "password"=> Hash::make("jdn3287")
        ]);
        $admin->assignRole("admin");
        $admin = User::create([
            "name"=> "Galvany",
            "email"=> "galvany@cosome.edu.pe",
            "password"=> Hash::make("12345678")
        ]);
        $admin->assignRole("admin");
    }
}
