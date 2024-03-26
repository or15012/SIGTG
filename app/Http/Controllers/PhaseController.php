<?php

namespace App\Http\Controllers;

use App\Models\Cycle;
use App\Models\Parameter;
use App\Models\Phase;
use App\Models\Protocol;
use App\Models\School;
use App\Models\Stage;
use Exception;
use Illuminate\Http\Request;

class PhaseController extends Controller
{
    const PERMISSIONS = [
        'index'    => 'Phases',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
        $this->middleware('check.school')->only(['index', 'create']);
    }


    public function index()
    {
        $phases = Phase::where('school_id', session('school', ['id']))->with(['cycle', 'school'])
            ->paginate(20);

        return view('phases.index', compact('phases'));
    }

    public function create()
    {
        $cycle = Cycle::where('status', 1)->first();
        return view('phases.create', [
            'cycle' => $cycle
        ]);
    }

    public function store(Request $request)
    {
        // Validación de los datos del ciclo y los parámetros
        $validatedData = $request->validate([
            'cycle_id'      => 'required|integer',
            'name'          => 'required|string',
            'description'   => 'required|string',
            'school_id'     => 'required|integer',
        ]);

        // Crear un nuevo ciclo
        $phase = Phase::create([
            'cycle_id'      => $validatedData['cycle_id'],
            'name'          => $validatedData['name'],
            'description'   => $validatedData['description'],
            'school_id'     => $validatedData['school_id'],
        ]);

        return redirect()->route('phases.index')->with('success', 'Fase creada con éxito');
    }

    public function show($id)
    {

        return view('cycles.show', compact('cycle'));
    }

    public function edit(Phase $phase)
    {
        return view('phases.edit', compact('phase'));
    }

    public function update(Request $request, Phase $phase)
    {
        // Validación de los datos del ciclo y los parámetros

        $validatedData = $request->validate([
            'name'          => 'required|string',
            'description'   => 'required|string',
        ]);

        $phase->update([
            'name'          => $validatedData['name'],
            'description'   => $validatedData['description'],
        ]);

        return redirect()->route('phases.index')->with('success', 'Fase actualizada con éxito');
    }

    public function destroy(Phase $phase)
    {
        // Eliminar el ciclo
        $phase->delete();

        return redirect()->route('phases.index')->with('success', 'Fase eliminada con éxito');
    }

    public function assignStages(Phase $phase)
    {
        $stages = Stage::where('protocol_id', 5)
            ->where('school_id', session('school', ['id']))
            ->whereNotIn('id', $phase->stages->pluck('id'))
            ->get();

        $stagesAssigned = $phase->stages;

        return view('phases.assign-stages', compact('phase', 'stagesAssigned', 'stages'));
    }


    public function storeAssignStages(Request $request, Phase $phase)
    {
        // Asignas stages a phases con orden
        $stages_id = array();
        if (isset($request->stages)) {
            foreach ($request->stages as $key => $value) {
                $stages_id[$value] = ['order' => $key];
            }
            $phase->stages()->sync($stages_id);
        } else {
            $phase->stages()->detach();
        }

        return redirect()->back()->with('success', 'Fase actualizada con éxito');
    }

    public function getPhase(Phase $phase)
    {
        try {
            return response()->json(['success' => true, 'phase' => $phase]);
        } catch (Exception $th) {
            return response()->json(['success' => false, 'phase' => []]);
        }
    }


    public function stageCreate(Phase $phase)
    {
        $protocols  = Protocol::all();
        $schools    = School::all();
        $cycles     = Cycle::where('status', 1)->get();

        return view('phases.stage.create')->with(compact('protocols', 'schools', 'cycles', 'phase'));
    }

    public function stageStore(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'protocol'      => 'required|integer|min:1|exists:protocols,id',
            'cycle'         => 'required|integer|min:1|exists:cycles,id',
            'school'        => 'required|integer|min:1|exists:schools,id',
            'percentage'    => 'required|integer|min:1|max:100',
            'phase_id'      => 'required|integer|min:1|exists:phases,id',
            'start_date'    => 'required|date',
            'end_date'      => [
                'required',
                'date',
                'after_or_equal:start_date', // Asegura que end_date sea después o igual a start_date
            ],
        ]);

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

            if (isset($sortAvailable))
                return back()->withInput()->with('error', 'Orden de etapa ya utilizado.');


            if (($currentlyStages + intval($request->percentage)) > 100)
                return back()->withInput()->with('error', 'No puede superar el 100% en porcentaje de áreas.');


            $stage = new Stage();
            $stage->name        = $request->name;
            $stage->protocol_id = $request->protocol;
            $stage->cycle_id    = $request->cycle;
            $stage->school_id   = $request->school;
            $stage->sort        = $request->sort;
            $stage->percentage  = $request->percentage;
            $stage->start_date  = $request->start_date;
            $stage->end_date    = $request->end_date;
            $stage->save();

            $stages_id = array();
            $stages_id[$stage->id] = ['order' => $stage->sort];
            $phase = Phase::find($request->phase_id);
            $phase->stages()->sync($stages_id);

            return redirect()->route('phases.index')->with('success', 'Área temática creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('stages.create')->with('error', 'Ocurrio un error al registrar área temática.');
        }
    }
}
