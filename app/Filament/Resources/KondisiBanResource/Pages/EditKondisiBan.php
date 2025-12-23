<?php

namespace App\Filament\Resources\KondisiBanResource\Pages;

use App\Filament\Resources\KondisiBanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKondisiBan extends EditRecord
{
    protected static string $resource = KondisiBanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}