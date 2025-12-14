<?php

namespace App\Filament\Resources\PerjalananResource\Pages;

use App\Filament\Resources\PerjalananResource;
use App\Filament\Resources\BaseEditRecord;
use Filament\Actions;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Request; // Import Request facade
use App\Filament\Pages\ScheduleOverview; // Import ScheduleOverview page

class EditPerjalanan extends BaseEditRecord
{
    protected static string $resource = PerjalananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('copyLink')
                ->label('Copy Link Pelacakan')
                ->view('filament.resources.perjalanan-resource.actions.copy-link', ['record' => $this->record]),
        ];
    }

    protected function getRedirectUrl(): string
    {
        if (Request::query('returnTo') === 'calendar') {
            return ScheduleOverview::getUrl();
        }

        return parent::getRedirectUrl();
    }
}
