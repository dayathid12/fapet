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
            ->columns([
                TextColumn::make('perjalanan.id')
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
                TextColumn::make('tipe_penugasan')
                    ->label('Tipe Tugas')
                    ->searchable(),
                TextColumn::make('kendaraan.merk_type')
                    ->label('Merk/Tipe Kendaraan')
                    ->searchable(),
                TextColumn::make('kendaraan_nopol')
                    ->label('Nomor Polisi')
                    ->searchable(),
                TextColumn::make('waktu_selesai_penugasan')
                    ->label('Waktu Selesai Penugasan')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('perjalanan.waktu_keberangkatan')
                    ->label('Waktu Keberangkatan')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('perjalanan.waktu_kepulangan')
                    ->label('Waktu Kepulangan')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('tanggal_awal_tugas')
                    ->label('Tanggal Awal Tugas')
                    ->getStateUsing(function ($record) {
                        // Handle both 'Antar (Keberangkatan)' and 'Antar & Jemput'
                        if ($record->tipe_penugasan === 'Antar (Keberangkatan)' || $record->tipe_penugasan === 'Antar & Jemput') {
                            $waktuKeberangkatan = $record->perjalanan?->waktu_keberangkatan;
                            return $waktuKeberangkatan;
                        }
                        return null; // Return null for all other cases
                    })
                    ->dateTime('d F Y, H:i')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tanggal_akhir_tugas')
                    ->label('Tanggal Akhir Tugas')
                    ->formatStateUsing(function (string $state, \App\Models\PerjalananKendaraan $record): string {
                        $tipePenugasan = $record->tipe_penugasan;
                        $waktuKepulangan = $record->perjalanan->waktu_kepulangan ?? '';
                        $waktuSelesaiPenugasan = $record->waktu_selesai_penugasan ?? '';

                        if ($tipePenugasan === 'Antar & Jemput' || $tipePenugasan === 'Jemput (Kepulangan)') {
                            return $waktuKepulangan ? \Carbon\Carbon::parse($waktuKepulangan)->format('Y-m-d H:i:s') : '';
                        } elseif ($tipePenugasan === 'Antar (Keberangkatan)') {
                            return $waktuSelesaiPenugasan ? \Carbon\Carbon::parse($waktuSelesaiPenugasan)->format('Y-m-d H:i:s') : '';
                        }
                        return '';
                    })
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('jenis_kegiatan')
                    ->relationship('perjalanan', 'jenis_kegiatan')
                    ->options([
                        'LK' => 'LK (Luar Kota)',
                        'LB' => 'LB (Luar Biasa)',
                    ])
                    ->label('Jenis Kegiatan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
