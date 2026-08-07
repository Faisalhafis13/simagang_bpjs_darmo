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
use App\Http\Controllers\BackOffice\PesertaController as BackOfficePesertaController;
use App\Http\Controllers\BackOffice\MentorController;
use App\Http\Controllers\BackOffice\PerguruanTinggiController;
use App\Http\Controllers\BackOffice\LogbookController as AdminLogbookController;
use App\Http\Controllers\BackOffice\PengajuanController as BackOfficePengajuanController;

use App\Http\Controllers\Peserta\PesertaController as PesertaPesertaController;
use App\Http\Controllers\Peserta\LogbookController as PesertaLogbookController;

use App\Http\Controllers\Mentor\LogbookController as MentorLogbookController;


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
| Login
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'index'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Password
|--------------------------------------------------------------------------
*/

Route::get('/password/change', [PasswordChangeController::class, 'show'])
    ->middleware('auth')
    ->name('password.change');

Route::post('/password/change', [PasswordChangeController::class, 'update'])
    ->middleware('auth')
    ->name('password.change.post');


/*
|--------------------------------------------------------------------------
| File Preview
|--------------------------------------------------------------------------
*/

Route::get(
    '/file/preview/{type}/{filename}',
    [\App\Http\Controllers\BackOffice\FileController::class, 'preview']
);


/*
|--------------------------------------------------------------------------
| Back Office
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('back-office')
    ->name('back-office.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Role User
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/role-user',
            [RoleUserController::class, 'index']
        )->name('role-user');

        Route::get(
            '/role-user/data',
            [RoleUserController::class, 'getData']
        );

        Route::get(
            '/role-user/data/{id}',
            [RoleUserController::class, 'show']
        );


        /*
        |--------------------------------------------------------------------------
        | Role Menu
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/role-menu',
            [RoleMenuController::class, 'index']
        )->name('role-menu');


        /*
        |--------------------------------------------------------------------------
        | Pengajuan
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pengajuan',
            [BackOfficePengajuanController::class, 'index']
        )->name('pengajuan');


        /*
        |--------------------------------------------------------------------------
        | Peserta - ADMIN
        |--------------------------------------------------------------------------
        */

Route::get(
    '/peserta',
    [BackOfficePesertaController::class, 'index']
)->name('peserta');

Route::get(
    '/peserta/data',
    [BackOfficePesertaController::class, 'getData']
);

Route::post(
    '/peserta/{id}/surat-penerimaan',
    [BackOfficePesertaController::class, 'uploadSuratPenerimaan']
)->name('peserta.surat-penerimaan.upload');

Route::delete(
    '/peserta/{id}/surat-penerimaan',
    [BackOfficePesertaController::class, 'deleteSuratPenerimaan']
)->name('peserta.surat-penerimaan.delete');
        /*
        |--------------------------------------------------------------------------
        | Mentor - ADMIN
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/mentor',
            [MentorController::class, 'index']
        )->name('mentor');


        /*
        |--------------------------------------------------------------------------
        | Perguruan Tinggi
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/perguruan-tinggi',
            [PerguruanTinggiController::class, 'index']
        )->name('perguruan-tinggi');


        /*
        |--------------------------------------------------------------------------
        | Logbook - ADMIN
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/logbook',
            [AdminLogbookController::class, 'index']
        )->name('logbook');


        /*
        |--------------------------------------------------------------------------
        | History
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/history',
            [HistoryController::class, 'index']
        )->name('history');

        Route::get(
            '/history/data',
            [HistoryController::class, 'getData']
        );
    });


/*
|--------------------------------------------------------------------------
| Peserta
|--------------------------------------------------------------------------
|
| Semua halaman di bawah ini adalah milik USER/PESERTA yang login.
|
*/

Route::middleware('auth')
    ->prefix('peserta')
    ->name('peserta.')
    ->group(function () {

        Route::get(
            '/',
            [PesertaPesertaController::class, 'index']
        )->name('index');

        Route::get(
            '/logbook',
            [PesertaLogbookController::class, 'index']
        )->name('logbook.index');

        Route::get(
            '/logbook/data',
            [PesertaLogbookController::class, 'getData']
        )->name('logbook.data');

        Route::post(
            '/logbook',
            [PesertaLogbookController::class, 'store']
        );

        Route::get(
            '/logbook/{id}',
            [PesertaLogbookController::class, 'show']
        );

        Route::put(
            '/logbook/{id}',
            [PesertaLogbookController::class, 'update']
        );

        Route::delete(
            '/logbook/{id}',
            [PesertaLogbookController::class, 'destroy']
        );
    });
/*
|--------------------------------------------------------------------------
| Mentor
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('mentor')
    ->name('mentor.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Monitoring Logbook Mentor
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/logbook',
            [MentorLogbookController::class, 'index']
        )->name('logbook.index');


        /*
        | Daftar peserta milik mentor
        */
        Route::get(
            '/logbook/peserta',
            [MentorLogbookController::class, 'peserta']
        )->name('logbook.peserta');


        /*
        | Data logbook peserta
        */
        Route::get(
            '/logbook/data',
            [MentorLogbookController::class, 'getData']
        )->name('logbook.data');
    });