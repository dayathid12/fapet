<?php

namespace App\Filament\Resources\SPTJBResource\Pages;

use App\Filament\Resources\SPTJBResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSPTJBS extends ListRecords
{
    protected static string $resource = SPTJBResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
