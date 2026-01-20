<?php

namespace App\Filament\Resources\SuratTugasResource\Pages;

use App\Filament\Resources\SuratTugasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\MaxWidth;

class ListSuratTugas extends ListRecords
{
    protected static string $resource = SuratTugasResource::class;

    protected static ?string $title = 'Surat Tugas';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getMaxContentWidth(): ?string
    {
        return MaxWidth::Screen->value;
    }

    public function getTabs(): array
    {
        return [
            'pengajuan' => Tab::make('Pengajuan')
                ->icon('heroicon-o-document')
                ->badge(function () {
                    return static::getResource()::getEloquentQuery()
                        ->whereHas('perjalanan', function (Builder $query) {
                            $query->whereIn('status_perjalanan', ['Selesai', 'Terjadwal'])
                                  ->whereRaw("TRIM(COALESCE(jenis_kegiatan, '')) <> ''")
                                  ->where('jenis_kegiatan', '!=', 'DK')
                                  ->where(fn ($q) => $q->whereNull('no_surat_tugas')->orWhere('no_surat_tugas', ''))
                                  ->where(fn ($q) => $q->whereNull('upload_surat_tugas')->orWhere('upload_surat_tugas', ''));
                        })->count();
                })
                ->badgeColor('gray')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('perjalanan', function (Builder $subQuery) {
                        $subQuery->where(fn ($q) => $q->whereNull('no_surat_tugas')->orWhere('no_surat_tugas', ''))
                                 ->where(fn ($q) => $q->whereNull('upload_surat_tugas')->orWhere('upload_surat_tugas', ''));
                    });
                }),

            'proses' => Tab::make('Proses')
                ->icon('heroicon-o-cog')
                ->badge(function () {
                    return static::getResource()::getEloquentQuery()
                        ->whereHas('perjalanan', function (Builder $query) {
                            $query->where(fn ($q) => $q->whereNotNull('no_surat_tugas')->where('no_surat_tugas', '!=', ''))
                                  ->where(fn ($q) => $q->whereNull('upload_surat_tugas')->orWhere('upload_surat_tugas', ''));
                        })->count();
                })
                ->badgeColor('warning')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('perjalanan', function (Builder $subQuery) {
                        $subQuery->where(fn ($q) => $q->whereNotNull('no_surat_tugas')->where('no_surat_tugas', '!=', ''))
                                 ->where(fn ($q) => $q->whereNull('upload_surat_tugas')->orWhere('upload_surat_tugas', ''));
                    });
                }),

            'selesai' => Tab::make('Selesai')
                ->icon('heroicon-o-check-circle')
                ->badge(function () {
                    return static::getResource()::getEloquentQuery()
                        ->whereHas('perjalanan', function (Builder $query) {
                            $query->where(fn ($q) => $q->whereNotNull('upload_surat_tugas')->where('upload_surat_tugas', '!=', ''));
                        })->count();
                })
                ->badgeColor('success')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('perjalanan', function (Builder $subQuery) {
                        $subQuery->where(fn ($q) => $q->whereNotNull('upload_surat_tugas')->where('upload_surat_tugas', '!=', ''));
                    });
                }),
        ];
    }
}
