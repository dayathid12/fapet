<?php

namespace App\Filament\Resources\SPTJBResource\Pages;

use App\Filament\Resources\SPTJBResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSPTJB extends EditRecord
{
    protected static string $resource = SPTJBResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
