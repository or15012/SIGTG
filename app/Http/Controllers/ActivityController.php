<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Group;
use App\Models\Project;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Termwind\Components\Dd;

class ActivityController extends Controller
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

        $activities = $group->activities;
        return view('activities.index', compact('activities'));
    }


    public function create()
    {
        //obtener grupo actual del user logueado
        return view('activities.create', []);
    }

    public function downloadTemplate()
    {
        return response()->download(public_path('uploads/activities/formato-importacion-actividades.xlsx'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        // Obtiene el año actual
        $year = date('Y');
        $group = Group::where('groups.year', $year)
            ->where('groups.status', 1)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->first();

        $project = $group->projects->first();

        //dd($project);
        // Validación de los datos de la actividad
        $validatedData = $request->validate([
            'name'          => 'required|max:255',
            'description'   => 'required|max:255',
            'status'        => 'required',
            'date_start'    => 'required|date',
            'date_end'      => 'required|date',
        ]);


        // Crear un nueva actividad
        $activity = Activity::create([
            'name'          => $validatedData['name'],
            'description'   => $validatedData['description'],
            'status'        => $validatedData['status'],
            'date_start'    => date('Y-m-d', strtotime($validatedData['date_start'])),
            'date_end'      => date('Y-m-d', strtotime($validatedData['date_end'])),
            'group_id'      => $group->id,
            'project_id'    => $project->id

        ]);

        return redirect()->route('activities.index')->with('success', 'Actividad creado con éxito');
    }


    public function import(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'excelFile.required' => 'Selecciona un archivo de tipo .xslx',
            ]
        );

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('activities.index')
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        try {
            if (!is_dir(Storage::path('public/uploads/activities'))) {

                mkdir(Storage::path('public/uploads/activities'), 0755, true);
            }

            $extension = $request->file('excelFile')->getClientOriginalExtension();
            $request->file('excelFile')->storeAs('uploads/activities', 'file.' . $extension, 'public');

            $reader = new Xlsx();
            $spreadsheet = $reader->load(Storage::path('public/uploads/activities/file.' . $extension));
            $worksheet = $spreadsheet->getActiveSheet();
            $listado = $worksheet->toArray(null, true);

            DB::beginTransaction();
            $existen = false;

            DB::commit();

            if ($existen == true) {
                return redirect()->route('activities.index')
                    ->withErrors(['Algunas actividades ya existen'])
                    ->withInput();
            }

            return redirect()->route('activities.index')
                ->with(['success' => 'Importación correcta.'])
                ->withInput();

            Storage::disk('public')->delete('activities/file.' . $extension);

            return redirect()->route('activities.index')->with('success', 'Importación correcta.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('activities.index')
                ->withErrors(['Sorry, Error Occured !', 'Asegúrese que el archivo tenga el formato correcto.'])
                ->withInput();
        }
    }

    public function edit(Activity $activity)
    {
        return view('activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {

        //dd($project);
        // Validación de los datos de la actividad
        $validatedData = $request->validate([
            'name'          => 'required|max:255',
            'description'   => 'required|max:255',
            'status'        => 'required',
            'date_start'    => 'required|date',
            'date_end'      => 'required|date',
        ]);

        // Actualizar actividad
        $activity->update($validatedData);

        return redirect()->route('activities.index')->with('success', 'Actividad actualizada con éxito');
    }

    public function show(Activity $activity)
    {

        // Devuelve la vista 'activities.show' pasando la actividad como una variable compacta
        return view('activities.show', compact('activity'));
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Actividad eliminada correctamente.');
    }
}
