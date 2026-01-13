<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SPTJBUangPengemudiResource\Pages;
use App\Filament\Resources\SPTJBUangPengemudiResource\RelationManagers;
use App\Models\SPTJBPengemudi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class SPTJBUangPengemudiResource extends Resource
{
    protected static ?string $model = SPTJBPengemudi::class;

    protected static ?string $navigationLabel = 'SPTJB Uang Pengemudi';

    protected static ?string $navigationGroup = 'Pelaporan';

    protected static ?string $slug = 'sptjb-uang-pengemudi';

    protected static ?string $createActionLabel = 'Tambah Pengemudi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('no_sptjb')
                    ->label('No. SPTJB')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_surat')
                    ->label('Tanggal Surat')
                    ->required(),
                Forms\Components\TextInput::make('uraian')
                    ->label('Uraian')
                    ->default('Uang Saku Pengemudi dalam rangka melayani Kegiatan Civitas Akademika Unpad tgl 5 s.d. 9 ' . date('F') . ' ' . date('Y'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('penerima')
                    ->label('Penerima')
                    ->default('Amin dkk 14 org')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_jumlah_uang_diterima')
                    ->label('Total Uang Diterima')
                    ->prefix('Rp')
                    ->readOnly()
                    ->numeric()
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record) {
                            return '0';
                        }
                        $total = $record->load('details')->total_jumlah_uang_diterima;
                        if ($total == floor($total)) {
                            return number_format($total, 0, ',', '.');
                        } else {
                            return number_format($total, 2, ',', '.');
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_sptjb')
                    ->label('No. SPTJB')
                    ->searchable(),
                TextColumn::make('uraian')
                    ->label('Uraian'),
                TextColumn::make('penerima')
                    ->label('Penerima'),
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
            \App\Filament\Resources\SPTJBUangPengemudiResource\RelationManagers\SPTJBUangPengemudiDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSPTJBUangPengemudi::route('/'),
            'create' => Pages\CreateSPTJBUangPengemudi::route('/create'),
            'edit' => Pages\EditSPTJBUangPengemudi::route('/{record}/edit'),
        ];
    }
}
