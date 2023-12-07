<?php

namespace App\Http\Controllers;

use App\Models\Consulting;
use App\Models\Group;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class ConsultingController extends Controller
{
    const PERMISSIONS = [
        'index'     => 'Consultings',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userType = auth()->user()->type;

        if ($userType === 1) {
            $consultings = Consulting::select(
                'consultings.id',
                'consultings.topics',
                'consultings.number',
                'consultings.summary',
                'consultings.date'
            )
                ->join('groups as g', 'consultings.group_id', 'g.id')
                ->join('user_group as ug', 'ug.group_id', 'g.id')
                ->where('ug.user_id', auth()->user()->id)
                ->get();
        } else {
            $consultings = Consulting::select(
                'consultings.id',
                'consultings.topics',
                'consultings.number',
                'consultings.summary',
                'consultings.date'
            )
                ->join('groups as g', 'consultings.group_id', 'g.id')
                ->join('teacher_group as tg', 'tg.group_id', 'g.id')
                ->where('tg.user_id', auth()->user()->id)
                ->get();
        }

        // Creando una instancia del controlador Project
        $projectController = new ProjectController();

        //Obteniendo proyectos
        $projects = Project::all();

        //Llamando a la funcion disabaleProject
        foreach ($projects as $project) {
            $status = $projectController->disableProject($project);
            //dd($status);
        }

        return view('consultings.index', compact('consultings', 'userType','status'));
    }

    public function create()
    {
        // Obtiene el tipo de usuario actual
        $userType = auth()->user()->type;

        return view('consultings.create', compact('userType'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'topics'    => 'required',
            //'number'    => 'required|integer',
            //'summary'   => 'required',
            'date'      => 'required|date', // Campo 'fecha' es obligatorio y debe ser una fecha válida'
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

        if ($user->type === 1) {
            // Estudiante
            $data['topics'] = $request->input('topics');
            $data['date'] = $request->input('date');

            // Asignar el group_id al consulting basándose en la relación con el grupo
            $data['group_id'] = $group->id;

            $consulting = Consulting::create($data);
        } elseif ($user->type === 2) {
            // Docente
            $data['number'] = $request->input('number');
            $data['summary'] = $request->input('summary');
            $consulting = Consulting::create($data);
        }
        return redirect()->route('consultings.index')->with('success', 'Asesoria creada correctamente.');
    }

    public function edit(Consulting $consulting)
    {

        $user = auth()->user();
        $consulting->date = Carbon::parse($consulting->date)->format('Y-m-d');
        return view('consultings.edit', compact('consulting', 'user'));
    }

    public function update(Request $request, Consulting $consulting)
    {
        $user = auth()->user();
        if ($user->type === 1) {

            $data = $request->validate([
                'topics'    => 'required',
                'date'      => 'required|date', // Campo 'fecha' es obligatorio y debe ser una fecha válida'
            ]);
        } elseif ($user->type === 2) {

            $data = $request->validate([
                'summary'   => 'required',
                'date'      => 'required|date', // Campo 'fecha' es obligatorio y debe ser una fecha válida'
            ]);

            // Obtener el campo 'number' del request y sumarle 1
            //dd($consulting);
            if ($consulting->number === null) {


                $consultingGroup = Consulting::where('group_id', $request->group_id)
                    ->where('number', '!=', null)
                    ->orderBy('id', 'desc')
                    ->first();
                //dd($consultingGroup);
                if (isset($consultingGroup)) {
                    $data['number'] = $consultingGroup->number + 1;
                } else {
                    $data['number'] = 1;
                }
            }
        }



        $consulting->update($data);

        return redirect()->route('consultings.index')->with('success', 'Asesoria actualizada correctamente.');
    }

    public function show(Consulting $consulting)
    {

        // Devuelve la vista 'consultings.show' pasando la asesoría como una variable compacta
        return view('consultings.show', compact('consulting'));
    }

    public function destroy(Consulting $consulting)
    {
        $consulting->delete();

        return redirect()->route('consultings.index')->with('success', 'Asesoria eliminada correctamente.');
    }
}
