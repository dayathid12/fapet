<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perjalanan;
use App\Models\PerjalananKendaraan;
use Carbon\Carbon;

class PerjalananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'perjalanan_data' => [
                    'waktu_keberangkatan' => Carbon::create(2025, 12, 1, 8, 0, 0),
                    'waktu_kepulangan' => Carbon::create(2025, 12, 3, 17, 0, 0),
                    'status_perjalanan' => 'Terjadwal',
                    'alamat_tujuan' => 'Jakarta',
                    'lokasi_keberangkatan' => 'Bandung',
                    'jumlah_rombongan' => 5,
                    'jenis_kegiatan' => 'Rapat',
                    'nama_kegiatan' => 'Rapat Koordinasi',
                    'jenis_operasional' => 'Dinas',
                    'no_surat_tugas' => 'ST-1001/2025',
                    'nama_pengguna' => 'John Doe',
                    'kontak_pengguna' => '08123456789',
                    'nama_personil_perwakilan' => 'Alice Cooper',
                    'kontak_pengguna_perwakilan' => '08123456790',
                    'tujuan_wilayah_id' => 1,
                    'unit_kerja_id' => 1,
                ],
                'kendaraan_data' => [
                    'pengemudi_id' => 1,
                    'asisten_id' => 2,
                    'kendaraan_nopol' => 'D 1841 D',
                ]
            ],
            [
                'perjalanan_data' => [
                    'waktu_keberangkatan' => Carbon::create(2025, 12, 15, 9, 0, 0),
                    'waktu_kepulangan' => Carbon::create(2025, 12, 17, 16, 0, 0),
                    'status_perjalanan' => 'Terjadwal',
                    'alamat_tujuan' => 'Surabaya',
                    'lokasi_keberangkatan' => 'Bandung',
                    'jumlah_rombongan' => 3,
                    'jenis_kegiatan' => 'Seminar',
                    'nama_kegiatan' => 'Seminar Nasional',
                    'jenis_operasional' => 'Dinas',
                    'no_surat_tugas' => 'ST-2002/2025',
                    'nama_pengguna' => 'Jane Smith',
                    'kontak_pengguna' => '08198765432',
                    'nama_personil_perwakilan' => 'Charlie Brown',
                    'kontak_pengguna_perwakilan' => '08198765433',
                    'tujuan_wilayah_id' => 2,
                    'unit_kerja_id' => 2,
                ],
                'kendaraan_data' => [
                    'pengemudi_id' => 3,
                    'asisten_id' => 4,
                    'kendaraan_nopol' => 'D 1022 D',
                ]
            ],
            [
                'perjalanan_data' => [
                    'waktu_keberangkatan' => Carbon::create(2025, 12, 29, 7, 0, 0),
                    'waktu_kepulangan' => null,
                    'status_perjalanan' => 'Terjadwal',
                    'alamat_tujuan' => 'Yogyakarta',
                    'lokasi_keberangkatan' => 'Bandung',
                    'jumlah_rombongan' => 2,
                    'jenis_kegiatan' => 'Kunjungan',
                    'nama_kegiatan' => 'Kunjungan Kerja',
                    'jenis_operasional' => 'Dinas',
                    'no_surat_tugas' => 'ST-3003/2025',
                    'nama_pengguna' => 'Bob Johnson',
                    'kontak_pengguna' => '08134567890',
                    'nama_personil_perwakilan' => 'Diana Prince',
                    'kontak_pengguna_perwakilan' => '08134567891',
                    'tujuan_wilayah_id' => 3,
                    'unit_kerja_id' => 3,
                ],
                'kendaraan_data' => [
                    'pengemudi_id' => 5,
                    'asisten_id' => 6,
                    'kendaraan_nopol' => 'D 1814 A',
                ]
            ],
        ];

        foreach ($data as $item) {
            // Update or create perjalanan
            $perjalanan = Perjalanan::updateOrCreate(
                ['no_surat_tugas' => $item['perjalanan_data']['no_surat_tugas']],
                $item['perjalanan_data']
            );

            // Update or create detail kendaraan terkait
            PerjalananKendaraan::updateOrCreate(
                ['perjalanan_id' => $perjalanan->id],
                [
                    'kendaraan_nopol' => $item['kendaraan_data']['kendaraan_nopol'],
                    'pengemudi_id' => $item['kendaraan_data']['pengemudi_id'],
                    'asisten_id' => $item['kendaraan_data']['asisten_id'],
                ]
            );
        }
    }
}
