<?php

use App\Http\Controllers\ForumController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\WorkshopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Grupo para las rutas de propuestas
Route::group(['prefix' => 'proposals', 'as' => 'proposals.'], function () {
    Route::get('/', [ProposalController::class, 'index'])->name('index');
    Route::get('create', [ProposalController::class, 'create'])->name('create');
    Route::post('/', [ProposalController::class, 'store'])->name('store');
    Route::get('/show/{proposal}', [ProposalController::class, 'show'])->name('show');
    Route::delete('/destroy/{proposal}', [ProposalController::class, 'destroy'])->name('destroy');
    Route::get('/download/{proposal}/{file}', [ProposalController::class, 'proposalDownload'])->name('download');

    Route::group(['prefix' => 'applications', 'as' => 'applications.'], function () {
        //Rutas para estudiantes para aplicar a propuestas.
        Route::get('/', [ProposalController::class, 'indexApplication'])->name('index');
        Route::get('/create/{proposal}', [ProposalController::class, 'createApplication'])->name('create');
        Route::post('/', [ProposalController::class, 'storeApplication'])->name('store');
    });
});

//Grupo de rutas talleres
Route::group(['prefix' => 'workshops', 'as' => 'workshops.'], function () {
    Route::get('/', [WorkshopController::class, 'index'])->name('index');
    Route::get('create', [WorkshopController::class, 'create'])->name('create');
    Route::post('/', [WorkshopController::class, 'store'])->name('store');
    Route::get('/show/{workshop}', [WorkshopController::class, 'show'])->name('show');
    Route::delete('/destroy/{workshop}', [WorkshopController::class, 'destroy'])->name('destroy');
    Route::get('/download/{workshop}/{file}', [WorkshopController::class, 'workshopDownload'])->name('download');
});

//Grupo de rutas foros
Route::group(['prefix' => 'forums', 'as' => 'forums.'], function () {
    Route::get('/', [ForumController::class, 'index'])->name('index');
    Route::get('create', [ForumController::class, 'create'])->name('create');
    Route::post('/', [ForumController::class, 'store'])->name('store');
    Route::get('/show/{forums}', [ForumController::class, 'show'])->name('show');
    Route::delete('/destroy/{forums}', [ForumController::class, 'destroy'])->name('destroy');
    Route::get('/download/{forums}/{file}', [ForumController::class, 'forumDownload'])->name('download');
});
