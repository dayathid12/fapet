<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StafResource\Pages;
use App\Filament\Resources\StafResource\RelationManagers;
use App\Models\Staf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StafResource extends Resource
{
    protected static ?string $model = Staf::class;

    protected static ?string $navigationLabel = 'Data Pengemudi';
    protected static ?string $navigationGroup = 'Poll Kendaraan';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Dasar')
                    ->description('Informasi identitas pokok pegawai')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('id_nama')
                                    ->label('ID/NAMA')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->default(fn () => 'STF' . str_pad(\App\Models\Staf::count() + 1, 4, '0', STR_PAD_LEFT))
                                    ->helperText('ID otomatis dibuat'),

                                Forms\Components\TextInput::make('nama_staf')
                                    ->label('NAMA LENGKAP')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nama lengkap'),

                                Forms\Components\TextInput::make('nip_staf')
                                    ->label('NIP')
                                    ->maxLength(255)
                                    ->required()
                                    ->placeholder('Masukkan NIP pegawai'),
                            ]),
                    ]),

                Forms\Components\Section::make('Informasi Pribadi')
                    ->description('Data pribadi dan keluarga')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->displayFormat('d/m/Y')
                                    ->native(false)
                                    ->placeholder('Pilih tanggal lahir'),

                                Forms\Components\TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->maxLength(255)
                                    ->placeholder('Masukkan tempat lahir'),

                                Forms\Components\TextInput::make('no_ktp')
                                    ->label('Nomor KTP')
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nomor KTP'),

                                Forms\Components\TextInput::make('no_npwp')
                                    ->label('Nomor NPWP')
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nomor NPWP'),

                                Forms\Components\Textarea::make('alamat_rumah')
                                    ->label('Alamat Rumah/Domisili')
                                    ->rows(3)
                                    ->columnSpanFull()
                                    ->placeholder('Masukkan alamat lengkap dengan RT/RW'),
                            ]),
                    ]),

                Forms\Components\Section::make('Informasi Kepegawaian')
                    ->description('Data terkait status kepegawaian')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('gol_pangkat')
                                    ->label('Golongan/Pangkat')
                                    ->maxLength(255)
                                    ->placeholder('Masukkan golongan/pangkat'),

                                Forms\Components\TextInput::make('status')
                                    ->label('Status')
                                    ->maxLength(255)
                                    ->placeholder('Masukkan status pegawai'),

                                Forms\Components\TextInput::make('pendidikan_aktif')
                                    ->label('Pendidikan Aktif')
                                    ->maxLength(255)
                                    ->placeholder('Masukkan pendidikan aktif'),

                                Forms\Components\TextInput::make('kartu_pegawai')
                                    ->label('Kartu Pegawai')
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nomor kartu pegawai'),

                                Forms\Components\TextInput::make('status_kepegawaian')
                                    ->label('Status Kepegawaian')
                                    ->maxLength(255)
                                    ->placeholder('Masukkan status kepegawaian'),

                                Forms\Components\DatePicker::make('menuju_pensiun')
                                    ->label('Menuju Pensiun')
                                    ->displayFormat('d/m/Y')
                                    ->native(false)
                                    ->placeholder('Pilih tanggal pensiun'),
                            ]),
                    ]),

                Forms\Components\Section::make('Informasi Kontak')
                    ->description('Data komunikasi dan kontak')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('no_telepon')
                                    ->label('Nomor Telepon')
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nomor telepon'),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan alamat email'),
                            ]),
                    ]),

                Forms\Components\Section::make('Informasi BPJS')
                    ->description('Data asuransi sosial')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('no_bpjs_kesehatan')
                                    ->label('BPJS Kesehatan')
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nomor BPJS Kesehatan'),

                                Forms\Components\TextInput::make('no_bpjs_ketenagakerjaan')
                                    ->label('BPJS Ketenagakerjaan (Non PNS)')
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nomor BPJS Ketenagakerjaan'),
                            ]),
                    ]),

                Forms\Components\Section::make('Informasi Keuangan')
                    ->description('Data rekening dan status aplikasi')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('rekening')
                                    ->label('Nomor Rekening')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nomor rekening'),

                                Forms\Components\TextInput::make('nama_bank')
                                    ->label('Nama Bank')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nama bank'),

                                Forms\Components\TextInput::make('status_aplikasi')
                                    ->label('Status Aplikasi')
                                    ->maxLength(255)
                                    ->placeholder('Masukkan status aplikasi'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_staf')
                    ->label('NAMA')
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-o-user')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nip_staf')
                    ->label('NIP')
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-o-identification')
                    ->searchable(),


                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label('TANGGAL LAHIR')
                    ->date('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->visibleFrom('md'),
                Tables\Columns\TextColumn::make('menuju_pensiun')
                    ->label('MENUJU PENSIUN')
                    ->getStateUsing(function ($record) {
                        if (!$record->tanggal_lahir) {
                            return 'Data tanggal lahir tidak tersedia';
                        }

                        $tanggalLahir = \Carbon\Carbon::parse($record->tanggal_lahir);
                        $usiaPensiun = $tanggalLahir->copy()->addYears(58);
                        $sekarang = \Carbon\Carbon::now();

                        if ($sekarang->greaterThanOrEqualTo($usiaPensiun)) {
                            return 'Sudah pensiun';
                        }

                        $diff = $sekarang->diff($usiaPensiun);
                        $tahun = $diff->y;
                        $bulan = $diff->m;
                        $hari = $diff->d;

                        $parts = [];
                        if ($tahun > 0) $parts[] = $tahun . ' tahun';
                        if ($bulan > 0) $parts[] = $bulan . ' bulan';
                        if ($hari > 0) $parts[] = $hari . ' hari';

                        return implode(', ', $parts) . ' lagi';
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw("
                            CASE
                                WHEN tanggal_lahir IS NULL THEN 9999999999
                                WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) >= 58 THEN 9999999998
                                ELSE TIMESTAMPDIFF(DAY, CURDATE(), DATE_ADD(tanggal_lahir, INTERVAL 58 YEAR))
                            END " . $direction);
                    })
                    ->icon('heroicon-o-clock')
                    ->visibleFrom('md'),


                Tables\Columns\TextColumn::make('no_telepon')
                    ->label('NO TELEPON')
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->visibleFrom('md'),



                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_aplikasi')
                    ->label('Status Aplikasi')
                    ->options([
                        'Pengemudi' => 'Pengemudi',
                        'Asisten' => 'Asisten',
                    ])
                    ->multiple()
                    ->placeholder('Pilih status aplikasi'),
                Tables\Filters\SelectFilter::make('status_kepegawaian')
                    ->label('Status Kepegawaian')
                    ->options([
                        'Pegawai Tetap Non PNS' => 'Pegawai Tetap Non PNS',
                        'PNS' => 'PNS',
                    ])
                    ->multiple()
                    ->placeholder('Pilih status kepegawaian'),
                Tables\Filters\SelectFilter::make('gol_pangkat')
                    ->label('Golongan/Pangkat')
                    ->options(function () {
                        return \App\Models\Staf::distinct()->pluck('gol_pangkat', 'gol_pangkat')->filter()->toArray();
                    })
                    ->multiple()
                    ->placeholder('Pilih golongan/pangkat'),
                Tables\Filters\SelectFilter::make('pendidikan_aktif')
                    ->label('Pendidikan Aktif')
                    ->options(function () {
                        return \App\Models\Staf::distinct()->pluck('pendidikan_aktif', 'pendidikan_aktif')->filter()->toArray();
                    })
                    ->multiple()
                    ->placeholder('Pilih pendidikan aktif'),
                Tables\Filters\SelectFilter::make('status_pensiun')
                    ->label('Status Pensiun')
                    ->options([
                        'akan_pensiun' => 'Akan Pensiun',
                        'sudah_pensiun' => 'Sudah Pensiun',
                        'data_tidak_lengkap' => 'Data Tidak Lengkap',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        return $query->where(function ($q) use ($data) {
                            foreach ($data['value'] as $status) {
                                if ($status === 'akan_pensiun') {
                                    $q->orWhereRaw("TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) < 58");
                                } elseif ($status === 'sudah_pensiun') {
                                    $q->orWhereRaw("TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) >= 58");
                                } elseif ($status === 'data_tidak_lengkap') {
                                    $q->orWhereNull('tanggal_lahir');
                                }
                            }
                        });
                    })
                    ->multiple()
                    ->placeholder('Pilih status pensiun'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->color('warning'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->poll('30s')
            ->emptyStateHeading('Tidak ada data pengemudi')
            ->emptyStateDescription('Belum ada pengemudi yang terdaftar. Mulai dengan membuat pengemudi baru.')
            ->emptyStateIcon('heroicon-o-user-group');
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
            'index' => Pages\ListStafs::route('/'),
            'create' => Pages\CreateStaf::route('/create'),
            'edit' => Pages\EditStaf::route('/{record}/edit'),
        ];
    }
}
