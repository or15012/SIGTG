<?php

use App\Http\Controllers\ForumController;
use App\Http\Controllers\WorkshopController;
use Illuminate\Support\Facades\Route;

//Grupo de rutas foros
Route::group(['prefix' => 'forum', 'as' => 'forum.'], function () {
    Route::get('/', [ForumController::class, 'index'])->name('index');
    Route::get('create', [ForumController::class, 'create'])->name('create');
    Route::post('/', [ForumController::class, 'store'])->name('store');
    Route::get('/show/{forums}', [ForumController::class, 'show'])->name('show');
    Route::delete('/destroy/{forums}', [ForumController::class, 'destroy'])->name('destroy');
    Route::get('/download/{forums}/{file}', [ForumController::class, 'forumDownload'])->name('download');
});

//Grupo de rutas talleres
Route::group(['prefix' => 'workshop', 'as' => 'workshop.'], function () {
    Route::get('/', [WorkshopController::class, 'index'])->name('index');
    Route::get('create', [WorkshopController::class, 'create'])->name('create');
    Route::post('/', [WorkshopController::class, 'store'])->name('store');
    Route::get('/show/{workshop}', [WorkshopController::class, 'show'])->name('show');
    Route::delete('/destroy/{workshop}', [WorkshopController::class, 'destroy'])->name('destroy');
    Route::get('/download/{workshop}/{file}', [WorkshopController::class, 'workshopDownload'])->name('download');
});

