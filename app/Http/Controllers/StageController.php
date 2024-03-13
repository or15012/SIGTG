<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CriteriaStage;
use Illuminate\Http\Request;
use App\Models\Cycle;
use App\Models\EvaluationCriteria;
use App\Models\Protocol;
use App\Models\School;
use App\Models\Stage;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Illuminate\Validation\Rule;

class StageController extends Controller
{
    const PERMISSIONS = [
        'index'    => 'Stages',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
        $this->middleware('check.protocol')->only(['index', 'create', 'update']);
        $this->middleware('check.school')->only(['index', 'create']);
    }

    public function index()
    {
        $stages = [];
        $stages = Stage::with('protocol', 'cycle', 'school')
            ->where('protocol_id', session('protocol')['id'])
            ->where('school_id', session('school', ['id']))
            ->paginate(10);
        return view('stage.index', compact('stages'));
    }

    public function create()
    {
        $protocols  = Protocol::all();
        $schools    = School::all();
        $cycles     = Cycle::where('status', 1)->get();
        $courses    = Course::all();

        return view('stage.create')->with(compact('protocols', 'schools', 'cycles', 'courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'protocol'      => 'required|integer|min:1|exists:protocols,id',
            'cycle'         => 'required|integer|min:1|exists:cycles,id',
            'school'        => 'required|integer|min:1|exists:schools,id',
            'percentage'    => 'required|integer|min:1|max:100',
        ]);

        switch (session('protocol')['id']) {

            case 5:
                $request->validate([
                    'start_date' => 'required|date',
                    'end_date' => [
                        'required',
                        'date',
                        'after_or_equal:start_date', // Asegura que end_date sea después o igual a start_date
                    ],
                ]);
                break;
        }

        try {

            $currentlyStages = Stage::where('protocol_id', $request->protocol)
                ->where('school_id', $request->school)
                ->where('cycle_id', $request->cycle)
                ->sum('percentage');

            $sortAvailable = Stage::where('protocol_id', $request->protocol)
                ->where('school_id', $request->school)
                ->where('cycle_id', $request->cycle)
                ->where('sort', $request->sort)
                ->first();

            if (isset($sortAvailable)) {
                return back()->withInput()->with('error', 'Orden de etapa ya utilizado.');
            }

            if (($currentlyStages + intval($request->percentage)) > 100) {
                switch (session('protocol')['id']) {
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                        return back()->withInput()->with('error', 'No puede superar el 100% en porcentaje de etapas.');
                        break;
                    case 5:
                        return back()->withInput()->with('error', 'No puede superar el 100% en porcentaje de áreas.');
                        break;
                    default:
                        break;
                }
            }

            $stage = new Stage();
            $stage->name        = $request->name;
            $stage->protocol_id = $request->protocol;
            $stage->cycle_id    = $request->cycle;
            $stage->school_id   = $request->school;
            $stage->sort        = $request->sort;
            $stage->percentage  = $request->percentage;

            switch (session('protocol')['id']) {
                case 1:
                case 2:
                case 3:
                    $stage->type = $request->type;
                    break;
                case 4:
                    # code...
                    $stage->course_id   = $request->course;
                    $stage->type        = $request->type;
                    break;
                case 5:
                    # code...
                    $stage->start_date  = $request->start_date;
                    $stage->end_date    = $request->end_date;
                    break;
                default:
                    # code...
                    break;
            }
            $stage->save();

            switch (session('protocol')['id']) {
                case 1:
                case 2:
                case 3:
                case 4:
                    return redirect()->route('stages.index')->with('success', 'Etapa Evaluativa creada exitosamente.');
                    break;
                case 5:
                    return redirect()->route('stages.index')->with('success', 'Área creada exitosamente.');
                    break;
            }
        } catch (\Exception $e) {
            switch (session('protocol')['id']) {
                case 1:
                case 2:
                case 3:
                case 4:
                    return redirect()->route('stages.create')->with('error', 'Ocurrio un error al registrar etapa.');
                    break;
                case 5:
                    return redirect()->route('stages.create')->with('error', 'Ocurrio un error al registrar área.');
                    break;
            }
        }
    }

    public function edit(Stage $stage)
    {
        $protocols          = Protocol::all();
        $schools            = School::all();
        $cycles             = Cycle::where('status', 1)->get();
        $coursesByCycle     = Course::where('cycle_id', $stage->cycle_id)->get();

        return view('stage.edit')->with(compact('stage', 'protocols', 'schools', 'cycles', 'coursesByCycle'));
    }

    public function update(Request $request, Stage $stage)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'protocol'      => 'required|integer|min:1|exists:protocols,id',
            'cycle'         => 'required|integer|min:1|exists:cycles,id',
            'school'        => 'required|integer|min:1|exists:schools,id',
            'sort'          => 'required|integer|exists:stages,sort',
            'percentage'    => 'required|integer|min:1|max:100',
        ]);

        try {
            switch (session('protocol')['id']) {

                case 5:
                    $request->validate([
                        'start_date' => 'required|date',
                        'end_date' => [
                            'required',
                            'date',
                            'after_or_equal:start_date', // Asegura que end_date sea después o igual a start_date
                        ],
                    ]);
                    break;
            }

            $sortAvailable = Stage::where('protocol_id', $request->protocol)
                ->where('school_id', $request->school)
                ->where('cycle_id', $request->cycle)
                ->where('sort', $request->sort)
                ->where('id', '=!', $stage->id)
                ->first();

            if (isset($sortAvailable)) {
                return back()->withInput()->with('error', 'Orden de etapa ya utilizado.');
            }
            $currentlyStages = Stage::where('protocol_id', $request->protocol)
                ->where('school_id', $request->school)
                ->where('cycle_id', $request->cycle)
                ->where('id', '=!', $stage->id)
                ->sum('percentage');

            if (($currentlyStages + intval($request->percentage)) > 100) {
                switch (session('protocol')['id']) {
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                        return back()->withInput()->with('error', 'No puede superar el 100% en porcentaje de etapas.');
                        break;
                    case 5:
                        return back()->withInput()->with('error', 'No puede superar el 100% en porcentaje de áreas.');
                        break;
                    default:
                        break;
                }
            }

            $stage->name        = $request->name;
            $stage->protocol_id = $request->protocol;
            $stage->cycle_id    = $request->cycle;
            $stage->school_id   = $request->school;
            $stage->sort        = $request->sort;
            $stage->percentage  = $request->percentage;

            switch (session('protocol')['id']) {
                case 1:
                case 2:
                case 3:
                    $stage->type        = $request->type;
                    break;
                case 4:
                    $stage->course_id   = $request->course;
                    $stage->type        = $request->type;
                    break;
                case 5:
                    $stage->start_date  = $request->start_date;
                    $stage->end_date    = $request->end_date;
                    break;
                default:
                    break;
            }
            $stage->update();

            return redirect()->route('stages.index')->with('success', 'Etapa Evaluativa actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('stages.edit', ['stage' => $stage])->with('error', 'La Etapa Evaluativa ya se encuentra registrada, revisar.');
        }
    }

    public function destroy(Stage $stage)
    {
        $stage->delete();

        return redirect()->route('stages.index')->with('success', 'Etapa Evaluativa eliminada exitosamente.');
    }

    public function downloadTemplate()
    {
        return response()->download(public_path('uploads/stages/formato-importacion-criterios.xlsx'));
    }

    public function downloadTemplateSubareas()
    {
        return response()->download(public_path('uploads/stages/formato-importacion-subareas.xlsx'));
    }

    public function modalLoadCriterias(Request $request)
    {
        return view('stage.modal.attach_load_criterias', ['stage_id' => $request->stage_id]);
    }

    public function storeLoadCriterias(Request $request)
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
            $stage          = Stage::find($request->stage_id);
            $criterias      = $stage->criterias;

            $currentlyPercentage = 0;
            foreach ($criterias as $key => $item) {
                $currentlyPercentage = $currentlyPercentage + $item->percentage;
            }

            $totalPercentage    = 0;
            $data               = array();

            DB::beginTransaction();

            foreach ($listado as $key => $item) {

                if (session('protocol')['id'] != 5) {
                    if ($item[0] == null || $item[1] == null || $item[2] == null) {
                        continue;
                    }
                    if ($item[0] == "" || $item[1] == "" || $item[2] == "") {
                        continue;
                    }

                    if ($key !== 0) {
                        $temp = array(
                            'name'          => $item[0],
                            'description'   => $item[1],
                            'percentage'    => $item[2],
                            'stage_id'      => $request->stage_id,
                        );
                        $data[]             = $temp;
                        $tempPercentage     = intval($item[2]);
                        $totalPercentage    = $totalPercentage + $tempPercentage;
                    }
                } else {
                    if ($item[0] == null || $item[1] == null) {
                        continue;
                    }
                    if ($item[0] == "" || $item[1] == "") {
                        continue;
                    }

                    if ($key !== 0) {
                        $temp = array(
                            'name'          => $item[0],
                            'description'   => $item[1],
                            'stage_id'      => $request->stage_id,
                        );
                        $data[]             = $temp;
                    }
                }
            }

            if (session('protocol')['id'] != 5) {
                if (($currentlyPercentage + $totalPercentage) > 100) {
                    return redirect()->back()->with('error', 'Lo sentimos el porcentaje de los criterios supera el 100%.');
                }
            }


            $inserts = EvaluationCriteria::insert($data);

            DB::commit();

            switch (session('protocol')['id']) {
                case '5':
                    return redirect()->route('stages.index')->with('success', 'Subáreas cargadas exitosamente');
                    break;
                case '1':
                case '2':
                case '3':
                case '4':
                    return redirect()->route('stages.index')->with('success', 'Criterios cargados exitosamente');
                    break;

                default:
                    break;
            }
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->route('stages.index')
                ->with('error', 'Error, los criterios no pudieron cargarse.')
                ->withInput();
        }
    }




}
