<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BiayaBBMResource\Pages;
use App\Filament\Resources\BiayaBBMResource\RelationManagers;
use App\Models\BiayaBBM;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BiayaBBMResource extends Resource
{
    protected static ?string $model = BiayaBBM::class;

     protected static ?string $navigationLabel = 'BBM';
     protected static ?string $navigationGroup = 'Poll Kendaraan';

    protected static ?int $navigationSort = 2;
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
                //
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
            'index' => Pages\ListBiayaBBMS::route('/'),
            'create' => Pages\CreateBiayaBBM::route('/create'),
            'edit' => Pages\EditBiayaBBM::route('/{record}/edit'),
        ];
    }
}
