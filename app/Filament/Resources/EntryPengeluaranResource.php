<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntryPengeluaranResource\Pages;
use App\Filament\Resources\EntryPengeluaranResource\RelationManagers;
use App\Models\EntryPengeluaran;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class EntryPengeluaranResource extends Resource
{
    protected static ?string $model = EntryPengeluaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nomor_berkas')
                    ->label('Nomor Berkas')
                    ->disabled()
                    ->required()
                    ->maxLength(255)
                    ->default(function () {
                        $currentDate = Carbon::now();
                        $dd = $currentDate->format('d');
                        $yy = $currentDate->format('y');

                        // Query for the latest entry for today's date and year
                        $latestEntry = EntryPengeluaran::where('nomor_berkas', 'like', $dd . $yy . '-%')
                                                        ->orderByDesc('nomor_berkas') // Order by the full string to get the highest suffix
                                                        ->first();

                        $sequence = 1;
                        if ($latestEntry) {
                            $parts = explode('-', $latestEntry->nomor_berkas);
                            // Ensure the suffix is an integer and increment it
                            if (count($parts) > 1 && is_numeric(end($parts))) {
                                $sequence = (int) end($parts) + 1;
                            }
                        }
                        return $dd . $yy . '-' . $sequence;
                    }),
                TextInput::make('nama_berkas')
                    ->label('Nama Berkas')
                    ->default('Tanda Terima SPJ BBM dan Tol Th. ' . Carbon::now()->year)
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_berkas')
                    ->label('Nomor Berkas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_berkas')
                    ->label('Nama Berkas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('nomor_berkas', 'desc')
            ->filters([
                //
            ])
            ->actions([
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
            RelationManagers\RincianPengeluaranRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEntryPengeluarans::route('/'),
            'create' => Pages\CreateEntryPengeluaran::route('/create'),
            'edit' => Pages\EditEntryPengeluaran::route('/{record}/edit'),
            'rincian-biaya' => Pages\ManageRincianBiayas::route('/{record}/rincian-biaya/{rincianPengeluaranId}'),
        ];
    }
}
