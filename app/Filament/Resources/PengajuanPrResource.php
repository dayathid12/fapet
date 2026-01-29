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
use Filament\Tables\Columns\ViewColumn;

class PengajuanPrResource extends Resource
{
    protected static ?string $model = PengajuanPr::class;
      protected static ?int $navigationSort = 0;
  protected static ?string $navigationGroup = 'Pelaporan';

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

                        Forms\Components\Grid::make(2)
                            ->schema([
                                TextInput::make('total')
                                    ->label('Total')
                                    ->prefix('Rp')
                                    ->placeholder('0,00')
                                    /**
                                     * PERBAIKAN: Menggunakan single quote agar PHP tidak menganggap $money sebagai variabel PHP.
                                     * Ini akan merender x-mask:dynamic="$money($input, '.', ',')" di browser.
                                     */

                                    ->extraInputAttributes([
                                        'class' => 'text-right font-mono',
                                        'inputmode' => 'numeric',
                                        'maxlength' => '20',
                                        'oninput' => "let v = this.value.replace(/\\D/g, ''); if(!v){ this.value = ''; return; } this.value = new Intl.NumberFormat('id-ID').format(v);",
                                    ])
                                    /**
                                     * Menggunakan formatStateUsing agar saat EDIT data,
                                     * angka dari database langsung terformat di form.
                                     */
                                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 0, ',', '.') : null)
                                    /**
                                     * Dehydrate: Mengubah format mata uang (koma sebagai pemisah ribuan, titik sebagai desimal)
                                     * menjadi format numerik standar untuk database.
                                     */
                                    ->dehydrateStateUsing(function ($state) {
                                        if (!$state) return 0;
                                        // Hapus semua karakter selain angka (menghilangkan pemisah ribuan '.' atau ',')
                                        $cleaned = preg_replace('/\D/', '', $state);
                                        return (float) $cleaned;
                                    })
                                    ->required()
                                    ->minValue(0),

                                Forms\Components\DateTimePicker::make('tanggal_usulan')
                                    ->label('Tanggal Usulan')
                                    ->default(now())
                                    ->disabled()
                                    ->required(),
                            ]),

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
                            ->appendFiles()
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('nomor_ajuan', 'desc')
            ->columns([
                // Add columns if needed, but since using custom view, might not be necessary
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
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
