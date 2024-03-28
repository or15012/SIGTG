<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subarea;
use App\Models\Area;
use App\Models\EvaluationCriteria;
use App\Models\EvaluationStage;
use App\Models\EvaluationStageNote;
use App\Models\Project;
use App\Models\Stage;
use App\Models\SubareaCriteria;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class SubAreaController extends Controller
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

        return view('subareas.criteria-create', compact('stage', 'sumatory'));
    }


    public function criteriasStore(Request $request)
    {
        // dd($request);
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
        $sumatory   = SubareaCriteria::where('stage_id', $stage_id)->sum('percentage');

        if (($sumatory + $percentage) > $stage->percentage) {
            return redirect()->back()->with('error', "No se pudo completar la acción. El porcentaje supera el $stage->percentage%.");
        }

        try {

            $datos = array(
                'name'                      => $request->name,
                'percentage'                => $percentage,
                'stage_id'                  => $stage_id,
                'description'               => $request->description,
                'type'                      => $request->type
            );
            if (session('protocol')['id'] == 5) {
                $datos['subarea_id'] =  implode(',', $request->subareas);
            }

            $criteria = SubareaCriteria::create($datos);

            if ($request->has('subareas')) {
                // 1. Recupera los datos de las subáreas
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

                $criteria->evaluationCriterias()->attach($criteriaIds);
            }

            if (session('protocol')['id'] == 5) {
                return redirect()->route('stages.coordinator.evaluations.index', $stage_id)->with('success', 'Se añadió evaluación correctamente.');
            } else {
                return redirect()->route('stages.index')->with('success', 'Se añadió el criterio de evaluación correctamente.');
            }
        } catch (Exception $e) {
            dd($e);
            return redirect()->route('stages.index')->with('error', 'El criterio de evaluacion está duplicado.');
        }
    }


    public function criteriasIndex($id)
    {
        $stage     = EvaluationCriteria::findOrfail($id);
        $criterias = SubareaCriteria::where('evaluation_criteria_id', $stage->id)->get();


        return view('subareas.criteria-index', compact('criterias', 'stage'));
    }

    public function criteriasEdit(SubareaCriteria $criteria)
    {

        $stage = EvaluationCriteria::findOrfail($criteria->evaluation_criteria_id);

        return view('subareas.criteria-edit')->with(compact('criteria', 'stage'));
    }

    public function criteriasUpdate(Request $request, SubareaCriteria $criteria)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'percentage'    => 'required|integer|min:1|max:100',
            'stage'         => 'required|integer|min:1',
            'description'   => 'required|string',
        ]);

        $stage_id   = $data['stage'];
        $percentage = $data['percentage'];
        $sumatory = SubareaCriteria::where('evaluation_criteria_id', $stage_id)->where('id', '!=', $criteria->id)->sum('percentage');

        if ($sumatory + $percentage > 100) {
            return redirect()->route('stages.index')->with('error', 'No se pudo completar la acción. El porcentaje supera el 100%.');
        }

        try {
            $criteria->update([
                'name'          => $data['name'],
                'percentage'    => $data['percentage'],
                'description'   => $data['description'],
            ]);

            return redirect()->route('criterias.subareas.index', ['id' => $criteria->evaluation_criteria_id])->with('success', 'Criterio de Evaluación actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('criterias.subareas.edit', ['criteria' => $criteria])->with('error', 'El criterio de evaluación ya se encuentra registrado, revisar.');
        }
    }


    public function criteriasDestroy(SubareaCriteria $criteria)
    {
        $criteria->delete();

        return redirect()->route('criterias.subareas.index', ['id' => $criteria->evaluation_criteria_id])->with('success', 'Criterio de Evaluación eliminada exitosamente.');
    }
}
