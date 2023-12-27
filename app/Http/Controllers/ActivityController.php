<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Group;
use App\Models\Project;
use DateTime;
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
        //dd($project);
        // Validación de los datos de la actividad
        $validatedData = $request->validate([
            'name'          => 'required|max:255',
            'description'   => 'required|max:255',
            'status'        => 'required',
            'date_start'    => 'required|date',
            'date_end'      => 'required|date',
        ]);

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

    public function modalLoadActivities(Request $request)
    {
        return view('activities.modal.attach_load');
    }

    public function import(Request $request)
    {

        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'activities.required' => 'Selecciona un archivo de tipo .xslx',
                ]
            );

            if (!is_dir(Storage::path('public/uploads/activities'))) {

                mkdir(Storage::path('public/uploads/activities'), 0755, true);
            }


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

            $extension = $request->file('activities')->getClientOriginalExtension();
            $request->file('activities')->storeAs('uploads/activities', 'file.' . $extension, 'public');

            $reader         = new Xlsx();
            $spreadsheet    = $reader->load(Storage::path('public/uploads/activities/file.' . $extension));
            $worksheet      = $spreadsheet->getActiveSheet();
            $listado        = $worksheet->toArray(null, true);

            DB::beginTransaction();

            foreach ($listado as $key => $item) {

                if ($item[0] == null || $item[1] == null || $item[2] == null || $item[3] == null || $item[4] == null) {
                    continue;
                }
                if ($item[0] == "" || $item[1] == "" || $item[2] == "" || $item[3] == "" || $item[4] == "") {
                    continue;
                }

                if ($key !== 0) {
                    $temp = array(
                        'name'          => $item[0],
                        'description'   => $item[1],
                        'status'        => $item[2],
                        'date_start'    => date('Y-m-d', strtotime($item[3])),
                        'date_end'      => date('Y-m-d', strtotime($item[4])),
                        'group_id'      => $group->id,
                        'project_id'    => $project->id
                    );
                    $data[]             = $temp;
                }
            }


            $inserts = Activity::insert($data);

            DB::commit();

            return redirect()->route('activities.index')->with('success', 'Actividades cargadas exitosamente');
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->route('activities.index')
                ->with('error', 'Error, las actividades no pudieron cargarse.')
                ->withInput();
        }
    }

}
