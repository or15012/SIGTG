<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Cycle;
use App\Models\Protocol;
use App\Models\School;
use Illuminate\Http\Request;
use App\Models\Stage;
use PhpParser\Node\Stmt\TryCatch;

class StageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $stages = [];
        $stages = Stage::with('protocol', 'cycle', 'school')->get(); //Definición de métodos del modelo.
        return view('stage.index', compact('stages'));
    }

    public function create()
    {
        $protocols = Protocol::all();
        $schools    = School::all();
        $cycles     = Cycle::all();

        return view('stage.create') ->with(compact('protocols','schools','cycles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'protocol'      => 'required|integer|min:1|exists:protocols,id',
            'cycle'         => 'required|integer|min:1|exists:cycles,id',
            'school'        => 'required|integer|min:1|exists:schools,id',
        ]);


        try {
            $stage = Stage::create([
                'name'          => $request['name'],
                'protocol_id'   => $request['protocol'],
                'cycle_id'      => $request['cycle'],
                'school_id'     => $request['school'],
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

        return view('stage.edit') ->with(compact('stage','protocols','schools','cycles'));
    }

    public function update(Request $request, Stage $stage)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'protocol'      => 'required|integer|min:1|exists:protocols,id',
            'cycle'         => 'required|integer|min:1|exists:cycles,id',
            'school'        => 'required|integer|min:1|exists:schools,id',
        ]);

        try {
            $stage->update([
                'name'          => $request['name'],
                'protocol_id'   => $request['protocol'],
                'cycle_id'      => $request['cycle'],
                'school_id'     => $request['school'],
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
}
