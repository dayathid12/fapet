<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SPTJBResource\Pages;
use App\Filament\Resources\SPTJBResource\RelationManagers;
use App\Models\SPTJB;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SPTJBResource extends Resource
{
    protected static ?string $model = SPTJB::class;

        protected static ?string $navigationGroup = 'Pelaporan';
      protected static ?string $navigationLabel = 'SPTJB Pengemudi';

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
            'index' => Pages\ListSPTJBS::route('/'),
            'create' => Pages\CreateSPTJB::route('/create'),
            'edit' => Pages\EditSPTJB::route('/{record}/edit'),
        ];
    }
}
