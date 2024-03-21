<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Cycle;
use App\Models\Extension;
use App\Models\Project;
use App\Models\Protocol;
use App\Models\School;
use Illuminate\Http\Request;
use App\Models\Stage;
use App\Models\TypeExtension;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;
use App\Mail\SendMail;
use App\Models\Agreement;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            // 'type_extension_id'    => 'required|exists:type_extensions,id',
            'description'          => 'required|string|max:255'
        ]);


        try {
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

            // Obtener información adicional para el correo electrónico
            $project = Project::find($request['project_id']);

            // Obtener usuarios asignados al proyecto
            $recipients = $project->group->users;

            $notification = Notification::create(['title' => 'Alerta', 'message' => 'Nueva extensión creada', 'user_id' => Auth::user()->id]);
            // Envío de correo electrónico a cada destinatario
            foreach ($recipients as $recipient) {
                try {
                    $emailData = [
                        'user'      => $recipient,
                        'extension' => $extension,
                        'project'   => $project,
                    ];
                    Mail::to($recipient->email)->send(new SendMail('mail.extension-created', 'Nueva extensión creada', $emailData));

                    UserNotification::create(['user_id' => $recipient->id, 'notification_id' => $notification->id, 'is_read' => 0]);
                } catch (\Throwable $th) {
                    // Manejar la excepción
                }
            }

            return redirect()->route('extensions.index', $request['project_id'])->with('success', 'Prórroga creada exitosamente.');
        } catch (\Exception $e) {
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

            // Obtener información adicional para el correo electrónico
            $project = Project::find($request['project_id']);

            // Obtener usuarios asignados al proyecto
            $recipients = $project->group->users;

            $notification = Notification::create(['title' => 'Alerta', 'message' => 'Extensión actualizada. La extensión ' . $extension->description . ' ha sido actualizada.', 'user_id' => Auth::user()->id]);
            // Envío de correo electrónico a cada destinatario
            foreach ($recipients as $recipient) {
                try {
                    $emailData = [
                        'user'      => $recipient,
                        'extension' => $extension,
                        'project'   => $project,
                        'status'    => $extension->status,
                    ];
                    Mail::to($recipient->email)->send(new SendMail('mail.extension-updated', 'Extensión actualizada', $emailData));
                    UserNotification::create(['user_id' => $recipient->id, 'notification_id' => $notification->id, 'is_read' => 0]);
                } catch (\Throwable $th) {
                    // Manejar la excepción
                }
            }

            return redirect()->route('extensions.index', $request['project_id'])->with('success', 'Prórroga actualizada exitosamente.');
        } catch (\Exception $e) {
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

        // Filtrar retiros por protocolo y escuela
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

    //Coordinador aprueba o rechaza el retiro
    public function coordinatorUpdate(Request $request, Withdrawal $withdrawal)
    {
        $user       = $withdrawal->user; //Obteniendo usuario que ha presentado retiro

        $validatedData = $request->validate([
            'decision' => 'required',
        ]);

        $withdrawal->status = $request->decision;

        $withdrawal->update();

        // Cargar el modelo TypeWithdrawal correspondiente
        $typeWithdrawal = TypeWithdrawal::findOrFail($withdrawal->type_withdrawals_id);


        // Envío de notificación y correo electrónico al estudiante deñ estado del retiro
        $notificationStudent = Notification::create([
            'title' => 'Alerta de solicitud de retiro de trabajo de grado',
            'message' => "Se ha recibido una actualización sobre el estado de su solicitud de retiro de trabajo de grado",
            'user_id' => $user->id,
        ]);

        try {
            $emailData = [
                'user' => $user,
                'withdrawal' => $withdrawal,
                'name'        => $typeWithdrawal->name
            ];

            //dd($emailData);

            Mail::to($user->email)->send(new SendMail('mail.withdrawal-updated', 'Estado de solicitud de retiro de trabajo de grado actualizada con éxito', $emailData));
            UserNotification::create(['user_id' => $user->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
        } catch (Exception $th) {
            // Manejar la excepción
            // dd($th);
        }

        return redirect()->route('withdrawals.coordinator.index')->with('success', 'Estado de retiro actualizado.');
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
            return redirect()->back()->with('success', 'Prorroga aceptada.');
        } catch (\Throwable $th) {

            return redirect()->back()->with('error', 'Algo salió mal, intente nuevamente.');
        }
    }
}
