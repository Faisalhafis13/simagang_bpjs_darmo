<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Public\PengajuanController;
use App\Http\Controllers\Public\HasilController;

use App\Http\Controllers\BackOffice\RoleUserController;
use App\Http\Controllers\BackOffice\RoleMenuController;
use App\Http\Controllers\BackOffice\MentorController;
use App\Http\Controllers\BackOffice\PerguruanTinggiController;
use App\Http\Controllers\BackOffice\HistoryController;
use App\Http\Controllers\BackOffice\LogbookController;
use App\Http\Controllers\BackOffice\PesertaController;
use App\Http\Controllers\BackOffice\PengajuanController as BackOfficePengajuanController;

use App\Http\Controllers\Peserta\LogbookController as PesertaLogbookController;

/*
|--------------------------------------------------------------------------
| Public API
|--------------------------------------------------------------------------
*/

Route::prefix('public')->group(function () {

    Route::post('/pengajuan',[PengajuanController::class,'store']);

    Route::post('/hasil',[HasilController::class,'cari']);

});

/*
|--------------------------------------------------------------------------
| Back Office API
|--------------------------------------------------------------------------
*/

Route::prefix('back-office')->group(function(){

    Route::get('/role-user',[RoleUserController::class,'getData']);

    Route::get('/role-user/{id}',[RoleUserController::class,'show']);

    Route::post('/role-user',[RoleUserController::class,'store']);

    Route::put('/role-user/{id}',[RoleUserController::class,'update']);

    Route::delete('/role-user/{id}',[RoleUserController::class,'destroy']);



    Route::get('/role-menu',[RoleMenuController::class,'getData']);

    Route::get('/role-menu/{id}',[RoleMenuController::class,'show']);

    Route::post('/role-menu',[RoleMenuController::class,'store']);

Route::put('/role-menu/{roleMenu}',
[RoleMenuController::class,'update']);

Route::delete('/role-menu/{roleMenu}',
[RoleMenuController::class,'destroy']);


    Route::get('/pengajuan',[BackOfficePengajuanController::class,'getData']);

    Route::put('/pengajuan/{id}',[BackOfficePengajuanController::class,'update']);



    Route::get('/peserta',[PesertaController::class,'getData']);



    Route::get('/mentor',[MentorController::class,'getData']);

    Route::get('/mentor/{id}',[MentorController::class,'show']);

    Route::post('/mentor',[MentorController::class,'store']);

    Route::put('/mentor/{id}',[MentorController::class,'update']);

    Route::delete('/mentor/{id}',[MentorController::class,'destroy']);

    Route::get('/mentor-peserta',[MentorController::class,'peserta']);



    Route::get('/perguruan-tinggi',[PerguruanTinggiController::class,'getData']);



    Route::get('/logbook',[LogbookController::class,'getData']);

    Route::post('/logbook',[LogbookController::class,'store']);

    Route::delete('/logbook/{id}',[LogbookController::class,'destroy']);



    Route::get('/history',[HistoryController::class,'getData']);

});

