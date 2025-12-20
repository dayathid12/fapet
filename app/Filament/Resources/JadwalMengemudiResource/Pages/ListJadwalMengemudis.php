<?php

namespace App\Filament\Resources\JadwalMengemudiResource\Pages;

use App\Filament\Resources\JadwalMengemudiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwalMengemudis extends ListRecords
{
    protected static string $resource = JadwalMengemudiResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
