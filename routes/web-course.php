<?php


use App\Http\Controllers\CourseController;
use App\Http\Controllers\CoursePreRegistrationController;
use App\Http\Controllers\EntityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the 'web' middleware group. Now create something great!
|
*/

//Grupo para las rutas de cursos
Route::group(['prefix' => 'courses', 'as' => 'courses.'], function () {
    Route::get('/', [CourseController::class, 'index'])->name('index');
    Route::get('create', [CourseController::class, 'create'])->name('create');
    Route::post('/', [CourseController::class, 'store'])->name('store');
    Route::post('import-registrations', [CourseController::class, 'importRegistrations'])->name('import.registrations');
    Route::get('download-template', [CourseController::class, 'downloadTemplate'])->name('download.template');
    Route::get('{id}', [CourseController::class, 'show'])->name('show');
    Route::get('{id}/edit', [CourseController::class, 'edit'])->name('edit');
    Route::put('{id}', [CourseController::class, 'update'])->name('update');
    Route::delete('{id}', [CourseController::class, 'destroy'])->name('destroy');
    Route::get('/get-by-cycle/{cycle}', [CourseController::class, 'getByCycle'])->name('get.by.cycle');
});

//Grupo para las rutas de entidades
Route::group(['prefix' => 'entities', 'as' => 'entities.'], function () {
    Route::get('/', [EntityController::class, 'index'])->name('index');
    Route::get('create', [EntityController::class, 'create'])->name('create');
    Route::post('/', [EntityController::class, 'store'])->name('store');
    Route::get('{id}', [EntityController::class, 'show'])->name('show');
    Route::get('{id}/edit', [EntityController::class, 'edit'])->name('edit');
    Route::put('{id}', [EntityController::class, 'update'])->name('update');
    Route::delete('{id}', [EntityController::class, 'destroy'])->name('destroy');
});

//Grupo para las rutas de preinscripcion de cursos
Route::group(['prefix' => 'courses.preregistrations', 'as' => 'courses.preregistrations.'], function () {
    Route::get('/', [CoursePreRegistrationController::class, 'index'])->name('index');
    Route::get('create', [CoursePreRegistrationController::class, 'create'])->name('create');
    Route::post('/', [CoursePreRegistrationController::class, 'store'])->name('store');
});

