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

    protected static ?string $navigationLabel = 'Entry Pengeluaran';

    protected static ?string $navigationGroup = 'Poll Kendaraan';
    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nomor_berkas')
                    ->label('Nomor Berkas')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->maxLength(255)
                    ->default(function () {
                        // Get the highest 'nomor_berkas'
                        $latestEntry = EntryPengeluaran::orderByRaw('CAST(nomor_berkas AS UNSIGNED) DESC')->first();

                        $newSequence = 1;
                        if ($latestEntry && is_numeric($latestEntry->nomor_berkas)) {
                            $newSequence = (int)$latestEntry->nomor_berkas + 1;
                        }

                        return str_pad($newSequence, 4, '0', STR_PAD_LEFT);
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
                    ->sortable()
                    ->formatStateUsing(fn ($state): string => str_pad($state, 4, '0', STR_PAD_LEFT)),
                TextColumn::make('nama_berkas')
                    ->label('Nama Berkas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_bbm')
                    ->label('Total Pengeluaran BBM')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('total_toll')
                    ->label('Total Pengeluaran Toll')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('total_parkir')
                    ->label('Total Pengeluaran Parkir')
                    ->money('IDR')
                    ->getStateUsing(function (EntryPengeluaran $record): float {
                        return ($record->total_parkir_biaya ?? 0) + ($record->total_parkir_pengeluaran ?? 0);
                    })
                    ->sortable(),
                TextColumn::make('total_rincian_biayas')
                    ->label('Total Rincian Biayas')
                    ->money('IDR')
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

        public static function getEloquentQuery(): Builder

        {

            return parent::getEloquentQuery()

                ->withSum(['rincianBiayas as total_bbm' => function ($query) {

                    $query->where('tipe', 'BBM');

                }], 'biaya')

                ->withSum(['rincianBiayas as total_toll' => function ($query) {

                    $query->where('tipe', 'Toll');

                }], 'biaya')

                ->withSum(['rincianBiayas as total_parkir_biaya' => function ($query) {

                    $query->where('tipe', 'Parkir');

                }], 'biaya')

                ->withSum('rincianPengeluarans as total_parkir_pengeluaran', 'biaya_parkir')

                ->withSum('rincianBiayas as total_rincian_biayas', 'biaya');

        }

    

        public static function shouldRegisterNavigation(): bool

        {

            return auth()->user()->can('view_any_entry::pengeluaran');

        }

    }
