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
use Exception;

class EventsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Project $project)
    {
        $events = [];
        $events = Events::get();

        // Creando una instancia del controlador Project
        $projectController = new ProjectController();

        //Llamando a la funcion disableProject
        $status = $projectController->disableProject($project);

        return view('events.index', compact('events', 'project', 'status'));
    }

    public function create(Project $project)
    {
        return view('events.create')->with(compact('project'));
    }


    public function store(Request $request, Project $project)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'required|string|max:255',
            'place'         => 'required|string',
            'date'          => 'required|date', 
            'user_id'       => 'required',
            'group_id'      => 'required',
            'project_id'    => 'required',
            'cycle_id'      => 'required',
            'school_id'     => 'required',   
        ]);

        try {
            // Crear el evento
            $event = new Events;
            $event->name        = $request->input('name');
            $event->description = $request->input('description');
            $event->place       = $request->input('place');
            $event->date        = date('Y-m-d H:i:s', strtotime($validatedData['date']));
    
            // Obtener los datos relacionados con el proyecto
            $event->school_id   = $request->input('school_id');
            $event->cycle_id    = $request->input('cycle_id');
            $event->user_id     = $request->input('user_id');
            $event->group_id    = $request->input('group_id');
            $event->project_id  = $request->input('project_id');
            $event->save();
        
            // Obtener estudiantes de la escuela del protocolo y del ciclo activo
            $getStudents = Group::join('user_group as ug', 'groups.id', 'ug.group_id')
                ->join('users as u', 'u.id', 'ug.user_id')
                ->join('user_protocol as up', 'up.user_id', 'u.id')
                ->where('groups.cycle_id', $validatedData['cycle_id'])
                ->where('u.school_id', $validatedData['school_id'])
                ->where('up.status', true)
                ->where('up.protocol_id', 3)
                ->select('u.email', 'u.first_name', 'u.last_name')
                ->get();

            // Preparar un conjunto de datos común para todos los estudiantes
            $emailData = [
                'event' => $event,
            ];

            try {
                //Mail::bcc($getStudents->pluck('email')->toArray())->send(new SendMail('mail.send-invitation-event', 'Notificación de defensa', $emailData));
            } catch (Exception $th) {
                dd($th->getMessage());
                //throw $th;
            }

            //dd($event);
            return redirect()->route('events.index', ['project' => $validatedData['project_id']])->with('success', 'Se añadió la defensa correctamente.');
        } catch (\Throwable $th) {
            //dd($th);
            return redirect()->route('events.index', ['project' => $validatedData['project_id']])->with('error', 'La defensa no pudo ser añadida.');
        }
    }

    public function edit(Events $events, Project $project)
    {
        return view('events.edit') ->with(compact('events','project'));
    }

    public function update(Request $request, Project $project, Events $event)
    {
        //dd($event);
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'required|string|max:255',
            'place'         => 'required|string',
            'date'          => 'required|date', 
            'user_id'       => 'required',
            'group_id'      => 'required',
            'project_id'    => 'required',
            'cycle_id'      => 'required',
            'school_id'     => 'required',   
        ]);
    
        try {
            if (!$event) {
                return redirect()->route('events.index', ['project' => $validatedData['project_id']])->with('error', 'El evento no existe.');
            }
    
            $event->update([
                'name'        => $request->input('name'),
                'description' => $request->input('description'),
                'place'       => $request->input('place'),
                'date'        => date('Y-m-d H:i:s', strtotime($validatedData['date'])),
                'school_id'   => $request->input('school_id'),
                'cycle_id'    => $request->input('cycle_id'),
                'user_id'     => $request->input('user_id'),
                'group_id'    => $request->input('group_id'),
                'project_id'  => $request->input('project_id'),
            ]);
    
            //dd($event);
            return redirect()->route('events.index', ['project' => $validatedData['project_id']])->with('success', 'Se actualizó la defensa correctamente.');
        } catch (\Throwable $th) {
            //dd($th);
            return redirect()->route('events.index', ['project' => $validatedData['project_id']])->with('error', 'La defensa no pudo ser actualizada.');
        }
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
