<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PengajuanController;
use App\Http\Controllers\Public\HasilController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordChangeController;

use App\Http\Controllers\BackOffice\DashboardController;
use App\Http\Controllers\BackOffice\HistoryController;
use App\Http\Controllers\BackOffice\RoleUserController;
use App\Http\Controllers\BackOffice\RoleMenuController;
use App\Http\Controllers\BackOffice\PesertaController;
use App\Http\Controllers\BackOffice\MentorController;
use App\Http\Controllers\BackOffice\PerguruanTinggiController;
use App\Http\Controllers\BackOffice\LogbookController as AdminLogbookController;
use App\Http\Controllers\BackOffice\PengajuanController as BackOfficePengajuanController;

use App\Http\Controllers\Peserta\LogbookController as PesertaLogbookController;
use App\Http\Controllers\Mentor\LogbookController as MentorLogbookController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class,'index'])->name('home');

Route::get('/pengajuan', [PengajuanController::class,'index'])->name('pengajuan');

Route::get('/hasil', [HasilController::class,'index'])->name('hasil');

/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

Route::get('/login',[LoginController::class,'index'])->name('login');

Route::post('/login',[LoginController::class,'login'])->name('login.post');

Route::post('/logout',[LoginController::class,'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/password/change',[PasswordChangeController::class,'show'])
    ->middleware('auth')
    ->name('password.change');

Route::post('/password/change',[PasswordChangeController::class,'update'])
    ->middleware('auth')
    ->name('password.change.post');

Route::get('/file/preview/{type}/{filename}',
    [\App\Http\Controllers\BackOffice\FileController::class,'preview']
);

/*
|--------------------------------------------------------------------------
| Back Office
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('back-office')
    ->name('back-office.')
    ->group(function(){

    Route::get('/dashboard',[DashboardController::class,'index'])
        ->name('dashboard');

    Route::get('/role-user',[RoleUserController::class,'index'])
        ->name('role-user');

    Route::get('/role-user/data',[RoleUserController::class,'getData']);

    Route::get('/role-user/data/{id}',[RoleUserController::class,'show']);

    Route::get('/role-menu',[RoleMenuController::class,'index'])
        ->name('role-menu');

    Route::get('/pengajuan',[BackOfficePengajuanController::class,'index'])
        ->name('pengajuan');

    Route::get('/peserta',[PesertaController::class,'index'])
        ->name('peserta');

    Route::get('/mentor',[MentorController::class,'index'])
        ->name('mentor');

    Route::get('/perguruan-tinggi',[PerguruanTinggiController::class,'index'])
        ->name('perguruan-tinggi');

    Route::get('/logbook',[AdminLogbookController::class,'index'])
        ->name('logbook');

    Route::get('/history',[HistoryController::class,'index'])
        ->name('history');

    Route::get('/history/data',[HistoryController::class,'getData']);
});


/*
|--------------------------------------------------------------------------
| Peserta
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('peserta')
    ->name('peserta.')
    ->group(function () {

        Route::get('/logbook', [PesertaLogbookController::class,'index'])
            ->name('logbook.index');

        Route::get('/logbook/data', [PesertaLogbookController::class,'getData']);

        Route::post('/logbook', [PesertaLogbookController::class,'store']);

        Route::get('/logbook/{id}', [PesertaLogbookController::class,'show']);

        Route::put('/logbook/{id}', [PesertaLogbookController::class,'update']);

        Route::delete('/logbook/{id}', [PesertaLogbookController::class,'destroy']);

    });

Route::middleware('auth')
    ->prefix('mentor')
    ->name('mentor.')
    ->group(function () {


        Route::get('/logbook',
            [
                MentorLogbookController::class,
                'index'
            ]
        )
        ->name('logbook.index');



        Route::get('/logbook/peserta',
            [
                MentorLogbookController::class,
                'peserta'
            ]
        )
        ->name('logbook.peserta');



        Route::get('/logbook/data',
            [
                MentorLogbookController::class,
                'getData'
            ]
        )
        ->name('logbook.data');


    });