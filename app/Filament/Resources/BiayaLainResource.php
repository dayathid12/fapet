<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BiayaLainResource\Pages;
use App\Filament\Resources\BiayaLainResource\RelationManagers;
use App\Models\BiayaLain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BiayaLainResource extends Resource
{
    protected static ?string $model = BiayaLain::class;
    protected static ?string $navigationIcon = 'heroicon-s-truck';
  protected static ?string $navigationLabel = 'Parkir & Biaya Lain';
  protected static ?string $navigationGroup = 'Poll Kendaraan';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor_perjalanan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('uraian_biaya')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('biaya')
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
                Tables\Columns\TextColumn::make('uraian_biaya')
                    ->searchable(),
                Tables\Columns\TextColumn::make('biaya')
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
            'index' => Pages\ListBiayaLains::route('/'),
            'create' => Pages\CreateBiayaLain::route('/create'),
            'edit' => Pages\EditBiayaLain::route('/{record}/edit'),
        ];
    }
}
