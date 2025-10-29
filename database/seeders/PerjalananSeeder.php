<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perjalanan;
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
                'nomor_perjalanan' => 1,
                'waktu_keberangkatan' => Carbon::create(2025, 10, 1, 8, 0, 0),
                'waktu_kepulangan' => Carbon::create(2025, 10, 3, 17, 0, 0),
                'status_perjalanan' => 'Terjadwal',
                'alamat_tujuan' => 'Jakarta',
                'lokasi_keberangkatan' => 'Bandung',
                'jumlah_rombongan' => 5,
                'jenis_kegiatan' => 'Rapat',
                'nama_kegiatan' => 'Rapat Koordinasi',
                'jenis_operasional' => 'Dinas',
                'status_operasional' => 'Belum Ditetapkan',
                'no_surat_tugas' => 'ST-0001/2025',
                'file_surat_jalan' => null,
                'docs_surat_tugas' => null,
                'upload_surat_tugas' => null,
                'download_file' => null,
                'surat_peminjaman_kendaraan' => null,
                'dokumen_pendukung' => null,
                'status_cek_1' => false,
                'status_cek_2' => false,
                'nama_pengguna' => 'John Doe',
                'kontak_pengguna' => '08123456789',
                'pengemudi_id' => 1,
                'asisten_id' => 2,
                'nopol_kendaraan' => 'D 1 DUP',
                'tujuan_wilayah_id' => 1,
                'unit_kerja_id' => 1,
            ],
            [
                'nomor_perjalanan' => 2,
                'waktu_keberangkatan' => Carbon::create(2025, 10, 15, 9, 0, 0),
                'waktu_kepulangan' => Carbon::create(2025, 10, 17, 16, 0, 0),
                'status_perjalanan' => 'Menunggu Persetujuan',
                'alamat_tujuan' => 'Surabaya',
                'lokasi_keberangkatan' => 'Bandung',
                'jumlah_rombongan' => 3,
                'jenis_kegiatan' => 'Seminar',
                'nama_kegiatan' => 'Seminar Nasional',
                'jenis_operasional' => 'Dinas',
                'status_operasional' => 'Belum Ditetapkan',
                'no_surat_tugas' => 'ST-0002/2025',
                'file_surat_jalan' => null,
                'docs_surat_tugas' => null,
                'upload_surat_tugas' => null,
                'download_file' => null,
                'surat_peminjaman_kendaraan' => null,
                'dokumen_pendukung' => null,
                'status_cek_1' => false,
                'status_cek_2' => false,
                'nama_pengguna' => 'Jane Smith',
                'kontak_pengguna' => '08198765432',
                'pengemudi_id' => 3,
                'asisten_id' => 4,
                'nopol_kendaraan' => 'D 1022 D',
                'tujuan_wilayah_id' => 2,
                'unit_kerja_id' => 2,
            ],
            [
                'nomor_perjalanan' => 3,
                'waktu_keberangkatan' => Carbon::create(2025, 10, 29, 7, 0, 0),
                'waktu_kepulangan' => null,
                'status_perjalanan' => 'Ditolak',
                'alamat_tujuan' => 'Yogyakarta',
                'lokasi_keberangkatan' => 'Bandung',
                'jumlah_rombongan' => 2,
                'jenis_kegiatan' => 'Kunjungan',
                'nama_kegiatan' => 'Kunjungan Kerja',
                'jenis_operasional' => 'Dinas',
                'status_operasional' => 'Belum Ditetapkan',
                'no_surat_tugas' => 'ST-0003/2025',
                'file_surat_jalan' => null,
                'docs_surat_tugas' => null,
                'upload_surat_tugas' => null,
                'download_file' => null,
                'surat_peminjaman_kendaraan' => null,
                'dokumen_pendukung' => null,
                'status_cek_1' => false,
                'status_cek_2' => false,
                'nama_pengguna' => 'Bob Johnson',
                'kontak_pengguna' => '08134567890',
                'pengemudi_id' => 5,
                'asisten_id' => 6,
                'nopol_kendaraan' => 'D 1 DUP',
                'tujuan_wilayah_id' => 3,
                'unit_kerja_id' => 3,
            ],
        ];

        foreach ($data as $item) {
            Perjalanan::create($item);
        }
    }
}
