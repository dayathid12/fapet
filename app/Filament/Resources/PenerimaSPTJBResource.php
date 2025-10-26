<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenerimaSPTJBResource\Pages;
use App\Filament\Resources\PenerimaSPTJBResource\RelationManagers;
use App\Models\PenerimaSPTJB;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenerimaSPTJBResource extends Resource
{
    protected static ?string $model = PenerimaSPTJB::class;

    protected static ?string $navigationGroup = 'Pelaporan';
    protected static ?string $navigationLabel = 'Penerima SPTJB';

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
            'index' => Pages\ListPenerimaSPTJBS::route('/'),
            'create' => Pages\CreatePenerimaSPTJB::route('/create'),
            'edit' => Pages\EditPenerimaSPTJB::route('/{record}/edit'),
        ];
    }
}
