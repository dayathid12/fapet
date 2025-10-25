<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UrbangResource\Pages;
use App\Filament\Resources\UrbangResource\RelationManagers;
use App\Models\Urbang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UrbangResource extends Resource
{
    protected static ?string $model = Urbang::class;
  protected static ?string $navigationLabel = 'Urbang';
  protected static ?string $navigationGroup = 'Urbang';
 

    public static function form(Form $form): Form
    {
        return $form
                 ->schema([
                Forms\Components\TextInput::make('Nama_Pekerjaan')
                    ->required(),
                Forms\Components\TextInput::make('Lokasi_Pekerjaan')
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
            'index' => Pages\ListUrbangs::route('/'),
            'create' => Pages\CreateUrbang::route('/create'),
            'edit' => Pages\EditUrbang::route('/{record}/edit'),
        ];
    }
}
