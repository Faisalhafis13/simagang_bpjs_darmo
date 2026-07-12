<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PengajuanController;
use App\Http\Controllers\Public\HasilController;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\BackOffice\DashboardController;
use App\Http\Controllers\BackOffice\RoleUserController;
use App\Http\Controllers\BackOffice\RoleMenuController;
/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/pengajuan', [PengajuanController::class, 'index'])
    ->name('pengajuan');

Route::get('/hasil', [HasilController::class, 'index'])
    ->name('hasil');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

//Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class,'index'])
        ->name('login');

    Route::post('/login', [LoginController::class,'login'])
        ->name('login.post');

//});

Route::post('/logout', [LoginController::class,'logout'])
    ->middleware('auth')
    ->name('logout');



Route::middleware('auth')
    ->prefix('back-office')
    ->name('back-office.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class,'index'])
            ->name('dashboard');

    });

