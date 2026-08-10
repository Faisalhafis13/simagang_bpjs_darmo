<?php

use Illuminate\Support\Facades\Route;

// =====================================================
// PUBLIC CONTROLLERS
// =====================================================

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PengajuanController;
use App\Http\Controllers\Public\HasilController;

// =====================================================
// AUTH CONTROLLERS
// =====================================================

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordChangeController;

// =====================================================
// BACK OFFICE CONTROLLERS
// =====================================================

use App\Http\Controllers\BackOffice\DashboardController;
use App\Http\Controllers\BackOffice\HistoryController;
use App\Http\Controllers\BackOffice\RoleUserController;
use App\Http\Controllers\BackOffice\RoleMenuController;
use App\Http\Controllers\BackOffice\PesertaController as BackOfficePesertaController;
use App\Http\Controllers\BackOffice\MentorController;
use App\Http\Controllers\BackOffice\PerguruanTinggiController;
use App\Http\Controllers\BackOffice\LogbookController as AdminLogbookController;
use App\Http\Controllers\BackOffice\PengajuanController as BackOfficePengajuanController;
use App\Http\Controllers\BackOffice\ExportController;

// =====================================================
// PESERTA CONTROLLERS
// =====================================================

use App\Http\Controllers\Peserta\PesertaController as PesertaPesertaController;
use App\Http\Controllers\Peserta\LogbookController as PesertaLogbookController;

// =====================================================
// MENTOR CONTROLLERS
// =====================================================

use App\Http\Controllers\Mentor\LogbookController as MentorLogbookController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan');

Route::get('/hasil', [HasilController::class, 'index'])->name('hasil');

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| PASSWORD
|--------------------------------------------------------------------------
*/

Route::get('/password/change', [PasswordChangeController::class, 'show'])->middleware('auth')->name('password.change');

Route::post('/password/change', [PasswordChangeController::class, 'update'])->middleware('auth')->name('password.change.post');

/*
|--------------------------------------------------------------------------
| FILE PREVIEW
|--------------------------------------------------------------------------
*/

Route::get('/file/preview/{type}/{filename}', [\App\Http\Controllers\BackOffice\FileController::class, 'preview']);

/*
|--------------------------------------------------------------------------
| BACK OFFICE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('back-office')->name('back-office.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| ROLE USER
|--------------------------------------------------------------------------
*/

Route::get('/role-user', [RoleUserController::class, 'index'])
    ->name('role-user');

Route::get('/role-user/data', [RoleUserController::class, 'getData'])
    ->name('role-user.data');

Route::get('/role-user/data/{id}', [RoleUserController::class, 'show'])
    ->name('role-user.show');

Route::post('/role-user', [RoleUserController::class, 'store'])
    ->name('role-user.store');

Route::put('/role-user/{id}', [RoleUserController::class, 'update'])
    ->name('role-user.update');

Route::delete('/role-user/{id}', [RoleUserController::class, 'destroy'])
    ->name('role-user.destroy');

Route::get('/role-user/export', [ExportController::class, 'roleUser'])
    ->name('role-user.export');

    /*
    |--------------------------------------------------------------------------
    | ROLE MENU
    |--------------------------------------------------------------------------
    */

    Route::get('/role-menu', [RoleMenuController::class, 'index'])->name('role-menu');

    Route::get('/role-menu/export', [ExportController::class, 'roleMenu'])->name('role-menu.export');

    /*
    |--------------------------------------------------------------------------
    | PENGAJUAN MAGANG
    |--------------------------------------------------------------------------
    */

    Route::get('/pengajuan', [BackOfficePengajuanController::class, 'index'])->name('pengajuan');

    Route::get('/pengajuan/data', [BackOfficePengajuanController::class, 'getData'])->name('pengajuan.data');

    Route::put('/pengajuan/{id}', [BackOfficePengajuanController::class, 'update'])->name('pengajuan.update');

    Route::get('/pengajuan/export', [ExportController::class, 'pengajuan'])->name('pengajuan.export');

    /*
    |--------------------------------------------------------------------------
    | PESERTA - ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/peserta', [BackOfficePesertaController::class, 'index'])->name('peserta');

    Route::get('/peserta/data', [BackOfficePesertaController::class, 'getData'])->name('peserta.data');

    Route::get('/peserta/export', [ExportController::class, 'peserta'])->name('peserta.export');

    Route::post('/peserta/{id}/surat-penerimaan', [BackOfficePesertaController::class, 'uploadSuratPenerimaan'])->name('peserta.surat-penerimaan.upload');

    Route::delete('/peserta/{id}/surat-penerimaan', [BackOfficePesertaController::class, 'deleteSuratPenerimaan'])->name('peserta.surat-penerimaan.delete');

    /*
    |--------------------------------------------------------------------------
    | MENTOR - ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/mentor', [MentorController::class, 'index'])->name('mentor');

    Route::get('/mentor/export', [ExportController::class, 'mentor'])->name('mentor.export');

    /*
    |--------------------------------------------------------------------------
    | PERGURUAN TINGGI
    |--------------------------------------------------------------------------
    */

    Route::get('/perguruan-tinggi', [PerguruanTinggiController::class, 'index'])->name('perguruan-tinggi');

    Route::get('/perguruan-tinggi/export', [ExportController::class, 'perguruanTinggi'])->name('perguruan-tinggi.export');

    /*
    |--------------------------------------------------------------------------
    | LOGBOOK - ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/logbook', [AdminLogbookController::class, 'index'])->name('logbook');

    Route::get('/logbook/data', [AdminLogbookController::class, 'getData'])->name('logbook.data');

    Route::get('/logbook/export', [ExportController::class, 'logbook'])->name('logbook.export');

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    Route::get('/history', [HistoryController::class, 'index'])->name('history');

    Route::get('/history/data', [HistoryController::class, 'getData'])->name('history.data');

    Route::get('/history/export', [ExportController::class, 'history'])->name('history.export');
});

/*
|--------------------------------------------------------------------------
| PESERTA
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('peserta')->name('peserta.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PESERTA
    |--------------------------------------------------------------------------
    */

    Route::get('/', [PesertaPesertaController::class, 'index'])->name('index');

/*
|--------------------------------------------------------------------------
| LOGBOOK PESERTA
|--------------------------------------------------------------------------
*/

Route::get('/logbook', [PesertaLogbookController::class, 'index'])
    ->name('logbook.index');

Route::get('/logbook/data', [PesertaLogbookController::class, 'getData'])
    ->name('logbook.data');

Route::post('/logbook', [PesertaLogbookController::class, 'store'])
    ->name('logbook.store');

Route::get('/logbook/export', [ExportController::class, 'logbookPeserta'])
    ->name('logbook.export');

Route::get('/logbook/{id}', [PesertaLogbookController::class, 'show'])
    ->name('logbook.show');

Route::put('/logbook/{id}', [PesertaLogbookController::class, 'update'])
    ->name('logbook.update');

Route::delete('/logbook/{id}', [PesertaLogbookController::class, 'destroy'])
    ->name('logbook.destroy');
    });

/*
|--------------------------------------------------------------------------
| MENTOR
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('mentor')->name('mentor.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | LOGBOOK MENTOR
    |--------------------------------------------------------------------------
    */

    Route::get('/logbook', [MentorLogbookController::class, 'index'])->name('logbook.index');
    Route::get('/logbook/peserta', [MentorLogbookController::class, 'peserta'])->name('logbook.peserta');
    Route::get('/logbook/data', [MentorLogbookController::class, 'getData'])->name('logbook.data');
    Route::get('/logbook/export', [ExportController::class, 'logbookMentor'])->name('logbook.export');
    Route::put('/logbook/{id}/approve', [MentorLogbookController::class, 'approve'])->name('logbook.approve');
    Route::put('/logbook/{id}/feedback', [MentorLogbookController::class, 'feedback'])->name('logbook.feedback');

});