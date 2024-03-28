<?php

namespace App\Http\Controllers;

use DateTime;
use App\Mail\SendMail;
use App\Models\CriteriaSubarea;
use App\Models\Cycle;
use App\Models\EvaluationCriteria;
use App\Models\EvaluationCritSubareaCrit;
use App\Models\EvaluationStage;
use App\Models\EvaluationStageNote;
use App\Models\EvaluationSubarea;
use App\Models\Group;
use App\Models\Notification;
use App\Models\Project;
use App\Models\Stage;
use App\Models\SubArea;
use App\Models\SubareaCriteria;
use App\Models\SubareaDocument;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UserProjectNote;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EvaluationController extends Controller
{
    //

    const PERMISSIONS = [
        // 'index.student.project'    => 'Projects.students',
        // 'index.adviser.project'    => 'Projects.advisers'

    ];

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:' . self::PERMISSIONS['index.student.project'])->only(['index']);
        // $this->middleware('permission:' . self::PERMISSIONS['index.adviser.project'])->only(['coordinator.Index']);
    }



    public function index()
    {
        //obtendre el proyecto del usuario logueado
        $user = Auth::user();
        // Obtiene el año actual
        $year = date('Y');
        // Realiza una consulta para verificar si el usuario está en un grupo del año actual

        $group = Group::where('groups.year', $year)
            ->where('groups.status', 1)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->first();

        if (!isset($group)) return redirect()->route('root')->with('error', 'No tienes trabajo de grado inicializado.');


        $project = Project::join('profiles as p', 'projects.profile_id', 'p.id')
            ->select('projects.id', 'projects.name', 'projects.approvement_report', 'projects.status')
            ->where('projects.group_id', $group->id)
            ->first();

        if (!isset($project)) return redirect()->route('root')->with('error', 'No tienes un trabajo de grado activo.');

        $projectUsers = Project::join('profiles as p', 'projects.profile_id', 'p.id')
            ->join('user_group as ug', 'ug.group_id', 'projects.group_id')
            ->join('users as u', 'ug.user_id', 'u.id')
            ->where('projects.group_id', $group->id)
            ->get();

        $stages = Stage::where("protocol_id", $group->protocol_id)
            ->where('cycle_id', $group->cycle_id)
            ->where('school_id', $user->school_id)
            ->where('visible', 1)
            ->orderBy('stages.sort', 'asc')
            ->get();

        $stagesNote = Stage::where("protocol_id", $group->protocol_id)
            ->where('cycle_id', $group->cycle_id)
            ->where('school_id', $user->school_id)
            ->orderBy('stages.sort', 'asc')
            ->get();

        $evaluationStages = EvaluationStage::where('project_id', $project->id)
            ->select('stg.id')
            ->where('status', 1)
            ->where('visible', 1)
            ->join('stages as stg', 'evaluation_stages.stage_id', 'stg.id')
            ->get();

        $evaluationStagesNotes = EvaluationStage::where('project_id', $project->id)
            ->select('esn.user_id', 'esn.evaluation_stage_id', 'esn.note', 'stg.id')
            ->join('stages as stg', 'evaluation_stages.stage_id', 'stg.id')
            ->join('evaluation_stage_note as esn', 'evaluation_stages.id', 'esn.evaluation_stage_id')
            ->get();

        $groupCommittees = Group::select(
            'groups.id',
            'groups.number',
            'u.first_name',
            'u.middle_name',
            'u.last_name',
            'u.second_last_name',
            'u.email',
            'u.id',
            'tg.type',
        )
            ->join('teacher_group as tg', 'groups.id', 'tg.group_id')
            ->join('users as u', 'tg.user_id', 'u.id')
            ->join('protocols as p', 'groups.protocol_id', 'p.id')
            ->where('u.type', 2)
            ->where('groups.id', $group->id)
            ->get();

        $totalStages = $stages->count(); // Total de etapas
        $presentedStages = $evaluationStages->count(); // Etapas ya presentadas
        if ($totalStages > 0) {
            $progressPercentage = ($presentedStages / $totalStages) * 100;
        } else {
            $progressPercentage = 0; // En caso de que no haya etapas totales
        }

        return view('evaluations.index', [
            'projectUsers'          => $projectUsers,
            'project'               => $project,
            'stages'                => $stages,
            'evaluationStages'      => $evaluationStages,
            'groupCommittees'       => $groupCommittees,
            'progressPercentage'    => $progressPercentage,
            'group'                 => $group,
            'evaluationStagesNotes' => $evaluationStagesNotes,
            'stagesNote'            => $stagesNote,
        ]);
    }

    public function showSubareas(Project $project, Stage $area)
    {
        $status = $this->disableProject($project);
        // $evaluationSubareas = EvaluationCriteria::where('stage_id', $area->id)->get();
        // $evaluationStages = EvaluationSubarea::where('project_id', $project->id)
        //     ->select('stg.id')
        //     ->where('status', 1)
        //     ->join('evaluation_criteria as stg', 'evaluation_subareas.evaluation_criteria_id', 'stg.id')
        //     ->get();

        // $evaluationStage = EvaluationStage::where('stage_id', $area->id)
        //     ->where('project_id', $project->id)
        //     ->first();


        $evaluationSubareas = SubareaCriteria::where('stage_id', $area->id)->get();
        $evaluationStages = EvaluationSubarea::where('project_id', $project->id)
            ->select('stg.id')
            ->where('status', 1)
            ->join('subarea_criterias as stg', 'evaluation_subareas.subarea_criteria_id', 'stg.id')
            ->get();

        // dd($evaluationSubareas, $evaluationStages);
        $evaluationStage = EvaluationStage::where('stage_id', $area->id)
            ->where('project_id', $project->id)
            ->first();

        return view('evaluations.subareas.show-list', [
            "area"                  => $area,
            "project"               => $project,
            "evaluationSubareas"    => $evaluationSubareas,
            "status"                => $status,
            "evaluationStages"      => $evaluationStages,
            "evaluationStage"       => $evaluationStage
        ]);
    }

    public function disableProject(Project $project)
    {

        // Obtener fecha actual
        $status = TRUE;
        $today = new DateTime();

        $deadline = new DateTime($project->deadline);
        //dd($deadline);

        // Ver si fecha actual es menor o igual que la fecha de finalización de proyecto

        if ($today >= $deadline) {
            $status = FALSE;
        }

        return $status;
    }


    public function showSubarea(Project $project, SubareaCriteria $subarea)
    {
        $status = $this->disableProject($project);
        $evaluationStages = EvaluationSubarea::where('project_id', $project->id)
            ->where('subarea_criteria_id', $subarea->id)
            ->first();

        $evaluationDocuments = array();


        if (isset($evaluationStages)) {
            $evaluationDocuments = SubareaDocument::where('evaluation_subarea_id', $evaluationStages->id)
                ->get();
        } else {
            $evaluationStages                           = new EvaluationSubarea();
            $evaluationStages->project_id               = $project->id;
            $evaluationStages->subarea_criteria_id      = $subarea->id;
            $evaluationStages->save();
        }

        return view('evaluations.subareas.show', [
            "stage"                 => $subarea,
            "project"               => $project,
            "evaluationStages"      => $evaluationStages,
            "evaluationDocuments"   => $evaluationDocuments,
            "status"                => $status,

        ]);
    }

    public function submitSubarea(Request $request, EvaluationSubarea $evaluation_stage)
    {
        $evaluation_stage->status = $request->decision;
        $evaluation_stage->update();

        if ($evaluation_stage->status == 2) {
            // Envía el correo electrónico al asesor.
            // Le va a caer correo a todo el que tenga este rol.
            // Cuando tenga número de grupo, se manda a llamar al teacher.
            $role = 'Coordinador General';
            $userRoles = User::role($role)->get();
            $notification = Notification::create(['title' => 'Alerta de etapa', 'message' => "Te informamos que tu etapa ha sido enviada.", 'user_id' => Auth::user()->id]);
            foreach ($userRoles as $coordinator) {
                try {
                    $emailData = [
                        'user'                  => $coordinator,
                        'evaluation_stage'      => $evaluation_stage,
                    ];
                    //dd($emailData);
                    Mail::to($coordinator->email)->send(new SendMail('mail.stage-submitted', 'Notificación de etapa enviada', $emailData));
                    UserNotification::create(['user_id' => $coordinator->id, 'notification_id' => $notification->id, 'is_read' => 0]);
                } catch (\Throwable $th) {
                    // Manejar la excepción
                    //dd($th);
                }
            }
        }
        if ($evaluation_stage->status == 1) {
            //Envio de correo a estudiantes.
            $group = EvaluationSubarea::join('projects', 'projects.id', 'evaluation_subareas.project_id')
                ->join('groups', 'groups.id', 'projects.group_id')
                ->where('evaluation_subareas.id', $evaluation_stage->id)
                ->select('groups.*')
                ->first();

            $users = User::join('user_group as ug', 'ug.user_id', 'users.id')
                ->where('ug.group_id', $group->id)
                ->select('users.id', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.second_last_name', 'users.email')
                ->get();

            $notificationAproved = Notification::create(['title' => 'Alerta de etapa', 'message' => "Te informamos que tu etapa ha sido APROBADA.", 'user_id' => Auth::user()->id]);
            foreach ($users as  $students) {
                try {
                    $emailData = [
                        'user'                  => $students,
                        'evaluation_stage'      => $evaluation_stage,
                    ];
                    //dd($emailData);
                    Mail::to($students->email)->send(new SendMail('mail.stage-approved', 'Notificación de etapa aprobada', $emailData));
                    UserNotification::create(['user_id' => $students->id, 'notification_id' => $notificationAproved->id, 'is_read' => 0]);
                } catch (\Throwable $th) {
                    // Manejar la excepción
                    //dd($th);
                }
            }
        }

        //identificare si es la ultima etapa para cargar las notas finales

        return redirect()
            ->route('evaluations.show.subarea', [$evaluation_stage->project_id, $evaluation_stage->subarea_criteria_id])
            ->with('success', 'Subarea entregada correctamente.');
    }


    public function coordinatorIndex()
    {
        $user = Auth::user();
        $projects = Project::join('groups as g', 'g.id', 'projects.group_id')
            ->join('teacher_group as tg', 'tg.group_id', 'g.id')
            ->where('tg.user_id', $user->id)
            ->select('projects.id', 'projects.name', 'g.number')
            ->where('g.protocol_id', session('protocol')['id'])
            ->paginate(20);

        return view('evaluations.subareas.coordinator.index', [
            "projects"  => $projects
        ]);
    }

    public function coordinatorShow(Project $project)
    {
        $group = Group::find($project->group_id);
        $user = User::join('user_group as ug', 'ug.user_id', 'users.id')
            ->where('ug.group_id', $project->group_id)
            ->first();

        if (!isset($project)) return redirect()->route('root')->with('error', 'No tienes un proyecto activo.');

        $projectUsers = Project::join('profiles as p', 'projects.profile_id', 'p.id')
            ->join('user_group as ug', 'ug.group_id', 'projects.group_id')
            ->join('users as u', 'ug.user_id', 'u.id')
            ->where('projects.group_id', $group->id)
            ->get();



        $stages = Stage::where("protocol_id", $group->protocol_id)
            ->where('cycle_id', $group->cycle_id)
            ->where('school_id', $user->school_id)
            ->where('visible', 1)
            ->orderBy('stages.sort', 'asc')
            ->get();

        $stagesNote = Stage::where("protocol_id", $group->protocol_id)
            ->where('cycle_id', $group->cycle_id)
            ->where('school_id', $user->school_id)
            ->orderBy('stages.sort', 'asc')
            ->get();

        $evaluationStages = EvaluationStage::where('project_id', $project->id)
            ->select('stg.id')
            ->where('status', 1)
            ->where('visible', 1)
            ->join('stages as stg', 'evaluation_stages.stage_id', 'stg.id')
            ->get();

        $evaluationStagesNotes = EvaluationStage::where('project_id', $project->id)
            ->select('esn.user_id', 'esn.evaluation_stage_id', 'esn.note', 'stg.id')
            ->join('stages as stg', 'evaluation_stages.stage_id', 'stg.id')
            ->join('evaluation_stage_note as esn', 'evaluation_stages.id', 'esn.evaluation_stage_id')
            ->get();

        $groupCommittees = Group::select(
            'groups.id',
            'groups.number',
            'u.first_name',
            'u.middle_name',
            'u.last_name',
            'u.second_last_name',
            'u.email',
            'u.id',
            'tg.type',
        )
            ->join('teacher_group as tg', 'groups.id', 'tg.group_id')
            ->join('users as u', 'tg.user_id', 'u.id')
            ->join('protocols as p', 'groups.protocol_id', 'p.id')
            ->where('u.type', 2)
            ->where('groups.id', $group->id)
            ->get();

        $totalStages = $stages->count(); // Total de etapas
        $presentedStages = $evaluationStages->count(); // Etapas ya presentadas
        if ($totalStages > 0) {
            $progressPercentage = ($presentedStages / $totalStages) * 100;
        } else {
            $progressPercentage = 0; // En caso de que no haya etapas totales
        }

        return view('evaluations.index', [
            'projectUsers'          => $projectUsers,
            'project'               => $project,
            'stages'                => $stages,
            'evaluationStages'      => $evaluationStages,
            'groupCommittees'       => $groupCommittees,
            'progressPercentage'    => $progressPercentage,
            'group'                 => $group,
            'evaluationStagesNotes' => $evaluationStagesNotes,
            'stagesNote'            => $stagesNote
        ]);
    }


    public function approveStage(Project $project, Stage $stage)
    {
        $evaluationStage = EvaluationStage::where('stage_id', $stage->id)
            ->where('project_id', $project->id)
            ->first();

        $evaluationStage->status = 1;
        $evaluationStage->update();

        $evaluationSubareas         = EvaluationCriteria::where('stage_id', $stage->id)->count();
        $evaluationStagesApproved   = EvaluationStage::where('project_id', $project->id)
            ->where('status', 1)
            ->count();

        if ($evaluationSubareas === $evaluationStagesApproved) {
            $project->status = 2;
            $project->update();
        }

        return redirect()->back()->with('success', 'Exito! Se ha dado paso a la siguiente área.');
    }


    public function stagesCoordinatorEvaluationsCreate(Stage $stage)
    {
        $areaIds = explode(',', $stage->area_id);

        // Obtener todas las subáreas de las áreas asociadas al stage
        $subareas = SubArea::whereIn('area_id', $areaIds)->get();

        $sumatory = SubareaCriteria::where('stage_id', $stage->id)->sum('percentage');

        return view('evaluations.create', compact('stage', 'subareas', 'sumatory'));
    }


    public function stagesCoordinatorEvaluationsIndex(Stage $stage)
    {

        $evaluations = SubareaCriteria::where('stage_id', $stage->id)->get();
        return view('evaluations.list', compact('stage', 'evaluations'));
    }

    public function stagesCoordinatorEvaluationsEdit(SubareaCriteria $evaluation)
    {
        $stage              = Stage::find($evaluation->stage_id);
        $evaluationSubareas = EvaluationCritSubareaCrit::where('subarea_criteria_id', $evaluation->id)->get('evaluation_criteria_id');
        // $subareas           = $stage->criterias;
        $areaIds            = explode(',', $stage->area_id);
        $subareas           = SubArea::whereIn('area_id', $areaIds)->get();
        $selectedSubareas   = array();
        $sumatory           = SubareaCriteria::where('stage_id', $stage->id)->sum('percentage');

        foreach ($evaluationSubareas as $key => $value) {
            $selectedSubareas[] = $value->evaluation_criteria_id;
        }
        return view('evaluations.edit', compact('evaluation', 'stage', 'subareas', 'sumatory', 'evaluationSubareas', 'selectedSubareas'));
    }


    public function stagesCoordinatorEvaluationsUpdate(Request $request, SubareaCriteria $evaluation)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'percentage'    => 'required|integer|min:1|max:100',
            'stage'         => 'required|integer|min:1',
            'description'   => 'required|string'
        ]);

        if (session('protocol')['id'] == 5) {
            $data = $request->validate(['subareas'   => 'required|array']);
        }

        $stage_id   = $request->stage;
        $stage      = Stage::find($stage_id);
        $percentage = $request->percentage;
        $sumatory   = SubareaCriteria::where('stage_id', $stage_id)->where('id', '!=', $evaluation->id)->sum('percentage');

        if (($sumatory + $percentage) > $stage->percentage) {
            return redirect()->back()->with('error', "No se pudo completar la acción. El porcentaje supera el $stage->percentage%.");
        }

        try {

            $evaluation->name           = $request->name;
            $evaluation->percentage     = $request->percentage;
            $evaluation->stage_id       = $stage_id;
            $evaluation->description    = $request->description;
            $evaluation->type           = $request->type;
            if (session('protocol')['id'] == 5) {
                $evaluation->subarea_id =  implode(',', $request->subareas);
            }
            $evaluation->update();

            if ($request->has('subareas')) {
                $evaluation->evaluationCriterias()->detach();
                EvaluationCriteria::where('stage_id', $stage_id)->delete();

                $subareas = Subarea::whereIn('id', $request->subareas)->get();

                // 2. Inserta estos datos en la tabla evaluation_criteria
                $criteriaData = [];
                foreach ($subareas as $subarea) {
                    $criteriaData[] = [
                        'name'          => $subarea->name,
                        'description'   => $subarea->name,
                        'stage_id'      => $stage_id,
                        'percentage'    => 0,
                        'type'          => 0,
                        // Ajusta estos valores según tus necesidades
                    ];
                }
                $subareasText = EvaluationCriteria::insert($criteriaData);

                // 3. Recupera los IDs de las filas recién insertadas
                $criteriaIds = EvaluationCriteria::whereIn('name', $subareas->pluck('name'))->pluck('id');


                $evaluation->evaluationCriterias()->attach($criteriaIds);
            }

            return redirect()->route('stages.coordinator.evaluations.index', $stage_id)->with('success', 'Se actualizo la evaluación correctamente.');
        } catch (Exception $e) {
            dd($e);
            return redirect()->route('stages.coordinator.evaluations.index', $stage_id)->with('error', 'La evaluacion está duplicada.');
        }
    }

    public function coordinatorSubmitFinalStage(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'note' => 'required|numeric|min:0|max:10',
        ]);

        $project->status = $request->decision;
        $project->update();

        //lo primero que hare ahora es guardar la nota de la memoria
        $cycle  = Cycle::where('status', 1)->first();
        $stage  = Stage::where('protocol_id', session('protocol')['id'])
            ->where('school_id', session('school')['id'])
            ->where('cycle_id', $cycle->id)
            ->where('category', 3)
            ->first();

        //primero creare el evaluation_stages
        $evaluationStage                = new EvaluationStage();
        $evaluationStage->date          = Carbon::now();
        $evaluationStage->project_id    = $project->id;
        $evaluationStage->stage_id      = $stage->id;
        $evaluationStage->status        = 1;
        $evaluationStage->save();

        $group = $project->group;
        $students = Group::join('user_group as ug', 'ug.group_id', 'groups.id')
            ->where('groups.id', $group->id)
            ->get();

        foreach ($students as $user) {
            $evaluationStageNote                        = new EvaluationStageNote();
            $evaluationStageNote->evaluation_stage_id   = $evaluationStage->id;
            $evaluationStageNote->user_id               = $user->user_id;
            $evaluationStageNote->note                  = $request->note;
            $evaluationStageNote->save();
        }

        if ($request->decision == 3) {

            $group = $project->group;
            $students = Group::join('user_group as ug', 'ug.group_id', 'groups.id')
                ->where('groups.id', $group->id)
                ->get();

            foreach ($students as $student) {
                // Obtener el ID del estudiante
                $studentId = $student->user_id;

                // Encuentra todas las notas de las etapas evaluativas para el estudiante dado
                $evaluationNotes = EvaluationStageNote::where('user_id', $studentId)->get();

                $totalFinalGrade = 0;

                // Calcular la nota final ponderada para el estudiante actual
                foreach ($evaluationNotes as $note) {
                    // Obtén el porcentaje de la etapa evaluativa actual
                    $evaluationStage = EvaluationStage::find($note->evaluation_stage_id);
                    $stage = Stage::find($evaluationStage->stage_id);

                    $stagePercentage = $stage->percentage;
                    $stageGrade = $note->note;

                    // Aplica el porcentaje de la etapa evaluativa a la nota y suma al total
                    $totalFinalGrade += ($stageGrade * $stagePercentage) / 100;
                }

                // Realiza acciones con la $totalFinalGrade del estudiante si es necesario
                // Por ejemplo, guardarla en la base de datos o realizar otras operaciones

                // Puedes hacer algo como guardar la nota final en un campo del estudiante o en otra tabla
                $userProjectNote = UserProjectNote::create([
                    'user_id'       => $studentId,
                    'project_id'    => $project->id,
                    'note'          => $totalFinalGrade,

                ]);
            }
        } elseif ($request->decision == 1) {
            // Grupo aceptado, actualiza la fecha de vencimiento
            $this->handleGroupAcceptance($project);
        }

        //identificare si es la ultima etapa para cargar las notas finales

        switch (session('protocol')['id']) {
            case '5':
                return redirect()
                    ->route('evaluations.coordinator.show', [$project->id])
                    ->with('success', 'Proyecto actualizado correctamente.');
                break;
            case '1':
            case '2':
            case '3':
            case '4':
                return redirect()
                    ->route('projects.coordinator.show', [$project->id])
                    ->with('success', 'Proyecto actualizado correctamente.');
                break;

            default:
                break;
        }
    }

    public function execution($type)
    {
        // Validar que $type sea igual a 2 o 3
        if ($type != 2 && $type != 3) {
            abort(404);
        }

        $stages = Stage::with([
            'protocol', 'school',
            'cycle' => function ($query) {
                $query->where('status', 1);
            }
        ])->where('protocol_id', session('protocol')['id'])
            ->where('school_id', session('school')['id'])
            ->where('visible', 0)
            ->where('category', $type)
            ->get();

        $title = "Planificación de actividades para la ejecución del EXG";
        if ($title == 3) {
            $title = "Memoria de Capitalización de Experiencias del EXG";
        }
        return view('evaluations.execution.evaluation', compact('stages', 'title'));
    }
}
