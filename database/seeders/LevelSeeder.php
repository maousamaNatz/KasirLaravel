<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        Level::create(['nama_level' => 'Admin']);
        Level::create(['nama_level' => 'Kasir']);
        Level::create(['nama_level' => 'koki']);
    }
}
