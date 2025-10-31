<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitKerja;

class UnitKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitKerjas = [
            'Direktorat Kemahasiswaan',
            'Fakultas Ilmu Komunikasi',
            'Direktorat Pendidikan Non Gelar',
            'Fakultas Matematika dan Ilmu Pengetahuan Alam',
            'Fakultas Hukum',
            'Direktorat Kelembagaan dan Tata Kelola',
            'Rektor',
            'Direktorat Riset, Hilirisasi dan Pengabdian Pada Masyarakat',
            'Direktorat Pengelolaan Bisnis',
            'Direktorat Kerjasama dan Kemitraan Alumni',
            'Fakultas Peternakan',
            'Direktorat Pengelolaan Aset dan Sarana Prasarana',
            'Direktorat Sumber Daya Manusia dan Pengembangan Karir Tenaga Kependidikan',
            'Fakultas Teknologi Industri Pertanian',
            'Satuan Pengawas Internal (SPI)',
            'Fakultas Ilmu Budaya',
            'PSDKU Pangandaran',
            'Direktorat Akademik',
            'Fakultas Farmasi',
            'Wakil Rektor 1',
            'Fakultas Pertanian (Faperta)',
            'Sekolah Vokasi',
            'Fakultas Kedokteran Gigi (FKG)',
            'Satuan Pengembangan Strategis dan Reputasi Universitas',
            'Sekolah Pascasarjana',
            'Fakultas Perikanan dan Ilmu Kelautan (FPIK)',
            'Fakultas Ilmu Sosial dan Ilmu Politik',
            'Fakultas Ekonomi dan Bisnis',
            'Fakultas Keperawatan',
            'Direktorat Pemasaran',
            'Senat Akademik',
            'Kantor WR II',
            'Wakil Rektor IV',
            'Direktorat Keuangan dan Tresury',
            'Majelis Wali Amanat',
            'Pusat Digitalisasi dan Pengembangan Budaya Sunda (PDPBS) Unpad',
            'Badan Pengembangan Usaha Komersial dan Investasi (BPUKI)',
            'Rumah Sakit Unpad',
            'Direktorat Perencanaan, Sistem Informasi dan Transformasi Digital',
            'Kantor Sekretariat Pimpinan dan Protokoler',
            'Kantor WR Bidang Riset, Kerja Sama, dan Pemasaran',
            'Dharma Wanita',
            'Fakultas Teknik Geologi',
            'Wakil Rektor III',
            'Wakil Rektor 2',
            'Satuan Penjamin Mutu',
        ];

        foreach ($unitKerjas as $unitKerja) {
            UnitKerja::create([
                'nama_unit_kerja' => $unitKerja,
            ]);
        }
    }
}
