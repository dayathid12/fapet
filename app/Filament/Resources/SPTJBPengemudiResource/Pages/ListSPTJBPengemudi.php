<?php

namespace App\Filament\Resources\SPTJBPengemudiResource\Pages;

use App\Filament\Resources\SPTJBPengemudiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListSPTJBPengemudi extends ListRecords
{
    protected static string $resource = SPTJBPengemudiResource::class;

    protected static ?string $title = 'Daftar SPTJB Pengemudi';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $model = static::getResource()::getModel();

        // Replicate the global filter from the Resource file to ensure badge count is correct
        $applyGlobalFilter = function (Builder $query) {
            $query->whereHas('perjalanan', function (Builder $subQuery) {
                $subQuery->whereNotNull('upload_surat_tugas')
                         ->where('upload_surat_tugas', '!=', '');
            });
        };

        $ajukanQuery = $model::query();
        $applyGlobalFilter($ajukanQuery);
        $ajukanCount = $ajukanQuery->whereHasNotBeenProcessed()->count();

        $selesaiQuery = $model::query();
        $applyGlobalFilter($selesaiQuery);
        $selesaiCount = $selesaiQuery->whereHasBeenProcessed()->count();

        return [
            'Ajukan' => Tab::make('Ajukan')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHasNotBeenProcessed())
                ->badge($ajukanCount),
            'Selesai' => Tab::make('Selesai')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHasBeenProcessed())
                ->badge($selesaiCount),
        ];
    }
}
