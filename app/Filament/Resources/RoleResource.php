<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Roles and Permissions';

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canView($record): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canEdit($record): bool
    {
        return true;
    }

    public static function canDelete($record): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('guard_name')
                    ->required()
                    ->maxLength(255)
                    ->default('web'),
                Forms\Components\Select::make('permissions')
                    ->multiple()
                    ->relationship('permissions', 'name')
                    ->preload(),
                Forms\Components\Select::make('accessible_resources')
                    ->multiple()
                    ->options([
                        'App\Filament\Resources\UserResource' => 'Users',
                        'App\Filament\Resources\RoleResource' => 'Roles',
                        'App\Filament\Resources\PermissionResource' => 'Permissions',
                        'App\Filament\Resources\KendaraanResource' => 'Data Kendaraan',
                        'App\Filament\Resources\BookingKendaraanResource' => 'Jadwal Kendaraan',
                    ])
                    ->label('Accessible Resources')
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('guard_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('permissions.name')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('accessible_resources')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $resources = json_decode($record->accessible_resources, true) ?? [];
                        $labels = [
                            'App\Filament\Resources\UserResource' => 'Users',
                            'App\Filament\Resources\RoleResource' => 'Roles',
                            'App\Filament\Resources\PermissionResource' => 'Permissions',
                            'App\Filament\Resources\KendaraanResource' => 'Data Kendaraan',
                            'App\Filament\Resources\BookingKendaraanResource' => 'Jadwal Kendaraan',
                        ];
                        return array_map(fn($res) => $labels[$res] ?? $res, $resources);
                    })
                    ->label('Accessible Resources'),
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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
