<?php

namespace App\Http\Controllers;

use App\Models\EvaluationCriteria;
use App\Models\EvaluationDocument;
use App\Models\EvaluationStage;
use App\Models\EvaluationSubarea;
use App\Models\Group;
use App\Models\Project;
use App\Models\Stage;
use App\Models\SubArea;
use App\Models\SubareaDocument;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->orderBy('stages.sort', 'asc')
            ->get();

        $evaluationStages = EvaluationStage::where('project_id', $project->id)
            ->select('stg.id')
            ->where('status', 1)
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
        ]);
    }

    public function showSubareas(Project $project, Stage $area)
    {
        $status = $this->disableProject($project);
        $evaluationSubareas = EvaluationCriteria::where('stage_id', $area->id)->get();

        return view('evaluations.subareas.show-list', [
            "area"                  => $area,
            "project"               => $project,
            "evaluationSubareas"    => $evaluationSubareas,
            "status"                => $status,

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


    public function showSubarea(Project $project, EvaluationCriteria $subarea)
    {
        $status = $this->disableProject($project);
        $evaluationStages = EvaluationSubarea::where('project_id', $project->id)
            ->where('evaluation_criteria_id', $subarea->id)
            ->first();

            $evaluationDocuments = array();


            if (isset($evaluationStages)) {
                $evaluationDocuments = SubareaDocument::where('evaluation_subarea_id', $evaluationStages->id)
                    ->get();
            } else {
                $evaluationStages                           = new EvaluationSubarea();
                $evaluationStages->project_id               = $project->id;
                $evaluationStages->evaluation_criteria_id   = $subarea->id;
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
}
