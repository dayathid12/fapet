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
            'nopol_kendaraan' => 'D 1 DUP',
            'merk_type' => 'Toyota Avanza',
            'jenis_kendaraan' => 'Avanza',
        ]);

        Kendaraan::create([
            'nopol_kendaraan' => 'D 1022 D',
            'merk_type' => 'Mitsubishi Xpander',
            'jenis_kendaraan' => 'Xpander',
        ]);
    }
}
