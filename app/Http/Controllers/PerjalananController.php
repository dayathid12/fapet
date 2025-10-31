<?php

namespace App\Http\Controllers;

use App\Models\Perjalanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PerjalananController extends Controller
{
    public function generatePdf($nomor_perjalanan)
    {
        $perjalanan = Perjalanan::with(['unitKerja', 'wilayah', 'pengemudi', 'asisten'])->findOrFail($nomor_perjalanan);

        $pdf = Pdf::loadView('pdf.perjalanan', compact('perjalanan'))->setPaper('a4', 'portrait');

        return $pdf->stream('surat-perjalanan-' . $perjalanan->nomor_perjalanan . '.pdf');
    }
}
