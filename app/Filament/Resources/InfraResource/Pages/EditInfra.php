<?php

namespace App\Filament\Resources\InfraResource\Pages;

use App\Filament\Resources\InfraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInfra extends EditRecord
{
    protected static string $resource = InfraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
