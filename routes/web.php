<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\DesignerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', [AdminController::class,'login']);
Route::post('/admin/login', [AdminController::class,'authenticate']);

Route::middleware('admin')->group(function () {

    Route::get('/admin/dashboard', [AdminController::class,'dashboard']);

    Route::post('/admin/logout', [AdminController::class,'logout']);



    Route::get('/admin/floors', [FloorController::class,'index']);


    Route::get('/admin/designer/{floor}', [DesignerController::class,'index']);

});