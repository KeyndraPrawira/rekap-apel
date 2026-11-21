<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Master\PegawaiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('auth.login');
});

Route::get('/dashboard', [Dashboard::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('absen/export')->name('absen.export.')->group(function () {
    Route::get('pdf', [AbsenController::class, 'exportPdf'])->name('pdf');
    Route::get('excel', [AbsenController::class, 'exportExcel'])->name('excel');
    });
    Route::resource('pegawai', PegawaiController::class);
    Route::resource('absen', AbsenController::class);
    Route::get('/absen/{absen}/detail', [AbsenController::class, 'detail'])->name('absen.detail');
Route::post('/absen/{absen}/detail', [AbsenController::class, 'storeDetail'])->name('absen.detail.store');
});

require __DIR__.'/auth.php';
