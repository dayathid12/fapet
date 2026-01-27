<?php

namespace App\Http\Controllers;

use App\Models\Perjalanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PerjalananController extends Controller
{
    public function generatePdf($nomor_perjalanan)
    {
        $perjalanan = Perjalanan::with(['unitKerja', 'wilayah', 'pengemudi', 'asisten', 'kendaraan', 'jadwalPengemudis'])->findOrFail($nomor_perjalanan);
        // Eager load relationships, including 'details' for tipe_penugasan and waktu_selesai_penugasan
        // Assuming 'details' is the relationship name for PerjalananKendaraan or similar that holds tipe_penugasan
        $perjalanan = Perjalanan::with(['unitKerja', 'wilayah', 'pengemudi', 'asisten', 'kendaraan', 'jadwalPengemudis', 'details'])->findOrFail($nomor_perjalanan);

        // Set locale to Indonesian for Carbon formatting in the view
        \Carbon\Carbon::setLocale('id');
        $pdf = Pdf::loadView('pdf.perjalanan', compact('perjalanan'))->setPaper('a4', 'portrait');

        return $pdf->stream('surat-perjalanan-' . $perjalanan->nomor_perjalanan . '.pdf');
    }

    public function showFile($encodedPath)
    {
        $filePath = base64_decode($encodedPath);

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        $file = Storage::disk('public')->get($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);

        // Forcing download if not PDF or image
        if (!in_array($mimeType, ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'])) {
            return response()->download(Storage::disk('public')->path($filePath), basename($filePath), [
                'Content-Type' => $mimeType,
            ]);
        }

        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
