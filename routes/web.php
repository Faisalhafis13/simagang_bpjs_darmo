<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PengajuanController;
use App\Http\Controllers\Public\HasilController;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\BackOffice\DashboardController;
use App\Http\Controllers\BackOffice\RoleUserController;
use App\Http\Controllers\BackOffice\RoleMenuController;
use App\Http\Controllers\BackOffice\PesertaController;
use App\Http\Controllers\BackOffice\MentorController;
use App\Http\Controllers\BackOffice\PerguruanTinggiController;
use App\Http\Controllers\BackOffice\LogbookController;
use App\Http\Controllers\BackOffice\PengajuanController as BackOfficePengajuanController;
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

        /*
        |--------------------------------------------------------------------------
        | Role User
        |--------------------------------------------------------------------------
        */

        Route::get('/role-user', [RoleUserController::class,'index'])
            ->name('role-user');

        Route::get('/role-user/data', [RoleUserController::class,'getData']);
        Route::get('/role-user/data/{id}', [RoleUserController::class,'show']);

        Route::get('/pengajuan', [BackOfficePengajuanController::class,'index'])
            ->name('pengajuan');

        Route::get('/peserta', [PesertaController::class,'index'])
            ->name('peserta');

        Route::get('/mentor', [MentorController::class,'index'])
            ->name('mentor');

        Route::get('/perguruan-tinggi', [PerguruanTinggiController::class,'index'])
            ->name('perguruan-tinggi');

        Route::get('/logbook', [LogbookController::class,'index'])
            ->name('logbook');

        /*
        |--------------------------------------------------------------------------
        | Role Menu
        |--------------------------------------------------------------------------
        */

        Route::get('/role-menu', [RoleMenuController::class,'index'])
            ->name('role-menu');

    });