<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'nama_user' => 'Administrator',
            'id_level' => 1,
        ]);

        User::create([
            'username' => 'kasir1',
            'password' => Hash::make('password'),
            'nama_user' => 'Kasir Utama',
            'id_level' => 2,
        ]);
        User::create([
            'username' => 'koki1',
            'password' => Hash::make('password'),
            'nama_user' => 'Koki Utama',
            'id_level' => 3,
        ]);

    }
}
