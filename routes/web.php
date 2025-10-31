<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PerjalananController;

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
