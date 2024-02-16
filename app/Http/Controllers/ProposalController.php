<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Mail\SendMail;
use App\Models\Application;
use App\Models\Cycle;
use App\Models\Entity;
use App\Models\EntityContact;
use App\Models\Group;
use App\Models\Notification;
use App\Models\Profile;
use App\Models\Project;
use App\Models\School;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserNotification;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ProposalController extends Controller
{
    const PERMISSIONS = [
        'index.advisers'    => 'Proposals.advisers',
        'index.students'    => 'Proposals.students',
        'index.applications.advisers' => 'Applications.advisers',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index.advisers'])->only(['index']);
        $this->middleware('permission:' . self::PERMISSIONS['index.students'])->only(['applicationIndex']);
        $this->middleware('permission:' . self::PERMISSIONS['index.applications.advisers'])->only(['applicationCoordinatorIndex']);

        $this->middleware('check.protocol')->only(['store']);
        $this->middleware('check.school')->only(['store']);
    }

    public function index()
    {
        $user = Auth::user();
        $proposals = Proposal::where('protocol_id', session('protocol')['id'])
            ->where('school_id', session('school', ['id']))
            ->get();

        return view('proposals.index', compact('proposals'));
    }

    public function create()
    {
        $entities = Entity::all();
        return view('proposals.create', ['entities' => $entities]);
    }

    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'description'           => 'required|string',
            'path'                  => 'required|mimes:pdf',
            'amount_student'        => 'required|integer',

        ]);

        if (session('protocol')['id'] == 2) {
            $request->validate(['entity_id'   =>  'required|integer']);
        }

        //dd($validatedData);
        $user = Auth::user();
        // Procesar y guardar el archivo
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('proposals');
            //dd($path);
        }

        try {
            $proposals = new Proposal;

            $proposals->name              = $request->input('name');
            $proposals->description       = $request->input('description');
            $proposals->path              = $path;
            $proposals->amount_student    = $request->input('amount_student');
            if (session('protocol')['id'] == 2) {
                $proposals->entity_id     = $request->input('entity_id');
            }
            $proposals->school_id         = session('school')['id'];
            $proposals->status            = 1;
            $proposals->user_id           = $user->id;
            $proposals->protocol_id       = session('protocol')['id'];
            $proposals->save();

            //dd($proposals);
            return redirect()->route('proposals.index')->with('success', 'Se añadió la propuesta correctamente.');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->route('proposals.index')->with('error', 'La propuesta no pudo ser añadida.');
        }
    }

    public function show(Proposal $proposal)
    {
        //dd($proposal);
        return view('proposals.show', compact('proposal'));
    }

    /*public function proposalUpdate(Request $request, Proposal $proposal)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'description'           => 'required|string',
            'path'                  => 'required|mimes:pdf',
            'amount_student'        => 'required|integer',
            'entity_id'             => 'requiered|integer',
            'user_id'               => 'requiered|integer'
        ]);

        $user = Auth::user();

        try {
            $proposal->update([
                'name'                  => $request['name'],
                'description'           => $request['description'],
                'path'                  => $request['path'],
                'amount_student'        => $request['amount_student'],
                'entity_id'             => $request['entity_id'],
                'status'                => $user->id,
                'user_id'               => $request['user_id'],
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }*/

    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        return redirect()->route('proposals.index')->with('success', 'Propuesta eliminada exitosamente.');
    }

    public function proposalDownload(Proposal $proposal, $file)
    {
        $filePath = storage_path('app/' . $proposal->$file);
        //dd($filePath);
        return response()->download($filePath);
    }

    public function applicationIndex()
    {
        $user = Auth::user();
        $proposals = Proposal::where('protocol_id', session('protocol')['id'])
            ->where('school_id', session('school', ['id']))
            ->get();

        return view('proposals.applications.index', compact('proposals'));
    }

    public function applicationCoordinatorIndex()
    {

        $proposals = Proposal::with('entity')->get();
        $applications = Application::join('proposals as p', 'applications.proposal_id', 'p.id')
            ->where('p.protocol_id', session('protocol')['id'])
            ->where('p.school_id', session('school')['id'])
            ->select('applications.user_id', 'applications.name', 'applications.status', 'applications.id', 'applications.proposal_id')
            ->with('user')
            ->with('proposal')
            ->get();

        if (session('protocol')['id'] == 2) {
            return view('proposals.applications.coordinator.index', compact(['proposals', 'applications']));
        } elseif (session('protocol')['id'] == 3) {
            return view('proposals.applications.investigation.coordinator.index', compact(['proposals', 'applications']));
        } else {
            abort(404, "No puede gestionar propuestas en el protocolo actual");
        }
    }
    public function applicationCreate(Proposal $proposal)
    {
        if (session('protocol')['id'] == 2) {
            return view('proposals.applications.create', compact('proposal'));
        } elseif (session('protocol')['id'] == 3) {
            return view('proposals.applications.investigation.create', compact('proposal'));
        } else {
            abort(404, "No puede gestionar propuestas en el protocolo actual");
        }
    }

    public function applicationStore(Request $request)
    {

        $user = Auth::user();
        // Validar si el usuario ya ha aplicado a esta propuesta

        if ($user->applications()->where('proposal_id', $request->proposal_id)->exists()) {
            return redirect()->route('proposals.applications.index')->with('error', 'Ya has aplicado a esta propuesta.');
        }
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'path'                  => 'required|mimes:pdf',
            'proposal_id'           => 'required|integer'
        ]);

        // Procesar y guardar el archivo
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('applications');
            //dd($path);
        }
        //Obtener el usuario logueado
        $user = Auth::user();
        //Crear un nueva aplicación
        $application = Application::create([
            'name'          => $validatedData['name'],
            'status'        => 0,
            'path'          => $path,
            'user_id'       => $user->id,
            'proposal_id'   => $validatedData['proposal_id'],
        ]);

        $proposal = Proposal::find($validatedData['proposal_id']);

        // Obtener la entidad asociada a la propuesta
        $entity = $proposal->entity;


        if ($entity) {
            // Obtener los correos electrónicos asociados a la entidad
            $contacts = $entity->entity_contacts;

            // Iterar sobre los contactos y enviar correos electrónicos

            foreach ($contacts as $contact) {

                try {
                    $emailData = [
                        'entity'      => $entity,
                        'proposal'    => $proposal,
                        'contact'     => $contact,
                        'application' => $application,
                        'name'        => $contact->name,
                    ];
                  ($emailData);
                    // Enviar correo electrónico al contacto
                    Mail::to($contact->email)->send(new SendMail('mail.application-entity-saved', 'Nueva aplicación recibida', $emailData));
                } catch (\Throwable $th) {

                    dd($th);
                    // Manejar errores si falla el envío del correo electrónico
                    // Puedes registrar el error o continuar con el próximo contacto
                    continue;
                }
            }


            //Envio de correo a coordinador.
            $role = 'Coordinador General';
            $userRoles = User::role($role)->get(); //modificar para diferenciar por modalidades.

            $notificationStudent = Notification::create(['title' => 'Alerta de aplicación', 'message' => "Se ha postulado correctamente ha esta propuesta, su CV está pendiente de revisión", 'user_id' => Auth::user()->id]);
            $notificationCoordinator = Notification::create(['title' => 'Alerta de aplicación', 'message' => "El estudiante ha enviado su CV para revisión",  'user_id' => $user->id]);
            foreach ($userRoles as $coordinator) {
                try {
                    $emailData = [
                        'user'       => $coordinator,
                        'proposal'      => $proposal,
                        'application'   => $application,
                    ];
                    //dd($emailData);

                    Mail::to($coordinator->email)->send(new SendMail('mail.application-coordinator-saved', 'Notificación de aplicación enviada', $emailData));
                    UserNotification::create(['user_id' => $coordinator->id, 'notification_id' => $notificationCoordinator->id, 'is_read' => 0]);
                } catch (\Throwable $th) {
                }
            }

            // Envío de correo electrónico a estudiante

            try {
                $emailData = [
                    'user' => $user,
                    'proposal'      => $proposal,
                    'application'   => $application,
                ];

                Mail::to($user->email)->send(new SendMail('mail.application-saved', 'Aplicación creada con éxito', $emailData));
                UserNotification::create(['user_id' => $user->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
            } catch (Exception $th) {
                // Manejar la excepción
            }

            return redirect()->route('proposals.applications.index', [$proposal->proposal_id])->with('success', 'Has aplicado correctamente a la pasantía.');
        }
    }

    //El coorinador acepta o rechaza CV
    public function coordinatorUpdate(Request $request, Application $application)
    {
        $validatedData = $request->validate([
            'decision' => 'required',
        ]);


        $user       = $application->user; //Obteniendo usuario que ha aplicado
        $school     = $user->school; //Obteniendo escuela a la que pertenece
        $protocol   = $user->protocol->first(); //obteniendo protocolo al que pertenece
        $cycle      = Cycle::where('status', 1)->first(); //Obteniendo ciclo activo
        $year       = date('Y');

        $application->status = $request->decision;
        if ($request->decision == 1) {

            $lastGroupNumber = Group::where('protocol_id', $protocol->id)
                ->where('cycle_id', $cycle->id)
                ->max('number');

            // Verificar si $lastGroupNumber es null y asignar el valor apropiado
            $nextGroupNumber = ($lastGroupNumber === null) ? 1 : ($lastGroupNumber + 1);

            $group                       = new Group();
            $group->number               = $nextGroupNumber;
            $group->year                 = $year;
            $group->status               = 1;
            $group->protocol_id          = $protocol->id;
            $group->cycle_id             = $cycle->id;
            $group->state_id             = 9;
            $group->save();


            $groupUser                       = new UserGroup();
            $groupUser->status               = 1;
            $groupUser->is_leader            = 1;
            $groupUser->user_id              = $user->id;
            $groupUser->group_id             = $group->id;

            $groupUser->save();



            //creandole el perfil internamente
            // $profile                        = new Profile();
            // $profile->name                  = "Protocolo PPP";
            // $profile->description           = "Protocolo PPP";
            // $profile->proposal_priority     = 1;
            // $profile->group_id              = $group->id;
            // $profile->status                = 1;
            // $profile->type                  = 1;
            // $profile->save();


            //creandole el proyecto internamente
            // $project                = new Project();
            // $project->name          =  "Pasantía profesional";
            // $project->group_id      = $group->id;
            // $project->profile_id    = $profile->id;
            // $project->save();
        }

        $application->update();
        // Envío de notificación y correo electrónico al estudiante
        $notificationStudent = Notification::create([
            'title' => 'Alerta de aplicación',
            'message' => "Se ha recibido una actualización sobre su aplicación a la pasantía profesional",
            'user_id' => $user->id,
        ]);

        try {
            $emailData = [
                'user' => $user,
                'application' => $application,
            ];

            //dd($emailData);

            Mail::to($user->email)->send(new SendMail('mail.application-updated', 'Aplicación actualizada con éxito', $emailData));
            UserNotification::create(['user_id' => $user->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
        } catch (Exception $th) {
            // Manejar la excepción
        }

        return redirect()->route('proposals.applications.coordinator.index')->with('success', 'Estado de aplicación actualizado.');
    }

    public function applicationCoordinatorShow(Application $application)
    {
        if (session('protocol')['id'] == 2) {
            return view('proposals.applications.coordinator.show', compact('application'));
        } elseif (session('protocol')['id'] == 3) {
            return view('proposals.applications.investigation.coordinator.show', compact('application'));
        } else {
            abort(404, "No puede gestionar propuestas en el protocolo actual");
        }
    }

    public function applicationDownload(Application $application, $file)
    {
        $filePath = storage_path('app/' . $application->$file);
        //dd($filePath);
        return response()->download($filePath);
    }
}
