<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeknikResource\Pages;
use App\Filament\Resources\TeknikResource\RelationManagers;
use App\Models\Teknik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeknikResource extends Resource
{
    protected static ?string $model = Teknik::class;
    protected static ?string $navigationGroup = 'Teknik';
      protected static ?string $navigationLabel = 'Teknik';
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Nama_Pekerjaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Lokasi_Pekerjaan')
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
            'index' => Pages\ListTekniks::route('/'),
            'create' => Pages\CreateTeknik::route('/create'),
            'edit' => Pages\EditTeknik::route('/{record}/edit'),
        ];
    }
}
