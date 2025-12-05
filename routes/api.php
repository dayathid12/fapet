<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WilayahController;
use App\Http\Controllers\Api\UnitKerjaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/wilayah/search', [WilayahController::class, 'search'])->name('api.wilayah.search');
Route::get('/unit-kerja/search', [UnitKerjaController::class, 'search'])->name('api.unit-kerja.search');
