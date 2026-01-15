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
            'semua' => Tab::make('Semua')
                ->icon('heroicon-o-queue-list'),

            'ajukan' => Tab::make('Ajukan')
                ->icon('heroicon-o-document')
                ->badge(function () {
                    return static::getResource()::getEloquentQuery()
                        ->whereHas('perjalanan', function (Builder $query) {
                            $query->whereNotNull('upload_surat_tugas')
                                 ->where('upload_surat_tugas', '!=', '');
                        })
                        ->whereDoesntHave('pengemudi', function ($q) {
                            $q->whereExists(function ($exists) {
                                $exists->selectRaw('1')
                                       ->from('sptjb_uang_pengemudi_details')
                                       ->whereRaw('sptjb_uang_pengemudi_details.nama = stafs.nama_staf');
                            });
                        })
                        ->whereDoesntHave('asisten', function ($q) {
                            $q->whereExists(function ($exists) {
                                $exists->selectRaw('1')
                                       ->from('sptjb_uang_pengemudi_details')
                                       ->whereRaw('sptjb_uang_pengemudi_details.nama = stafs.nama_staf');
                            });
                        })
                        ->count();
                })
                ->badgeColor('gray')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('perjalanan', function (Builder $subQuery) {
                        $subQuery->whereNotNull('upload_surat_tugas')
                                 ->where('upload_surat_tugas', '!=', '');
                    })
                    ->whereDoesntHave('pengemudi', function ($q) {
                        $q->whereExists(function ($exists) {
                            $exists->selectRaw('1')
                                   ->from('sptjb_uang_pengemudi_details')
                                   ->whereRaw('sptjb_uang_pengemudi_details.nama = stafs.nama_staf');
                        });
                    })
                    ->whereDoesntHave('asisten', function ($q) {
                        $q->whereExists(function ($exists) {
                            $exists->selectRaw('1')
                                   ->from('sptjb_uang_pengemudi_details')
                                   ->whereRaw('sptjb_uang_pengemudi_details.nama = stafs.nama_staf');
                        });
                    });
                }),

            'selesai' => Tab::make('Selesai')
                ->icon('heroicon-o-check-circle')
                ->badge(function () {
                    return static::getResource()::getEloquentQuery()
                        ->whereHas('perjalanan', function (Builder $query) {
                            $query->whereNotNull('upload_surat_tugas')
                                 ->where('upload_surat_tugas', '!=', '');
                        })
                        ->where(function ($query) {
                            $query->whereHas('pengemudi', function ($q) {
                                $q->whereExists(function ($exists) {
                                    $exists->selectRaw('1')
                                           ->from('sptjb_uang_pengemudi_details')
                                           ->whereRaw('sptjb_uang_pengemudi_details.nama = stafs.nama_staf');
                                });
                            })->orWhereHas('asisten', function ($q) {
                                $q->whereExists(function ($exists) {
                                    $exists->selectRaw('1')
                                           ->from('sptjb_uang_pengemudi_details')
                                           ->whereRaw('sptjb_uang_pengemudi_details.nama = stafs.nama_staf');
                                });
                            });
                        })
                        ->count();
                })
                ->badgeColor('success')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('perjalanan', function (Builder $subQuery) {
                        $subQuery->whereNotNull('upload_surat_tugas')
                                 ->where('upload_surat_tugas', '!=', '');
                    })
                    ->where(function ($query) {
                        $query->whereHas('pengemudi', function ($q) {
                            $q->whereExists(function ($exists) {
                                $exists->selectRaw('1')
                                       ->from('sptjb_uang_pengemudi_details')
                                       ->whereRaw('sptjb_uang_pengemudi_details.nama = stafs.nama_staf');
                            });
                        })->orWhereHas('asisten', function ($q) {
                            $q->whereExists(function ($exists) {
                                $exists->selectRaw('1')
                                       ->from('sptjb_uang_pengemudi_details')
                                       ->whereRaw('sptjb_uang_pengemudi_details.nama = stafs.nama_staf');
                            });
                        });
                    });
                }),
        ];
    }
}
