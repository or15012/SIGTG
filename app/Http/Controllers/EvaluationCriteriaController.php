<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Stage;
use App\Models\EvaluationCriteria;
use App\Models\SubareaCriteria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class EvaluationCriteriaController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $stage     = Stage::findOrfail($id);
        $criterias = EvaluationCriteria::where('stage_id', $stage->id)->get();


        return view('criteria.index', compact('criterias', 'stage'));
    }

    public function create($id)
    {

        $stage = Stage::findOrfail($id);
        $sumatory = EvaluationCriteria::where('stage_id', $stage->id)->sum('percentage');

        return view('criteria.create', compact('stage', 'sumatory'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            // 'percentage'    => 'required|integer|min:1|max:100',
            'stage'         => 'required|integer|min:1|exists:stages,id',
            'description'   => 'required|string'
        ]);

        $stage_id   = $data['stage'];
        $percentage = 0;
        if (session('protocol')['id'] != 5) {
            $data       = $request->validate(['percentage' => 'required|integer|min:1|max:100']);
            $percentage = $data['percentage'];
            $sumatory   = EvaluationCriteria::where('stage_id', $stage_id)->sum('percentage');

            if ($sumatory + $percentage > 100) {
                return redirect()->route('stages.index')->with('error', 'No se pudo completar la acción. El porcentaje supera el 100%.');
            }
        }

        try {
            $criteria               = new EvaluationCriteria();
            $criteria->name         = $request['name'];
            $criteria->percentage   = $percentage;
            $criteria->stage_id     = $stage_id;
            $criteria->description  = $request['description'];
            $criteria->save();

            if (session('protocol')['id'] != 5) {
                return redirect()->route('stages.index')->with('success', 'Se añadió el criterio de evaluación correctamente.');
            } else {
                return redirect()->route('stages.index')->with('success', 'Se añadió la subárea correctamente.');
            }
        } catch (Exception $e) {
            return redirect()->route('stages.index')->with('error', 'El criterio de evaluacion está duplicado.');
        }
    }

    public function edit(EvaluationCriteria $criteria)
    {
        $stage = Stage::findOrfail($criteria->stage_id);

        return view('criteria.edit')->with(compact('criteria', 'stage'));
    }

    public function update(Request $request, EvaluationCriteria $criteria)
    {
        //boton para crear criterios desde lista de criterios.
        //aqui tengo que revisar aqui
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            // 'percentage'    => 'required|integer|min:1|max:100',
            'stage'         => 'required|integer|min:1|exists:stages,id',
            'description'   => 'required|string',
        ]);

        $stage_id   = $data['stage'];

        if (session('protocol')['id'] != 5) {
            $percentage = $data['percentage'];
            $sumatory = DB::table('evaluation_criteria')->where('stage_id', $stage_id)->where('id', '!=', $criteria->id)->sum('percentage');
            if ($sumatory + $percentage > 100) {
                return redirect()->route('stages.index')->with('error', 'No se pudo completar la acción. El porcentaje supera el 100%.');
            }
            $criteria->percentage   = $data['percentage'];
        }

        try {
            $criteria->name         = $data['name'];
            $criteria->description  = $data['description'];

            switch (session('protocol')['id']) {
                case 1:
                    # code...

                    break;
                case 5:
                    # code...
                    $criteria->type = $request->type;
                    break;
                default:
                    # code...
                    break;
            }
            $criteria->update();
            if (session('protocol')['id'] != 5) {
                return redirect()->route('criterias.index', ['id' => $criteria->stage_id])->with('success', 'Criterio de Evaluación actualizado exitosamente.');

            } else {
                return redirect()->route('criterias.index', ['id' => $criteria->stage_id])->with('success', 'Subárea actualizada exitosamente.');

            }
        } catch (\Exception $e) {
            return redirect()->route('criterias.edit', ['criteria' => $criteria])->with('error', 'El criterio de evaluación ya se encuentra registrado, revisar.');
        }
    }

    public function destroy(EvaluationCriteria $criteria)
    {
        $criteria->delete();

        return redirect()->route('criterias.index', ['id' => $criteria->stage_id])->with('success', 'Criterio de Evaluación eliminada exitosamente.');
    }




    public function stagesCoordinatorEvaluationsCriteriasCreate(SubareaCriteria $evaluation)
    {
        $criterias = $evaluation->criterias;
        $sumatory = Criteria::where('subarea_criteria_id', $evaluation->id)->sum('percentage');

        return view('criterias.create', compact('evaluation', 'criterias', 'sumatory'));
    }

    public function stagesCoordinatorEvaluationsCriteriasStore(Request $request)
    {

        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'percentage'        => 'required|integer|min:1|max:100',
            'evaluation'        => 'required|integer|min:1|exists:subarea_criterias,id',
            'description'       => 'required|string'
        ]);

        $stage_id   = $data['evaluation'];
        $percentage = 0;
        $percentage = $data['percentage'];
        $sumatory   = Criteria::where('subarea_criteria_id', $stage_id)->sum('percentage');

        if ($sumatory + $percentage > 100) {
            return redirect()->back()->with('error', 'No se pudo completar la acción. El porcentaje supera el 100%.');
        }

        try {
            $criteria                       = new Criteria();
            $criteria->name                 = $request['name'];
            $criteria->percentage           = $percentage;
            $criteria->subarea_criteria_id  = $stage_id;
            $criteria->description          = $request['description'];
            $criteria->save();

            return redirect()->route('stages.index')->with('success', 'Se añadió el criterio de evaluación correctamente.');
        } catch (Exception $e) {
            return redirect()->route('stages.index')->with('error', 'El criterio de evaluacion está duplicado.');
        }
    }


    public function stagesCoordinatorEvaluationsCriteriasIndex(SubareaCriteria $evaluation)
    {

        $criterias = Criteria::where('subarea_criteria_id', $evaluation->id)->get();
        return view('criterias.list', compact('criterias', 'evaluation'));
    }

    public function stagesCoordinatorEvaluationsCriteriasEdit(Criteria $criteria)
    {
        return view('criterias.edit')->with(compact('criteria'));
    }


    public function stagesCoordinatorEvaluationsCriteriasUpdate(Request $request, Criteria $criteria)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'percentage'    => 'required|integer|min:1|max:100',
            'description'   => 'required|string',
        ]);

        $percentage = $data['percentage'];
        $sumatory = DB::table('criterias')->where('subarea_criteria_id', $criteria->subarea_criteria_id)->where('id', '!=', $criteria->id)->sum('percentage');

        if ($sumatory + $percentage > 100) {
            return redirect()->back()->with('error', 'No se pudo completar la acción. El porcentaje supera el 100%.');
        }

        try {
            $criteria->name         = $data['name'];
            $criteria->percentage   = $data['percentage'];
            $criteria->description  = $data['description'];
            $criteria->update();
            return redirect()->route('stages.index')->with('success', 'Criterio de Evaluación actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'El criterio de evaluación ya se encuentra registrado, revisar.');
        }
    }

    public function stagesCoordinatorEvaluationsCriteriasModal(Request $request)
    {
        return view('evaluations.modal.attach_load_criterias', ['stage_id' => $request->stage_id]);
    }

    public function stagesCoordinatorEvaluationsCriteriasLoad(Request $request)
    {

        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'criterias.required' => 'Selecciona un archivo de tipo .xslx',
                ]
            );

            if (!is_dir(Storage::path('public/uploads/stages'))) {

                mkdir(Storage::path('public/uploads/stages'), 0755, true);
            }



            $extension = $request->file('criterias')->getClientOriginalExtension();
            $request->file('criterias')->storeAs('uploads/stages', 'file.' . $extension, 'public');

            $reader         = new Xlsx();
            $spreadsheet    = $reader->load(Storage::path('public/uploads/stages/file.' . $extension));
            $worksheet      = $spreadsheet->getActiveSheet();
            $listado        = $worksheet->toArray(null, true);
            $stage          = SubareaCriteria::find($request->stage_id);
            // $criterias      = $stage->criterias
            $currentlyPercentage = 0;
            $currentlyPercentage   = Criteria::where('subarea_criteria_id', $request->stage_id)->sum('percentage');

            // $currentlyPercentage = 0;
            // foreach ($criterias as $key => $item) {
            //     $currentlyPercentage = $currentlyPercentage + $item->percentage;
            // }

            $totalPercentage    = 0;
            $data               = array();

            DB::beginTransaction();

            foreach ($listado as $key => $item) {

                if ($item[0] == null || $item[1] == null || $item[2] == null) {
                    continue;
                }
                if ($item[0] == "" || $item[1] == "" || $item[2] == "") {
                    continue;
                }

                if ($key !== 0) {
                    $temp = array(
                        'name'                  => $item[0],
                        'description'           => $item[1],
                        'percentage'            => $item[2],
                        'subarea_criteria_id'   => $request->stage_id,
                    );
                    $data[]             = $temp;
                    $tempPercentage     = intval($item[2]);
                    $totalPercentage    = $totalPercentage + $tempPercentage;
                }
            }

            if (($currentlyPercentage + $totalPercentage) > 100) {
                return redirect()->back()->with('error', 'Lo sentimos el porcentaje de los criterios supera el 100%.');
            }



            $inserts = Criteria::insert($data);

            DB::commit();


            return redirect()->back()->with('success', 'Criterios cargados exitosamente');
        } catch (Exception $th) {
            DB::rollBack();
            dd($th);
            return redirect()->route('stages.index')
                ->with('error', 'Error, los criterios no pudieron cargarse.')
                ->withInput();
        }
    }
}
