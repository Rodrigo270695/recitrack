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
    }
}
