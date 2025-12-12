<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingKendaraanResource\Pages;
use App\Models\Kendaraan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingKendaraanResource extends Resource
{
    protected static ?string $model = Kendaraan::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck'; // Changed icon to truck

    protected static ?string $navigationGroup = 'Poll Kendaraan';
    protected static ?int $navigationSort = 1; // Changed sort order to be after Jadwal Pengemudi

    protected static ?string $navigationLabel = 'Booking Kendaraan'; // Changed navigation label

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nopol_kendaraan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jenis_kendaraan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('merk_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('warna_tanda')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tahun_pembuatan')
                    ->numeric()
                    ->maxLength(4),
                Forms\Components\TextInput::make('nomor_rangka')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nomor_mesin')
                    ->maxLength(255),
                Forms\Components\TextInput::make('jenis_bbm_default')
                    ->maxLength(255),
                Forms\Components\TextInput::make('lokasi_kendaraan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('penggunaan')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('foto_kendaraan')
                    ->image()
                    ->directory('kendaraan-photos'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nopol_kendaraan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kendaraan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('merk_type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lokasi_kendaraan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('foto_kendaraan')
                    ->square(),
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
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingKendaraans::route('/'),
            'create' => Pages\CreateBookingKendaraan::route('/create'),
            'edit' => Pages\EditBookingKendaraan::route('/{record}/edit'),
        ];
    }
}