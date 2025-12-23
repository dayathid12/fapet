<?php

namespace App\Filament\Resources\KondisiBanResource\Pages;

use App\Filament\Resources\KondisiBanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKondisiBans extends ListRecords
{
    protected static string $resource = KondisiBanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}