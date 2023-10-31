<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProtocolController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ConsultingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//HOME ROUTE
Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

//Rutas para autentificación y usuarios
Auth::routes();
Route::post('/update-profile', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/users', [RegisterController::class, 'index'])->name('users.index');

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
    Route::get('{id}', [GroupController::class, 'show'])->name('show');
    Route::get('{id}/edit', [GroupController::class, 'edit'])->name('edit');
    Route::put('{id}', [GroupController::class, 'update'])->name('update');
    Route::delete('{id}', [GroupController::class, 'destroy'])->name('destroy');
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


Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
