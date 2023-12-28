<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subarea;
use App\Models\Area;
use App\Models\EvaluationCriteria;
use App\Models\EvaluationStage;
use App\Models\EvaluationStageNote;
use App\Models\Project;
use App\Models\SubareaCriteria;
use App\Models\User;
use Illuminate\Http\Request;

class SubareaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Project $project, Area $area)
    {
        $group      = $project->group()->first();
        $subareas   = Subarea::where('area_id', $area->id)->get();
        $users      = User::join('user_group as ug', 'ug.user_id', 'users.id')
            ->where('ug.group_id', $group->id)
            ->select('users.id', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.second_last_name')
            ->get();
        $evaluationStages = EvaluationStage::where('project_id', $project->id)
            ->where('area_id', $area->id)
            ->first();
        $grades     = Subarea::where('evaluation_stage_id', $evaluationStages->id)->get();

        return view('evaluation_stage.create', [
            'group'             => $group,
            'subareas'          => $subareas,
            'area'              => $area,
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
                $totalGrade = 0;

                foreach ($note as $subareaId => $value) {
                    $percentage = Subarea::find($subareaId)->percentage;

                    // Calcular la contribución de esta nota al total según el porcentaje del criterio
                    $totalGrade += ($value * $percentage) / 100;

                    Subarea::updateOrCreate(
                        [
                            'user_id'                   => $userId,
                            'subarea_id'                => $subareaId,
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

    public function criteriasCreate($id)
    {

        $stage = EvaluationCriteria::findOrfail($id);
        $sumatory = SubareaCriteria::where('evaluation_criteria_id', $stage->id)->sum('percentage');

        return view('criteria.create', compact('stage', 'sumatory'));
    }


}
