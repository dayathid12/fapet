<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerjalananResource\Pages;
use App\Filament\Resources\PerjalananResource\RelationManagers;
use App\Models\Perjalanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerjalananResource extends Resource
{
    protected static ?string $model = Perjalanan::class;

    protected static ?string $navigationLabel = 'Pelayanan Perjalanan';
    protected static ?string $navigationGroup = 'Poll Kendaraan';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)

                    ->schema([
                        // Kolom 1
                        Forms\Components\ToggleButtons::make('status_operasional')
                            ->options([
                                'Peminjaman' => 'Peminjaman',
                                'Operasional' => 'Operasional',
                            ])
                            ->grouped()
                            ->required(),

                        // Kolom 2
                        Forms\Components\ToggleButtons::make('status_perjalanan')
                            ->options([
                                'Menunggu Persetujuan' => 'Menunggu Persetujuan',
                                'Terjadwal' => 'Terjadwal',
                                'Ditolak' => 'Ditolak',
                            ])
                            ->grouped()
                            ->required(),

                        // Kolom 3
                        Forms\Components\ToggleButtons::make('jenis_kegiatan')
                            ->options([
                                'LK' => 'LK',
                                'DK' => 'DK',
                                'LB' => 'LB',
                            ])
                            ->grouped()
                            ->required(),
                    ]),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\DateTimePicker::make('waktu_keberangkatan')
                            ->required(),
                        Forms\Components\DateTimePicker::make('waktu_kepulangan'),
                    ]),

                Forms\Components\Textarea::make('alamat_tujuan')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('lokasi_keberangkatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jumlah_rombongan')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('nama_kegiatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jenis_operasional')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('no_surat_tugas')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('file_surat_jalan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('docs_surat_tugas')
                    ->maxLength(255),
                Forms\Components\TextInput::make('upload_surat_tugas')
                    ->maxLength(255),
                Forms\Components\TextInput::make('download_file')
                    ->maxLength(255),
                Forms\Components\Toggle::make('status_cek_1')
                    ->required(),
                Forms\Components\Toggle::make('status_cek_2')
                    ->required(),
                Forms\Components\TextInput::make('pengguna_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('pengemudi_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('asisten_id')
                    ->numeric(),
                Forms\Components\TextInput::make('nopol_kendaraan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tujuan_wilayah_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_perjalanan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_keberangkatan')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_kepulangan')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_perjalanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokasi_keberangkatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_rombongan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_operasional')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_operasional')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_surat_tugas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_surat_jalan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('docs_surat_tugas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('upload_surat_tugas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('download_file')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status_cek_1')
                    ->boolean(),
                Tables\Columns\IconColumn::make('status_cek_2')
                    ->boolean(),
                Tables\Columns\TextColumn::make('pengguna_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pengemudi_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asisten_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nopol_kendaraan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tujuan_wilayah_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListPerjalanans::route('/'),
            'create' => Pages\CreatePerjalanan::route('/create'),
            'edit' => Pages\EditPerjalanan::route('/{record}/edit'),
        ];
    }
}
