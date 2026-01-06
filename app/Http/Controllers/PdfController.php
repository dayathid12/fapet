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
}
