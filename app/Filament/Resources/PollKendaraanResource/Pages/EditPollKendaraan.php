<?php

namespace App\Filament\Resources\PollKendaraanResource\Pages;

use App\Filament\Resources\PollKendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPollKendaraan extends EditRecord
{
    protected static string $resource = PollKendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
