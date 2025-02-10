<?php

namespace Database\Seeders;

use App\Models\Makanan;
use Illuminate\Database\Seeder;

class MakananSeeder extends Seeder
{
    public function run(): void
    {
        Makanan::create([
            'nama_masakan' => 'Nasi Goreng',
            'harga' => 25000,
            'status_masakan' => 'Tersedia'
        ]);

        Makanan::create([
            'nama_masakan' => 'Ayam Bakar',
            'harga' => 35000,
            'status_masakan' => 'Tersedia'
        ]);
    }
}
