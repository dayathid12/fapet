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
        $perjalanan->load('unitKerja', 'wilayah', 'pengemudi', 'asisten');

        $pdf = Pdf::loadView('pdf.surat_tugas', compact('perjalanan'))->setPaper('a4', 'portrait');

        // Sanitize the filename
        $fileName = 'surat-tugas-' . preg_replace('/[^a-zA-Z0-9_-]/', '', $perjalanan->nomor_perjalanan ?? $perjalanan->id) . '.pdf';

        return $pdf->stream($fileName);
    }

    public function generateSuratTugasWord($no_surat_tugas)
    {
        $perjalanan = Perjalanan::where('no_surat_tugas', $no_surat_tugas)->firstOrFail();

        // Eager load relationships to prevent N+1 query problems in the view
        $perjalanan->load('unitKerja', 'wilayah', 'pengemudi', 'asisten');

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
}
