<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KendaraanResource\Pages;
use App\Filament\Resources\KendaraanResource\RelationManagers;
use App\Models\Kendaraan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KendaraanResource extends Resource
{
    protected static ?string $model = Kendaraan::class;

    protected static ?string $navigationLabel = 'Data Kendaraan';
    protected static ?string $navigationGroup = 'Poll Kendaraan';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nopol_kendaraan')
                    ->label('Nopol Kendaraan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('merk_type')
                    ->label('Merk & Tipe')
                    ->maxLength(255),
                Forms\Components\TextInput::make('jenis_kendaraan')
                    ->label('Jenis')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('warna_tanda')
                    ->label('Warna TNKB')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tahun_pembuatan')
                    ->label('Tahun Pembuatan'),
                Forms\Components\TextInput::make('nomor_rangka')
                    ->label('Nomor Rangka')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nomor_mesin')
                    ->label('Nomor Mesin')
                    ->maxLength(255),
                Forms\Components\TextInput::make('lokasi_kendaraan')
                    ->label('Lokasi Kendaraan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('penggunaan')
                    ->label('Penggunaan')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('foto_kendaraan')
                    ->label('Foto Kendaraan')
                    ->directory('foto-kendaraan')
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(5120)
                    ->image()
                    ->preserveFilenames(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nopol_kendaraan')
                    ->label('NOPOL')
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-o-truck')
                    ->weight('bold')
                    ->searchable()
                    ->formatStateUsing(fn (string $state, $record) => $state . ' - ' . $record->jenis_kendaraan),
                Tables\Columns\TextColumn::make('merk_type')
                    ->label('Merk & Tipe')
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kendaraan')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Minibus' => 'primary',
                        'SUV' => 'success',
                        'Mikrobus' => 'warning',
                        'Ambulance' => 'danger',
                        'Pick Up' => 'info',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Minibus' => 'heroicon-o-users',
                        'SUV' => 'heroicon-o-cube',
                        'Mikrobus' => 'heroicon-o-building-office',
                        'Ambulance' => 'heroicon-o-heart',
                        'Pick Up' => 'heroicon-o-wrench-screwdriver',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokasi_kendaraan')
                    ->label('Lokasi')
                    ->badge()
                    ->color('secondary')
                    ->icon('heroicon-o-map-pin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_pembuatan')
                    ->label('Tahun Pembuatan')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-calendar-days')
                    ->searchable(),

                Tables\Columns\ImageColumn::make('foto_kendaraan')
                    ->label('Foto Kendaraan')
                    ->circular()
                    ->defaultImageUrl('/images/logo-universitas.png')
                    ->size(60),
            ])
            ->filters([
                // ... (filters remain unchanged)
            ])
            ->actions([
                Tables\Actions\Action::make('manage_kondisi_ban')
                    ->label('Kelola Kondisi Ban')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('success')
                    ->url(fn ($record) => route('filament.app.resources.kondisi-bans.index', ['tableFilters[nopol_kendaraan][value]' => $record->nopol_kendaraan])),
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
            ->emptyStateHeading('Tidak ada data kendaraan')
            ->emptyStateDescription('Belum ada kendaraan yang terdaftar. Mulai dengan membuat kendaraan baru.')
            ->emptyStateIcon('heroicon-o-truck');
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
            'index' => Pages\ListKendaraans::route('/'),
            'create' => Pages\CreateKendaraan::route('/create'),
            'view' => Pages\ViewKendaraan::route('/{record}'),
            'edit' => Pages\EditKendaraan::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('view_any_kendaraan');
    }
}
