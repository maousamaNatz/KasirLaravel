<?php

namespace Database\Seeders;

use App\Models\Makanan;
use Illuminate\Database\Seeder;

class MakananSeeder extends Seeder
{
    public function run(): void
    {
        $makanan = [
            [
                'nama_masakan' => 'Nasi Goreng Special',
                'harga' => 35000,
                'status_masakan' => 'Tersedia'
            ],
            [
                'nama_masakan' => 'Ayam Bakar Madu',
                'harga' => 45000,
                'status_masakan' => 'Tersedia'
            ],
            [
                'nama_masakan' => 'Soto Ayam Lamongan',
                'harga' => 28000,
                'status_masakan' => 'Tersedia'
            ],
            [
                'nama_masakan' => 'Gado-Gado Jakarta',
                'harga' => 22000,
                'status_masakan' => 'Tersedia'
            ],
            [
                'nama_masakan' => 'Sate Ayam 10 Tusuk',
                'harga' => 30000,
                'status_masakan' => 'Tersedia'
            ],
            [
                'nama_masakan' => 'Bakso Jumbo Komplit',
                'harga' => 25000,
                'status_masakan' => 'Tersedia'
            ],
            [
                'nama_masakan' => 'Es Teh Manis',
                'harga' => 8000,
                'status_masakan' => 'Tersedia'
            ],
            [
                'nama_masakan' => 'Jus Alpukat',
                'harga' => 15000,
                'status_masakan' => 'Tersedia'
            ],
            [
                'nama_masakan' => 'Nasi Liwet Sunda',
                'harga' => 30000,
                'status_masakan' => 'Tersedia'
            ],
            [
                'nama_masakan' => 'Ikan Gurame Bakar',
                'harga' => 55000,
                'status_masakan' => 'Tersedia'
            ],
            [
                'nama_masakan' => 'Tahu Telur Surabaya',
                'harga' => 20000,
                'status_masakan' => 'Tersedia'
            ],
            [
                'nama_masakan' => 'Es Jeruk Segar',
                'harga' => 12000,
                'status_masakan' => 'Tersedia'
            ],
            [
                'nama_masakan' => 'Pisang Goreng',
                'harga' => 10000,
                'status_masakan' => 'Tersedia'
            ],
            [
                'nama_masakan' => 'Steak Daging Sapi',
                'harga' => 65000,
                'status_masakan' => 'Tersedia'
            ],
            [
                'nama_masakan' => 'Kolak Pisang',
                'harga' => 15000,
                'status_masakan' => 'Tersedia'
            ]
        ];

        foreach ($makanan as $item) {
            Makanan::create($item);
        }
    }
}
