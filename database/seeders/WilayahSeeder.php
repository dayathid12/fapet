<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wilayahs = [
            ['nama_wilayah' => 'Bandung', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Bandung Barat', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Bekasi', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Bogor', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Ciamis', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Cianjur', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Cirebon', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Garut', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Indramayu', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Karawang', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Kuningan', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Majalengka', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Pangandaran', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Purwakarta', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Subang', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Sukabumi', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Sumedang', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Tasikmalaya', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Banjar', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Cimahi', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Depok', 'provinsi' => 'Jawa Barat'],
            ['nama_wilayah' => 'Jakarta Pusat', 'provinsi' => 'DKI Jakarta'],
            ['nama_wilayah' => 'Jakarta Utara', 'provinsi' => 'DKI Jakarta'],
            ['nama_wilayah' => 'Jakarta Barat', 'provinsi' => 'DKI Jakarta'],
            ['nama_wilayah' => 'Jakarta Selatan', 'provinsi' => 'DKI Jakarta'],
            ['nama_wilayah' => 'Jakarta Timur', 'provinsi' => 'DKI Jakarta'],
            ['nama_wilayah' => 'Kepulauan Seribu', 'provinsi' => 'DKI Jakarta'],
            ['nama_wilayah' => 'Tangerang', 'provinsi' => 'Banten'],
        ];

        foreach ($wilayahs as $wilayah) {
            \App\Models\Wilayah::create([
                'nama_wilayah' => $wilayah['nama_wilayah'],
                'kota_kabupaten' => $wilayah['nama_wilayah'],
                'provinsi' => $wilayah['provinsi'],
            ]);
        }
    }
}
