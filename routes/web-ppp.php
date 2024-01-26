<?php

use App\Http\Controllers\ProposalController;
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
        Route::get('/', [ProposalController::class, 'applicationIndex'])->name('index');
        Route::get('/create/{proposal}', [ProposalController::class, 'applicationCreate'])->name('create');
        Route::post('/', [ProposalController::class, 'applicationStore'])->name('store');

        Route::group(['prefix' => 'coordinator', 'as' => 'coordinator.'], function () {
            //Rutas para coordinador para listado de estudiantes que han aplicado a las propuestas
            Route::get('/', [ProposalController::class, 'applicationCoordinatorIndex'])->name('index');
            Route::get('show/{application}', [ProposalController::class, 'applicationCoordinatorShow'])->name('show');
            Route::get('/download/{application}/{file}', [ProposalController::class, 'applicationDownload'])->name('download');
            Route::put('/update/{application}', [ProposalController::class, 'coordinatorUpdate'])->name('update');

        });
    });
});


