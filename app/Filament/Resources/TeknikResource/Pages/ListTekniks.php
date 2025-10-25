<?php

namespace App\Filament\Resources\TeknikResource\Pages;

use App\Filament\Resources\TeknikResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTekniks extends ListRecords
{
    protected static string $resource = TeknikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
