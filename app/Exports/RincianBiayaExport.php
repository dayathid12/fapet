<?php

namespace App\Exports;

use App\Models\EntryPengeluaran;
use App\Models\RincianPengeluaran; // Import RincianPengeluaran model
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Collection; // Import Collection class

class RincianBiayaExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithCustomStartCell, ShouldAutoSize
{
    protected $entryPengeluaran;
    protected $nomorBerkas; // Will be set dynamically
    protected $totalBBM;
    protected $totalKartuPasBBM;
    protected $totalTol;
    protected $totalParkir;

    public function __construct(EntryPengeluaran $entryPengeluaran)
    {
        $this->entryPengeluaran = $entryPengeluaran;
        // Use the nomor_berkas from the EntryPengeluaran itself
        $this->nomorBerkas = $entryPengeluaran->nomor_berkas ?? 'N/A';
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function headings(): array
    {
        return [
            'No.',
            'Nomor Surat Jalan',
            'Kab / Kota',
            'Tujuan',
            'Kegiatan',
            'Unit Kerja/UKM',
            'Nopol Kendaraan',
            'Pengemudi',
            'Kode AT',
            'Jenis BBM',
            'Volume (liter)',
            'Biaya BBM (Rp.)',
            'Jumlah Kartu Pas BBM',
            'Kartu Pas BBM',
            'Kode Kartu Tol',
            'Biaya Tol (Rp.)',
            'Biaya Parkir/Lainnya (Rp.)',
        ];
    }

    public function collection(): Collection
    {
        $this->totalBBM = 0;
        $this->totalKartuPasBBM = 0;
        $this->totalTol = 0;
        $this->totalParkir = 0;

        return $this->entryPengeluaran->rincianPengeluarans()
            ->with([
                'perjalananKendaraan.perjalanan.unitKerja',
                'perjalananKendaraan.perjalanan.wilayah',
                'perjalananKendaraan.pengemudi',
                'perjalananKendaraan.kendaraan',
                'rincianBiayas'
            ])
            ->get()
            ->filter(function ($rincianPengeluaran) {
                // Skip if perjalananKendaraan is null
                return $rincianPengeluaran->perjalananKendaraan !== null;
            })
            ->map(function ($rincianPengeluaran) {
                $totalBBM = 0;
                $totalTol = 0;
                $totalParkir = 0;
                $jenisBBM = '';
                $volumeBBM = '';
                $kodeKartuTol = '-';
                $kodeAT = 'KK';

                $bbmBiayas = $rincianPengeluaran->rincianBiayas->where('tipe', 'bbm');
                $otherBiayas = $rincianPengeluaran->rincianBiayas->where('tipe', '!=', 'bbm');

                $hasPertaminaRetailBBM = $bbmBiayas->contains(function ($biaya) {
                    return $biaya->pertama_retail;
                });

                // Calculate total BBM for Pertamina Retail only
                $totalBBMPertaminaRetail = $bbmBiayas->filter(function ($biaya) {
                    return $biaya->pertama_retail;
                })->sum('biaya');

                if ($hasPertaminaRetailBBM) {
                    $filteredBbmBiayas = $bbmBiayas->filter(function ($biaya) {
                        return !$biaya->pertama_retail;
                    });
                } else {
                    $filteredBbmBiayas = $bbmBiayas;
                }

                $filteredBiayas = $filteredBbmBiayas->concat($otherBiayas);

                $rincianPengeluaran->should_skip = false; // Default

                if ($filteredBiayas->isEmpty() && ($bbmBiayas->isNotEmpty() || $otherBiayas->isNotEmpty())) {
                     // If there were biayas initially, but all were filtered out, mark to skip this rincianPengeluaran
                    $rincianPengeluaran->should_skip = true;
                } else {
                    foreach ($filteredBiayas as $biaya) {
                        if ($biaya->tipe === 'bbm') {
                            $totalBBM += $biaya->biaya;
                            if (empty($jenisBBM) && !empty($biaya->jenis_bbm)) {
                                $jenisBBM = $biaya->jenis_bbm;
                            }
                            if (empty($volumeBBM) && !empty($biaya->volume)) {
                                $volumeBBM = $biaya->volume;
                            }
                        } elseif ($biaya->tipe === 'toll') {
                            $totalTol += $biaya->biaya;
                            $kodeKartuTol = $biaya->deskripsi ?? '-';
                        } elseif ($biaya->tipe === 'parkir') {
                            $totalParkir += $biaya->biaya;
                        }
                    }
                }


                $this->totalBBM += $totalBBM;
                $this->totalKartuPasBBM += $totalBBMPertaminaRetail;
                $this->totalTol += $totalTol;
                $this->totalParkir += $totalParkir;

                $rincianPengeluaran->aggregated_total_bbm = $totalBBM;
                $rincianPengeluaran->aggregated_total_bbm_pertamina_retail = $totalBBMPertaminaRetail;
                $rincianPengeluaran->aggregated_jenis_bbm = $jenisBBM;
                $rincianPengeluaran->aggregated_volume_bbm = $volumeBBM;
                $rincianPengeluaran->aggregated_total_tol = $totalTol;
                $rincianPengeluaran->aggregated_kode_kartu_tol = $kodeKartuTol;
                $rincianPengeluaran->aggregated_total_parkir = $totalParkir;
                $rincianPengeluaran->aggregated_kode_at = $kodeAT;


                return $rincianPengeluaran;
            })
                        ->filter(function ($rincianPengeluaran) {
                            // Filter out entries that were marked to be skipped
                            return !$rincianPengeluaran->should_skip;
                        });
                }

                public function map($row): array
                {
                    static $no = 1;

        // Defensive checks for relationships
        $perjalananKendaraan = $row->perjalananKendaraan;
        $perjalanan = $perjalananKendaraan->perjalanan ?? null;
        $pengemudi = $perjalananKendaraan->pengemudi ?? null;
        $kendaraan = $perjalananKendaraan->kendaraan ?? null;
        $unitKerja = $perjalanan->unitKerja ?? null;
        $wilayah = $perjalanan->wilayah ?? null;


        return [
            sprintf('%03d', $no++),
            $perjalanan->nomor_perjalanan ?? '',
            $wilayah->nama_wilayah ?? '',
            $perjalanan->alamat_tujuan ?? '',
            $perjalanan->nama_kegiatan ?? '',
            $unitKerja->nama_unit_kerja ?? '',
            $kendaraan->nopol_kendaraan ?? '',
            $pengemudi->nama_staf ?? '',
            $row->aggregated_kode_at,
            $row->aggregated_jenis_bbm,
            $row->aggregated_volume_bbm,
            $row->aggregated_total_bbm,
            $row->aggregated_total_bbm_pertamina_retail,
            $row->aggregated_total_bbm_pertamina_retail,
            $row->aggregated_kode_kartu_tol,
            $row->aggregated_total_tol,
            $row->aggregated_total_parkir,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // --- 1. SETUP JUDUL (HEADER ATAS) ---

                // Baris 1: Judul Utama
                $sheet->mergeCells('A1:Q1');
                $sheet->setCellValue('A1', 'Tanda Terima SPJ BBM dan Tol Th. 2025');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 18, 'underline' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Baris 2: Nomor Berkas
                $sheet->mergeCells('A2:Q2');
                $sheet->setCellValue('A2', 'Nomor Berkas: ' . $this->nomorBerkas);
                $sheet->getStyle('A2')->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER], 'size' => 15,
                ]);

                // Baris 3: Tanggal (Rata Kanan)
                // Mengambil tanggal hari ini atau tanggal spesifik
                $tanggalCetak = \Carbon\Carbon::now()->translatedFormat('d F Y');
                $sheet->setCellValue('Q3', $tanggalCetak);
                $sheet->getStyle('Q3')->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
                ]);

                // --- 2. STYLING TABEL DATA ---

                // Mencari baris terakhir data
                $lastRow = $sheet->getHighestRow();

                // Style Header Tabel (Baris 4)
                $sheet->getStyle('A4:P4')->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Border untuk seluruh data (A4 sampai baris terakhir)
                $sheet->getStyle('A4:P' . $lastRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);

                // Tambahkan border biasa untuk kolom Biaya Parkir/Lainnya (Rp.) (kolom Q)
                $sheet->getStyle('Q4:Q' . $lastRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);

                // Formatting Rupiah/Accounting untuk kolom data Biaya BBM, Jumlah Kartu Pas BBM, Kartu Pas BBM, Biaya Tol, dan Biaya Parkir
                $sheet->getStyle('L5:L' . $lastRow) // Kolom Biaya BBM
                      ->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('M5:M' . $lastRow) // Kolom Jumlah Kartu Pas BBM
                      ->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('N5:N' . $lastRow) // Kolom Kartu Pas BBM
                      ->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('P5:P' . $lastRow) // Kolom Biaya Tol
                      ->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('Q5:Q' . $lastRow) // Kolom Biaya Parkir/Lainnya
                      ->getNumberFormat()->setFormatCode('#,##0');


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
                $sheet->setCellValue('L' . $rekapHeaderRow, 'Jumlah BBM (Rp.)');
                $sheet->setCellValue('M' . $rekapHeaderRow, 'Jumlah Kartu Pas BBM (Rp.)');
                $sheet->setCellValue('N' . $rekapHeaderRow, 'Jumlah Biaya Tol (Rp.)');
                $sheet->setCellValue('O' . $rekapHeaderRow, 'Jumlah Biaya Parkir/Lainnya (Rp.)');

                // Styling Bold Header Rekap
                $sheet->getStyle('L' . $rekapHeaderRow . ':O' . $rekapHeaderRow)->getFont()->setBold(true);

                // Set Total Values
                // Total BBM (Kolom L)
                $sheet->setCellValue('L' . $rekapValueRow, $this->totalBBM);

                // Total Kartu Pas BBM (Kolom M)
                $sheet->setCellValue('M' . $rekapValueRow, $this->totalKartuPasBBM);

                // Total Tol (Kolom N)
                $sheet->setCellValue('N' . $rekapValueRow, $this->totalTol);

                // Total Parkir (Kolom O)
                $sheet->setCellValue('O' . $rekapValueRow, $this->totalParkir);

                // Formatting Rupiah/Accounting (Opsional)
                $sheet->getStyle('L' . $rekapValueRow . ':O' . $rekapValueRow)
                      ->getNumberFormat()->setFormatCode('#,##0');

                // Border Kotak Rekapitulasi
                // Area: K(Header)-O(Value)
                $sheet->getStyle('K' . $rekapHeaderRow . ':O' . $rekapValueRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
            },
        ];
    }
}

