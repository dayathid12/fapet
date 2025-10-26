<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PollKendaraanResource\Pages;
use App\Filament\Resources\PollKendaraanResource\RelationManagers;
use App\Models\PollKendaraan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PollKendaraanResource extends Resource
{
    protected static ?string $model = PollKendaraan::class;
    protected static ?string $navigationLabel = 'Poll Kendaraan';
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
                Tables\Actions\EditAction::make()
                    ->extraAttributes(['class' => 'hover:bg-blue-100']),
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
            'index' => Pages\ListPollKendaraans::route('/'),
            'create' => Pages\CreatePollKendaraan::route('/create'),
            'edit' => Pages\EditPollKendaraan::route('/{record}/edit'),
        ];
    }
}
