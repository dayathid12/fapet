<?php

namespace App\Filament\Resources\PengajuanPrResource\Pages;

use App\Filament\Resources\PengajuanPrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanPrs extends ListRecords
{
    protected static string $resource = PengajuanPrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
