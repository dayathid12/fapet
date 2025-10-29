<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kendaraan;

class KendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kendaraan::create([
            'nopol' => 'D 1 DUP',
            'merk' => 'Toyota',
            'jenis' => 'Avanza',
        ]);

        Kendaraan::create([
            'nopol' => 'D 1022 D',
            'merk' => 'Mitsubishi',
            'jenis' => 'Xpander',
        ]);
    }
}