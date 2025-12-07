<?php

namespace App\Filament\Resources\PerjalananResource\Pages;

use App\Filament\Resources\PerjalananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditPerjalanan extends EditRecord
{
    protected static string $resource = PerjalananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('copyLink')
                ->label('Copy Link Pelacakan')
                ->view('filament.resources.perjalanan-resource.actions.copy-link', ['record' => $this->record]),
        ];
    }
}