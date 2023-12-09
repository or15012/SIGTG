<?php

namespace App\Http\Controllers;

use App\Models\Cycle;
use App\Models\Parameter;
use Illuminate\Http\Request;

class CycleController extends Controller
{

    const PERMISSIONS = [
        'index'    => 'Cycles',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
    }


    public function index()
    {
        $cycles = Cycle::with('parameters')->get();
        return view('cycles.index', compact('cycles'));
    }

    public function create()
    {
        $parameterNames = Parameter::PARAMETERS;
        return view('cycles.create', compact('parameterNames'));
    }

    public function store(Request $request)
    {
        // Validación de los datos del ciclo y los parámetros
        $validatedData = $request->validate([
            'number'        => 'required|integer',
            'year'          => 'required|integer',
            'status'        => 'required|boolean',
            'date_start'    => 'required|date',
            'date_end'      => 'required|date|weeks_between',
            'parameters'    => 'array', // Campo que contendrá los parámetros
        ], [
            'date_end.weeks_between' => 'La diferencia entre date_start y date_end debe ser de :weeks semanas.',
        ]);
        // Crear un nuevo ciclo
        $cycle = Cycle::create([
            'number'        => $validatedData['number'],
            'year'          => $validatedData['year'],
            'status'        => $validatedData['status'],
            'date_start'    => date('Y-m-d', strtotime($validatedData['date_start'])),
            'date_end'      => date('Y-m-d', strtotime($validatedData['date_end'])),
        ]);

        // Guardar los parámetros
        foreach ($validatedData['parameters'] as $key => $value) {
            Parameter::create([
                'name'      => $key,
                'value'     => $value,
                'cycle_id'  => $cycle->id,
            ]);
        }

        return redirect()->route('cycles.index')->with('success', 'Ciclo creado con éxito');
    }

    public function show($id)
    {
        $cycle = Cycle::findOrFail($id);
        return view('cycles.show', compact('cycle'));
    }

    public function edit($id)
    {
        $cycle = Cycle::findOrFail($id);
        $parameterNames = Parameter::PARAMETERS;
        return view('cycles.edit', compact('cycle', 'parameterNames'));
    }

    public function update(Request $request, $id)
    {
        // Validación de los datos del ciclo y los parámetros

        $validatedData = $request->validate([
            'number'        => 'required|integer',
            'year'          => 'required|integer',
            'status'        => 'required|boolean',
            'date_start'    => 'required|date',
            'date_end'      => 'required|date',
            'parameters'    => 'array', // Campo que contendrá los parámetros
        ]);

        // Encontrar el ciclo que se desea actualizar
        $cycle = Cycle::findOrFail($id);

        // Actualizar los datos del ciclo
        $cycle->update([
            'number'    => $validatedData['number'],
            'year'      => $validatedData['year'],
            'status'    => $validatedData['status'],
            'date_start'    => date('Y-m-d', strtotime($validatedData['date_start'])),
            'date_end'      => date('Y-m-d', strtotime($validatedData['date_end'])),
        ]);
        // dd($cycle);
        // Actualizar o crear los parámetros
        $parameterNames = Parameter::PARAMETERS; // Obtén el array de nombres de parámetros

        foreach ($parameterNames as $key => $name) {
            // Encuentra el parámetro por su nombre
            $parameter = $cycle->parameters()->where('name', $key)->first();

            // Si el parámetro existe, actualízalo
            if ($parameter) {
                $parameter->update(['value' => $validatedData['parameters'][$key]]);
            } else {
                // Si no existe, crea un nuevo parámetro
                Parameter::create([
                    'name'      => $key,
                    'value'     => $validatedData['parameters'][$key],
                    'cycle_id'  => $cycle->id,
                ]);
            }
        }
        return redirect()->route('cycles.index')->with('success', 'Ciclo actualizado con éxito');
    }

    public function destroy($id)
    {
        // Encontrar el ciclo que se desea eliminar
        $cycle = Cycle::findOrFail($id);

        // Eliminar los parámetros asociados al ciclo
        $cycle->parameters()->delete();

        // Eliminar el ciclo
        $cycle->delete();

        return redirect()->route('cycles.index')->with('success', 'Ciclo eliminado con éxito');
    }
}
