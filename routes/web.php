<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\DesignerController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\StairConnectionController;

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

    Route::get('/admin/designer/transitions', [DesignerController::class, 'transitionWaypoints']);

    Route::get('/admin/designer/{floor}', [DesignerController::class, 'index']);

    Route::post('/admin/designer/{floor}/save', [DesignerController::class, 'save'])
        ->name('designer.save');

    
    Route::get('/admin/designer/{floor}/load', [DesignerController::class,'load'])
        ->name('designer.load');



    Route::get('/admin/stairs', [StairConnectionController::class,'index'])->name('stairs.index');

    Route::post('/admin/stairs', [StairConnectionController::class,'store'])->name('stairs.store');

    Route::delete('/admin/stairs/{stair}', [StairConnectionController::class,'destroy'])->name('stairs.destroy');

    Route::post('/admin/waypoint/{waypoint}/toggle', [DesignerController::class, 'toggleTransition']);


    Route::post('/admin/waypoint/{waypoint}/link', [DesignerController::class, 'linkWaypoint']);

    // qr code
    Route::get('/admin/floors/{floor}/qr', [FloorController::class,'qr'])->name('floors.qr');

});