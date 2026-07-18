<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\DesignerController;
use App\Http\Controllers\NavigationController;

Route::get('/', function () {
    return view('welcome');
});

    // navigation
    Route::get('/navigation', [NavigationController::class,'index'])
        ->name('navigation.index');

    Route::get('/navigation/floor/{floor}', [NavigationController::class,'floor']);

    // shortest path
    Route::get('/navigation/{start}/{end}', [NavigationController::class,'path'])
    ->name('navigation.path');


    Route::get('/admin/login', [AdminController::class,'login']);
    Route::post('/admin/login', [AdminController::class,'authenticate']);

// ADMIN ROUTES
Route::middleware('admin')->group(function () {

    Route::get('/admin/dashboard', [AdminController::class,'dashboard']);

    Route::post('/admin/logout', [AdminController::class,'logout']);



    Route::get('/admin/floors', [FloorController::class,'index']);


    Route::get('/admin/designer/{floor}', [DesignerController::class, 'index']);

    Route::post('/admin/designer/{floor}/save', [DesignerController::class, 'save'])
        ->name('designer.save');

    
    Route::get('/admin/designer/{floor}/load', [DesignerController::class,'load'])
        ->name('designer.load');


});