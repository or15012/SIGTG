<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EvaluationCriteria;
use App\Models\CriteriaStage;
use App\Models\EvaluationStage;
use App\Models\EvaluationStageNote;
use App\Models\Group;
use App\Models\Project;
use App\Models\Stage;
use App\Models\TeacherGroup;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class CriteriaStageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function create(Project $project, Stage $stage)
    {

        $group      = $project->group()->first();
        $criteria   = EvaluationCriteria::where('stage_id', $stage->id)->get();
        $users      = User::join('user_group as ug', 'ug.user_id', 'users.id')
            ->where('ug.group_id', $group->id)
            ->select('users.id', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.second_last_name')
            ->get();
        $evaluationStages = EvaluationStage::where('project_id', $project->id)
            ->where('stage_id', $stage->id)
            ->first();
        $grades     = CriteriaStage::where('evaluation_stage_id', $evaluationStages->id)->get();

        return view('evaluation_stage.create', [
            'group'             => $group,
            'criteria'          => $criteria,
            'stage'             => $stage,
            'users'             => $users,
            'grades'            => $grades,
            'evaluationStages'  => $evaluationStages
        ]);
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'evaluation_stage_id'   => 'required|exists:evaluation_stages,id',
            'notes'                 => 'required|array',
            'finalnote'             => 'required|array',
        ]);

        try {
            foreach ($request->notes as $userId => $note) {
                $totalGrade = 0; // Inicializar la nota final del estudiante
                foreach ($note as $criteriaId => $value) {

                    $percentage = EvaluationCriteria::find($criteriaId)->percentage;

                    // Calcular la contribución de esta nota al total según el porcentaje del criterio
                    $totalGrade += ($value * $percentage) / 100;

                    CriteriaStage::updateOrCreate(
                        [
                            'user_id'                   => $userId,
                            'evaluation_criteria_id'    => $criteriaId,
                            'evaluation_stage_id'       => $request->evaluation_stage_id,

                        ],
                        [
                            'note'                      => $value
                        ]
                    );
                }

                $totalGrade = round($totalGrade, 2);

                // Guardar la nota final para este estudiante
                EvaluationStageNote::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'evaluation_stage_id' => $request->evaluation_stage_id,
                    ],
                    [
                        'note' => $totalGrade,
                    ]
                );
            }
            return back()->with('success', 'Notas guardadas exitosamente.');
        } catch (\Throwable $th) {
            return redirect()->route('grades.index')->with('error', $th->getMessage());
        }
    }
}
