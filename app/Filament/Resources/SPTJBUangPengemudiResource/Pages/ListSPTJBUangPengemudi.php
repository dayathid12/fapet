<?php

namespace App\Filament\Resources\SPTJBUangPengemudiResource\Pages;

use App\Filament\Resources\SPTJBUangPengemudiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSPTJBUangPengemudi extends ListRecords
{
    protected static string $resource = SPTJBUangPengemudiResource::class;

    protected static string $view = 'filament-panels::resources.pages.list-records';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}
