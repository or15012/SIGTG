<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Cycle;
use App\Models\School;
use App\Models\Project;

class EventsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
   
        $events = Events::all();
        return view('events.index', compact('events'));
    }

    public function create()
    {
        $users = User::all();
        $groups = Group::all();
        $cycles = Cycle::all();
        $schools = School::all();
        $projects = Project::all();

        return view('events.create', compact('users', 'groups', 'cycles', 'schools', 'projects'));
    }


    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
        ]);

        $event = Events::create($request->all());
        return redirect()->route('events.show', $event->id)->with('success', 'Defensa creada exitosamente');
    }

    public function show(Events $events)
    {
        return view('events.show', compact('events'));
    }


    public function destroy(Events $events)
    {
        $events->delete();
        return redirect()->route('events.index')->with('success', 'Evento eliminado exitosamente');
    }
}
