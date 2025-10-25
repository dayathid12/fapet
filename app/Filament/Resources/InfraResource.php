<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InfraResource\Pages;
use App\Filament\Resources\InfraResource\RelationManagers;
use App\Models\Infra;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InfraResource extends Resource
{
    protected static ?string $model = Infra::class;
    protected static ?string $navigationLabel = 'Infra';
    protected static ?string $navigationGroup = 'Infrastruktur';

   

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
            'index' => Pages\ListInfras::route('/'),
            'create' => Pages\CreateInfra::route('/create'),
            'edit' => Pages\EditInfra::route('/{record}/edit'),
        ];
    }
}
