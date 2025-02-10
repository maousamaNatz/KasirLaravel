<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LevelSeeder::class,  // Seeder untuk tabel levels
            UserSeeder::class,   // Seeder untuk tabel users
            MakananSeeder::class // Seeder untuk tabel makanan
        ]);
    }
}
