<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with('parameters')->get();
        return view('groups.index', compact('groups'));
    }

    /**
     * Vista para inicializar grupo.
     *
     * @return \Illuminate\Http\Response
     */
    public function initialize()
    {
        $user = Auth::user();
        return view('groups.initialize', compact('user'));
    }

    public function create()
    {
        $parameterNames = Parameter::PARAMETERS;
        return view('groups.create', compact('parameterNames'));
    }

    public function store(Request $request)
    {
        // Validación de los datos del ciclo y los parámetros
        $validatedData = $request->validate([
            'number'        => 'required|integer',
            'year'          => 'required|integer',
            'status'        => 'required|boolean',
            'parameters'    => 'array', // Campo que contendrá los parámetros
        ]);
        // dd($request);
        // Crear un nuevo ciclo
        $group = Group::create([
            'number'    => $validatedData['number'],
            'year'      => $validatedData['year'],
            'status'    => $validatedData['status'],
        ]);

        // Guardar los parámetros
        foreach ($validatedData['parameters'] as $key => $value) {
            Parameter::create([
                'name'      => $key,
                'value'     => $value,
                'group_id'  => $group->id,
            ]);
        }

        return redirect()->route('groups.index')->with('success', 'Ciclo creado con éxito');
    }

    public function show($id)
    {
        $group = Group::findOrFail($id);
        return view('groups.show', compact('group'));
    }

    public function edit($id)
    {
        $group = Group::findOrFail($id);
        $parameterNames = Parameter::PARAMETERS;
        return view('groups.edit', compact('group', 'parameterNames'));
    }

    public function update(Request $request, $id)
    {
        // Validación de los datos del ciclo y los parámetros

        $validatedData = $request->validate([
            'number'        => 'required|integer',
            'year'          => 'required|integer',
            'status'        => 'required|boolean',
            'parameters'    => 'array', // Campo que contendrá los parámetros
        ]);

        // Encontrar el ciclo que se desea actualizar
        $group = Group::findOrFail($id);

        // Actualizar los datos del ciclo
        $group->update([
            'number'    => $validatedData['number'],
            'year'      => $validatedData['year'],
            'status'    => $validatedData['status'],
        ]);
        // dd($group);
        // Actualizar o crear los parámetros
        $parameterNames = Parameter::PARAMETERS; // Obtén el array de nombres de parámetros

        foreach ($parameterNames as $key => $name) {
            // Encuentra el parámetro por su nombre
            $parameter = $group->parameters()->where('name', $key)->first();

            // Si el parámetro existe, actualízalo
            if ($parameter) {
                $parameter->update(['value' => $validatedData['parameters'][$key]]);
            } else {
                // Si no existe, crea un nuevo parámetro
                Parameter::create([
                    'name'      => $key,
                    'value'     => $validatedData['parameters'][$key],
                    'group_id'  => $group->id,
                ]);
            }
        }
        return redirect()->route('groups.index')->with('success', 'Ciclo actualizado con éxito');
    }

    public function destroy($id)
    {
        // Encontrar el ciclo que se desea eliminar
        $group = Group::findOrFail($id);

        // Eliminar los parámetros asociados al ciclo
        $group->parameters()->delete();

        // Eliminar el ciclo
        $group->delete();

        return redirect()->route('groups.index')->with('success', 'Ciclo eliminado con éxito');
    }
}
