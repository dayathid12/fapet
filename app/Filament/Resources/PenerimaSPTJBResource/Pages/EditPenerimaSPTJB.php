<?php

namespace App\Filament\Resources\PenerimaSPTJBResource\Pages;

use App\Filament\Resources\PenerimaSPTJBResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenerimaSPTJB extends EditRecord
{
    protected static string $resource = PenerimaSPTJBResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
