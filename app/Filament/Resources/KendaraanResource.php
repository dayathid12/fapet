<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KendaraanResource\Pages;
use App\Filament\Resources\KendaraanResource\RelationManagers;
use App\Models\Kendaraan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KendaraanResource extends Resource
{
    protected static ?string $model = Kendaraan::class;

      protected static ?string $navigationLabel = 'Data Kendaraan';
  protected static ?string $navigationGroup = 'Poll Kendaraan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('jenis_kendaraan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('merk_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('warna_tanda')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tahun_pembuatan'),
                Forms\Components\TextInput::make('nomor_rangka')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nomor_mesin')
                    ->maxLength(255),
                Forms\Components\TextInput::make('jenis_bbm_default')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nopol_kendaraan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kendaraan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('merk_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('warna_tanda')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_pembuatan'),
                Tables\Columns\TextColumn::make('nomor_rangka')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_mesin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_bbm_default')
                    ->searchable(),
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
            'index' => Pages\ListKendaraans::route('/'),
            'create' => Pages\CreateKendaraan::route('/create'),
            'edit' => Pages\EditKendaraan::route('/{record}/edit'),
        ];
    }
}
