<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratTugasResource\Pages;
use App\Filament\Resources\SuratTugasResource\RelationManagers;
use App\Models\Perjalanan; // Used for relationships
use App\Models\PerjalananKendaraan; // New base model
use App\Models\Kendaraan;
use App\Models\Staf;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuratTugasResource extends Resource
{
    protected static ?string $model = \App\Models\PerjalananKendaraan::class;

    protected static ?string $navigationLabel = 'Surat Tugas';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('perjalanan_id')
                    ->label('Nomor Perjalanan / Surat Tugas')
                    ->options(Perjalanan::all()->pluck('no_surat_tugas', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('pengemudi_id')
                    ->label('Pengemudi')
                    ->options(Staf::all()->pluck('nama_staf', 'staf_id'))
                    ->searchable()
                    ->required(),
                Select::make('kendaraan_nopol')
                    ->label('Nomor Polisi Kendaraan')
                    ->options(Kendaraan::all()->pluck('nopol_kendaraan', 'nopol_kendaraan'))
                    ->searchable()
                    ->required(),
                Select::make('asisten_id')
                    ->label('Asisten')
                    ->options(Staf::all()->pluck('nama_staf', 'staf_id'))
                    ->searchable()
                    ->nullable(),
                TextInput::make('tipe_penugasan')
                    ->maxLength(255)
                    ->nullable(),
                DateTimePicker::make('waktu_selesai_penugasan')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->whereHas('perjalanan', function ($q) {
                    $q->where('status_perjalanan', 'Terjadwal')
                      ->where('jenis_kegiatan', '!=', 'DK');
                });
            })
            ->columns([
                TextColumn::make('perjalanan.nomor_perjalanan')
                    ->label('Nomor Perjalanan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('perjalanan.no_surat_tugas')
                    ->label('Nomor Surat')
                    ->searchable(),
                TextColumn::make('pengemudi.nama_staf')
                    ->label('Pengemudi')
                    ->searchable(),
                TextColumn::make('asisten.nama_staf')
                    ->label('Asisten Pengemudi')
                    ->searchable(),
                TextColumn::make('tanggal_awal_tugas')
                    ->label('Tanggal Awal Tugas')
                    ->getStateUsing(function (\App\Models\PerjalananKendaraan $record): ?string {
                        switch ($record->tipe_penugasan) {
                            case 'Antar & Jemput':
                            case 'Antar (Keberangkatan)':
                                return $record->perjalanan?->waktu_keberangkatan;
                            case 'Jemput (Kepulangan)':
                                return $record->perjalanan?->waktu_kepulangan;
                            default:
                                return null;
                        }
                    })
                    ->dateTime('d F Y, H:i')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tanggal_akhir_tugas')
                    ->label('Tanggal Akhir Tugas')
                    ->getStateUsing(function (\App\Models\PerjalananKendaraan $record): ?string {
                        switch ($record->tipe_penugasan) {
                            case 'Antar & Jemput':
                                return $record->perjalanan?->waktu_kepulangan;
                            case 'Antar (Keberangkatan)':
                                return $record->waktu_selesai_penugasan;
                            case 'Jemput (Kepulangan)':
                                return $record->waktu_selesai_penugasan;
                            default:
                                return null;
                        }
                    })
                    ->dateTime('d F Y, H:i')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tipe_penugasan')
                    ->label('Tipe Tugas')
                    ->searchable(),
                TextColumn::make('perjalanan.jenis_kegiatan')
                    ->label('Jenis Kegiatan')
                    ->searchable(),
                TextColumn::make('perjalanan.status_perjalanan')
                    ->label('Status Perjalanan')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\IconButtonAction::make('download_surat_tugas')
                    ->label('Surat Tugas')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (\App\Models\PerjalananKendaraan $record): string => route('surat-tugas.pdf', ['record' => $record->perjalanan_id]))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('edit_perjalanan_kendaraan')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->modal()
                    ->fillForm(fn (\App\Models\PerjalananKendaraan $record): array => [
                        'perjalanan_id' => $record->perjalanan_id,
                        'pengemudi_id' => $record->pengemudi_id,
                        'kendaraan_nopol' => $record->kendaraan_nopol,
                        'asisten_id' => $record->asisten_id,
                        'tipe_penugasan' => $record->tipe_penugasan,
                        'waktu_selesai_penugasan' => $record->waktu_selesai_penugasan,
                    ])
                    ->form([
                        Select::make('perjalanan_id')
                            ->label('Nomor Perjalanan / Surat Tugas')
                            ->options(Perjalanan::all()->pluck('no_surat_tugas', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('pengemudi_id')
                            ->label('Pengemudi')
                            ->options(Staf::all()->pluck('nama_staf', 'staf_id'))
                            ->searchable()
                            ->required(),
                        Select::make('kendaraan_nopol')
                            ->label('Nomor Polisi Kendaraan')
                            ->options(Kendaraan::all()->pluck('nopol_kendaraan', 'nopol_kendaraan'))
                            ->searchable()
                            ->required(),
                        Select::make('asisten_id')
                            ->label('Asisten')
                            ->options(Staf::all()->pluck('nama_staf', 'staf_id'))
                            ->searchable()
                            ->nullable(),
                        TextInput::make('tipe_penugasan')
                            ->maxLength(255)
                            ->nullable(),
                        DateTimePicker::make('waktu_selesai_penugasan')
                            ->nullable(),
                    ])
                    ->action(function (\App\Models\PerjalananKendaraan $record, array $data) {
                        $record->update($data);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratTugas::route('/'),
            'create' => Pages\CreateSuratTugas::route('/create'),
            'edit' => Pages\EditSuratTugas::route('/{record}/edit'),
        ];
    }
}
