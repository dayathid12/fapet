<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PerjalananController;
use App\Http\Controllers\PeminjamanKendaraanController;
use App\Http\Controllers\PdfController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/google/redirect', [SocialiteController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('google.callback');

// Route untuk Jadwal Kendaraan
Route::get('/jadwal-kendaraan', [JadwalController::class, 'index'])->name('jadwal.index');

// Route untuk PDF Perjalanan
Route::get('/perjalanan/{nomor_perjalanan}/pdf', [PerjalananController::class, 'generatePdf'])->name('perjalanan.pdf');

Route::get('/PeminjamanKendaraanUnpad', [PeminjamanKendaraanController::class, 'show'])->name('peminjaman.form');
Route::post('/PeminjamanKendaraanUnpad/submit', [PeminjamanKendaraanController::class, 'submit'])->name('peminjaman.submit');
Route::get('/peminjaman/sukses/{token}', [PeminjamanKendaraanController::class, 'success'])->name('peminjaman.success');
Route::get('/peminjaman/status/{token}', [PeminjamanKendaraanController::class, 'status'])->name('peminjaman.status');

Route::delete('/biaya/{id}', function ($id) {
    \App\Models\RincianBiaya::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Item berhasil dihapus');
})->name('biaya.delete');

Route::get('/files/{encodedPath}', [PerjalananController::class, 'showFile'])->name('file.show');

// Route untuk PDF Surat Tugas
Route::get('/surat-tugas/{no_surat_tugas}/pdf', [PdfController::class, 'generateSuratTugas'])->name('surat-tugas.pdf');

// Route untuk Word Surat Tugas
Route::get('/surat-tugas/{no_surat_tugas}/word', [PdfController::class, 'generateSuratTugasWord'])->name('surat-tugas.word');

// Route untuk PDF SPTJB

Route::get('/sptjb/detail/{detail}/pdf', [PdfController::class, 'generateSptjbPdf'])->name('sptjb.pdf');
Route::get('/sptjb/{sptjb}/pdf', [PdfController::class, 'generateSptjbFullPdf'])->name('sptjb.full.pdf');
