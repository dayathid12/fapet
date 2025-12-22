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

        return $data;
    }
}
