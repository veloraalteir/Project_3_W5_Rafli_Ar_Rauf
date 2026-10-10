<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'rafli',
            'password' => 'password123',
            'nama_lengkap' => 'Rafli Ar Rauf',
        ]);

        User::create([
            'username' => 'admin',
            'password' => 'admin123',
            'nama_lengkap' => 'Administrator',
        ]);
    }
}
