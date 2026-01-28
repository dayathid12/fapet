<?php

namespace App\Filament\Resources\PengajuanPrResource\Pages;

use App\Filament\Resources\PengajuanPrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanPrs extends ListRecords
{
    protected static string $resource = PengajuanPrResource::class;

    protected static string $view = 'filament.resources.pengajuan-pr-resource.pages.list-pengajuan-prs';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Pengajuan')
                ->icon('heroicon-s-plus')
                ->size('lg')
                ->button(),
        ];
    }

    /**
     * Define the CreateAction for this page, used in custom Blade views.
     */
    public function getCreateAction(): Actions\CreateAction
    {
        return Actions\CreateAction::make()
            ->label('Buat Pengajuan') // Consistent with getHeaderActions
            ->icon('heroicon-s-plus') // Consistent with getHeaderActions
            ->size('lg'); // Consistent with getHeaderActions
    }
}
