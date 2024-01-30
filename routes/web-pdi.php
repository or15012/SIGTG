<?php

use App\Http\Controllers\ForumController;
use App\Http\Controllers\WorkshopController;
use Illuminate\Support\Facades\Route;

//Grupo de rutas foros
Route::group(['prefix' => 'forum', 'as' => 'forum.'], function () {
    Route::get('/', [ForumController::class, 'index'])->name('index');
    Route::get('create', [ForumController::class, 'create'])->name('create');
    Route::post('/', [ForumController::class, 'store'])->name('store');
    Route::get('/show/{forum}', [ForumController::class, 'show'])->name('show');
    Route::delete('/destroy/{forum}', [ForumController::class, 'destroy'])->name('destroy');
    Route::get('/download/{forum}/{file}', [ForumController::class, 'forumDownload'])->name('download');
    Route::get('show-list-forums-workshops', [ForumController::class, 'showListForumsWorkshops'])->name('show.list.forums.workshops');
    Route::get('confirm-assistance-forums-workshops/{id}/{type}', [ForumController::class, 'confirmAssistanceForumsWorkshops'])->name('confirm.assistance.forums.workshops');

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

