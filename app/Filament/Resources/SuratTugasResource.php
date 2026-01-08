<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratTugasResource\Pages;
use App\Models\Perjalanan;
use App\Models\Kendaraan;
use App\Models\Staf;
use App\Models\PerjalananKendaraan;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SuratTugasResource extends Resource
{
    protected static ?string $model = PerjalananKendaraan::class;

    protected static ?string $navigationLabel = 'Surat Tugas';


        protected static ?string $navigationGroup = 'Pelaporan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Informasi Penugasan')
                            ->description('Detail penugasan kendaraan dan personil.')
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                Select::make('perjalanan_id')
                                    ->label('Nomor Perjalanan')
                                    ->options(Perjalanan::all()->pluck('nomor_perjalanan', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->prefixIcon('heroicon-m-hashtag')
                                    ->columnSpanFull(),

                                Select::make('pengemudi_id')
                                    ->label('Pengemudi')
                                    ->options(Staf::all()->pluck('nama_staf', 'staf_id'))
                                    ->searchable()
                                    ->required()
                                    ->prefixIcon('heroicon-m-user'),

                                Select::make('asisten_id')
                                    ->label('Asisten (Opsional)')
                                    ->options(Staf::all()->pluck('nama_staf', 'staf_id'))
                                    ->searchable()
                                    ->nullable()
                                    ->prefixIcon('heroicon-m-user-group'),

                                Select::make('kendaraan_nopol')
                                    ->label('Kendaraan')
                                    ->options(Kendaraan::all()->pluck('nopol_kendaraan', 'nopol_kendaraan'))
                                    ->searchable()
                                    ->required()
                                    ->prefixIcon('heroicon-m-truck'),

                                TextInput::make('tipe_penugasan')
                                    ->placeholder('Contoh: Antar Jemput Tamu')
                                    ->maxLength(255)
                                    ->nullable(),

                                DateTimePicker::make('waktu_selesai_penugasan')
                                    ->label('Estimasi Selesai')
                                    ->nullable(),
                            ])->columns(2),
                    ])->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Status & Dokumen')
                            ->icon('heroicon-o-document-check')
                            ->schema([
                                TextInput::make('no_surat_tugas')
                                    ->label('No. Surat Tugas')
                                    ->placeholder('Auto / Input Manual')
                                    ->maxLength(255),

                                FileUpload::make('upload_tte')
                                    ->label('File TTE')
                                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                                    ->maxSize(10240)
                                    ->directory('tte')
                                    ->imagePreviewHeight('100')
                                    ->openable(),

                                FileUpload::make('upload_surat_tugas')
                                    ->label('Scan Surat Tugas')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->maxSize(10240)
                                    ->directory('surat-tugas')
                                    ->openable(),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->whereHas('perjalanan', function ($q) {
                    $q->where('status_perjalanan', 'Terjadwal')
                      ->where('jenis_kegiatan', '!=', 'DK');
                });
            })
            ->contentGrid([
                'md' => 2,
                'xl' => 3, // Layout grid 3 kolom di layar besar
            ])
            ->columns([
                Stack::make([
                    // --- HEADER: No Surat & Status ---
                    Split::make([
                        // Wrapper untuk Nomor Surat dan Nomor Perjalanan
                        Split::make([
                            TextColumn::make('nomor_perjalanan_display') // Custom name for display
                                ->label('Nomor Perjalanan')
                                ->state(fn (?PerjalananKendaraan $record) => $record?->perjalanan?->nomor_perjalanan)
                                ->color('gray')
                                ->size('xl')
                                ->weight('bold')
                                ->visible(fn (?PerjalananKendaraan $record) => !empty($record?->perjalanan?->nomor_perjalanan))
                                ->extraAttributes(fn (?PerjalananKendaraan $record) => !empty($record?->perjalanan?->nomor_perjalanan) ? ['class' => 'bg-blue-50 text-blue-800 px-2 py-1 rounded-md'] : []),
                            TextColumn::make('custom_no_surat')
                                ->label('Nomor Surat')
                                ->state(fn (?PerjalananKendaraan $record) => $record?->perjalanan?->no_surat_tugas)
                                ->icon('heroicon-m-document-text')
                                ->weight('bold')
                                ->color('primary')
                                ->formatStateUsing(fn (?string $state): string => $state ?: 'Draft Surat')
                                ->size('xl')
                                ->extraAttributes(fn (?PerjalananKendaraan $record) => !empty($record?->perjalanan?->no_surat_tugas) ? ['class' => 'bg-green-50 text-green-800 px-2 py-1 rounded-md'] : []),
                        ])->columnSpan(2),

                        TextColumn::make('status_surat_tugas')
                            ->badge()
                            ->alignEnd()
                            ->getStateUsing(function (?PerjalananKendaraan $record): string {
                                if (!$record) return 'Pengajuan';
                                if (!empty($record->perjalanan->upload_surat_tugas)) return 'Selesai';
                                if (!empty($record->perjalanan->no_surat_tugas)) return 'Proses';
                                return 'Pengajuan';
                            })
                            ->extraAttributes(fn (string $state): array => match ($state) {
                                'Selesai' => [
                                    'class' => 'bg-blue-600 text-white',
                                ],
                                'Proses' => [
                                    'class' => 'bg-yellow-400 text-black',
                                ],
                                'Pengajuan' => [
                                    'class' => 'bg-gray-500 text-white',
                                ],
                                default => [],

                            }),
                    ])->extraAttributes(function (?PerjalananKendaraan $record): array {
                        $status = $record ? (
                            !empty($record->perjalanan->upload_surat_tugas) ? 'Selesai' :
                            (!empty($record->perjalanan->no_surat_tugas) ? 'Proses' : 'Pengajuan')
                        ) : 'Pengajuan';
                        return [
                            'class' => 'p-8 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-primary-500 transition-all duration-300 group',
                            'style' => match ($status) {
                                'Selesai' => 'background: linear-gradient(135deg, #FFB3BA, #FFDFBA); color: #5D4037; box-shadow: 0 12px 40px rgba(255, 179, 186, 0.3);',
                                'Proses' => 'background: linear-gradient(135deg, #BAE1FF, #FFFFBA); color: #424242; box-shadow: 0 12px 40px rgba(186, 225, 255, 0.3);',
                                'Pengajuan' => 'background: linear-gradient(135deg, #D4A5A5, #FFC3A0); color: #5D4037; box-shadow: 0 12px 40px rgba(212, 165, 165, 0.3);',
                                default => 'background: linear-gradient(135deg, #E6E6FA, #F0E68C); color: #424242; box-shadow: 0 12px 40px rgba(230, 230, 250, 0.3);',
                            }
                        ];
                    }),

                    // --- BODY: Driver & Detail Tugas ---
                    Split::make([
                        // BAGIAN KIRI: Info Personil & Armada
                        Stack::make([
                            // GANTI NAMA: pengemudi.nama_staf -> custom_nama_pengemudi
                            TextColumn::make('custom_nama_pengemudi')
                                ->label('Pengemudi')
                                ->state(fn (?PerjalananKendaraan $record) => $record?->pengemudi?->nama_staf)
                                ->weight('bold')
                                ->size('lg')
                                ->icon('heroicon-m-user')
                                ->iconColor('primary')
                                ->extraAttributes(['class' => 'mt-2']),

                            // GANTI NAMA: asisten.nama_staf -> custom_nama_asisten
                            TextColumn::make('custom_nama_asisten')
                                ->label('Asisten')
                                ->state(fn (?PerjalananKendaraan $record) => $record?->asisten?->nama_staf)
                                ->icon('heroicon-m-user-group')
                                ->color('gray')
                                ->size('sm')
                                ->visible(fn (?PerjalananKendaraan $record) => !empty($record?->asisten_id)),

                            TextColumn::make('kendaraan_nopol')
                                ->icon('heroicon-m-truck')
                                ->size('md')
                                ->color('gray')
                                ->formatStateUsing(fn ($state) => "Nopol: {$state}"),
                        ])->space(1),

                        // BAGIAN KANAN: Info Kegiatan & Waktu
                        Stack::make([
                            // GANTI NAMA: perjalanan.jenis_kegiatan -> custom_jenis_kegiatan
                            TextColumn::make('custom_jenis_kegiatan')
                                ->label('Jenis Kegiatan')
                                ->state(fn (?PerjalananKendaraan $record) => $record?->perjalanan?->jenis_kegiatan)
                                ->weight('bold')
                                ->alignEnd()
                                ->limit(25)
                                ->extraAttributes(['class' => 'mt-2 text-right']),

                            TextColumn::make('tipe_penugasan')
                                ->badge()
                                ->color('info')
                                ->alignEnd(),

                            TextColumn::make('tanggal_awal_tugas')
                                ->label('Tanggal Awal Tugas')
                                ->icon('heroicon-m-clock')
                                ->size('md')
                                ->color('warning')
                                ->dateTime('d M, H:i')
                                ->alignEnd()
                                ->getStateUsing(fn (?PerjalananKendaraan $record) => match ($record?->tipe_penugasan) {
                                    'Antar & Jemput', 'Antar (Keberangkatan)' => $record?->perjalanan?->waktu_keberangkatan,
                                    'Jemput (Kepulangan)' => $record?->perjalanan?->waktu_kepulangan,
                                    default => null,
                                }),

                            TextColumn::make('tanggal_akhir_tugas')
                                ->label('Tanggal Akhir Tugas')
                                ->size('xs')
                                ->color('warning')
                                ->dateTime('d M, H:i')
                                ->alignEnd()
                                ->getStateUsing(fn (?PerjalananKendaraan $record) => $record?->perjalanan?->waktu_kepulangan)
                                ->visible(fn (?PerjalananKendaraan $record) => !empty($record?->perjalanan?->waktu_kepulangan))
                                ->extraAttributes(['class' => 'transform translate-x-1']),
                        ])->space(1)->alignEnd(),
                    ]),
                ])
                ->space(3)
                ->extraAttributes(function (?PerjalananKendaraan $record): array {
                    $status = $record ? (
                        !empty($record->perjalanan->upload_surat_tugas) ? 'Selesai' :
                        (!empty($record->perjalanan->no_surat_tugas) ? 'Proses' : 'Pengajuan')
                    ) : 'Pengajuan';
                    return [
                        'class' => 'p-5 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-2xl hover:border-primary-500 hover:-translate-y-2 hover:scale-103 transition-all duration-500 group backdrop-blur-md transform',
                        'style' => match ($status) {
                            'Selesai' => 'background: linear-gradient(135deg, rgba(255, 235, 238, 0.9), rgba(255, 243, 224, 0.9)); color: #4A4A4A; box-shadow: 0 25px 50px rgba(255, 179, 186, 0.3), 0 0 30px rgba(255, 179, 186, 0.2); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.4); filter: drop-shadow(0 0 15px rgba(255, 179, 186, 0.4));',
                            'Proses' => 'background: linear-gradient(135deg, rgba(240, 248, 255, 0.9), rgba(255, 250, 240, 0.9)); color: #4A4A4A; box-shadow: 0 25px 50px rgba(186, 225, 255, 0.3), 0 0 30px rgba(186, 225, 255, 0.2); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.4); filter: drop-shadow(0 0 15px rgba(186, 225, 255, 0.4));',
                            'Pengajuan' => 'background: linear-gradient(135deg, rgba(248, 240, 240, 0.9), rgba(255, 245, 235, 0.9)); color: #4A4A4A; box-shadow: 0 25px 50px rgba(212, 165, 165, 0.3), 0 0 30px rgba(212, 165, 165, 0.2); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.4); filter: drop-shadow(0 0 15px rgba(212, 165, 165, 0.4));',
                            default => 'background: linear-gradient(135deg, rgba(250, 245, 255, 0.9), rgba(255, 250, 245, 0.9)); color: #4A4A4A; box-shadow: 0 25px 50px rgba(230, 230, 250, 0.3), 0 0 30px rgba(230, 230, 250, 0.2); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.4); filter: drop-shadow(0 0 15px rgba(230, 230, 250, 0.4));',
                        }
                    ];
                }),
            ])
            ->actions([
                Tables\Actions\Action::make('download_pdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn (?PerjalananKendaraan $record): string => $record?->perjalanan?->no_surat_tugas ? route('surat-tugas.pdf', ['no_surat_tugas' => $record->perjalanan->no_surat_tugas]) : '#')
                    ->openUrlInNewTab()
                    ->visible(fn (?PerjalananKendaraan $record): bool => !empty($record?->perjalanan?->no_surat_tugas))
                    ->extraAttributes(['class' => 'mt-4']),

                Tables\Actions\Action::make('download_word')
                    ->label('Unduh Word')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(fn (?PerjalananKendaraan $record): string => $record?->perjalanan?->no_surat_tugas ? route('surat-tugas.word', ['no_surat_tugas' => $record->perjalanan->no_surat_tugas]) : '#')
                    ->openUrlInNewTab()
                    ->visible(fn (?PerjalananKendaraan $record): bool => !empty($record?->perjalanan?->no_surat_tugas))
                    ->extraAttributes(['class' => 'mt-4']),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratTugas::route('/'),
            'create' => Pages\CreateSuratTugas::route('/create'),
            'edit' => Pages\EditSuratTugas::route('/{record}/edit'),
        ];
    }
}
