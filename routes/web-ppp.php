<?php
use App\Http\Controllers\ProposalController;
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
