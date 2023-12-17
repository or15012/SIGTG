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
            'path'                  => 'required|mimes:pdf,xlsx,xls', // Esto valida que el archivo sea un PDF (puedes ajustar según tus necesidades)
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
}
