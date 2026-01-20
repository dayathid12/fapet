<?php

namespace App\Filament\Resources\SPTJBPengemudiResource\Pages;

use App\Filament\Resources\SPTJBPengemudiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListSPTJBPengemudi extends ListRecords
{
    protected static string $resource = SPTJBPengemudiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Ajukan' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Ajukan'))
                ->badge(SPTJBPengemudiResource::getModel()::where('status', 'Ajukan')->count()),
            'Selesai' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Selesai'))
                ->badge(SPTJBPengemudiResource::getModel()::where('status', 'Selesai')->count()),
        ];
    }
}
