<?php


use App\Http\Controllers\CourseController;
use App\Http\Controllers\CoursePreRegistrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardPruebaController;
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
    Route::get('/group/cycle/{id?}',[DashboardController::class, 'ajaxGroup'])->name('group');
    Route::get('/extension/cycle/{id?}',[DashboardController::class, 'ajaxExtension'])->name('extension');
    Route::get('excel/protocol/cycle/{id?}',[DashboardController::class, 'ajaxExcelProto'])->name('excel_proto');
    Route::get('excel/course/cycle/{id?}',[DashboardController::class, 'ajaxExcelCourse'])->name('excel_course');
    Route::get('excel/groups/cycle/{id?}',[DashboardController::class, 'ajaxExcelGroups'])->name('excel_groups');
    Route::get('excel/extensions/cycle/{id?}',[DashboardController::class, 'ajaxExcelExtensions'])->name('excel_extensions');
});


//Grupo para las rutas de dashboard prueba
Route::group(['prefix' => 'dashboards', 'as' => 'dashboards.'], function () {
    Route::get('/', [DashboardPruebaController::class, 'index'])->name('index');
    Route::get('/protocol/cycle/{id?}',[DashboardPruebaController::class, 'ajaxProto'])->name('proto');
    Route::get('/course/cycle/{id?}',[DashboardPruebaController::class, 'ajaxCourse'])->name('course');
    Route::get('/group/cycle/{id?}',[DashboardPruebaController::class, 'ajaxGroup'])->name('group');
    Route::get('/extension/cycle/{id?}',[DashboardPruebaController::class, 'ajaxExtension'])->name('extension');
    Route::get('/projec/cycle/{id?}',[DashboardPruebaController::class, 'ajaxExtension'])->name('extension');
    Route::get('/projec/cycle/{id?}',[DashboardPruebaController::class, 'ajaxStatus'])->name('status');
    Route::get('excel/protocol/cycle/{id?}',[DashboardPruebaController::class, 'ajaxExcelProto'])->name('excel_proto');
    Route::get('excel/course/cycle/{id?}',[DashboardPruebaController::class, 'ajaxExcelCourse'])->name('excel_course');
    Route::get('excel/groups/cycle/{id?}',[DashboardPruebaController::class, 'ajaxExcelGroups'])->name('excel_groups');
    Route::get('excel/extensions/cycle/{id?}',[DashboardPruebaController::class, 'ajaxExcelExtensions'])->name('excel_extensions');
    Route::get('excel/project/cycle/{id?}',[DashboardPruebaController::class, 'ajaxExcelStatus'])->name('excel_status');
});
