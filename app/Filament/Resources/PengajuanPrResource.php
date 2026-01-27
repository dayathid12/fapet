<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanPrResource\Pages;
use App\Models\PengajuanPr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;

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
                            ->label('Nama Pekerjaan')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('total')
                            ->label('Total')
                            ->prefix('Rp')
                            ->placeholder('0,00')
                            /**
                             * PERBAIKAN: Menggunakan single quote agar PHP tidak menganggap $money sebagai variabel PHP.
                             * Ini akan merender x-mask:dynamic="$money($input, '.', ',')" di browser.
                             */
                            ->extraAlpineAttributes([
                                'x-mask:dynamic' => '$money($input, ".", ",")',
                            ])
                            ->extraInputAttributes([
                                'class' => 'text-right font-mono',
                                'inputmode' => 'numeric',
                            ])
                            /**
                             * Menggunakan formatStateUsing agar saat EDIT data,
                             * angka dari database langsung terformat di form.
                             */
                            ->formatStateUsing(fn ($state) => $state ? number_format($state, 0, ',', '.') : null)
                            /**
                             * Dehydrate: Membersihkan semua titik sebelum dikirim ke server.
                             * Ini memastikan data yang masuk ke database tetap numerik murni.
                             */
                            ->dehydrateStateUsing(fn ($state) => $state ? (float) str_replace('.', '', $state) : 0)
                            ->required()
                            ->minValue(0),

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
                            ->hidden(),

                        Forms\Components\FileUpload::make('proses_pr_screenshots')
                            ->label('Proses PR Screenshots')
                            ->multiple()
                            ->image()
                            ->directory('pengajuan-pr-screenshots')
                            ->imageEditor()
                            ->maxSize(5120)
                            ->downloadable()
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
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_perkerjaan')
                    ->label('Nama Pekerjaan')
                    ->limit(50),

                Tables\Columns\TextColumn::make('tanggal_usulan')
                    ->label('Tanggal Usulan')
                    ->dateTime('d/m/Y H:i'),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable()
                    ->alignment('right'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i'),
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
        return [];
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
