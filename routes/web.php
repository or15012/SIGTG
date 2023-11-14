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
use App\Http\Controllers\ExtensionController;
use App\Http\Controllers\StageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
    Route::get('/', [RegisterController::class, 'index'])->name('index');
    Route::get('/download-template', [RegisterController::class, 'downloadTemplate'])->name('download.template');
    Route::post('/import', [RegisterController::class, 'import'])->name('import');
    Route::get('testCorreo', [RegisterController::class, 'testCorreo'])->name('test.correo');
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

    //Rutas para estudiantes creación y edicion de preperfiles
    Route::get('/preprofile/index', [ProfileController::class, 'preProfileIndex'])->name('preprofile.index');
    Route::get('/preprofiles/show/{preprofile}', [ProfileController::class, 'preProfileShow'])->name('preprofile.show');
    Route::get('/preprofile/create', [ProfileController::class, 'preProfileCreate'])->name('preprofile.create');
    Route::post('/preprofile/store', [ProfileController::class, 'preProfileStore'])->name('preprofile.store');
    Route::get('/preprofile/edit/{preprofile}', [ProfileController::class, 'preProfileEdit'])->name('preprofile.edit');
    Route::put('/preprofile/update/{preprofile}', [ProfileController::class, 'preProfileUpdate'])->name('preprofile.update');
    Route::delete('/preprofile/destroy/{preprofile}', [ProfileController::class, 'preProfileDestroy'])->name('preprofile.destroy');
    Route::get('/preprofiles/download/{preprofile}/{file}',[ProfileController::class, 'preProfileDownload'])->name('preprofile.download');

    //Rutas para coordinadores revision, cambio de estado y generacion de obseraciones de preperfiles
    Route::get('/preprofile/coordinator/index', [ProfileController::class, 'preProfileCoordinatorIndex'])->name('preprofile.coordinator.index');
    Route::get('/preprofile/coordinator/show/{preprofile}', [ProfileController::class, 'preProfileCoordinatorShow'])->name('preprofile.coordinator.show');
    Route::put('/preprofile/coordinator/update/{preprofile}', [ProfileController::class, 'preProfileCoordinatorUpdate'])->name('preprofile.coordinator.update');
    Route::get('/preprofile/coordinator/observation/list/{preprofile}', [ProfileController::class, 'preProfileCoordinatorObservationsList'])->name('preprofile.coordinator.observation.list');
    Route::get('/preprofile/coordinator/observation/create/{preprofile}', [ProfileController::class, 'preProfileCoordinatorObservationCreate'])->name('preprofile.coordinator.observation.create');
    Route::post('/preprofile/coordinator/observation/store', [ProfileController::class, 'preProfileCoordinatorObservationStore'])->name('preprofile.coordinator.observation.store');

    //Rutas para estudiantes  edicion de perfiles
    Route::get('/index', [ProfileController::class, 'profileIndex'])->name('index');
    Route::get('/show/{profile}', [ProfileController::class, 'profileShow'])->name('show');
    Route::get('/edit/{profile}', [ProfileController::class, 'profileEdit'])->name('edit');
    Route::put('/update/{profile}', [ProfileController::class, 'profileUpdate'])->name('update');

    //Rutas para coordinadores revision, cambio de estado y generacion de obseraciones de perfiles
    Route::get('/coordinator/index', [ProfileController::class, 'coordinatorIndex'])->name('coordinator.index');
    Route::get('/coordinator/show/{profile}', [ProfileController::class, 'coordinatorShow'])->name('coordinator.show');
    Route::put('/coordinator/update/{preprofile}', [ProfileController::class, 'coordinatorUpdate'])->name('coordinator.update');
    Route::get('/coordinator/observation/list/{profile}', [ProfileController::class, 'coordinatorObservationsList'])->name('coordinator.observation.list');
    Route::get('/coordinator/observation/create/{profile}', [ProfileController::class, 'coordinatorObservationCreate'])->name('coordinator.observation.create');
    Route::post('/coordinator/observation/store', [ProfileController::class, 'coordinatorObservationStore'])->name('coordinator.observation.store');

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


// Prórrogas (extensions).
Route::group(['prefix' => 'extensions', 'as' => 'extensions.'], function () {
    Route::get('/', [ExtensionController::class, 'index'])->name('index');
    Route::get('/create', [ExtensionController::class, 'create'])->name('create');
    Route::post('/', [ExtensionController::class,  'store'])->name('store');
    Route::get('/{extension}/edit', [ExtensionController::class, 'edit'])->name('edit');
    Route::put('/{extension}', [ExtensionController::class, 'update'])->name('update');
});

// download file
Route::middleware('auth')->get('download', fn(Request $request)=>response()->download(storage_path('app/' . $request->file)))->name('download');

Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
