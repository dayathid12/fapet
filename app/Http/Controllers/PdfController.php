<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perjalanan;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function generateSuratTugas($no_surat_tugas)
    {
        $perjalanan = Perjalanan::where('no_surat_tugas', $no_surat_tugas)->firstOrFail();

        // Eager load relationships to prevent N+1 query problems in the view
        $perjalanan->load('unitKerja', 'wilayah', 'pengemudi', 'asisten', 'details');

        // Set locale to Indonesian
        \Carbon\Carbon::setLocale('id');

        $pdf = Pdf::loadView('pdf.surat_tugas', compact('perjalanan'))->setPaper('a4', 'portrait');

        // Sanitize the filename
        $fileName = 'surat-tugas-' . preg_replace('/[^a-zA-Z0-9_-]/', '', $perjalanan->nomor_perjalanan ?? $perjalanan->id) . '.pdf';

        return $pdf->stream($fileName);
    }

    public function generateSuratTugasWord($no_surat_tugas)
    {
        $perjalanan = Perjalanan::where('no_surat_tugas', $no_surat_tugas)->firstOrFail();

        // Eager load relationships to prevent N+1 query problems in the view
        $perjalanan->load('unitKerja', 'wilayah', 'pengemudi', 'asisten', 'details');

        // Render the view to HTML
        $html = view('pdf.surat_tugas', compact('perjalanan'))->render();

        // Adjust image sizes for Word document compatibility
        // Make logo smaller for Word
        $html = str_replace('width: 110px;', 'width: 70px;', $html);
        // Make TTE signature smaller for Word
        $html = str_replace('max-width: 300px; max-height: 150px;', 'max-width: 200px; max-height: 100px;', $html);
        // Adjust header text sizes for Word
        $html = str_replace('font-size: 14pt;', 'font-size: 12pt;', $html);
        $html = str_replace('font-size: 16pt;', 'font-size: 14pt;', $html);
        $html = str_replace('font-size: 10pt;', 'font-size: 9pt;', $html);

        // Sanitize the filename
        $fileName = 'surat-tugas-' . preg_replace('/[^a-zA-Z0-9_-]/', '', $perjalanan->nomor_perjalanan ?? $perjalanan->id) . '.doc';

        // Return HTML as Word document
        return response($html)
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function generateSptjbPdf(\App\Models\SPTJBUangPengemudiDetail $detail)
    {
        $sptjb = $detail->sptjb;
        dd($sptjb->no_sptjb);

        // Set locale to Indonesian
        \Carbon\Carbon::setLocale('id');
        // Get today's date and format it in Indonesian
        $tanggal = \Carbon\Carbon::now()->translatedFormat('d F Y');

        $pdf = Pdf::loadView('pdf.sptjb', compact('sptjb', 'detail', 'tanggal'))->setPaper('a4', 'portrait');

        $fileName = 'sptjb-' . preg_replace('/[^a-zA-Z0-9_-]/', '', $sptjb->no_sptjb) . '-' . $detail->id . '.pdf';

        return $pdf->stream($fileName);
    }

    public function generateSptjbFullPdf(\App\Models\SPTJBPengemudi $sptjb)
    {
        // Load details
        $sptjb->load('details');

        // Set locale to Indonesian
        \Carbon\Carbon::setLocale('id');
        // Get today's date and format it in Indonesian
        $tanggal = \Carbon\Carbon::now()->translatedFormat('d F Y');

        $pdf = Pdf::loadView('pdf.sptjb_full', compact('sptjb', 'tanggal'))->setPaper('legal', 'portrait');

        $fileName = 'sptjb-full-' . preg_replace('/[^a-zA-Z0-9_-]/', '', $sptjb->no_sptjb) . '.pdf';

        return $pdf->stream($fileName);
    }

    public function generateSptjbTablePdf(\App\Models\SPTJBPengemudi $sptjb)
    {
        // Load details
        $sptjb->load('details');

        // Set locale to Indonesian
        \Carbon\Carbon::setLocale('id');

        // Generate grouped details
        $details = $sptjb->details;
        $grouped = $details->groupBy('nama');
        $groupedDetails = collect();
        $no = 1;
        foreach ($grouped as $nama => $group) {
            $record = new \stdClass();
            $record->no = $no++;
            $record->nama = $nama;
            $record->jabatan = $group->first()->jabatan;
            $record->besaran_uang_per_hari = $group->first()->besaran_uang_per_hari;
            $record->jumlah_hari = $group->sum('jumlah_hari');
            $record->jumlah_uang_diterima = $group->sum(function($item) {
                return $item->besaran_uang_per_hari * $item->jumlah_hari;
            });
            $staf = \App\Models\Staf::where('nama_staf', $nama)->first();
            $record->nomor_rekening = $staf ? $staf->rekening : null;
            $record->golongan = $staf ? $staf->gol_pangkat : null;

            // Combine nomor_surat: unique, sort numerically
            $nomorSurat = $group->pluck('nomor_surat')->filter()->unique()->sort(function($a, $b) {
                $aNum = is_numeric($a) ? (int)$a : $a;
                $bNum = is_numeric($b) ? (int)$b : $b;
                if (is_int($aNum) && is_int($bNum)) {
                    return $aNum <=> $bNum;
                }
                return strcmp($a, $b);
            })->implode(',');
            $record->nomor_surat = $nomorSurat;

            // Combine tanggal_penugasan: unique dates, sort chronologically
            $tanggalPenugasan = collect();
            foreach ($group as $item) {
                if ($item->tanggal_penugasan) {
                    $dates = explode(',', $item->tanggal_penugasan);
                    $tanggalPenugasan = $tanggalPenugasan->merge($dates);
                }
            }
            $tanggalPenugasan = $tanggalPenugasan->unique()->sort()->implode(',');
            $record->tanggal_penugasan = $tanggalPenugasan;

            $groupedDetails->push($record);
        }

        $earliestStartDate = null;
        $latestEndDate = null;

        foreach ($sptjb->details as $detail) {
            $perjalanan = Perjalanan::where('no_surat_tugas', $detail->nomor_surat)->first();
            if ($perjalanan && $perjalanan->waktu_keberangkatan && $perjalanan->waktu_kepulangan) {
                $currentStartDate = \Carbon\Carbon::parse($perjalanan->waktu_keberangkatan);
                $currentEndDate = \Carbon\Carbon::parse($perjalanan->waktu_kepulangan);

                if ($earliestStartDate === null || $currentStartDate->lt($earliestStartDate)) {
                    $earliestStartDate = $currentStartDate;
                }
                if ($latestEndDate === null || $currentEndDate->gt($latestEndDate)) {
                    $latestEndDate = $currentEndDate;
                }
            }
        }

        $dateRangeString = '';
        if ($earliestStartDate && $latestEndDate) {
            if ($earliestStartDate->format('Ym') === $latestEndDate->format('Ym')) {
                // Same month and year: "3 - 16 Januari 2026"
                $dateRangeString = $earliestStartDate->format('j') . ' - ' . $latestEndDate->translatedFormat('j F Y');
            } else {
                // Different month or year: "3 Desember 2026 s.d 11 Januari 2027"
                $dateRangeString = $earliestStartDate->translatedFormat('j F Y') . ' s.d ' . $latestEndDate->translatedFormat('j F Y');
            }
        }

        $pdf = Pdf::loadView('pdf.sptjb_table', compact('sptjb', 'groupedDetails', 'dateRangeString'))->setPaper('a4', 'landscape');

        $fileName = 'sptjb-table-' . preg_replace('/[^a-zA-Z0-9_-]/', '', $sptjb->no_sptjb) . '.pdf';

        return $pdf->stream($fileName);
    }
}
