<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\TypeExtension;
use App\Models\Agreement;
use App\Models\Notification;
use App\Models\Extension;
use App\Models\Project;
use App\Models\User;
use App\Models\UserNotification;
use App\Mail\SendMail;
use Exception;

class ExtensionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Project $project)
    {

        $extensions = [];
        $extensions = Extension::get();

        // Creando una instancia del controlador Project
        $projectController = new ProjectController();

        //Llamando a la funcion disableProject

        $status = $projectController->disableProject($project);
        //dd($status);

        return view('extension.index', compact('extensions', 'project', 'status'));
    }

    public function create(Project $project)
    {
        $conteo = Extension::where('project_id', $project->id)
            ->where('status', 0)->count();
        if ($conteo >= 1) {
            return redirect()->back()->with('error', 'Ya posee una prorroga presentada y sin resolución.');
        }
        $type_extensions = TypeExtension::all();

        return view('extension.create')->with(compact('project', 'type_extensions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id'          => 'required|exists:projects,id',
            'description'          => 'required|string|max:255'
        ]);


        try {
            $user = Auth::user();
            $conteo = Extension::where('project_id', $request['project_id'])
                ->where('status', 0)->count();
            if ($conteo >= 1) {
                return redirect()->back()->with('error', 'Ya posee una prorroga presentada y sin resolución.');
            }

            if ($request->hasFile('extension_request_path')) {
                $extension_request_path = $request->file('extension_request_path')->store('extensions'); // Define la carpeta de destino donde se guardará el archivo
            }
            if ($request->hasFile('schedule_activities_path')) {
                $schedule_activities_path = $request->file('schedule_activities_path')->store('extensions'); // Define la carpeta de destino donde se guardará el archivo
            }
            if ($request->hasFile('approval_letter_path')) {
                $approval_letter_path = $request->file('approval_letter_path')->store('extensions'); // Define la carpeta de destino donde se guardará el archivo
            }
            //buscare las extensiones aprobadas del grupo y agregare una mas al conteo que llevan de aprobadas.
            $conteo = Extension::where('project_id', $request['project_id'])
                ->where('status', 1)->count();
            $conteo = $conteo + 1;

            $extension = Extension::create([
                'project_id'                => $request['project_id'],
                'type_extension_id'         => $conteo,
                'description'               => $request['description'],
                'status'                    => 0,
                'extension_request_path'    => $extension_request_path,
                'schedule_activities_path'  => $schedule_activities_path,
                'approval_letter_path'      => $approval_letter_path,
            ]);

            //Envio de correo a coordinador.
            $typeExtension = TypeExtension::findOrFail($extension->type_extension_id);

            // Obtener información adicional para el correo electrónico
            $project = Project::find($request['project_id']);
            $role = 'Coordinador General';
            $userRoles = User::role($role)->get(); //modificar para diferenciar por modalidades.
            $notificationCoordinator = Notification::create(['title' => 'Alerta de prorroga de trabajo de grado', 'message' => "El estudiante ha enviado su solicitud de prorroga de trabajo de grado para revisión",  'user_id' => $user->id]);
            foreach ($userRoles as $coordinator) {
                try {
                    $emailData = [
                        'user'         => $coordinator,
                        'extension'    => $extension,
                        'name'         => $typeExtension->name,
                        'group'        => $project->group['number']
                    ];

                    Mail::to($coordinator->email)->send(new SendMail('mail.extension-coordinator-saved', 'Notificación de prorroga de trabajo de grado presentado', $emailData));
                    UserNotification::create(['user_id' => $coordinator->id, 'notification_id' => $notificationCoordinator->id, 'is_read' => 0]);
                } catch (Exception $th) {
                    Log::info($th);
                }
            }

            // Obtener usuarios asignados al proyecto
            $recipients = $project->group->users;

            $notification = Notification::create(['title' => 'Alerta', 'message' => 'Nueva prorroga creada', 'user_id' => Auth::user()->id]);
            // Envío de correo electrónico a cada destinatario
            foreach ($recipients as $recipient) {
                try {
                    $emailData = [
                        'user'      => $recipient,
                        'extension' => $extension,
                        'project'   => $project,
                    ];
                    Mail::to($recipient->email)->send(new SendMail('mail.extension-created', 'Nueva prorroga creada', $emailData));

                    UserNotification::create(['user_id' => $recipient->id, 'notification_id' => $notification->id, 'is_read' => 0]);
                } catch (Exception $th) {
                    // Manejar la excepción
                }
            }

            return redirect()->route('extensions.index', $request['project_id'])->with('success', 'Prórroga creada exitosamente.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }

    public function edit(Extension $extension, Project $project)
    {
        $conteo = Extension::where('id', $extension->id)
            ->whereIn('status', [1, 2])->count();
        if ($conteo >= 1) {
            return redirect()->back()->with('error', 'La prorroga ya posee resolución.');
        }

        $type_extensions = TypeExtension::all();

        return view('extension.edit')->with(compact('extension', 'project', 'type_extensions'));
    }

    public function update(Request $request, Extension $extension)
    {
        $conteo = Extension::where('id', $extension->id)
            ->whereIn('status', [1, 2])->count();
        if ($conteo >= 1) {
            return redirect()->back()->with('error', 'La prorroga ya posee resolución.');
        }

        $data = $request->validate([
            'project_id'          => 'required|exists:projects,id',
            'type_extension_id'    => 'required|exists:type_extensions,id',
            'description'          => 'required|string|max:255',
            'status'      => 'required|integer|min:0|max:2',
        ]);

        try {
            $fields = [
                'project_id'          => $request['project_id'],
                'type_extension_id'   => $request['type_extension_id'],
                'description'      => $request['description'],
                'status'     => $request['status'],
            ];

            if ($request->hasFile('extension_request_path')) {
                if (is_file(storage_path('app/' . $extension->extension_request_path))) {
                    Storage::delete($extension->extension_request_path);
                }
                $fields['extension_request_path'] = $request->file('extension_request_path')->store('extensions');
            }
            if ($request->hasFile('schedule_activities_path')) {
                if (is_file(storage_path('app/' . $extension->schedule_activities_path))) {
                    Storage::delete($extension->schedule_activities_path);
                }
                $fields['schedule_activities_path'] = $request->file('schedule_activities_path')->store('extensions');
            }
            if ($request->hasFile('approval_letter_path')) {
                if (is_file(storage_path('app/' . $extension->approval_letter_path))) {
                    Storage::delete($extension->approval_letter_path);
                }
                $fields['approval_letter_path'] = $request->file('approval_letter_path')->store('extensions');
            }

            $extension->update($fields);

            return redirect()->route('extensions.index', $request['project_id'])->with('success', 'Prórroga actualizada exitosamente.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }
    public function destroy(Extension $extension)
    {
        //
    }

    /**
     *
     * Coordinación
     */

    public function coordinatorIndex()
    {
        // Obtener el coordinador en sesión
        $coordinator = Auth::user();

        // Filtrar prorrogas por protocolo y escuela
        $extensions = Extension::join('type_extensions as te', 'te.id', 'extensions.id')
            ->join('projects as p', 'extensions.project_id', 'p.id')
            ->join('groups as  g', 'p.group_id', 'g.id')
            ->join('user_group as ug', 'ug.group_id', 'g.id')
            ->join('users as u', 'u.id', 'ug.user_id')
            ->where('u.school_id', session('school')['id'])
            ->where('g.protocol_id', session('protocol')['id'])
            ->select(
                'g.id',
                'p.name',
                'extensions.description',
                'extensions.status',
                'extensions.id as id_extension',
                'te.name as name_type',
            )
            ->distinct('g.id')
            ->get();

        // dd($extensions);
        return view('extension.coordinator.index', compact('extensions'));
    }

    public function coordinatorShow(Extension $extension)
    {

        $type_extensions = TypeExtension::all();
        $project = Project::find($extension->project_id);

        return view('extension.coordinator.show')->with(compact('extension', 'project'));
    }

    //Coordinador aprueba o rechaza el prorroga
    public function coordinatorUpdate(Request $request, Extension $extension)
    {

        $validatedData = $request->validate([
            'decision' => 'required',
        ]);

        $extension->status = $request->decision;

        $extension->update();

        // Cargar el modelo typeExtension correspondiente
        $typeExtension = TypeExtension::findOrFail($extension->type_extension_id);


        $users = User::join('user_group as ug', 'ug.user_id', 'users.id')
            ->join('projects as p',  'p.group_id', 'ug.group_id')
            ->where('p.id', $extension->project_id)
            ->select('users.id', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.second_last_name', 'users.email')
            ->get();

        // Envío de notificación y correo electrónico al estudiante deñ estado del prorroga
        foreach ($users as  $student) {
            $notificationStudent = Notification::create([
                'title'     => 'Alerta de solicitud de prorroga de trabajo de grado',
                'message'   => "Se ha recibido una actualización sobre el estado de su solicitud de prorroga de trabajo de grado",
                'user_id'   => $student->id,
            ]);

            try {
                $emailData = [
                    'user'          => $student,
                    'extension'     => $extension,
                    'name'          => $typeExtension->name
                ];

                //dd($emailData);

                Mail::to($student->email)->send(new SendMail('mail.extension-updated', 'Estado de solicitud de prorroga de trabajo de grado actualizada con éxito', $emailData));
                UserNotification::create(['user_id' => $student->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
            } catch (Exception $th) {
                // Manejar la excepción
                // dd($th);
                Log::info($th);
            }
        }


        return redirect()->route('extensions.coordinator.index')->with('success', 'Estado de prorroga actualizado.');
    }

    public function modalApprovement(Request $request)
    {
        return view('extension.modal.attach_approvement', ['extension_id' => $request->extension_id]);
    }

    public function storeApprovement(Request $request)
    {
        try {

            $extension = Extension::find($request->extension_id);
            $extension->status = 1;
            $extension->update();

            $project = Project::find($extension->project_id);
            //Insertare el acuerdo del estudiante
            $agreement                     = new Agreement();
            $agreement->number             = $request->number_agreement;
            $agreement->approval_date      = $request->date_agreement;
            $agreement->description        = $request->description;
            $agreement->group_id            = $project->group_id;
            $agreement->user_load_id       = auth()->user()->id;
            $agreement->type_agreement_id  = 4;
            $agreement->save();

            $typeExtension = TypeExtension::findOrFail($extension->type_extension_id);
            $users = User::join('user_group as ug', 'ug.user_id', 'users.id')
                ->join('projects as p',  'p.group_id', 'ug.group_id')
                ->where('p.id', $extension->project_id)
                ->select('users.id', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.second_last_name', 'users.email')
                ->get();

            // Envío de notificación y correo electrónico al estudiante deñ estado del prorroga
            foreach ($users as  $student) {
                $notificationStudent = Notification::create([
                    'title'     => 'Alerta de solicitud de prorroga de trabajo de grado',
                    'message'   => "Se ha recibido una actualización sobre el estado de su solicitud de prorroga de trabajo de grado",
                    'user_id'   => $student->id,
                ]);

                try {
                    $emailData = [
                        'user'          => $student,
                        'extension'     => $extension,
                        'name'          => $typeExtension->name
                    ];

                    Mail::to($student->email)->send(new SendMail('mail.extension-updated', 'Estado de solicitud de prorroga de trabajo de grado actualizada con éxito', $emailData));
                    UserNotification::create(['user_id' => $student->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
                } catch (Exception $th) {
                    Log::info($th);
                }
            }

            return redirect()->back()->with('success', 'Prorroga aceptada.');
        } catch (Exception $th) {

            return redirect()->back()->with('error', 'Algo salió mal, intente nuevamente.');
        }
    }
}
