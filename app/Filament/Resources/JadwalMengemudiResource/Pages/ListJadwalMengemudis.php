<?php

namespace App\Filament\Resources\JadwalMengemudiResource\Pages;

use App\Filament\Resources\JadwalMengemudiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwalMengemudis extends ListRecords
{
    protected static string $resource = JadwalMengemudiResource::class;

    protected static string $view = 'filament.resources.jadwal-mengemudi-resource.pages.list-jadwal-mengemudis';

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    protected function getViewData(): array
    {
        $data = parent::getViewData();
        $data['dataRecords'] = $this->getTableRecords();

        // Calculate total jam by summing hours from all records
        $query = $this->getTableQuery();
        $allRecords = $query->get();
        $totalJam = 0;
        foreach ($allRecords as $record) {
            if ($record->waktu_keberangkatan && $record->waktu_kepulangan) {
                $start = \Carbon\Carbon::parse($record->waktu_keberangkatan);
                $end = \Carbon\Carbon::parse($record->waktu_kepulangan);
                $totalJam += $start->diffInHours($end);
            }
        }
        $data['totalJam'] = $totalJam;
        $user = auth()->user();

        // Menggunakan relasi untuk mendapatkan data staf, ini lebih andal
        $staf = $user->staf; 

        $data['userName'] = $staf ? $staf->nama_staf : $user->name; // Fallback ke nama user jika relasi staf tidak ada
        $data['userNip'] = $staf ? $staf->nip_staf : 'N/A'; // Tampilkan N/A jika relasi staf tidak ada

        return $data;
    }
}
