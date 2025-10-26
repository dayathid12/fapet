<?php

namespace App\Filament\Resources\PenerimaSPTJBResource\Pages;

use App\Filament\Resources\PenerimaSPTJBResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenerimaSPTJBS extends ListRecords
{
    protected static string $resource = PenerimaSPTJBResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
