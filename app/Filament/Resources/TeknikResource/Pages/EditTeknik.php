<?php

namespace App\Filament\Resources\TeknikResource\Pages;

use App\Filament\Resources\TeknikResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeknik extends EditRecord
{
    protected static string $resource = TeknikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
