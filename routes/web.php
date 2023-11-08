<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\EvaluationCriteriaController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProtocolController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ConsultingController;
use App\Http\Controllers\StageController;
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

//HOME ROUTE
Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

//Rutas para autentificación y usuarios
Auth::routes();
Route::post('/update-profile', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

Route::get('/home', [HomeController::class, 'home'])->name('home');

Route::group(['prefix' => 'users'], function () {
    Route::get('/', [RegisterController::class, 'index'])->name('users.index');
    Route::get('/download-template', [RegisterController::class, 'downloadTemplate'])->name('users.download.template');
    Route::post('/import', [RegisterController::class, 'import_users'])->name('users.import');

});

Route::group(['prefix' => 'students', 'as' => 'students.'], function () {
    Route::get('/get-student/{carnet}', [StudentController::class, 'getStudent'])->name('get.student');

});

//Grupo para las rutas de escuelas
Route::group(['prefix' => 'schools', 'as' => 'schools.'], function () {
    Route::get('/', [SchoolController::class, 'index'])->name('index');
    Route::get('/create', [SchoolController::class, 'create'])->name('create');
    Route::post('/', [SchoolController::class, 'store'])->name('store');
    Route::get('/{school}', [SchoolController::class, 'show'])->name('show');
    Route::get('/{school}/edit', [SchoolController::class, 'edit'])->name('edit');
    Route::put('/{school}', [SchoolController::class, 'update'])->name('update');
    Route::delete('/{school}', [SchoolController::class, 'destroy'])->name('destroy');
});

//Grupo para las rutas de protocolos
Route::group(['prefix' => 'protocols', 'as' => 'protocols.'], function () {
    Route::get('/', [ProtocolController::class, 'index'])->name('index');
    Route::get('/{protocol}', [ProtocolController::class, 'show'])->name('show');
    Route::get('/{protocol}/edit', [ProtocolController::class, 'edit'])->name('edit');
    Route::put('/{protocol}', [ProtocolController::class, 'update'])->name('update');
    Route::delete('/{protocol}', [ProtocolController::class, 'destroy'])->name('destroy');
});

//Grupo para las rutas de roles
Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('/create', [RoleController::class, 'create'])->name('create');
    Route::post('/', [RoleController::class, 'store'])->name('store');
    Route::get('/{role}', [RoleController::class, 'show'])->name('show');
    Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
    Route::put('/{role}', [RoleController::class, 'update'])->name('update');
    Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
});

//Grupo para las rutas de ciclos
Route::group(['prefix' => 'cycles', 'as' => 'cycles.'], function () {
    Route::get('/', [CycleController::class, 'index'])->name('index');
    Route::get('create', [CycleController::class, 'create'])->name('create');
    Route::post('/', [CycleController::class, 'store'])->name('store');
    Route::get('{id}', [CycleController::class, 'show'])->name('show');
    Route::get('{id}/edit', [CycleController::class, 'edit'])->name('edit');
    Route::put('{id}', [CycleController::class, 'update'])->name('update');
    Route::delete('{id}', [CycleController::class, 'destroy'])->name('destroy');
});

//Grupo para las rutas de grupo
Route::group(['prefix' => 'groups', 'as' => 'groups.'], function () {
    Route::get('/', [GroupController::class, 'index'])->name('index');
    Route::get('create', [GroupController::class, 'create'])->name('create');
    Route::post('/', [GroupController::class, 'store'])->name('store');
    Route::get('/initialize', [GroupController::class, 'initialize'])->name('initialize');
    Route::post('/confirm-store', [GroupController::class, 'confirmStore'])->name('confirm.store');
    Route::get('{id}/edit', [GroupController::class, 'edit'])->name('edit');
    Route::put('{id}', [GroupController::class, 'update'])->name('update');
    Route::delete('{id}', [GroupController::class, 'destroy'])->name('destroy');

    //Rutas para actualiza comite evaluador y asesor.
    Route::get('/evaluating-committee-index/{group}', [GroupController::class, 'evaluatingCommitteeIndex'])->name('evaluating.committee.index');
    Route::put('/evaluating-committee-update/{group}', [GroupController::class, 'evaluatingCommitteeUpdate'])->name('evaluating.committee.update');
    Route::delete('/evaluating-committee-destroy/{user}/{type}/{group}', [GroupController::class, 'evaluatingCommitteeDestroy'])->name('evaluating.committee.destroy');

});

//Grupo para las rutas de asesoria.
Route::group(['prefix' => 'consultings', 'as' => 'consultings.'], function () {
    Route::get('/', [ConsultingController::class, 'index'])->name('index');
    Route::get('/create', [ConsultingController::class, 'create'])->name('create');
    Route::post('/', [ConsultingController::class,  'store'])->name('store');
    Route::get('/{consulting}', [ConsultingController::class, 'show'])->name('show');
    Route::get('/{consulting}/edit', [ConsultingController::class, 'edit'])->name('edit');
    Route::put('/{consulting}', [ConsultingController::class, 'update'])->name('update');
    Route::delete('/{consulting}', [ConsultingController::class, 'destroy'])->name('destroy');
});


//Grupo para las rutas de preperfil y perfil
Route::group(['prefix' => 'profiles', 'as' => 'profiles.'], function () {

    Route::get('/preprofile/index', [ProfileController::class, 'preProfileIndex'])->name('preprofile.index');
    Route::get('/preprofiles/show/{preprofile}', [ProfileController::class, 'preProfileShow'])->name('preprofile.show');
    Route::get('/preprofile/create', [ProfileController::class, 'preProfileCreate'])->name('preprofile.create');
    Route::post('/preprofile/store', [ProfileController::class, 'preProfileStore'])->name('preprofile.store');
    Route::get('/preprofile/edit/{preprofile}', [ProfileController::class, 'preProfileEdit'])->name('preprofile.edit');
    Route::put('/preprofile/update/{preprofile}', [ProfileController::class, 'preProfileUpdate'])->name('preprofile.update');
    Route::delete('/preprofile/destroy/{preprofile}', [ProfileController::class, 'preProfileDestroy'])->name('preprofile.destroy');
    Route::get('preprofiles/download/{preprofile}',[ProfileController::class, 'preProfileDownload'])->name('preprofile.download');

    Route::get('/preprofile/coordinator/index', [ProfileController::class, 'preProfileCoodinatorIndex'])->name('preprofile.coordinator.index');
    Route::get('/preprofile/coordinator/show/{preprofile}', [ProfileController::class, 'preProfileCoodinatorShow'])->name('preprofile.coordinator.show');
    Route::put('/preprofile/coordinator/update/{preprofile}', [ProfileController::class, 'preProfileCoodinatorUpdate'])->name('preprofile.coordinator.update');
    Route::get('/preprofile/coordinator/observation/list/{preprofile}', [ProfileController::class, 'preProfileCoodinatorObservationsList'])->name('preprofile.coordinator.observation.list');
    Route::get('/preprofile/coordinator/observation/create/{preprofile}', [ProfileController::class, 'preProfileCoordinatorObservationCreate'])->name('preprofile.coordinator.observation.create');
    Route::post('/preprofile/coordinator/observation/store', [ProfileController::class, 'preProfileCoordinatorObservationStore'])->name('preprofile.coordinator.observation.store');


    Route::get('/index', [ProfileController::class, 'index'])->name('index');
    Route::get('/coordinator-show', [ProfileController::class, 'coordinatorShow'])->name('coordinator.show');
    Route::get('/coordinator-observation-list', [ProfileController::class, 'coordinatorObservationList'])->name('coordinator.observation.list');
    Route::get('/coordinator-observation-create', [ProfileController::class, 'coordinatorObservationCreate'])->name('coordinator.observation.create');



});

//Grupo para las rutas de etapas evaluativas.
Route::group(['prefix' => 'stages', 'as' => 'stages.'], function () {
    Route::get('/', [StageController::class, 'index'])->name('index');
    Route::get('/create', [StageController::class, 'create'])->name('create');
    Route::post('/', [StageController::class,  'store'])->name('store');
    Route::get('/{stage}', [StageController::class, 'show'])->name('show');
    Route::get('/{stage}/edit', [StageController::class, 'edit'])->name('edit');
    Route::put('/{stage}', [StageController::class, 'update'])->name('update');
    Route::delete('/{stage}', [StageController::class, 'destroy'])->name('destroy');
});

//Grupo para las rutas de criterios de evaluación.
Route::group(['prefix' => 'criterias', 'as' => 'criterias.'], function () {
    Route::get('/{id}', [EvaluationCriteriaController::class, 'index'])->name('index');
    Route::get('/create/{id}', [EvaluationCriteriaController::class, 'create'])->name('create');
    Route::post('/store', [EvaluationCriteriaController::class,  'store'])->name('store');
    Route::get('/{criteria}/edit', [EvaluationCriteriaController::class, 'edit'])->name('edit');
    Route::put('/{criteria}', [EvaluationCriteriaController::class, 'update'])->name('update');
    Route::delete('/{criteria}', [EvaluationCriteriaController::class, 'destroy'])->name('destroy');
});



Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
