<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', [AdminController::class,'login']);
Route::post('/admin/login', [AdminController::class,'authenticate']);

Route::middleware('admin')->group(function () {

    Route::get('/admin/dashboard', [AdminController::class,'dashboard']);

    Route::post('/admin/logout', [AdminController::class,'logout']);

});