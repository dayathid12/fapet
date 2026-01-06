<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratTugasResource\Pages;
use App\Filament\Resources\SuratTugasResource\RelationManagers;
use App\Models\Perjalanan; // Used for relationships
use App\Models\PerjalananKendaraan; // New base model
use App\Models\Kendaraan;
use App\Models\Staf;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
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
                    ->options(Perjalanan::all()->pluck('nomor_perjalanan', 'id'))
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
                    ->searchable()
                    ->formatStateUsing(fn (?string $state): string => $state ?? ''),
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
                TextColumn::make('status_surat_tugas')
                    ->label('Status')
                    ->getStateUsing(function (\App\Models\PerjalananKendaraan $record): string {
                        $perjalanan = $record->perjalanan;
                        if (!empty($perjalanan->upload_surat_tugas)) {
                            return 'Selesai';
                        } elseif (!empty($perjalanan->no_surat_tugas)) {
                            return 'Proses';
                        } else {
                            return 'Pengajuan';
                        }
                    })
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\Action::make('download_surat_tugas')
                    ->label('')
                    ->tooltip('Download Surat Tugas')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (\App\Models\PerjalananKendaraan $record): string => $record->perjalanan->no_surat_tugas ? route('surat-tugas.pdf', ['no_surat_tugas' => $record->perjalanan->no_surat_tugas]) : '#')
                    ->openUrlInNewTab()
                    ->visible(fn (\App\Models\PerjalananKendaraan $record): bool => !empty($record->perjalanan->no_surat_tugas)),
                Tables\Actions\Action::make('edit_perjalanan_kendaraan')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->modal()
                    ->fillForm(fn (\App\Models\PerjalananKendaraan $record): array => [
                        'perjalanan_id' => $record->perjalanan_id,
                        'no_surat_tugas' => $record->perjalanan->no_surat_tugas,
                        'upload_tte' => $record->perjalanan->upload_tte,
                        'upload_surat_tugas' => $record->perjalanan->upload_surat_tugas,
                        'pengemudi_id' => $record->pengemudi_id,
                        'kendaraan_nopol' => $record->kendaraan_nopol,
                        'asisten_id' => $record->asisten_id,
                        'tipe_penugasan' => $record->tipe_penugasan,
                        'waktu_selesai_penugasan' => $record->waktu_selesai_penugasan,
                    ])
                    ->form([
                        Placeholder::make('Detail Perjalanan')
                            ->label('Detail Perjalanan'),
                        Grid::make(2)
                            ->schema([
                                Select::make('perjalanan_id')
                                    ->label('Nomor Perjalanan')
                                    ->options(Perjalanan::all()->pluck('nomor_perjalanan', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->disabled(),
                                Select::make('pengemudi_id')
                                    ->label('Pengemudi')
                                    ->options(Staf::all()->pluck('nama_staf', 'staf_id'))
                                    ->searchable()
                                    ->required()
                                    ->disabled(),
                                Select::make('kendaraan_nopol')
                                    ->label('Nomor Polisi Kendaraan')
                                    ->options(Kendaraan::all()->pluck('nopol_kendaraan', 'nopol_kendaraan'))
                                    ->searchable()
                                    ->required()
                                    ->disabled(),
                                Select::make('asisten_id')
                                    ->label('Asisten')
                                    ->options(Staf::all()->pluck('nama_staf', 'staf_id'))
                                    ->searchable()
                                    ->nullable()
                                    ->disabled(),
                                TextInput::make('tipe_penugasan')
                                    ->label('Tipe Penugasan')
                                    ->maxLength(255)
                                    ->nullable()
                                    ->disabled(),
                                DateTimePicker::make('waktu_selesai_penugasan')
                                    ->label('Waktu Selesai Penugasan')
                                    ->nullable()
                                    ->disabled(),
                            ]),
                        TextInput::make('no_surat_tugas')
                            ->label('Nomor Surat Tugas')
                            ->maxLength(255)
                            ->nullable(),
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('upload_tte')
                                    ->label('Upload File TTE')
                                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                                    ->maxSize(10240) // 10MB
                                    ->directory('tte')
                                    ->nullable(),
                                FileUpload::make('upload_surat_tugas')
                                    ->label('Upload File Surat Tugas')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->maxSize(10240) // 10MB
                                    ->directory('surat-tugas')
                                    ->nullable(),
                            ]),
                    ])
                    ->action(function (\App\Models\PerjalananKendaraan $record, array $data) {
                        // The main model for this resource is PerjalananKendaraan.
                        // We only update the related Perjalanan model here.
                        $record->perjalanan->update($data);
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
