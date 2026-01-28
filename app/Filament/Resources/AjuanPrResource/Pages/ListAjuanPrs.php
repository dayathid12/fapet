<?php

namespace App\Filament\Resources\AjuanPrResource\Pages;

use App\Filament\Resources\AjuanPrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAjuanPrs extends ListRecords
{
    protected static string $resource = AjuanPrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
