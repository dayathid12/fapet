# TODO: Tambahkan Pengurutan Manual untuk Kolom Daftar Kendaraan

## Langkah-langkah yang Harus Diselesaikan:

1. **Tambahkan kolom `sort_order` ke tabel `kendaraans`**:
   - Buat migrasi untuk menambahkan kolom `sort_order` ke tabel `kendaraans`, mirip dengan yang ada di tabel `stafs`.
   - [x] Migrasi dibuat dan dijalankan.

2. **Perbarui model `Kendaraan`**:
   - Tambahkan `sort_order` ke dalam array `$fillable` di model `Kendaraan`.
   - [x] `sort_order` ditambahkan ke `$fillable`.

3. **Modifikasi `BookingKendaraanCalendar.php`**:
   - Tambahkan properti `manualSortOrder` untuk menyimpan urutan manual.
   - Tambahkan metode `updateKendaraanSort` untuk menangani pembaruan urutan.
   - Ubah query kendaraan untuk diurutkan berdasarkan `sort_order`.
   - [x] Properti `manualSortOrder` ditambahkan.
   - [x] Metode `updateKendaraanSort` ditambahkan.
   - [x] Query kendaraan diubah untuk diurutkan berdasarkan `sort_order`.

4. **Perbarui tampilan `booking-kendaraan-calendar.blade.php`**:
   - Tambahkan JavaScript sortable untuk baris kendaraan, mirip dengan yang ada di jadwal-pengemudi-calendar.blade.php.
   - Tambahkan handle drag pada sel kendaraan.
   - [x] JavaScript sortable ditambahkan.
   - [x] Handle drag ditambahkan.

5. **Jalankan migrasi**:
   - Jalankan migrasi untuk menerapkan perubahan database.
   - [x] Migrasi dijalankan.

6. **Uji fungsionalitas**:
   - Pastikan pengurutan manual berfungsi dengan baik di halaman booking-kendaraans.
   - [ ] Uji fungsionalitas (belum dilakukan).
