<?php

use App\Http\Controllers\ActivityControlador;
use App\Http\Controllers\ActivityControllador;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CriteriaStageController;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\EvaluationCriteriaController;
use App\Http\Controllers\EvaluationStageController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProtocolController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ConsultingController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CoursePreRegistrationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvaluationDocumentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ExtensionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\SubareaController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\ProposalController;
use App\Models\Activity;
use App\Models\CoursePreregistration;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    Route::get('{id}', [CourseController::class, 'show'])->name('show');
    Route::get('{id}/edit', [CourseController::class, 'edit'])->name('edit');
    Route::put('{id}', [CourseController::class, 'update'])->name('update');
    Route::delete('{id}', [CourseController::class, 'destroy'])->name('destroy');
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

//Grupo para las rutas de propuestas
Route::group(['prefix' => 'proposals', 'as' => 'proposals.'], function () {
    Route::get('/', [ProposalController::class, 'index'])->name('index');
    Route::get('create', [ProposalController::class, 'create'])->name('create');
    Route::post('/', [ProposalController::class, 'store'])->name('store');
    Route::get('/show/{proposal}', [ProposalController::class, 'show'])->name('show');
    Route::delete('{id}', [ProposalController::class, 'destroy'])->name('destroy');
    Route::get('/download/{proposal}/{file}', [ProposalController::class, 'proposalDownload'])->name('download');
});