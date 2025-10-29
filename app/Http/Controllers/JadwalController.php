<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perjalanan;
use App\Models\Kendaraan;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->input('bulan', Carbon::now()->format('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $selectedMonth);

        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        $daysInMonth = $date->daysInMonth;

        // 1. Ambil semua kendaraan
        $kendaraans = Kendaraan::orderBy('nopol')->get();

        $jadwal = [];

        foreach ($kendaraans as $kendaraan) {
            $nopol = $kendaraan->nopol;
            // 2. Inisialisasi jadwal untuk setiap kendaraan
            $jadwal[$nopol] = array_fill(1, $daysInMonth, ['status' => 'Tersedia', 'kegiatan' => '-']);

            // 3. Ambil perjalanan untuk kendaraan ini di bulan yang dipilih
            $perjalanans = Perjalanan::where('nopol_kendaraan', $nopol)
                ->where('status_perjalanan', '!= ', 'Ditolak')
                ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                    $query->whereBetween('waktu_keberangkatan', [$startOfMonth, $endOfMonth])
                          ->orWhereBetween('waktu_kepulangan', [$startOfMonth, $endOfMonth])
                          ->orWhere(function ($sub) use ($startOfMonth, $endOfMonth) {
                              $sub->where('waktu_keberangkatan', '<=', $startOfMonth)
                                  ->where('waktu_kepulangan', '>=', $endOfMonth);
                          });
                })
                ->get();

            // 4. Update jadwal dengan data perjalanan
            foreach ($perjalanans as $perjalanan) {
                $startDay = $perjalanan->waktu_keberangkatan->isBefore($startOfMonth) ? 1 : $perjalanan->waktu_keberangkatan->day;
                $endDay = $perjalanan->waktu_kepulangan->isAfter($endOfMonth) ? $daysInMonth : $perjalanan->waktu_kepulangan->day;

                for ($day = $startDay; $day <= $endDay; $day++) {
                    if (isset($jadwal[$nopol][$day])) {
                        $status_map = [
                            'Terjadwal' => 'Terjadwal',
                            'Menunggu Persetujuan' => 'Menunggu'
                        ];
                        $jadwal[$nopol][$day] = [
                            'status' => $status_map[$perjalanan->status_perjalanan] ?? 'Tersedia',
                            'kegiatan' => $perjalanan->nama_kegiatan . ' (' . $perjalanan->waktu_keberangkatan->format('H:i') . ' - ' . $perjalanan->waktu_kepulangan->format('H:i') . ')'
                        ];
                    }
                }
            }
        }

        // Buat daftar bulan untuk dropdown
        $bulanList = [];
        for ($i = 0; $i < 12; $i++) {
            $loopDate = Carbon::now()->addMonths($i);
            $bulanList[$loopDate->format('Y-m')] = $loopDate->format('m - F Y');
        }

        return view('jadwal.index', [
            'jadwal' => $jadwal,
            'daysInMonth' => $daysInMonth,
            'selectedMonth' => $selectedMonth,
            'date' => $date,
            'bulanList' => $bulanList
        ]);
    }
}
