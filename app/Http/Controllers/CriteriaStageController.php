<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\EvaluationCriteria;
use App\Models\CriteriaStage;
use App\Models\CriteriaSubarea;
use App\Models\EvaluationStage;
use App\Models\EvaluationStageNote;
use App\Models\EvaluationSubarea;
use App\Models\EvaluationSubareaNote;
use App\Models\Project;
use App\Models\Stage;
use App\Models\SubareaCriteria;
use App\Models\User;
use App\Models\UserProjectNote;
use Exception;
use Illuminate\Http\Request;

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
            'evaluationStages'  => $evaluationStages,
            'project'           => $project
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

    public function subareaCreate(Project $project, SubareaCriteria $stage)
    {

        $group      = $project->group()->first();
        $criteria   = Criteria::where('subarea_criteria_id', $stage->id)->get();
        $users      = User::join('user_group as ug', 'ug.user_id', 'users.id')
            ->where('ug.group_id', $group->id)
            ->select('users.id', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.second_last_name')
            ->get();
        $evaluationStages = EvaluationSubarea::where('project_id', $project->id)
            ->where('subarea_criteria_id', $stage->id)
            ->first();
        $grades     = CriteriaSubarea::where('evaluation_subarea_id', $evaluationStages->id)->get();

        return view('evaluation_stage.subareas.create', [
            'group'             => $group,
            'criteria'          => $criteria,
            'stage'             => $stage,
            'users'             => $users,
            'grades'            => $grades,
            'evaluationStages'  => $evaluationStages,
            'project'           => $project
        ]);
    }



    public function subareaStore(Request $request)
    {

        $data = $request->validate([
            'evaluation_stage_id'       => 'required|exists:evaluation_subareas,id',
            'evaluation_criteria_id'    => 'required|exists:subarea_criterias,id',
            'notes'                     => 'required|array',
            'finalnote'                 => 'required|array',
        ]);

        $evaluationCriteria = SubareaCriteria::find($request->evaluation_criteria_id);
        $evaluationSubArea  = EvaluationSubarea::find($request->evaluation_stage_id);

        $instance = EvaluationStage::updateOrCreate(
            [
                'project_id' => $evaluationSubArea->project_id,
                'stage_id' => $evaluationCriteria->stage_id,
            ],
            [
                'status' => 0,
            ]
        );

        $evaluationStageId = $instance->id;
        $usuarioId = 0;
        try {
            foreach ($request->notes as $userId => $note) {
                $usuarioId = $userId;
                $totalGrade = 0; // Inicializar la nota final del estudiante
                foreach ($note as $criteriaId => $value) {

                    $percentage = SubareaCriteria::find($criteriaId)->percentage;

                    // Calcular la contribución de esta nota al total según el porcentaje del criterio
                    $totalGrade += ($value * $percentage) / 100;
                    // dd($userId, $criteriaId, $request->evaluation_stage_id, $value);
                    EvaluationSubareaNote::updateOrCreate(
                        [
                            'user_id'               => $userId,
                            'evaluation_subarea_id' => $request->evaluation_stage_id,
                            'criteria_id'           => $criteriaId
                        ],
                        [
                            'note' => $value,
                        ]
                    );
                }

                $totalGrade = round($totalGrade, 2);

                // Guardar la nota final para este estudiante


                CriteriaSubarea::updateOrCreate(
                    [
                        'user_id'                   => $userId,
                        'evaluation_subareas_id'    => $request->evaluation_stage_id,
                    ],
                    [
                        'note'                      => $totalGrade
                    ]
                );


                // CriteriaStage::updateOrCreate(
                //     [
                //         'user_id'                   => $userId,
                //         'evaluation_criteria_id'    => $request->evaluation_criteria_id,
                //         'evaluation_stage_id'       => $evaluationStageId,

                //     ],
                //     [
                //         'note'                      => $totalGrade
                //     ]
                // );
            }

            //area necesito traer todas la notas de las subarea para calcular la nota final de stage


            $notesSubareas = CriteriaSubarea::join('evaluation_subareas as es', 'criteria_subareas.evaluation_subarea_id', 'es.id')
                ->join('subarea_criterias sc', 'sc.id', 'es.subarea_criteria_id')
                ->where('evaluation_subarea_id', $request->evaluation_stage_id)
                ->get();

            $globalNote =  $this->calculateNoteStage($notesSubareas);

            EvaluationStageNote::updateOrCreate(
                [
                    'user_id' => $userId,
                    'evaluation_stage_id' => $evaluationStageId,
                ],
                [
                    'note' => $globalNote,
                ]
            );


            //voy a recuperar todos los stages con sus notas para calcular y actualizar nota del proyecto

            $evaluationStages = EvaluationStage::join('evaluation_stage_note as esn', 'esn.evaluation_stage_id', 'evaluation_stages.id')
                ->join('stages as s', 's.id', 'evaluation_stages.stage_id')
                ->where('evaluation_stages.project_id', $evaluationSubArea->project_id)
                ->get();

            $globalNoteProject =  $this->calculateNoteStage($evaluationStages);
            UserProjectNote::updateOrCreate(
                [
                    'user_id' => $usuarioId,
                    'project_id' => $evaluationSubArea->project_id,
                ],
                [
                    'note' => $globalNoteProject,
                ]
            );

            return back()->with('success', 'Notas guardadas exitosamente.');
        } catch (Exception $th) {
            dd($th);
            return redirect()->route('grades.index')->with('error', $th->getMessage());
        }
    }

    public function calculateNoteStage($notesSubareas)
    {
        $globalNote = 0;
        $totalPercentage = 0;

        foreach ($notesSubareas as $subarea) {
            $note = (float)$subarea->note;
            $percentage = (float)$subarea->percentage;

            // Calculate the weighted note
            $weightedNote = ($note * $percentage) / 100;

            // Add to the global note
            $globalNote += $weightedNote;

            // Track the total percentage to ensure it sums to 100
            $totalPercentage += $percentage;
        }

        return $globalNote;
    }
}
