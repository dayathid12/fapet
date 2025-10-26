<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BiayaTolResource\Pages;
use App\Filament\Resources\BiayaTolResource\RelationManagers;
use App\Models\BiayaTol;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BiayaTolResource extends Resource
{
    protected static ?string $model = BiayaTol::class;

  protected static ?string $navigationLabel = 'Biaya Tol';
   
  protected static ?string $navigationGroup = 'Poll Kendaraan';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor_perjalanan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('lokasi_tol')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kode_kartu_tol')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('biaya_tol')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('gambar_bukti')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_perjalanan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lokasi_tol')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kode_kartu_tol')
                    ->searchable(),
                Tables\Columns\TextColumn::make('biaya_tol')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gambar_bukti')
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
            'index' => Pages\ListBiayaTols::route('/'),
            'create' => Pages\CreateBiayaTol::route('/create'),
            'edit' => Pages\EditBiayaTol::route('/{record}/edit'),
        ];
    }
}
