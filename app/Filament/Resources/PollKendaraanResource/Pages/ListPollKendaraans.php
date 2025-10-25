<?php

namespace App\Filament\Resources\PollKendaraanResource\Pages;

use App\Filament\Resources\PollKendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPollKendaraans extends ListRecords
{
    protected static string $resource = PollKendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
