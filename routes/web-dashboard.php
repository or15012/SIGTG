<?php


use App\Http\Controllers\CourseController;
use App\Http\Controllers\CoursePreRegistrationController;
use App\Http\Controllers\DashboardController;
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
Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/protocol/cycle/{id?}',[DashboardController::class, 'ajaxProto'])->name('proto');
    Route::get('/course/cycle/{id?}',[DashboardController::class, 'ajaxCourse'])->name('course');
    Route::get('excel/protocol/cycle/{id?}',[DashboardController::class, 'ajaxExcelProto'])->name('excel_proto');
    Route::get('excel/course/cycle/{id?}',[DashboardController::class, 'ajaxExcelCourse'])->name('excel_course');
});


