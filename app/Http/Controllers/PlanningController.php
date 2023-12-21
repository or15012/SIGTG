<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PlanningController extends Controller
{
    public function index()
    {
        //obtener grupo actual del user logueado
        $user = Auth::user();
        // Obtiene el año actual
        $year = date('Y');
        // Realiza una consulta para verificar si el usuario está en un grupo del año actual

        $group = Group::where('groups.year', $year)
            ->where('groups.status', 1)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->first();

        if (!isset($group)) {
            return redirect('home')->withErrors(['message' => 'No tienes un grupo activo.']);
        }

        $plannings = Profile::where('group_id', $group->id)
            ->where('type', 0)
            ->paginate(10);

        return view('plannings.index', compact('plannings'));
    }

    public function create()
    {
        //obtener grupo actual del user logueado
        return view('plannings.create', []);
    }

    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'description'           => 'required|string',
            'path'                  => 'required|mimes:pdf,xlsx,xls', // Esto valida que el archivo sea un PDF o excel (puedes ajustar según tus necesidades)
        ]);

        // Procesar y guardar el archivo
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('plannings'); // Define la carpeta de destino donde se guardará el archivo
        }

        $user = Auth::user();
        $year = date('Y');
        $group = Group::where('groups.year', $year)
            ->where('groups.status', 1)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->first();
        $validatedPlanningPriority = Profile::where('group_id', $group->id)
            ->where('proposal_priority', $request->input('proposal_priority'))
            ->first();

        if (isset($validatedPlanningPriority)) {
            return redirect()->back()
                ->withErrors(['message' => 'Ya posee una planificación registrada.'])
                ->withInput();
        }

        // Crear una nueva planificación
        $planning                        = new Profile;
        $planning->name                  = $request->input('name');
        $planning->description           = $request->input('description');
        $planning->path                  = $path; // Asigna el nombre del archivo (o null si no se cargó un archivo)
        $planning->type                  = 0;
        $planning->group_id              = $group->id;
        $planning->status                = 0;
        $planning->save();

        return redirect()->route('plannings.index')->with('success', 'La planificación se ha guardado correctamente');
    }

    public function show(Profile $planning)
    {
        return view('plannings.show', compact('planning'));
    }

    public function edit(Profile $planning)
    {

        return view('plannings.edit', ['planning' => $planning]);
    }

    public function update(Request $request, Profile $planning)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'path' => 'nullable|mimes:pdf,xlsx,xls', // Esto valida que el nuevo archivo sea un PDF o excel (puedes ajustar según tus necesidades)

        ]);
        try {
            // Actualizar los campos del perfil
            $planning->name               = $request->input('name');
            $planning->description        = $request->input('description');
            // Procesar y guardar el nuevo archivo si se proporciona
            if ($request->hasFile('path')) {
                $path = $request->file('path')->store('plannings');
                $planning->path = $path;
            }

            $planning->update();

            return redirect()->route('plannings.index')->with('success', 'La planificación se ha actualizado correctamente');
        } catch (\Throwable $th) {
            // Log de errores o manejo adicional
            //Log::error('Error al actualizar el preperfil: ' . $th->getMessage());
            return redirect()->route('plannings.index')->with('error', 'Hubo un error al actualizar la planificación. Por favor, inténtelo de nuevo.');
        }
    }


    public function planningDownload(Profile $planning, $file)
    {

        $filePath = storage_path('app/' . $planning->$file);
        return response()->download($filePath);

    }
    public function destroy(Profile $planning)
    {
        $planning->delete();

        return redirect()->route('plannings.index')->with('success', 'Planificación eliminado correctamente.');
    }
}
