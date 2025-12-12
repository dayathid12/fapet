<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalPengemudiResource\Pages;
use App\Models\JadwalPengemudi;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Carbon\Carbon;

class JadwalPengemudiResource extends Resource
{

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Poll Kendaraan';
    protected static ?int $navigationSort = 0;

    protected static ?string $navigationLabel = 'Jadwal Pengemudi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_pengemudi')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ViewColumn::make('nama_pengemudi')
                    ->view('filament.tables.columns.jadwal-pengemudi-nama-with-actions')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status_jadwal')
                    ->options([
                        'terjadwal' => 'Terjadwal',
                        'selesai' => 'Selesai',
                    ])
                    ->query(function ($query, array $data) {
                        if (array_key_exists('value', $data) && $data['value'] !== null) {
                            $today = Carbon::today();
                            if ($data['value'] === 'terjadwal') {
                                $query->whereDate('tanggal_jadwal', '>=', $today);
                            } elseif ($data['value'] === 'selesai') {
                                $query->whereDate('tanggal_jadwal', '<', $today);
                            }
                        }
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalPengemudis::route('/'),
            'create' => Pages\CreateJadwalPengemudi::route('/create'),
            'edit' => Pages\EditJadwalPengemudi::route('/{record}/edit'),
        ];
    }
}
