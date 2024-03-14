<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Cycle;
use App\Models\School;
use App\Mail\SendMail;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Models\UserNotification;
use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    const PERMISSIONS = [
        'index.advisers'    => 'Events.adviser.show',
        'index.students'    => 'Events.students',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index.advisers'])->only(['coordinatorIndex']);
        $this->middleware('permission:' . self::PERMISSIONS['index.students'])->only(['index']);
    }

    public function index(Project $project)
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

            $events = Events::select(
                'events.id',
                'events.name',
                'events.description',
                'events.place',
                'events.date'
            )
            ->join('projects as p', 'events.project_id', 'p.id')
            ->join('cycles as c', 'events.cycle_id', 'c.id')
            ->join('schools as s', 'events.school_id', 's.id')
            ->join('groups as g', 'events.group_id', 'g.id')
            ->join('user_group as ug', 'ug.group_id', 'g.id')
            ->join('users as u', 'events.user_id', 'u.id') 
            ->where('u.id', auth()->user()->id)
            ->get();
       
        // Creando una instancia del controlador Project
        $projectController = new ProjectController();

        //Llamando a la funcion disableProject
        $status = $projectController->disableProject($project);

        return view('events.index', compact('userType', 'events', 'project', 'status'));
    }

    public function create(Project $project)
    {
        // Obtiene el tipo de usuario actual
        $userType = auth()->user()->type;
        return view('events.create', compact('userType', 'project'));
    }


    public function store(Request $request, Project $project)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'required|string|max:255',
            'place'         => 'required|string',
            'date'          => 'required|date',
            'status'        => 'required',
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
            $event->status      = 0;
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
        $status = $events->status();
        //dd($status);
        if ($status === 'Aprobada') {
            return redirect()->back()->with('error', 'No puedes editar esta defensa porque ya ha sido aceptada.');
        } elseif ($status == 'Rechazada') {
            return redirect()->back()->with('error', 'No puedes editar esta defensa porque ya ha sido rechazada.');
        }

        $user = auth()->user();

        return view('events.edit') ->with(compact('events','project', 'user'));
    }

    public function update(Request $request, Project $project, Events $event)
    {
        //dd($event);
        $user = auth()->user();
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'required|string|max:255',
            'place'         => 'required|string',
            'date'          => 'required|date', 
            'status'        => 'required',
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
                'status'      => 0,
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

    public function destroy(Events $events)
    {
        $events->delete();
        return redirect()->route('events.index')->with('success', 'Evento eliminado exitosamente');
    }

    public function coordinatorIndex()
    {
        // Obtener el coordinador en sesión
        $coordinator = Auth::user();

        // Filtrar por protocolo y escuela
        $events = Events::with(['user', 'group'])
            ->whereHas('user', function ($query) use ($coordinator) {
                $query->where('school_id', session('school')['id']);
            })
            ->whereHas('group', function ($query) use ($coordinator) {
                $query->where('protocol_id', session('protocol')['id']);
            })
            ->get();

        //dd($events);
        return view('events.coordinator.index', compact('events'));
    }

    public function coordinatorShow(Events $events)
    {

        $events = Events::all();

        return view('events.coordinator.show')->with(compact('events'));
    }

    public function coordinatorUpdate(Request $request, Events $events)
    {
        $user       = $events->user; //Obteniendo usuario que ha presentado retiro

        $validatedData = $request->validate([
            'decision' => 'required',
        ]);

        $events->status = $request->decision;

        $events->update();


        // Envío de notificación y correo electrónico al estudiante deñ estado de la defensa
        try {
            //dd($emailData);
        } catch (Exception $th) {
            // Manejar la excepción
            // dd($th);
        }

        return redirect()->route('events.coordinator.index')->with('success', 'Estado de defensa actualizada.');
    }


}
