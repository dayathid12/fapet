<?php

namespace App\Filament\Resources\PerjalananResource\Pages;

trait MutatesPerjalananData
{
    protected function mutateDetailsData(array $data): array
    {
        if (isset($data['details'])) {
            $globalBerangkat = $data['waktu_keberangkatan'] ?? null;
            $globalPulang = $data['waktu_kepulangan'] ?? null;

            $data['details'] = array_map(function ($detail) use ($globalBerangkat, $globalPulang) {
                $tipeTugas = $detail['tipe_penugasan'] ?? null;
                $waktuSelesaiPenugasan = $detail['waktu_selesai_penugasan'] ?? null;

                $waktuMulai = null;
                $waktuSelesai = null;

                if ($tipeTugas === 'Antar & Jemput') {
                    $waktuMulai = $globalBerangkat;
                    $waktuSelesai = $globalPulang;
                } elseif ($tipeTugas === 'Antar (Keberangkatan)') {
                    $waktuMulai = $globalBerangkat;
                    $waktuSelesai = $waktuSelesaiPenugasan;
                } elseif ($tipeTugas === 'Jemput (Kepulangan)') {
                    // Dalam skenario jemput, perjalanan dimulai pada waktu 'selesai penugasan'
                    // yang seharusnya diisi oleh pengguna sebagai titik awal jemput.
                    $waktuMulai = $waktuSelesaiPenugasan;
                    $waktuSelesai = $globalPulang;
                }

                $detail['waktu_mulai_tugas'] = $waktuMulai;
                $detail['waktu_selesai_tugas'] = $waktuSelesai;

                return $detail;
            }, $data['details']);
        }

        return $data;
    }
}
