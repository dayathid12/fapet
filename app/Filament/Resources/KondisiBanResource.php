<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KondisiBanResource\Pages;
use App\Filament\Resources\KondisiBanResource\RelationManagers;
use App\Models\KondisiBan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KondisiBanResource extends Resource
{
    protected static ?string $model = KondisiBan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Kondisi Ban';

    public static function form(Form $form): Form
    {
        $schema = [];

        if (!filled(request()->query('nopol_kendaraan'))) {
            $schema[] = Forms\Components\Select::make('nopol_kendaraan')
                ->relationship('kendaraan', 'nopol_kendaraan')
                ->searchable()
                ->preload()
                ->required();
        }

        $schema = array_merge($schema, [
            Forms\Components\TextInput::make('ban_depan_kiri')
                ->maxLength(255),
            Forms\Components\TextInput::make('ban_depan_kanan')
                ->maxLength(255),
            Forms\Components\TextInput::make('ban_belakang_kiri')
                ->maxLength(255),
            Forms\Components\TextInput::make('ban_belakang_kanan')
                ->maxLength(255),
            Forms\Components\TextInput::make('odo_terbaru')
                ->numeric(),
        ]);

        return $form
            ->schema($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nopol_kendaraan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ban_depan_kiri')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ban_depan_kanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ban_belakang_kiri')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ban_belakang_kanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('odo_terbaru')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListKondisiBans::route('/'),
            'create' => Pages\CreateKondisiBan::route('/create'),
            'edit' => Pages\EditKondisiBan::route('/{record}/edit'),
        ];
    }
}