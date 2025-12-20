<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalMengemudiResource\Pages;
use App\Filament\Resources\JadwalMengemudiResource\RelationManagers;
use App\Models\JadwalMengemudi;
use App\Models\Perjalanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JadwalMengemudiResource extends Resource
{
    protected static ?string $model = Perjalanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pengemudi.nama_staf') // Nama Pengemudi dari relasi Staf
                    ->label('Nama Pengemudi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kendaraan.nopol') // Nomor Polisi Kendaraan dari relasi Kendaraan
                    ->label('Nomor Polisi Kendaraan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_perjalanan') // Asumsi ada di model Perjalanan
                    ->label('Status Perjalanan')
                    ->badge() // Tampilkan sebagai badge untuk tampilan lebih baik
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu' => 'warning',
                        'berangkat' => 'info',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_personil_perwakilan') // Nama Perwakilan dari model Perjalanan
                    ->label('Nama Perwakilan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kontak_pengguna_perwakilan') // Kontak Perwakilan dari model Perjalanan
                    ->label('Kontak Perwakilan'),
                Tables\Columns\TextColumn::make('lokasi_keberangkatan') // Lokasi Keberangkatan dari model Perjalanan
                    ->label('Lokasi Keberangkatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_rombongan') // Jumlah Rombongan dari model Perjalanan
                    ->label('Jumlah Rombongan')
                    ->numeric(),
                Tables\Columns\TextColumn::make('alamat_tujuan') // Alamat Tujuan dari model Perjalanan
                    ->label('Alamat Tujuan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wilayah.nama_wilayah') // Kota Kabupaten dari relasi Wilayah
                    ->label('Kota Kabupaten')
                    ->searchable()
                    ->sortable(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (auth()->check()) {
                    $user = auth()->user();
                    // Filter berdasarkan nama_lengkap dari relasi 'pengemudi' (Staf)
                    $query->whereHas('pengemudi', function (Builder $pengemudiQuery) use ($user) {
                        $pengemudiQuery->where('stafs.nama_staf', $user->name);
                    });
                }
                return $query;
            })
            ->filters([
                Tables\Filters\SelectFilter::make('status_perjalanan')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'berangkat' => 'Berangkat',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ])
                    ->label('Filter Status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Jika user memiliki relasi staf, filter berdasarkan staf_id
        if ($user && $user->staf) {
            $query->whereHas('details', function (Builder $query) use ($user) {
                $query->where('pengemudi_id', $user->staf->id);
            });
        }
        // Tambahkan logika lain jika ada peran admin atau yang bisa melihat semua
        // if ($user && $user->hasRole('admin')) {
        //     return parent::getEloquentQuery(); // Admin melihat semua
        // }

        return $query;
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
            'index' => Pages\ListJadwalMengemudis::route('/'),
            'edit' => Pages\EditJadwalMengemudi::route('/{record}/edit'),
        ];
    }
}
