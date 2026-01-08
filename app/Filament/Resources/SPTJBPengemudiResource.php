<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SPTJBPengemudiResource\Pages;
use App\Models\PerjalananKendaraan;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SPTJBPengemudiResource extends Resource
{
    protected static ?string $model = PerjalananKendaraan::class;

    protected static ?string $navigationLabel = 'Daftar Pengemudi';

    protected static ?string $navigationGroup = 'Pelaporan';

    protected static ?string $slug = 'daftar-sptjb-pengemudis';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('perjalanan.nomor_perjalanan')
                    ->label('Nomor Perjalanan')
                    ->searchable(),
                TextColumn::make('perjalanan.no_surat_tugas')
                    ->label('Nomor Surat Tugas')
                    ->searchable(),
                TextColumn::make('pengemudi.nama_staf')
                    ->label('Pengemudi'),
                TextColumn::make('asisten.nama_staf')
                    ->label('Asisten')
                    ->placeholder('Tidak ada'),
                TextColumn::make('kendaraan_nopol')
                    ->label('Kendaraan'),
                TextColumn::make('perjalanan.jenis_kegiatan')
                    ->label('Jenis Kegiatan'),
                TextColumn::make('tipe_penugasan')
                    ->label('Tipe Penugasan')
                    ->badge(),
                TextColumn::make('perjalanan.waktu_keberangkatan')
                    ->label('Waktu Keberangkatan')
                    ->dateTime('d M Y, H:i'),
                TextColumn::make('perjalanan.waktu_kepulangan')
                    ->label('Waktu Kepulangan')
                    ->dateTime('d M Y, H:i'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color('success')
                    ->default('Selesai'),
            ])
            ->filters([
                SelectFilter::make('jenis_kegiatan')
                    ->label('Jenis Kegiatan')
                    ->multiple()
                    ->options([
                        'LK' => 'Luar Kota',
                        'LB' => 'Luar Biasa',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['values'])) {
                            return $query;
                        }
                        return $query->whereHas('perjalanan', function (Builder $q) use ($data) {
                            $q->whereIn('jenis_kegiatan', $data['values']);
                        });
                    }),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $query->whereHas('perjalanan', function (Builder $subQuery) {
                    $subQuery->whereNotNull('upload_surat_tugas')
                             ->where('upload_surat_tugas', '!=', '');
                });
            })
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSPTJBPengemudi::route('/'),
        ];
    }
}
