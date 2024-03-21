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
        'index.advisers' => 'Events.advisers',
        'index.students' => 'Events.students',
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
        //dd($year);
        $events = Events::join('groups as gr', 'events.group_id', 'gr.id')
            ->where('gr.protocol_id', session('protocol')['id'])
            ->where('events.group_id', $group->id)
            ->select('events.*')
            ->paginate(30);

            //dd($events);
        return view('events.index', compact('events', 'project'));
    }

    public function create(Project $project)
    {
        //dd($project);
        return view('events.create', compact('project'));
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

        //dd($validatedData);

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
                ->where('groups.cycle_id', $request->input('cycle_id'))
                ->where('u.school_id', $request->input('school_id'))
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
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'required|string|max:255',
            'place'         => 'required|string',
            'date'          => 'required|date',
        ]);

        try {
            $user = Auth::user();
            
            // Verifica si el evento existe
            if (!$event) {
                return redirect()->route('events.index', ['project' => $validatedData['project_id']])->with('error', 'El evento no existe.');
            }

            // Obtiene el año actual
            $year = date('Y');
            $group = Group::where('groups.year', $year)
                ->where('groups.status', 1)
                ->whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->first();

            // Actualiza los campos del evento
            $fields = [
                'group_id'      => $group->id,
                'name'          => $request['name'],
                'description'   => $request['description'],
                'place'         => $request['place'],
                'date'          => $request['date'],
            ];
            $event->update($fields);

            //Envío de correo a coordinador.
            $role = 'Coordinador General';
            $userRoles = User::role($role)->get(); //modificar para diferenciar por modalidades.

            $notificationStudent = Notification::create(['title' => 'Alerta de defensa de trabajo de grado', 'message' => "Se ha enviado una actualización de la solicitud de retiro de trabajo de grado exitosamente, está pendiente de revisión", 'user_id' => Auth::user()->id]);
            
            foreach ($userRoles as $coordinator) {
                try {
                    $emailData = [
                        'user'                => $coordinator,
                        'name'                => $request['name'],
                        'description'         => $request['description'],
                        'status'              => $request['status']
                    ];

                    // Envía correo al coordinador
                    Mail::to($coordinator->email)->send(new SendMail('mail.event-coordinator-updated', 'Modificación de retiro de trabajo de grado presentado', $emailData));

                    // Crea notificación para el coordinador
                    $notificationCoordinator = Notification::create(['title' => 'Alerta de retiro de trabajo de grado', 'message' => "El estudiante ha enviado una actualización de su solicitud de defensa de trabajo de grado para revisión",  'user_id' => $coordinator->id]);
                    UserNotification::create(['user_id' => $coordinator->id, 'notification_id' => $notificationCoordinator->id, 'is_read' => 0]);
                } catch (\Throwable $th) {
                    //dd($th);
                }
            }

            // Envío de correo electrónico a estudiante
            try {
                $emailData = [
                    'user'         => $user,
                    'name'         => $request['name'],
                    'description'  => $request['description'],
                    'status'       => $request['status']
                ];

                // Envía correo al estudiante
                Mail::to($user->email)->send(new SendMail('mail.event-update', 'Modificación de defensa de trabajo de grado enviado con éxito', $emailData));
                UserNotification::create(['user_id' => $user->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
            } catch (Exception $th) {
                // Manejar la excepción
            }

            // Envío de correo a coordinador
            foreach ($userRoles as $coordinator) {
                try {
                    $emailData = [
                        'user'         => $coordinator,
                        'name'         => $request['name'],
                        'description'  => $request['description'],
                        'status'       => $request['status']
                    ];

                    // Envía correo al coordinador
                    Mail::to($coordinator->email)->send(new SendMail('mail.event-coordinator-updated', 'Modificación de retiro de trabajo de grado presentado', $emailData));

                    // Crea notificación para el coordinador
                    $notificationCoordinator = Notification::create(['title' => 'Alerta de retiro de trabajo de grado', 'message' => "El estudiante ha enviado una actualización de su solicitud de defensa de trabajo de grado para revisión",  'user_id' => $coordinator->id]);
                    UserNotification::create(['user_id' => $coordinator->id, 'notification_id' => $notificationCoordinator->id, 'is_read' => 0]);
                } catch (\Throwable $th) {
                    // Manejar la excepción
                }
            }
            //        return view('events.edit') ->with(compact('events','project', 'user'));

            return redirect()->route('events.index')->with('success', 'Se actualizó la defensa correctamente.')->with(compact('project'));
            
        } catch (Exception $e) {
            //dd($e);
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Algo salió mal. Intente nuevamente.');
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

    public function coordinatorShow($eventId)
    {
        //$events = Events::first(); // Obtener el primer evento
        $event = Events::findOrFail($eventId);
        return view('events.coordinator.show', compact('event'));
        //return view('events.coordinator.show', compact('events'));
    }
    

    public function coordinatorUpdate(Request $request, Events $event)
    {
        $user       = $event->user; //Obteniendo usuario que ha presentado retiro
        $validatedData = $request->validate([
            'decision' => 'required',
        ]);

        $event->status = $request->decision;
        $event->update();
        //dd($event);

        // Envío de notificación y correo electrónico al estudiante del estado de la defensa
         $notificationStudent = Notification::create([
            'title' => 'Alerta de solicitud de defensa de trabajo de grado',
            'message' => "Se ha recibido una actualización sobre el estado de su solicitud de defensa de trabajo de grado",
            'user_id' => $user->id,
        ]);

         try {
            $emailData = [
                'user' => $user,
                'event' => $event,
                'name'  => $event->name
            ];
            //dd($emailData);
            Mail::to($user->email)->send(new SendMail('mail.event-coordinator-updated', 'Estado de solicitud de defensa de trabajo de grado actualizada con éxito', $emailData));
            UserNotification::create(['user_id' => $user->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
        } catch (Exception $th) {
            // Manejar la excepción
            // dd($th);
        }

        return redirect()->route('events.coordinator.index')->with('success', 'Estado de defensa actualizada.');
    }


}
