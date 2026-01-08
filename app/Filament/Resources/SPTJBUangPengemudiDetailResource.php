<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SPTJBUangPengemudiDetailResource\Pages;
use App\Filament\Resources\SPTJBUangPengemudiDetailResource\RelationManagers;
use App\Models\SPTJBUangPengemudiDetail;
use App\Models\SPTJBPengemudi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SPTJBUangPengemudiDetailResource extends Resource
{
    protected static ?string $model = SPTJBUangPengemudiDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('sptjb_pengemudi_id')
                    ->default(fn () => request()->query('sptjb_pengemudi_id')),
                Forms\Components\Hidden::make('no_sptjb')
                    ->default(fn () => SPTJBPengemudi::find(request()->query('sptjb_pengemudi_id'))?->no_sptjb),
                Forms\Components\TextInput::make('no')
                    ->label('No')
                    ->disabled()
                    ->dehydrated(false)
                    ->default(fn () => static::getNextNo(request()->query('sptjb_pengemudi_id'))),
                Forms\Components\TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_penugasan')
                    ->label('Tanggal Penugasan')
                    ->required(),
                Forms\Components\TextInput::make('jumlah_hari')
                    ->label('Jumlah Hari')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('besaran_uang_per_hari')
                    ->label('Besaran uang / Hari (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                Forms\Components\TextInput::make('jumlah_rp')
                    ->label('Jumlah RP.')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                Forms\Components\TextInput::make('jumlah_uang_diterima')
                    ->label('Jumlah Uang Diterima')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                Forms\Components\TextInput::make('nomor_rekening')
                    ->label('Nomor Rekening')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('golongan')
                    ->label('Golongan')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jabatan')
                    ->label('Jabatan'),
                Tables\Columns\TextColumn::make('tanggal_penugasan')
                    ->label('Tanggal Penugasan')
                    ->date(),
                Tables\Columns\TextColumn::make('jumlah_hari')
                    ->label('Jumlah Hari'),
                Tables\Columns\TextColumn::make('besaran_uang_per_hari')
                    ->label('Besaran uang / Hari (Rp)')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('jumlah_rp')
                    ->label('Jumlah RP.')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('jumlah_uang_diterima')
                    ->label('Jumlah Uang Diterima')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('nomor_rekening')
                    ->label('Nomor Rekening'),
                Tables\Columns\TextColumn::make('golongan')
                    ->label('Golongan'),
                Tables\Columns\TextColumn::make('no_sptjb')
                    ->label('No. SPTJB'),
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
            'index' => Pages\ListSPTJBUangPengemudiDetails::route('/'),
            'create' => Pages\CreateSPTJBUangPengemudiDetail::route('/create'),
            'edit' => Pages\EditSPTJBUangPengemudiDetail::route('/{record}/edit'),
        ];
    }
}
