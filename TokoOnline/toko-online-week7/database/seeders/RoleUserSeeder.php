<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id_user' => 'USRADMIN001',
                'nama_lengkap' => 'Admin Demo',
                'email' => 'admin@example.com',
                'username' => 'admin',
                'password' => 'Password123!',
                'no_hp' => '081234567890',
                'alamat' => 'Bandung',
                'role' => 'admin',
            ],
            [
                'id_user' => 'USRCUSTOMER1',
                'nama_lengkap' => 'Customer Demo',
                'email' => 'customer@example.com',
                'username' => 'customer',
                'password' => 'Password123!',
                'no_hp' => '081234567891',
                'alamat' => 'Bandung',
                'role' => 'customer',
            ],
            [
                'id_user' => 'USRSTAFF001',
                'nama_lengkap' => 'Staff Demo',
                'email' => 'staff@example.com',
                'username' => 'staff',
                'password' => 'Password123!',
                'no_hp' => '081234567892',
                'alamat' => 'Bandung',
                'role' => 'staff',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['username' => $data['username']],
                $data
            );
        }
    }
}
