<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
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

class StageController extends Controller
{
    const PERMISSIONS = [
        'index'    => 'Stages',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
        $this->middleware('check.protocol')->only(['index', 'create']);
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
        $protocols = Protocol::all();
        $schools    = School::all();
        $cycles     = Cycle::all();

        return view('stage.create')->with(compact('protocols', 'schools', 'cycles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'protocol'      => 'required|integer|min:1|exists:protocols,id',
            'cycle'         => 'required|integer|min:1|exists:cycles,id',
            'school'        => 'required|integer|min:1|exists:schools,id',
            'sort'          => 'required|integer',
            'percentage'    => 'required|integer|min:1|max:100',
        ]);


        try {
            $stage = Stage::create([
                'name'          => $request['name'],
                'protocol_id'   => $request['protocol'],
                'cycle_id'      => $request['cycle'],
                'school_id'     => $request['school'],
                'sort'          => $request['sort'],
                'percentage'    => $request['percentage'],
            ]);

            return redirect()->route('stages.index')->with('success', 'Etapa Evaluativa creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('stages.create')->with('error', 'La Etapa Evaluativa ya se encuentra registrada, revisar.');
        }
    }

    public function edit(Stage $stage)
    {
        $protocols  = Protocol::all();
        $schools    = School::all();
        $cycles     = Cycle::all();

        return view('stage.edit')->with(compact('stage', 'protocols', 'schools', 'cycles'));
    }

    public function update(Request $request, Stage $stage)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'protocol'      => 'required|integer|min:1|exists:protocols,id',
            'cycle'         => 'required|integer|min:1|exists:cycles,id',
            'school'        => 'required|integer|min:1|exists:schools,id',
            'sort'          => 'required|integer',
            'percentage'    => 'required|integer|min:1|max:100',
        ]);

        try {
            $stage->update([
                'name'          => $request['name'],
                'protocol_id'   => $request['protocol'],
                'cycle_id'      => $request['cycle'],
                'school_id'     => $request['school'],
                'sort'          => $request['sort'],
                'percentage'    => $request['percentage'],
            ]);

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
            }
            // dd($data);

            if (($currentlyPercentage + $totalPercentage) > 100) {
                return redirect()->back()->with('error', 'Lo sentimos el porcentaje de los criterios supera el 100%.');
            }

            $inserts = EvaluationCriteria::insert($data);

            DB::commit();

            return redirect()->route('stages.index')->with('success', 'Criterios cargados exitosamente');
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->route('stages.index')
                ->with('error', 'Error, los criterios no pudieron cargarse.')
                ->withInput();
        }
    }
}
