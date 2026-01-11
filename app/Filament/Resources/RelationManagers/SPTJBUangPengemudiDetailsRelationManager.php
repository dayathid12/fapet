<?php

namespace App\Filament\Resources\RelationManagers;

use App\Models\SPTJBUangPengemudiDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SPTJBUangPengemudiDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('sptjb_pengemudi_id')
                    ->default(fn () => $this->ownerRecord->id),
                Forms\Components\Hidden::make('no_sptjb')
                    ->default(fn () => $this->ownerRecord->no_sptjb),
                Forms\Components\TextInput::make('no')
                    ->label('No')
                    ->disabled()
                    ->dehydrated(false)
                    ->default(fn () => static::getNextNo($this->ownerRecord->id)),
                Forms\Components\TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_penugasan')
                    ->label('Tanggal Penugasan'),
                Forms\Components\TextInput::make('jumlah_hari')
                    ->label('Jumlah Hari')
                    ->numeric(),
                Forms\Components\TextInput::make('besaran_uang_per_hari')
                    ->label('Besaran uang / Hari (Rp)')
                    ->numeric()
                    ->prefix('Rp'),

                Forms\Components\TextInput::make('jumlah_uang_diterima')
                    ->label('Jumlah Uang Diterima')
                    ->numeric()
                    ->prefix('Rp'),
                Forms\Components\TextInput::make('nomor_rekening')
                    ->label('Nomor Rekening')
                    ->maxLength(255),
                Forms\Components\TextInput::make('golongan')
                    ->label('Golongan')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
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

                Tables\Columns\TextColumn::make('jumlah_uang_diterima')
                    ->label('Jumlah Uang Diterima')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('nomor_rekening')
                    ->label('Nomor Rekening'),
                Tables\Columns\TextColumn::make('golongan')
                    ->label('Golongan'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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

    protected static function getNextNo($sptjbPengemudiId)
    {
        return SPTJBUangPengemudiDetail::where('sptjb_pengemudi_id', $sptjbPengemudiId)->count() + 1;
    }
}
