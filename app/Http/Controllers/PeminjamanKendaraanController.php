<?php

namespace App\Http\Controllers;

use App\Models\Perjalanan;
use App\Models\NotifikasiWA;
use App\Models\Wilayah;
use App\Models\UnitKerja;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Added this line

class PeminjamanKendaraanController extends Controller
{
    /**
     * Show the peminjaman kendaraan form
     */
    public function show()
    {
        $wilayahs = Wilayah::all();
        $unitKerjas = UnitKerja::all();

        return view('peminjaman-kendaraan-v2', [
            'wilayahs' => $wilayahs,
            'unitKerjas' => $unitKerjas,
        ]);
    }

    /**
     * Submit the form and save to database
     */
    public function submit(Request $request)
    {
        $data = $request->except('_token');

        // Validation
        $rules = [
            'waktu_keberangkatan' => 'required|date',
            'lokasi_keberangkatan' => 'required|string|max:255',
            'jumlah_rombongan' => 'required|numeric|min:1',
            'alamat_tujuan' => 'required|string|max:500',
            'nama_kegiatan' => 'required|string|max:255',
            'tujuan_wilayah_id' => 'required|exists:wilayahs,wilayah_id',
            'unit_kerja_id' => 'required|exists:unit_kerjas,unit_kerja_id',
            'nama_pengguna' => 'required|string|max:255',
            'kontak_pengguna' => 'required|string|max:20',
            'nama_personil_perwakilan' => 'required|string|max:255',
            'kontak_pengguna_perwakilan' => 'required|string|max:20',
            'status_sebagai' => 'required|in:Mahasiswa,Dosen,Staf,Lainnya',
            'surat_peminjaman' => 'required|file|mimes:pdf|max:20480',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:20480',
            'surat_izin_kegiatan' => 'nullable|file|mimes:pdf|max:20480',
        ];

        $validator = validator($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi data gagal',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        try {
            // Generate unique token/UUID
            $token = Str::uuid()->toString();

            // Handle file uploads
            $suratPeminjamanPath = null;
            if ($request->hasFile('surat_peminjaman')) {
                $suratPeminjamanPath = $request->file('surat_peminjaman')->store('surat-peminjaman-kendaraan', 'public');
            }

            $dokumenPendukungPath = null;
            if ($request->hasFile('dokumen_pendukung')) {
                $dokumenPendukungPath = $request->file('dokumen_pendukung')->store('dokumen-pendukung', 'public');
            }

            $suratIzinPath = null;
            if ($request->hasFile('surat_izin_kegiatan')) {
                $suratIzinPath = $request->file('surat_izin_kegiatan')->store('surat-izin-kegiatan', 'public');
            }

            // Prepare data for saving
            $saveData = [
                'token' => $token,
                'waktu_keberangkatan' => $data['waktu_keberangkatan'],
                'waktu_kepulangan' => $data['waktu_kepulangan'] ?? null,
                'lokasi_keberangkatan' => $data['lokasi_keberangkatan'],
                'jumlah_rombongan' => $data['jumlah_rombongan'],
                'alamat_tujuan' => $data['alamat_tujuan'],
                'nama_kegiatan' => $data['nama_kegiatan'],
                'tujuan_wilayah_id' => $data['tujuan_wilayah_id'],
                'unit_kerja_id' => $data['unit_kerja_id'],
                'nama_pengguna' => $data['nama_pengguna'],
                'kontak_pengguna' => $data['kontak_pengguna'],
                'nama_personil_perwakilan' => $data['nama_personil_perwakilan'],
                'kontak_pengguna_perwakilan' => $data['kontak_pengguna_perwakilan'],
                'status_sebagai' => $data['status_sebagai'],
                'provinsi' => $data['provinsi'] ?? null,
                'uraian_singkat_kegiatan' => $data['uraian_singkat_kegiatan'] ?? null,
                'catatan_keterangan_tambahan' => $data['catatan_keterangan_tambahan'] ?? null,
                'surat_peminjaman_kendaraan' => $suratPeminjamanPath, // Save file path
                'dokumen_pendukung' => $dokumenPendukungPath, // Save file path
                'status_perjalanan' => 'Menunggu Persetujuan',
                'jenis_operasional' => 'Peminjaman',
                'status_operasional' => 'Belum Ditetapkan',
                'pengemudi_id' => null,
                'nopol_kendaraan' => null,
            ];

            // Save file paths to saveData
            $saveData['surat_peminjaman_kendaraan'] = $suratPeminjamanPath;
            $saveData['dokumen_pendukung'] = $dokumenPendukungPath;
            $saveData['surat_izin_kegiatan'] = $suratIzinPath;

            // Save to database
            $perjalanan = Perjalanan::create($saveData);

            // Send WhatsApp notification after successful save
            $this->sendWhatsAppNotification($perjalanan, app(WhatsappService::class));

            return response()->json([
                'success' => true,
                'message' => 'Permohonan peminjaman kendaraan berhasil diajukan!',
                'token' => $token,
                'tracking_url' => route('peminjaman.status', ['token' => $token]),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show tracking status page
     */
    public function status($token)
    {
        $perjalanan = Perjalanan::where('token', $token)->firstOrFail();

        return view('peminjaman-status', [
            'perjalanan' => $perjalanan,
            'token' => $token,
        ]);
    }

    /**
     * Show success page after submission
     */
    public function success($token)
    {
        $perjalanan = Perjalanan::where('token', $token)->firstOrFail();

        return view('peminjaman-sukses', [
            'perjalanan' => $perjalanan,
            'token' => $token,
            'tracking_url' => route('peminjaman.status', ['token' => $token]),
        ]);
    }

    /**
     * Send WhatsApp notification after form submission.
     *
     * @param Perjalanan $perjalanan
     * @param WhatsappService $whatsappService
     * @return void
     */
    private function sendWhatsAppNotification(Perjalanan $perjalanan, WhatsappService $whatsappService): void
    {
        // 1. Find the specific template by its unique number
        $template = NotifikasiWA::where('nomor', 'WA-126D2A90')->first();

        if (!$template) {
            Log::error('Template NotifikasiWA dengan nomor WA-126D2A90 tidak ditemukan.');
            return;
        }

        // 2. Prepare the recipient number and message
        $recipientNumber = $this->formatPhoneNumber($perjalanan->kontak_pengguna_perwakilan);
        $trackingLink = route('peminjaman.status', ['token' => $perjalanan->token]);

        // 3. Construct the final message by appending the tracking link
        $finalMessage = $template->isi_pesan . "\n\nLink Tracking: " . $trackingLink;

        // 4. Use WatzapService to send the message
        $whatsappService->sendMessage($template->number_key, $recipientNumber, $finalMessage);
    }

    /**
     * Format phone number to international format (62)
     *
     * @param string $number
     * @return string
     */
    private function formatPhoneNumber(string $number): string
    {
        // Remove any non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);

        // If number starts with 0, replace it with 62
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }

        return $number;
    }
}
