<?php

namespace App\Exports;

use App\Models\EntryPengeluaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RincianBiayaExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithCustomStartCell, ShouldAutoSize
{
    protected $entryPengeluaran;

    // Asumsi nomor berkas bisa diambil dari relasi atau di-pass via constructor.
    // Disini saya set default/placeholder sesuai gambar.
    protected $nomorBerkas = '1125-1';

    public function __construct(EntryPengeluaran $entryPengeluaran)
    {
        $this->entryPengeluaran = $entryPengeluaran;
    }

    // Mengatur agar tabel data dimulai dari baris ke-4
    // (Memberi ruang untuk Judul di baris 1-3)
    public function startCell(): string
    {
        return 'A4';
    }

    public function headings(): array
    {
        // Disesuaikan persis dengan gambar (15 Kolom)
        return [
            'No.',
            'Nomor Surat Jalan',
            'Kab / Kota',
            'Tujuan',
            'Kegiatan',
            'Unit Kerja/UKM',
            'Nopol Kendaraan',
            'Pengemudi',
            'Kode AT', // Sesuai gambar (mungkin Kode ATM terpotong jadi Kode AT)
            'Jenis BBM',
            'Volume (liter)',
            'Biaya BBM (Rp.)',
            'Kode Kartu Tol',
            'Biaya Tol (Rp.)',
            'Biaya Parkir/Lainnya (Rp.)',
        ];
    }

    public function collection()
    {
        return $this->entryPengeluaran->rincianPengeluarans()
            ->with([
                'perjalananKendaraan.perjalanan.unitKerja',
                'perjalananKendaraan.perjalanan.wilayah',
                'perjalananKendaraan.pengemudi',
                'perjalananKendaraan.kendaraan',
                'rincianBiayas'
            ])
            ->get()
            ->flatMap(function ($rincianPengeluaran) {
                return $rincianPengeluaran->rincianBiayas->map(function ($biaya) use ($rincianPengeluaran) {
                    $data = array_merge($rincianPengeluaran->toArray(), $biaya->toArray());
                    $data['perjalananKendaraan'] = $rincianPengeluaran->perjalananKendaraan;
                    return (object) $data;
                });
            });
    }

    public function map($row): array
    {
        static $no = 1;

        // Mapping disesuaikan agar cocok dengan urutan headings
        return [
            $no++,
            $row->nomor_perjalanan ?? '',
            $row->kota_kabupaten ?? '',
            $row->alamat_tujuan ?? '',
            $row->perjalananKendaraan->perjalanan->nama_kegiatan ?? '',
            $row->nama_unit_kerja ?? '', // Sesuaikan field relasi unit kerja
            $row->nopol_kendaraan ?? '',
            $row->nama_pengemudi ?? '',
            'KK', // Hardcode contoh dari gambar (Kode AT), sesuaikan jika dinamis
            $row->tipe == 'bbm' ? ($row->jenis_bbm ?? '') : '',
            $row->tipe == 'bbm' ? ($row->volume ?? '') : '',
            $row->tipe == 'bbm' ? ($row->biaya ?? '') : '',
            $row->tipe == 'tol' ? 'm' : '-', // Contoh dari gambar, sesuaikan logikanya
            $row->tipe == 'tol' ? ($row->biaya ?? '') : '',
            $row->tipe == 'parkir_lainnya' ? ($row->biaya ?? '') : '',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // --- 1. SETUP JUDUL (HEADER ATAS) ---

                // Baris 1: Judul Utama
                $sheet->mergeCells('A1:O1');
                $sheet->setCellValue('A1', 'Tanda Terima SPJ BBM dan Tol Th. 2025');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'underline' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Baris 2: Nomor Berkas
                $sheet->mergeCells('A2:O2');
                $sheet->setCellValue('A2', 'Nomor Berkas: ' . $this->nomorBerkas);
                $sheet->getStyle('A2')->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Baris 3: Tanggal (Rata Kanan)
                // Mengambil tanggal hari ini atau tanggal spesifik
                $tanggalCetak = \Carbon\Carbon::now()->translatedFormat('d F Y');
                $sheet->setCellValue('O3', $tanggalCetak);
                $sheet->getStyle('O3')->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
                ]);

                // --- 2. STYLING TABEL DATA ---

                // Mencari baris terakhir data
                $lastRow = $sheet->getHighestRow();

                // Style Header Tabel (Baris 4)
                $sheet->getStyle('A4:O4')->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Border untuk seluruh data (A4 sampai baris terakhir)
                $sheet->getStyle('A4:O' . $lastRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);


                // --- 3. FOOTER (TANDA TANGAN & REKAPITULASI) ---

                $footerRow = $lastRow + 2; // Memberi jarak 1 baris kosong

                // Tanda Tangan Kiri
                $sheet->setCellValue('B' . $footerRow, 'Diserahkan Oleh:');

                // Tanda Tangan Tengah
                $sheet->setCellValue('D' . $footerRow, 'Diterima Oleh:');

                // --- REKAPITULASI (KANAN) ---
                // Layout disesuaikan dengan gambar:
                // Judul rekap ada di baris footerRow
                // Nilai rekap ada di baris footerRow + 1

                $rekapHeaderRow = $footerRow;
                $rekapValueRow = $footerRow + 1;

                // Label "Rekapitulasi" (Kolom K)
                $sheet->setCellValue('K' . $rekapHeaderRow, 'Rekapitulasi');
                $sheet->getStyle('K' . $rekapHeaderRow)->getFont()->setBold(true);
                $sheet->getStyle('K' . $rekapHeaderRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Header Kotak Rekapitulasi
                $sheet->setCellValue('L' . $rekapHeaderRow, 'BBM (Rp.)');
                $sheet->setCellValue('M' . $rekapHeaderRow, 'Biaya Tol (Rp.)');
                $sheet->setCellValue('N' . $rekapHeaderRow, 'Biaya Parkir/Lainnya (Rp.)'); // Perhatikan di gambar ini geser ke kiri (Kolom N, bukan O)

                // Styling Bold Header Rekap
                $sheet->getStyle('L' . $rekapHeaderRow . ':N' . $rekapHeaderRow)->getFont()->setBold(true);

                // Rumus SUM Total
                // Total BBM (Kolom L)
                $sheet->setCellValue('L' . $rekapValueRow, '=SUM(L5:L' . $lastRow . ')');

                // Total Tol (Kolom N di data, tapi ditaruh di M di rekap sesuai gambar layout visual)
                // Perhatikan: Data Tol ada di kolom N, tapi kotak rekap Tol ada di kolom M
                $sheet->setCellValue('M' . $rekapValueRow, '=SUM(N5:N' . $lastRow . ')');

                // Total Parkir (Kolom O di data, tapi ditaruh di N di rekap sesuai gambar layout visual)
                $sheet->setCellValue('N' . $rekapValueRow, '=SUM(O5:O' . $lastRow . ')');

                // Formatting Rupiah/Accounting (Opsional)
                $sheet->getStyle('L' . $rekapValueRow . ':N' . $rekapValueRow)
                      ->getNumberFormat()->setFormatCode('#,##0');

                // Border Kotak Rekapitulasi
                // Area: K(Header)-N(Value) sesuai visual
                $sheet->getStyle('K' . $rekapHeaderRow . ':N' . $rekapValueRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
            },
        ];
    }
}
