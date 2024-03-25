<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AdviserController;
use App\Http\Controllers\AgreementController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CriteriaStageController;
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
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvaluationDocumentController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ExtensionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\SubAreaController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\TypeAgreementController;
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

require __DIR__ . '/web-course.php';
require __DIR__ . '/web-ppp.php';
require __DIR__ . '/web-pdi.php';
require __DIR__ . '/web-exg.php';
require __DIR__ . '/web-dashboard.php';

//HOME ROUTE
Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

//Rutas para autentificación y usuarios
Auth::routes();
Route::post('/update-profile', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

Route::get('/home', [HomeController::class, 'home'])->name('home');

Route::group(['prefix' => 'sessions', 'as' => 'sessions.'], function () {
    Route::get('/set-protocol/{protocol}', [SessionController::class, 'setProtocol'])->name('set.protocol');
    Route::get('/set-school/{school}', [SessionController::class, 'setSchool'])->name('set.school');

    Route::get('/set-all-protocol/{protocol}', [SessionController::class, 'setAllProtocol'])->name('set.all.protocol');
    Route::get('/set-all-school/{school}', [SessionController::class, 'setAllSchool'])->name('set.all.school');
});
Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
    Route::get('/', [RegisterController::class, 'index'])->name('index');
    Route::post('/', [RegisterController::class, 'store'])->name('store');
    Route::get('/download-template', [RegisterController::class, 'downloadTemplate'])->name('download.template');
    Route::post('/import', [RegisterController::class, 'import'])->name('import');
    Route::get('/assign-roles/{user}', [RegisterController::class, 'assignRoles'])->name('assign.roles');
    Route::post('/assign-roles-store/{user}', [RegisterController::class, 'assignRolesStore'])->name('assign.roles.store');
    Route::get('/agreements/{user}', [RegisterController::class, 'agreements'])->name('agreements');
    Route::get('/{id}/edit', [RegisterController::class, 'showEditForm'])->name('edit');
    Route::put('/{id}/update', [RegisterController::class, 'update'])->name('update');
});

Route::group(['prefix' => 'students', 'as' => 'students.'], function () {
    Route::get('/get-student/{carnet}', [StudentController::class, 'getStudent'])->name('get.student');
    Route::get('/get-student-by-id/{id}', [StudentController::class, 'getStudentById'])->name('get.student.by.id');
    Route::get('/get-students', [StudentController::class, 'getStudents'])->name('get.students');
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
    Route::get('/index', [GroupController::class, 'index'])->name('index');
    Route::get('create', [GroupController::class, 'create'])->name('create');
    Route::post('/', [GroupController::class, 'store'])->name('store');
    Route::get('/initialize', [GroupController::class, 'initialize'])->name('initialize');
    Route::post('/confirm-store', [GroupController::class, 'confirmStore'])->name('confirm.store');
    Route::get('{id}/edit', [GroupController::class, 'edit'])->name('edit');
    Route::put('{id}', [GroupController::class, 'update'])->name('update');
    Route::delete('{id}', [GroupController::class, 'destroy'])->name('destroy');
    Route::post('/store', [GroupController::class, 'storeExg'])->name('exg');

    //Rutas para actualiza comite evaluador y asesor.
    Route::get('/evaluating-committee-index/{group}', [GroupController::class, 'evaluatingCommitteeIndex'])->name('evaluating.committee.index');
    Route::put('/evaluating-committee-update/{group}', [GroupController::class, 'evaluatingCommitteeUpdate'])->name('evaluating.committee.update');
    Route::delete('/evaluating-committee-destroy/{user}/{type}/{group}', [GroupController::class, 'evaluatingCommitteeDestroy'])->name('evaluating.committee.destroy');

    // rutas para adjuntar carta de autorizacion
    Route::get('/modal-authorization-letter', [GroupController::class, 'modalAuthorizationLetter'])->name('modal.autorization.letter');
    Route::post('/modal-authorization-letter', [GroupController::class, 'storeAuthorizationLetter'])->name('store.autorization.letter');

    //rutas para adjuntar acuerdo de asesores y jurados
    Route::get('/modal-authorization-agreement', [GroupController::class, 'modalAuthorizationAgreement'])->name('modal.autorization.agreement');
    Route::post('/modal-authorization-agreement', [GroupController::class, 'storeAuthorizationAgreement'])->name('store.autorization.agreement');

    Route::get('/download/{teachergroup}/{file}', [GroupController::class, 'teacherGroupDownload'])->name('download');
    //ruta para mostrar grupos asignados a docentes
    Route::get('/assigned/group', [GroupController::class, 'assignedGroup'])->name('assigned');
});

//Grupo para las rutas de asesoría.
Route::group(['prefix' => 'consultings', 'as' => 'consultings.'], function () {
    Route::get('/index/{project}', [ConsultingController::class, 'index'])->name('index');
    Route::get('/create/{project}', [ConsultingController::class, 'create'])->name('create');
    Route::post('/{project}', [ConsultingController::class,  'store'])->name('store');
    Route::get('/{consulting}/{project}', [ConsultingController::class, 'show'])->name('show');
    Route::get('/{consulting}/edit/{project}', [ConsultingController::class, 'edit'])->name('edit');
    Route::put('/{consulting}/{project}', [ConsultingController::class, 'update'])->name('update');
    Route::delete('/{consulting}', [ConsultingController::class, 'destroy'])->name('destroy');
});


//Grupo para las rutas de preperfil y perfil
Route::group(['prefix' => 'profiles', 'as' => 'profiles.'], function () {

    //Rutas para estudiantes  edicion de perfiles
    Route::get('/index', [ProfileController::class, 'profileIndex'])->name('index');
    Route::get('/show/{profile}', [ProfileController::class, 'profileShow'])->name('show');
    Route::get('/edit/{profile}', [ProfileController::class, 'profileEdit'])->name('edit');
    Route::put('/update/{profile}', [ProfileController::class, 'profileUpdate'])->name('update');

    Route::group(['prefix' => 'coordinator', 'as' => 'coordinator.'], function () {
        //Rutas para coordinadores revision, cambio de estado y generacion de obseraciones de perfiles
        Route::get('/index', [ProfileController::class, 'coordinatorIndex'])->name('index');
        Route::get('/show/{profile}', [ProfileController::class, 'coordinatorShow'])->name('show');
        Route::put('/update/{profile}', [ProfileController::class, 'coordinatorUpdate'])->name('update');
        Route::get('/observation/list/{profile}', [ProfileController::class, 'coordinatorObservationsList'])->name('observation.list');
        Route::get('/observation/create/{profile}', [ProfileController::class, 'coordinatorObservationCreate'])->name('observation.create');
        Route::post('/observation/store', [ProfileController::class, 'coordinatorObservationStore'])->name('observation.store');
    });

    //Rutas para estudiantes creación y edicion de preperfiles
    Route::group(['prefix' => 'preprofile', 'as' => 'preprofile.'], function () {
        Route::get('/index', [ProfileController::class, 'preProfileIndex'])->name('index');
        Route::get('/show/{preprofile}', [ProfileController::class, 'preProfileShow'])->name('show');
        Route::get('/create', [ProfileController::class, 'preProfileCreate'])->name('create');
        Route::post('/store', [ProfileController::class, 'preProfileStore'])->name('store');
        Route::post('/storeExg', [ProfileController::class, 'preProfileStoreExg'])->name('exg');
        Route::get('/edit/{preprofile}', [ProfileController::class, 'preProfileEdit'])->name('edit');
        Route::put('/update/{preprofile}', [ProfileController::class, 'preProfileUpdate'])->name('update');
        Route::delete('/destroy/{preprofile}', [ProfileController::class, 'preProfileDestroy'])->name('destroy');
        Route::get('/download/{preprofile}/{file}', [ProfileController::class, 'preProfileDownload'])->name('download');

        Route::group(['prefix' => 'coordinator', 'as' => 'coordinator.'], function () {
            //Rutas para coordinadores revision, cambio de estado y generacion de obseraciones de preperfiles
            Route::get('/index', [ProfileController::class, 'preProfileCoordinatorIndex'])->name('index');
            Route::get('/show/{preprofile}', [ProfileController::class, 'preProfileCoordinatorShow'])->name('show');
            Route::put('/update/{preprofile}', [ProfileController::class, 'preProfileCoordinatorUpdate'])->name('update');
            Route::get('/observation/list/{preprofile}', [ProfileController::class, 'preProfileCoordinatorObservationsList'])->name('observation.list');
            Route::get('/observation/create/{preprofile}', [ProfileController::class, 'preProfileCoordinatorObservationCreate'])->name('observation.create');
            Route::post('/observation/store', [ProfileController::class, 'preProfileCoordinatorObservationStore'])->name('observation.store');
        });
    });
});

//Grupo para las rutas de etapas evaluativas.
Route::group(['prefix' => 'stages', 'as' => 'stages.'], function () {
    Route::get('/', [StageController::class, 'index'])->name('index');
    Route::get('/create', [StageController::class, 'create'])->name('create');
    Route::post('/', [StageController::class,  'store'])->name('store');
    // Route::get('/{stage}', [StageController::class, 'show'])->name('show');
    Route::get('/{stage}/edit', [StageController::class, 'edit'])->name('edit');
    Route::put('/{stage}', [StageController::class, 'update'])->name('update');
    Route::delete('/{stage}', [StageController::class, 'destroy'])->name('destroy');
    Route::get('/download-template', [StageController::class, 'downloadTemplate'])->name('download.template');
    Route::get('/download-template-subareas', [StageController::class, 'downloadTemplateSubareas'])->name('download.template.subareas');
    Route::get('/modal-load-criterias', [StageController::class, 'modalLoadCriterias'])->name('modal.load.criterias');
    Route::post('/modal-load-criterias', [StageController::class, 'storeLoadCriterias'])->name('store.load.criterias');


    /**
     * rutas para examen tecnico profesional
     */
    Route::group(['prefix' => 'coordinator', 'as' => 'coordinator.'], function () {

        Route::group(['prefix' => 'evaluations', 'as' => 'evaluations.'], function () {
            Route::get('/create/{stage}', [EvaluationController::class, 'stagesCoordinatorEvaluationsCreate'])->name('create');
            Route::get('/index/{stage}', [EvaluationController::class, 'stagesCoordinatorEvaluationsIndex'])->name('index');
            Route::get('/edit/{evaluation}', [EvaluationController::class, 'stagesCoordinatorEvaluationsEdit'])->name('edit');
            Route::put('/update/{evaluation}', [EvaluationController::class, 'stagesCoordinatorEvaluationsUpdate'])->name('update');

            Route::group(['prefix' => 'criterias', 'as' => 'criterias.'], function () {
                Route::get('/create/{evaluation}', [EvaluationCriteriaController::class, 'stagesCoordinatorEvaluationsCriteriasCreate'])->name('create');
                Route::post('/store', [EvaluationCriteriaController::class, 'stagesCoordinatorEvaluationsCriteriasStore'])->name('store');
                Route::get('/index/{evaluation}', [EvaluationCriteriaController::class, 'stagesCoordinatorEvaluationsCriteriasIndex'])->name('index');
                Route::get('/edit/{criteria}', [EvaluationCriteriaController::class, 'stagesCoordinatorEvaluationsCriteriasEdit'])->name('edit');
                Route::put('/update/{criteria}', [EvaluationCriteriaController::class, 'stagesCoordinatorEvaluationsCriteriasUpdate'])->name('update');
                Route::get('/modal', [EvaluationCriteriaController::class, 'stagesCoordinatorEvaluationsCriteriasModal'])->name('modal');
                Route::post('/load', [EvaluationCriteriaController::class, 'stagesCoordinatorEvaluationsCriteriasLoad'])->name('load');
            });
        });
    });
});

//Grupo para las rutas de criterios de evaluación.
Route::group(['prefix' => 'criterias', 'as' => 'criterias.'], function () {

    Route::get('/create/{id}', [EvaluationCriteriaController::class, 'create'])->name('create');
    Route::post('/store', [EvaluationCriteriaController::class,  'store'])->name('store');
    Route::get('/{criteria}/edit', [EvaluationCriteriaController::class, 'edit'])->name('edit');
    Route::put('/{criteria}', [EvaluationCriteriaController::class, 'update'])->name('update');
    Route::delete('/{criteria}', [EvaluationCriteriaController::class, 'destroy'])->name('destroy');
    Route::get('/{id}', [EvaluationCriteriaController::class, 'index'])->name('index');

    Route::group(['prefix' => 'subareas', 'as' => 'subareas.'], function () {
        Route::get('/{id}', [SubAreaController::class, 'criteriasIndex'])->name('index');
        Route::get('/{criteria}/edit', [SubAreaController::class, 'criteriasEdit'])->name('edit');
        Route::put('/{criteria}', [SubAreaController::class, 'criteriasUpdate'])->name('update');
        Route::delete('/{criteria}', [SubAreaController::class, 'criteriasDestroy'])->name('destroy');
    });
});

//Grupo para los documentos
Route::group(['prefix' => 'evaluations_documents', 'as' => 'evaluations_documents.'], function () {
    Route::get('/', [EvaluationDocumentController::class, 'index'])->name('index');
    Route::get('/create/{evaluation_stage}', [EvaluationDocumentController::class, 'create'])->name('create');
    Route::post('/', [EvaluationDocumentController::class,  'store'])->name('store');
    Route::get('/{evaluation_document}', [EvaluationDocumentController::class, 'show'])->name('show');
    Route::get('/{evaluation_document}/edit', [EvaluationDocumentController::class, 'edit'])->name('edit');
    Route::put('/{evaluation_document}', [EvaluationDocumentController::class, 'update'])->name('update');
    Route::delete('/{evaluation_document}', [EvaluationDocumentController::class, 'destroy'])->name('destroy');
    Route::get('/download/{evaluation_document}/{file}', [EvaluationDocumentController::class, 'evaluationsDownload'])->name('download');



    //para documentos de subareas
    Route::group(['prefix' => 'subareas', 'as' => 'subareas.'], function () {
        Route::get('/', [EvaluationDocumentController::class, 'subareaIndex'])->name('index');
        Route::get('/create/{evaluation_subarea}', [EvaluationDocumentController::class, 'subareaCreate'])->name('create');
        Route::post('/', [EvaluationDocumentController::class,  'subareaStore'])->name('store');
        Route::get('/{evaluation_document}', [EvaluationDocumentController::class, 'subareaShow'])->name('show');
        Route::get('/{evaluation_document}/edit', [EvaluationDocumentController::class, 'subareaEdit'])->name('edit');
        Route::put('/{evaluation_document}', [EvaluationDocumentController::class, 'subareaUpdate'])->name('update');
        Route::delete('/{evaluation_document}', [EvaluationDocumentController::class, 'subareaDestroy'])->name('destroy');
        Route::get('/download/{evaluation_document}/{file}', [EvaluationDocumentController::class, 'subareaEvaluationsDownload'])->name('download');
    });
});

//Grupo para las rutas de notas.
Route::group(['prefix' => 'grades', 'as' => 'grades.'], function () {
    Route::get('/create/{project}/{stage}', [CriteriaStageController::class, 'create'])->name('create');
    Route::post('/store', [CriteriaStageController::class, 'store'])->name('store');

    Route::group(['prefix' => 'subareas', 'as' => 'subareas.'], function () {
        Route::get('/create/{project}/{stage}', [CriteriaStageController::class, 'subareaCreate'])->name('create');
        Route::post('/store', [CriteriaStageController::class, 'subareaStore'])->name('store');
    });
});

//Grupo para las rutas de proyectos
Route::group(['prefix' => 'projects', 'as' => 'projects.'], function () {
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/show-stage/{project}/{stage}', [ProjectController::class, 'showStage'])->name('show.stage');
    Route::put('/submit-stage/{evaluation_stage}', [ProjectController::class, 'submitStage'])->name('submit.stage');
    Route::get('/finish/{project}', [ProjectController::class, 'finish'])->name('finish');
    Route::get('/download/{project}/{file}', [ProjectController::class, 'download'])->name('download');
    Route::get('/final-volume/{project}', [ProjectController::class, 'finalVolume'])->name('final.volume');
    Route::post('/final-volume-store/{project}', [ProjectController::class, 'finalVolumeStore'])->name('final.volume.store');

    Route::get('/finish/note/{project}', [ProjectController::class, 'finishNote'])->name('finish.note');

    Route::group(['prefix' => 'coordinator', 'as' => 'coordinator.'], function () {
        Route::get('/', [ProjectController::class, 'coordinatorIndex'])->name('index');
        Route::get('/show/{project}', [ProjectController::class, 'coordinatorShow'])->name('show');
        Route::put('/submit-final-stage/{project}', [ProjectController::class, 'coordinatorSubmitFinalStage'])->name('submit.final.stage');
    });


    // rutas para adjuntar acta de aprobación
    Route::get('/modal-approvement-report', [ProjectController::class, 'modalApprovementReport'])->name('modal.approvement.report');
    Route::post('/modal-approvement-report', [ProjectController::class, 'storeApprovementReport'])->name('store.approvement.report');
});

//Grupo para las rutas de evaluación de examen
Route::group(['prefix' => 'evaluations', 'as' => 'evaluations.'], function () {
    Route::get('/', [EvaluationController::class, 'index'])->name('index');
    Route::get('/show-subareas/{project}/{area}', [EvaluationController::class, 'showSubareas'])->name('show.subareas');
    Route::get('/show-subarea/{project}/{subarea}', [EvaluationController::class, 'showSubarea'])->name('show.subarea');
    Route::put('/submit-stage/{evaluation_stage}', [EvaluationController::class, 'submitSubarea'])->name('submit.subarea');


    Route::get('/execution/{type}', [EvaluationController::class, 'execution'])->name('execution');

    Route::group(['prefix' => 'coordinator', 'as' => 'coordinator.'], function () {
        Route::get('/', [EvaluationController::class, 'coordinatorIndex'])->name('index');
        Route::get('/show/{project}', [EvaluationController::class, 'coordinatorShow'])->name('show');
        Route::get('/approve-stage/{project}/{stage}',  [EvaluationController::class, 'approveStage'])->name('approve.stage');

        Route::put('/submit-final-stage/{project}', [EvaluationController::class, 'coordinatorSubmitFinalStage'])->name('submit.final.stage');
    });


    // // rutas para adjuntar acta de aprobación
    // Route::get('/modal-approvement-report', [ProjectController::class, 'modalApprovementReport'])->name('modal.approvement.report');
    // Route::post('/modal-approvement-report', [ProjectController::class, 'storeApprovementReport'])->name('store.approvement.report');
});

// Prórrogas (extensions).
Route::group(['prefix' => 'extensions', 'as' => 'extensions.'], function () {
    Route::get('/index/{project}', [ExtensionController::class, 'index'])->name('index');
    Route::get('/create/{project}', [ExtensionController::class, 'create'])->name('create');
    Route::post('/', [ExtensionController::class,  'store'])->name('store');
    Route::get('/{extension}/edit/{project}', [ExtensionController::class, 'edit'])->name('edit');
    Route::put('/{extension}', [ExtensionController::class, 'update'])->name('update');

    Route::group(['prefix' => 'coordinator', 'as' => 'coordinator.'], function () {
        Route::get('/', [ExtensionController::class, 'coordinatorIndex'])->name('index');
        Route::get('show/{extension}', [ExtensionController::class, 'coordinatorShow'])->name('show');
        Route::put('/update/{extension}', [ExtensionController::class, 'coordinatorUpdate'])->name('update');
        // rutas para adjuntar acta de aprobación
        Route::get('/modal-approvement', [ExtensionController::class, 'modalApprovement'])->name('modal.approvement');
        Route::post('/store-approvement', [ExtensionController::class, 'storeApprovement'])->name('store.approvement');
    });
});

// Documentos
Route::group(['prefix' => 'document', 'as' => 'document.'], function () {
    Route::get('/authorization/letter/{group}', [DocumentController::class, 'authorizationLetter'])->name('authorization.letter');
    Route::get('/approvement/report/{project}', [DocumentController::class, 'approvement_report'])->name('approvement.report');
});

//Grupo para las rutas de areas
Route::group(['prefix' => 'areas', 'as' => 'areas.'], function () {
    Route::get('/', [AreaController::class, 'index'])->name('index');
    Route::get('/create', [AreaController::class, 'create'])->name('create');
    Route::post('/', [AreaController::class,  'store'])->name('store');
    Route::get('/{stage}', [AreaController::class, 'show'])->name('show');
    Route::get('/{area}/edit', [AreaController::class, 'edit'])->name('edit');
    Route::put('/{area}', [AreaController::class, 'update'])->name('update');
    Route::delete('/{area}', [AreaController::class, 'destroy'])->name('destroy');


    Route::group(['prefix' => 'subareas', 'as' => 'subareas.'], function () {
        Route::get('/', [AreaController::class, 'subareasIndex'])->name('index');
        Route::get('/create', [AreaController::class, 'subareasCreate'])->name('create');
        Route::post('/', [AreaController::class,  'subareasStore'])->name('store');
        Route::get('/{stage}', [AreaController::class, 'subareasShow'])->name('show');
        Route::get('/{area}/edit', [AreaController::class, 'subareasEdit'])->name('edit');
        Route::put('/{area}', [AreaController::class, 'subareasUpdate'])->name('update');
        Route::delete('/{area}', [AreaController::class, 'subareasDestroy'])->name('destroy');
    });
});

// Grupo para las rutas de subarea
Route::group(['prefix' => 'subareas', 'as' => 'subareas.'], function () {
    Route::get('/{id}', [SubAreaController::class, 'index'])->name('index');
    Route::get('/create/{id}', [SubAreaController::class, 'create'])->name('create');
    Route::post('/store', [SubAreaController::class, 'store'])->name('store');
    Route::get('/{subarea}/edit', [SubAreaController::class, 'edit'])->name('edit');
    Route::put('/{subarea}', [SubAreaController::class, 'update'])->name('update');
    Route::delete('/{subarea}', [SubAreaController::class, 'destroy'])->name('destroy');

    Route::group(['prefix' => 'criterias', 'as' => 'criterias.'], function () {
        Route::get('/create/{id}', [SubAreaController::class, 'criteriasCreate'])->name('create');
        Route::post('/store', [SubAreaController::class,  'criteriasStore'])->name('store');
    });
});

//Grupo para las rutas de notificaciones
Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('index');
    Route::get('/create', [NotificationController::class, 'create'])->name('create');
    Route::post('/store', [NotificationController::class, 'store'])->name('store');
    Route::get('/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark.as.read');
});


// download file
Route::middleware('auth')->get('download', [DocumentController::class, 'downloadDocument'])->name('download');


//Grupo para las rutas de fases.
Route::group(['prefix' => 'phases', 'as' => 'phases.'], function () {
    Route::get('/', [PhaseController::class, 'index'])->name('index');
    Route::get('/create', [PhaseController::class, 'create'])->name('create');
    Route::post('/store', [PhaseController::class,  'store'])->name('store');
    Route::get('/{phase}/edit', [PhaseController::class, 'edit'])->name('edit');
    Route::put('/{phase}', [PhaseController::class, 'update'])->name('update');
    Route::delete('/{phase}', [PhaseController::class, 'destroy'])->name('destroy');
    Route::get('/assign-stages/{phase}', [PhaseController::class, 'assignStages'])->name('assig.stages');
    Route::post('/store-assign-stages/{phase}', [PhaseController::class, 'storeAssignStages'])->name('store.assig.stages');

    //para ruta ajax
    Route::get('/get_phase/{phase}', [PhaseController::class, 'getPhase'])->name('get.phase');
});

//Grupo para las rutas de planificación
Route::group(['prefix' => 'plannings', 'as' => 'plannings.'], function () {
    Route::get('/', [PlanningController::class, 'index'])->name('index');
    Route::get('/create', [PlanningController::class, 'create'])->name('create');
    Route::post('/store', [PlanningController::class,  'store'])->name('store');
    Route::get('/show/{planning}', [PlanningController::class, 'show'])->name('show');
    Route::get('/edit/{planning}', [PlanningController::class, 'edit'])->name('edit');
    Route::put('/update/{planning}', [PlanningController::class, 'update'])->name('update');
    Route::get('/download/{planning}/{file}', [PlanningController::class, 'planningDownload'])->name('download');
    Route::delete('/destroy/{planning}', [PlanningController::class, 'destroy'])->name('destroy');
});

//Grupo para las rutas de actividad
Route::group(['prefix' => 'activities', 'as' => 'activities.'], function () {
    Route::get('/', [ActivityController::class, 'index'])->name('index');
    Route::get('/create', [ActivityController::class, 'create'])->name('create');
    Route::post('/store', [ActivityController::class,  'store'])->name('store');
    Route::get('/download-template', [ActivityController::class, 'downloadTemplate'])->name('download.template');
    Route::get('/modal-load', [ActivityController::class, 'modalLoadActivities'])->name('modal.load.activities');
    Route::post('/import', [ActivityController::class, 'import'])->name('import');
    Route::get('/{activity}/change-status', [ActivityController::class, 'modalStatus'])->name('modal.status.activities');
    Route::put('/status/{activity}', [ActivityController::class, 'changeStatus'])->name('status');
    Route::get('/show/{activity}', [ActivityController::class, 'show'])->name('show');
    Route::get('/{activity}/edit', [ActivityController::class, 'edit'])->name('edit');
    Route::put('/update/{activity}', [ActivityController::class, 'update'])->name('update');
    Route::delete('/destroy/{activity}', [ActivityController::class, 'destroy'])->name('destroy');

    Route::group(['prefix' => 'coordinator', 'as' => 'coordinator.'], function () {
        //Rutas para coordinadores para visualizar las actividades de grupos.
        Route::get('/index-groups', [ActivityController::class, 'indexGroup'])->name('index.groups');
        Route::get('/index/{group}', [ActivityController::class, 'indexCoordinator'])->name('index');
    });
});


//Grupo para las rutas de actividad de asesores
Route::group(['prefix' => 'advisers', 'as' => 'advisers.'], function () {
    Route::get('/', [AdviserController::class, 'index'])->name('index');
    Route::group(['prefix' => '/activities', 'as' => 'activities.'], function () {
        Route::get('/', [AdviserController::class, 'index_actividades'])->name('index');
        Route::get('/create', [AdviserController::class, 'create'])->name('create');
        Route::post('/store', [AdviserController::class,  'store'])->name('store');
        Route::get('/{activity}/edit', [AdviserController::class, 'edit'])->name('edit');
        Route::put('/update/{activity}', [AdviserController::class, 'update'])->name('update');
        Route::get('/show/{id}', [AdviserController::class, 'show'])->name('show');
    });
});


//Grupo para las rutas de bitacora
Route::group(['prefix' => 'logs', 'as' => 'logs.'], function () {
    Route::get('/', [LogController::class, 'index'])->name('index');
});

//Grupo para las rutas de defensa (eventos)
Route::group(['prefix' => 'events', 'as' => 'events.'], function () {
    Route::get('/events', [EventsController::class, 'index'])->name('index');
    /*
    Route::get('/index/{project}', [EventsController::class, 'index'])->name('index');
    Route::get('/create/{project}', [EventsController::class, 'create'])->name('create');
    Route::post('/store', [EventsController::class,  'store'])->name('store');
    Route::get('/{events}/edit/{project}', [EventsController::class, 'edit'])->name('edit');
    Route::put('/update/{events}', [EventsController::class, 'update'])->name('update');

    Route::group(['prefix' => 'coordinator', 'as' => 'coordinator.'], function () {
        Route::get('/', [EventsController::class, 'coordinatorIndex'])->name('index');
        Route::get('show/{events}', [EventsController::class, 'coordinatorShow'])->name('show');
        Route::put('/update/{events}', [EventsController::class, 'coordinatorUpdate'])->name('update');
    }); 
    */
});


// Retiros (withdrawals).
Route::group(['prefix' => 'withdrawals', 'as' => 'withdrawals.'], function () {
    Route::get('/', [WithdrawalController::class, 'index'])->name('index');
    Route::get('/create', [WithdrawalController::class, 'create'])->name('create');
    Route::post('/store', [WithdrawalController::class,  'store'])->name('store');
    Route::get('/{withdrawal}/edit', [WithdrawalController::class, 'edit'])->name('edit');
    Route::put('/update/{withdrawal}', [WithdrawalController::class, 'update'])->name('update');

    Route::group(['prefix' => 'coordinator', 'as' => 'coordinator.'], function () {
        Route::get('/', [WithdrawalController::class, 'coordinatorIndex'])->name('index');
        Route::get('show/{withdrawal}', [WithdrawalController::class, 'coordinatorShow'])->name('show');
        Route::put('/update/{withdrawal}', [WithdrawalController::class, 'coordinatorUpdate'])->name('update');

        // rutas para adjuntar acta de aprobación
        Route::get('/modal-approvement', [WithdrawalController::class, 'modalApprovement'])->name('modal.approvement');
        Route::post('/store-approvement', [WithdrawalController::class, 'storeApprovement'])->name('store.approvement');
    });
});



// Retiros (withdrawals).
Route::group(['prefix' => 'type_agreements', 'as' => 'type_agreements.'], function () {
    Route::get('/', [TypeAgreementController::class, 'index'])->name('index');
    Route::get('/create', [TypeAgreementController::class, 'create'])->name('create');
    Route::post('/', [TypeAgreementController::class, 'store'])->name('store');
    // Route::get('/{type}', [TypeAgreementController::class, 'show'])->name('show');
    Route::get('/{TypeAgreement}/edit', [TypeAgreementController::class, 'edit'])->name('edit');
    Route::put('/{TypeAgreement}', [TypeAgreementController::class, 'update'])->name('update');
    Route::delete('/{TypeAgreement}', [TypeAgreementController::class, 'destroy'])->name('destroy');
});

Route::group(['prefix' => 'agreements', 'as' => 'agreements.'], function () {
    Route::get('/create/group/{group}', [AgreementController::class, 'createAgreementGroup'])->name('create.group');
    Route::post('/store/group/{group}', [AgreementController::class, 'storeAgreementGroup'])->name('store.group');
    Route::get('/create/student/{student}', [AgreementController::class, 'createAgreementStudent'])->name('create.student');
    Route::post('/store/student/{student}', [AgreementController::class, 'storeAgreementStudent'])->name('store.student');
    Route::delete('/delete/{agreement}', [AgreementController::class, 'destroy'])->name('destroy');
    Route::get('/create/protocol', [AgreementController::class, 'createAgreementProtocol'])->name('create.protocol');
    Route::post('/store/protocol', [AgreementController::class, 'storeAgreementProtocol'])->name('store.protocol');
    Route::get('/protocol/school', [AgreementController::class, 'agreementsProtocolSchool'])->name('protocol.school');
});



// Estas rutas dejarlas de ultimo
Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
