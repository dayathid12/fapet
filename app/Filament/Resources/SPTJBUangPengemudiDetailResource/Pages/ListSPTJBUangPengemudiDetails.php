<?php

namespace App\Filament\Resources\SPTJBUangPengemudiDetailResource\Pages;

use App\Filament\Resources\SPTJBUangPengemudiDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSPTJBUangPengemudiDetails extends ListRecords
{
    protected static string $resource = SPTJBUangPengemudiDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
