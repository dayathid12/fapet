<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotifikasiWAResource\Pages;
use App\Models\NotifikasiWA;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotifikasiWAResource extends Resource
{
    protected static ?string $model = NotifikasiWA::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Notifikasi WA';

    protected static ?string $modelLabel = 'Notifikasi WA';

    protected static ?string $pluralModelLabel = 'Notifikasi WA';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor')
                    ->label('Nomor')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabled()
                    ->dehydrated()
                    ->default(function () {
                        do {
                            $nomor = 'WA-' . strtoupper(substr(md5(uniqid()), 0, 8));
                        } while (static::getModel()::where('nomor', $nomor)->exists());

                        return $nomor;
                    })
                    ->helperText('Nomor akan di-generate secara otomatis'),

                Forms\Components\TextInput::make('judul')
                    ->label('Judul')
                    ->required()
                    ->placeholder('Masukkan judul notifikasi'),

                Forms\Components\Textarea::make('isi_pesan')
                    ->label('Isi Pesan')
                    ->required()
                    ->rows(4)
                    ->placeholder('Masukkan isi pesan notifikasi'),

                Forms\Components\TextInput::make('number_key')
                    ->label('Number Key')
                    ->required()
                    ->placeholder('Contoh: 32SLSmpe9fiqBcP9'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')
                    ->label('Nomor')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('isi_pesan')
                    ->label('Isi Pesan')
                    ->limit(100)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 100) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('number_key')
                    ->label('Number Key')
                    ->copyable()
                    ->copyMessage('Number Key berhasil disalin')
                    ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListNotifikasiWAS::route('/'),
            'create' => Pages\CreateNotifikasiWA::route('/create'),
            'edit' => Pages\EditNotifikasiWA::route('/{record}/edit'),
        ];
    }
}
