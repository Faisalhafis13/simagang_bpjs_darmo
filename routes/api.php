<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Public\PengajuanController;
use App\Http\Controllers\Public\HasilController;

/*
|--------------------------------------------------------------------------
| Public API
|--------------------------------------------------------------------------
*/

Route::prefix('public')->group(function () {

    Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('api.pengajuan.store');
    Route::post('/hasil', [HasilController::class,'cari'])->name('api.hasil');
});