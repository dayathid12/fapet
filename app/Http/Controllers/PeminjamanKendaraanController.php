<?php

namespace App\Http\Controllers;

use App\Models\Perjalanan;
use App\Models\Wilayah;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $data = $request->all();

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
            'surat_peminjaman' => 'required|file|mimes:pdf,jpg,png|max:5120',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
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

            // Prepare data for saving
            $saveData = [
                'token' => $token,
                'waktu_keberangkatan' => $data['waktu_keberangkatan'],
                'waktu_kepulangan' => $data['waktu_kepulangan'] ?? null,
                'lokasi_keberangkatan' => $data['lokasi_keberangkatan'],
                'jumlah_rombongan' => $data['jumlah_rombongan'],
                'alamat_tujuan' => $data['alamat_tujuan'],
                'nama_kegiatan' => $data['nama_kegiatan'],
                'jenis_kegiatan' => $data['nama_kegiatan'],
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
                'status_perjalanan' => 'Menunggu Persetujuan',
                'jenis_operasional' => 'Peminjaman',
                'status_operasional' => 'Belum Ditetapkan',
                'pengemudi_id' => null,
                'nopol_kendaraan' => null,
            ];

            // Handle file uploads
            if ($request->hasFile('surat_peminjaman')) {
                $saveData['surat_peminjaman_kendaraan'] = $request->file('surat_peminjaman')->store('public/dokumen_peminjaman');
            }

            if ($request->hasFile('dokumen_pendukung')) {
                $saveData['dokumen_pendukung'] = $request->file('dokumen_pendukung')->store('public/dokumen_peminjaman');
            }

            // Save to database
            $perjalanan = Perjalanan::create($saveData);

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
}

