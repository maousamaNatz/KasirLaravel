<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        Level::create(['nama_level' => 'admin']);
        Level::create(['nama_level' => 'kasir']);
        Level::create(['nama_level' => 'koki']);
    }
}
