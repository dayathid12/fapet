<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StafResource\Pages;
use App\Filament\Resources\StafResource\RelationManagers;
use App\Models\Staf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StafResource extends Resource
{
    protected static ?string $model = Staf::class;

    protected static ?string $navigationLabel = 'Data Pengemudi';
    protected static ?string $navigationGroup = 'Poll Kendaraan';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_staf')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nip_staf')
                    ->maxLength(255),
                Forms\Components\TextInput::make('wa_staf')
                    ->maxLength(255),
                Forms\Components\TextInput::make('jabatan')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_staf')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nip_staf')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wa_staf')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jabatan')
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
            'index' => Pages\ListStafs::route('/'),
            'create' => Pages\CreateStaf::route('/create'),
            'edit' => Pages\EditStaf::route('/{record}/edit'),
        ];
    }
}
