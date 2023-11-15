<?php

namespace App\Http\Controllers;

use App\Models\EvaluationDocument;
use App\Models\EvaluationStage;
use App\Models\Group;
use App\Models\Project;
use App\Models\School;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

        $project = Project::join('profiles as p', 'projects.profile_id', 'p.id')
            ->where('projects.group_id', $group->id)
            ->first();

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

        return view('projects.index', [
            'projectUsers'          => $projectUsers,
            'project'               => $project,
            'stages'                => $stages,
            'evaluationStages'       => $evaluationStages,
            'groupCommittees'       => $groupCommittees,
            'progressPercentage'    => $progressPercentage,
        ]);
    }


    public function showStage(Project $project, Stage $stage)
    {
        $evaluationStages = EvaluationStage::where('project_id', $project->id)
            ->where('stage_id', $stage->id)
            ->first();
        $evaluationDocuments = array();

        if (isset($evaluationStages)) {
            $evaluationDocuments = EvaluationDocument::where('evaluation_stage_id', $evaluationStages->id)
                ->get();
        } else {
            $evaluationStages                = new EvaluationStage();
            $evaluationStages->project_id    = $project->id;
            $evaluationStages->stage_id      = $stage->id;
            $evaluationStages->save();
        }


        return view('projects.show-stage', [
            "stage"                 => $stage,
            "project"               => $project,
            "evaluationStages"       => $evaluationStages,
            "evaluationDocuments"   => $evaluationDocuments,
        ]);
    }


    public function submitStage(Request $request, EvaluationStage $evaluation_stage)
    {

        $evaluation_stage->status = $request->decision;
        $evaluation_stage->update();


        return redirect()
            ->route('projects.show.stage', [$evaluation_stage->project_id, $evaluation_stage->stage_id])
            ->with('success', 'Documento guardado correctamente.');
    }
}
