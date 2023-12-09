<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cycle;
use App\Models\Protocol;
use App\Models\School;
use App\Models\Stage;

class StageController extends Controller
{
    const PERMISSIONS = [
        'index'    => 'Stages',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
    }

    public function index()
    {
        $stages = [];
        $stages = Stage::with('protocol', 'cycle', 'school')
                ->where('protocol_id',session("protocol")['id'])
                ->where('school_id', session("school",['id']))
                ->get();
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

        return view('stage.edit') ->with(compact('stage','protocols','schools','cycles'));
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
}
