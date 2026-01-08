<?php

namespace App\Filament\Resources\SPTJBPengemudiResource\Pages;

use App\Filament\Resources\SPTJBPengemudiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListSPTJBPengemudi extends ListRecords
{
    protected static string $resource = SPTJBPengemudiResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getMaxContentWidth(): ?string
    {
        return MaxWidth::Screen->value;
    }
}
