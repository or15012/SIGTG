<?php

namespace App\Http\Controllers;

use App\Models\Cycle;
use App\Models\Parameter;
use App\Models\Phase;
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

        return redirect()->back()->with('success', 'Fase creada con éxito');
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
}
