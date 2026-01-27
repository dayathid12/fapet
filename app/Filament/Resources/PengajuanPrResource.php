<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanPrResource\Pages;
use App\Filament\Resources\PengajuanPrResource\RelationManagers;
use App\Models\PengajuanPr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput\Mask;
use Carbon\Carbon;

class PengajuanPrResource extends Resource
{
    protected static ?string $model = PengajuanPr::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Pengajuan PR';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pengajuan Purchase Request')
                    ->description('Buat pengajuan purchase request baru dengan mengisi detail pekerjaan dan informasi yang diperlukan.')
                    ->icon('heroicon-o-document-plus')
                    ->schema([
                        Forms\Components\Textarea::make('nama_perkerjaan')
                            ->label('Nama Perkerjaan')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('total')
                            ->label('Total')
                            ->prefix('Rp')
                            ->placeholder('0,00')
                            ->formatStateUsing(fn ($state) => $state ? number_format($state, 2, ',', '.') : null)
                            ->dehydrateStateUsing(fn ($state) => $state ? (float) str_replace(',', '.', str_replace('.', '', $state)) : null)
                            ->required(),

                        Forms\Components\DateTimePicker::make('tanggal_usulan')
                            ->label('Tanggal Usulan')
                            ->default(now())
                            ->disabled()
                            ->required(),

                        Forms\Components\FileUpload::make('upload_files')
                            ->label('Upload File')
                            ->multiple()
                            ->directory('pengajuan-pr-files')
                            ->maxSize(10240)
                            ->downloadable()
                            ->openable()
                            ->reorderable()
                            ->appendFiles()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('nomor_pr')
                            ->label('Nomor PR')
                            ->required()
                            ->placeholder('Masukkan nomor PR')
                            ->hidden(),

                        Forms\Components\FileUpload::make('proses_pr_screenshots')
                            ->label('Proses PR Screenshots')
                            ->multiple()
                            ->image()
                            ->directory('pengajuan-pr-screenshots')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->acceptedFileTypes(['image/*'])
                            ->maxSize(5120)
                            ->downloadable()
                            ->openable()
                            ->reorderable()
                            ->appendFiles()
                            ->columnSpanFull()
                            ->hidden(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_ajuan')
                    ->label('Nomor Ajuan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nomor_pr')
                    ->label('Nomor PR')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_perkerjaan')
                    ->label('Nama Perkerjaan')
                    ->limit(50)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_usulan')
                    ->label('Tanggal Usulan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->selectable(false)
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
            'index' => Pages\ListPengajuanPrs::route('/'),
            'create' => Pages\CreatePengajuanPr::route('/create'),
            'edit' => Pages\EditPengajuanPr::route('/{record}/edit'),
        ];
    }
}
