<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Cycle;
use App\Models\EvaluationStage;
use App\Models\EvaluationStageNote;
use App\Models\Group;
use App\Models\Notification;
use App\Models\User;
use App\Models\Observation;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Stage;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class ProfileController extends Controller
{

    const PERMISSIONS = [
        'index.student'    => 'Preprofiles.students',
        'index.adviser'    => 'Preprofiles.advisers',
        'index.student.profil'    => 'Profiles.students',
        'index.adviser.profil'    => 'Profiles.advisers',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index.student'])->only(['preProfileIndex']);
        $this->middleware('permission:' . self::PERMISSIONS['index.adviser'])->only(['preProfileCoordinatorIndex']);
        $this->middleware('permission:' . self::PERMISSIONS['index.student.profil'])->only(['profileIndex']);
        $this->middleware('permission:' . self::PERMISSIONS['index.adviser.profil'])->only(['coordinatorIndex']);
        $this->middleware('check.protocol')->only(['index', 'preProfileCoordinatorUpdate', 'preProfileCoordinatorIndex']);
        $this->middleware('check.school')->only(['index']);
    }

    /**
     *
     * Metodos para estudiantes creación y edicion de preperfiles
     */
    public function preProfileIndex()
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

        $preprofiles = Profile::join('groups as gr', 'profiles.group_id', 'gr.id')
            ->where('protocol_id', session('protocol')['id'])
            ->where('group_id', $group->id)
            ->where('type', 0)
            ->paginate(30);

        return view('preprofiles.index', compact('preprofiles'));
    }

    public function preProfileCreate()
    {
        //obtener grupo actual del user logueado
        return view('preprofiles.create', []);
    }

    public function preProfileStore(Request $request)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'description'           => 'required|string',
            'path'                  => 'required|mimes:pdf', // Esto valida que el archivo sea un PDF (puedes ajustar según tus necesidades)
            'summary_path'          => 'required|mimes:pdf',
            'vision_path'           => 'required|mimes:pdf',
            'size_calculation_path' => 'required|mimes:pdf',
            'proposal_priority'     => 'required|integer',
        ]);

        // Procesar y guardar el archivo
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('preprofiles'); // Define la carpeta de destino donde se guardará el archivo
        }

        if ($request->hasFile('summary_path')) {
            $summary_path = $request->file('summary_path')->store('preprofiles'); // Define la carpeta de destino donde se guardará el archivo
        }

        if ($request->hasFile('vision_path')) {
            $vision_path = $request->file('vision_path')->store('preprofiles'); // Define la carpeta de destino donde se guardará el archivo
        }

        if ($request->hasFile('size_calculation_path')) {
            $size_calculation_path = $request->file('size_calculation_path')->store('preprofiles'); // Define la carpeta de destino donde se guardará el archivo
        }

        $user = Auth::user();
        $year = date('Y');
        $group = Group::where('groups.year', $year)
            ->where('groups.status', 1)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->first();
        $validatedProposalPriority = Profile::where('group_id', $group->id)
            ->where('proposal_priority', $request->input('proposal_priority'))
            ->first();


        if (isset($validatedProposalPriority)) {
            return redirect()->back()
                ->withErrors(['message' => 'Ya posee un preperfil con el numero de prioridad asignado.'])
                ->withInput();
        }

        $protocols = $user->protocol()
            ->wherePivot('status', 1)
            ->pluck('name');

        // Crear un nuevo perfil
        $profile                        = new Profile;
        $profile->name                  = $request->input('name');
        $profile->description           = $request->input('description');
        $profile->path                  = $path; // Asigna el nombre del archivo (o null si no se cargó un archivo)
        $profile->summary_path          = $summary_path;
        $profile->vision_path           = $vision_path;
        $profile->size_calculation_path = $size_calculation_path;
        $profile->proposal_priority     = $request->input('proposal_priority');
        $profile->type                  = 0;
        $profile->group_id              = $group->id;
        $profile->status                = 0;
        $profile->save();

        //Envio de correo a coordinador.
        $role = 'Coordinador General';
        $userRoles = User::role($role)->get(); //modificar para diferenciar por modalidades.

        $type = "";
        switch (session('protocol')['id']) {
            case 1:
            case 4:
                $type = "pre-perfil";
                break;
            case 2:
            case 3:
            case 5:
                $type = "planificación";
                break;
            default:
                break;
        }


        $notification = Notification::create(['title' => "Alerta de $type", 'message' => "Su $type ha sido enviado, y está pendiente de revisión", 'user_id' => Auth::user()->id]);
        foreach ($userRoles as $coordinator) {
            try {
                $emailData = [
                    'user'          => $coordinator,
                    'group'         => $group,
                    'preprofile'    => $profile,
                    'type'          => $type,
                ];
                //dd($emailData);

                Mail::to($coordinator->email)->send(new SendMail('mail.preprofile-coordinator-saved', "Notificación de $type enviado", $emailData));
                UserNotification::create(['user_id' => $coordinator->id, 'notification_id' => $notification->id, 'is_read' => 0]);
            } catch (\Throwable $th) {
                // Manejar la excepción
            }
        }

        // Obtener estudiantes del grupo
        $students = $group->users;

        // Envío de correo electrónico a cada estudiante del grupo
        foreach ($students as $student) {
            try {
                $emailData = [
                    'user'          => $student,
                    'group'         => $group,
                    'preprofile'    => $profile,
                    'type'          => $type,
                ];
                Mail::to($student->email)->send(new SendMail('mail.preprofile-saved', "$type enviado con éxito", $emailData));
                UserNotification::create(['user_id' => $student->id, 'notification_id' => $notification->id, 'is_read' => 0]);
            } catch (Exception $th) {
                // Manejar la excepción
            }
        }
        return redirect()
            ->route('profiles.preprofile.index')
            ->with('success', "El $type se ha guardado correctamente")
            ->with('protocols', $protocols);
    }

    public function preProfileShow(Profile $preprofile)
    {
        return view('preprofiles.show', compact('preprofile'));
    }

    public function preProfileEdit(Profile $preprofile)
    {
        return view('preprofiles.edit', ['preprofile' => $preprofile]);
    }

    public function preProfileUpdate(Request $request, Profile $preprofile)
    {
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'description'           => 'required|string',
            'proposal_priority'     => 'required|integer',
            'path'                  => 'nullable|mimes:pdf', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
            'summary_path'          => 'nullable|mimes:pdf', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
            'vision_path'           => 'nullable|mimes:pdf', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
            'size_calculation_path' => 'nullable|mimes:pdf', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
        ]);
        try {

            $type = "";
            switch (session('protocol')['id']) {
                case 1:
                case 4:
                    $type = "pre-perfil";
                    break;
                case 2:
                case 3:
                case 5:
                    $type = "planificación";
                    break;
                default:
                    break;
            }
            // Actualizar los campos del perfil
            $preprofile->name               = $request->input('name');
            $preprofile->description        = $request->input('description');
            $preprofile->proposal_priority  = $request->input('proposal_priority');

            // Validación adicional para permitir actualizar la planificación solo si el protocolo es "examen"
            if ($request->hasFile('planning_path') && $preprofile->group->protocol->name == 'examen') {
                $planning_path = $request->file('planning_path')->store('preprofiles'); // Define la carpeta de destino donde se guardará el archivo
                $preprofile->planning_path = $planning_path;
                $preprofile->save();

                // Redireccionar a una vista específica para el protocolo "examen"
                return redirect()->route('plannings.index')->with('success', "El $type se ha actualizado correctamente"); //Ruta corregida
            }

            // Procesar y guardar el nuevo archivo si se proporciona
            if ($request->hasFile('path')) {
                $path = $request->file('path')->store('preprofiles');
                $preprofile->path = $path;
            }
            if ($request->hasFile('summary_path')) {
                $summary_path = $request->file('summary_path')->store('preprofiles');
                $preprofile->summary_path = $summary_path;
            }
            if ($request->hasFile('vision_path')) {
                $vision_path = $request->file('vision_path')->store('preprofiles');
                $preprofile->vision_path = $vision_path;
            }
            if ($request->hasFile('size_calculation_path')) {
                $size_calculation_path = $request->file('size_calculation_path')->store('preprofiles');
                $preprofile->size_calculation_path = $size_calculation_path;
            }
            $preprofile->update();

            // Envío de correo electrónico a cada estudiante del grupo
            $students = $preprofile->group->users;


            $notification = Notification::create(['title' => "Alerta de $type", 'message' => "Te informamos que tu $type se ha actualizado.", 'user_id' => Auth::user()->id]);

            foreach ($students as $student) {
                $mailData = [
                    'user'          => $student,
                    'preprofile'    => $preprofile,
                    'type'          => $type
                ];

                try {
                    Mail::to($student->email)->send(
                        new SendMail(
                            'mail.preprofile-updated',
                            "Actualización de $type",
                            $mailData
                        )
                    );
                    UserNotification::create(['user_id' => $student->id, 'notification_id' => $notification->id, 'is_read' => 0]);
                } catch (\Throwable $th) {
                    // Log de errores o manejo adicional
                    //Log::error('Error al enviar correo electrónico: ' . $th->getMessage());
                }
            }

            return redirect()->route('profiles.preprofile.index')->with('success', "El $type se ha actualizado correctamente");
        } catch (\Throwable $th) {
            // Log de errores o manejo adicional
            //Log::error('Error al actualizar el preperfil: ' . $th->getMessage());
            return redirect()->route('profiles.preprofile.index')->with('error', "Hubo un error al actualizar el $type. Por favor, inténtelo de nuevo.");
        }
    }

    public function preProfileDestroy(Profile $preprofile)
    {
        $preprofile->delete();

        return redirect()->route('profiles.preprofile.index')->with('success', 'Preperfil eliminado correctamente.');
    }

    public function preProfileDownload(Profile $preprofile, $file)
    {

        $filePath = storage_path('app/' . $preprofile->$file);
        return response()->download($filePath);
    }

    /**
     *
     * Metodos para coordinadores revision, cambio de estado y generacion de obseraciones de preperfiles
     */
    public function preProfileCoordinatorIndex()
    {
        //obtener grupo actual del user logueado
        $user = Auth::user();
        // Obtiene el año actual
        $year = date('Y');
        // Realiza una consulta para verificar si el usuario está en un grupo del año actual
        $protocolsWithStatus = $user->protocols()->wherePivot('status', 1)->first();
        $groups = Group::where('groups.year', $year)
            ->where('groups.status', 1)
            ->where('protocol_id', session('protocol')['id'])
            ->get(['id']);

        if (session('protocol')['id'] == 1) {
            $preprofiles = Profile::join('groups as g', 'g.id', 'profiles.group_id')
                ->whereIn('group_id', $groups)
                ->where('type', 0)
                ->select('profiles.status', 'profiles.name', 'profiles.description', 'profiles.created_at', 'g.number', 'profiles.id')
                ->paginate(10);
        } else {
            $preprofiles = Profile::join('groups as g', 'g.id', 'profiles.group_id')
                ->whereIn('group_id', $groups)
                ->select('profiles.status', 'profiles.name', 'profiles.description', 'profiles.created_at', 'g.number', 'profiles.id')
                ->paginate(10);
        }


        $protocols = $user->protocol()
            ->wherePivot('status', 1)
            ->pluck('protocols.id');



        return view('preprofiles.coordinator.index', compact('preprofiles', 'protocols'));
    }

    public function preProfileCoordinatorShow(Profile $preprofile)
    {
        //obtener grupo actual del user logueado
        $user = Auth::user();

        $protocols = $user->protocol()
            ->wherePivot('status', 1)
            ->pluck('protocols.id');

        return view('preprofiles.coordinator.show', compact('preprofile', 'protocols'));
    }

    public function preProfileCoordinatorUpdate(Request $request, Profile $preprofile)
    {
        $validatedData = $request->validate([
            'decision' => 'required', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
        ]);

        $preprofile->status = $request->decision;

        switch (session('protocol')['id']) {
            case 1:
            case 4:
                if ($request->decision == 1) {
                    //creare a partir del preperfil un perfil
                    $profile                        = new Profile();
                    $profile->name                  = $preprofile->name;
                    $profile->description           = $preprofile->description;
                    $profile->group_id              = $preprofile->group_id;
                    $profile->proposal_priority     = $preprofile->proposal_priority;
                    $profile->path                  = $preprofile->path;
                    $profile->vision_path           = $preprofile->vision_path;
                    $profile->summary_path          = $preprofile->summary_path;
                    $profile->size_calculation_path = $preprofile->size_calculation_path;
                    $profile->profile_id            = $preprofile->id;
                    $profile->status                = 0;
                    $profile->type                  = 1;
                    $profile->save();
                }
                break;
            case 2:
            case 3:
            case 5:
                if ($request->decision == 1) {
                    $validatedData = $request->validate([
                        'note' => 'required|numeric|min:0|max:10',
                    ]);



                    $preprofile->type = 1;

                    $project                = new Project();
                    $project->name          = $preprofile->name;
                    $project->group_id      = $preprofile->group_id;
                    $project->profile_id    = $preprofile->id;
                    $project->save();

                    $cycle  = Cycle::where('status', 1)->first();
                    $stage  = Stage::where('protocol_id', session('protocol')['id'])
                        ->where('school_id', session('school')['id'])
                        ->where('cycle_id', $cycle->id)
                        ->where('category', 2)
                        ->first();

                    //primero creare el evaluation_stages
                    $evaluationStage                = new EvaluationStage();
                    $evaluationStage->date          = Carbon::now();
                    $evaluationStage->project_id    = $project->id;
                    $evaluationStage->stage_id      = $stage->id;
                    $evaluationStage->status        = 1;
                    $evaluationStage->save();

                    $user = User::join('user_group as ug', 'ug.user_id', 'users.id')
                        ->join('groups as g', 'ug.group_id', 'g.id')
                        ->where('g.id', $preprofile->group_id)
                        ->select('users.id')
                        ->first();
                    $evaluationStageNote                        = new EvaluationStageNote();
                    $evaluationStageNote->evaluation_stage_id   = $evaluationStage->id;
                    $evaluationStageNote->user_id               = $user->id;
                    $evaluationStageNote->note                  = $request->note;
                    $evaluationStageNote->save();
                }

                break;
            default:
                break;
        }

        $preprofile->update();


        // Obtener estudiantes del grupo
        $students = $preprofile->group->users;

        $type = "";
        switch (session('protocol')['id']) {
            case 1:
            case 4:
                $type = "pre-perfil";
                break;
            case 2:
            case 3:
            case 5:
                $type = "planificación";
                break;
            default:
                break;
        }

        $notification = Notification::create(['title' => "Alerta de $type", 'message' => "Te informamos que tu $type se ha actualizado", 'user_id' => Auth::user()->id]);

        // Envío de correo electrónico a cada estudiante del grupo
        foreach ($students as $student) {
            $mailData = [
                'user'          => $student,
                'preprofile'    => $preprofile,
                'status'        => $preprofile->status,
                'type'          => $type,
            ];
            try {
                Mail::to($student->email)->send(
                    new SendMail(
                        'mail.preprofile-updated',
                        "Notificación de modificación de $type",
                        $mailData
                    )
                );
                UserNotification::create(['user_id' => $student->id, 'notification_id' => $notification->id, 'is_read' => 0]);
            } catch (\Throwable $th) {
                // Working..
                // dd($th);
            }
        }

        return redirect()->back()->with('success', 'Registro actualizado');
    }

    public function preProfileCoordinatorObservationsList(Profile $preprofile)
    {
        //obtener grupo actual del user logueado
        $user = Auth::user();

        $protocols = $user->protocol()
            ->wherePivot('status', 1)
            ->pluck('protocols.id');

        return view('preprofiles.coordinator.observations', ['preprofile' => $preprofile, 'protocols' => $protocols]);
    }

    public function preProfileCoordinatorObservationCreate(Profile $preprofile)
    {
        //obtener grupo actual del user logueado
        $user = Auth::user();

        $protocols = $user->protocol()
            ->wherePivot('status', 1)
            ->pluck('protocols.id');

        //dd($preprofile);

        return view('preprofiles.coordinator.create', ['preprofile' => $preprofile, 'protocols' => $protocols]);
    }

    public function preProfileCoordinatorObservationStore(Request $request)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'description' => 'required|string',
            'profile_id' => 'required', // Esto valida que el archivo sea un PDF (puedes ajustar según tus necesidades)
        ]);

        // Crear un nueva observación
        $observation                = new Observation();
        $observation->description   = $request->description;
        $observation->profile_id    = $request->profile_id;
        $observation->save();

        //Aqui debo enviar notificacion a estudiantes
        // Obtener estudiantes del grupo
        $profile                = Profile::find($request->profile_id);
        $students               = $profile->group->users;
        $notificationStudent    = Notification::create(['title' => 'Alerta de observación en perfil o planificación', 'message' => "Su planificación o perfil ha recibido una observación", 'user_id' => Auth::user()->id]);

        // Envío de correo electrónico a cada estudiante del grupo
        foreach ($students as $student) {
            try {
                $emailData = [
                    'user'      => $student,
                    'profile'   => $profile,
                ];

                Mail::to($student->email)->send(new SendMail('mail.observation', 'Observación en perfil o planificación', $emailData));
                UserNotification::create(['user_id' => $student->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
            } catch (Exception $th) {
                Log::info($th->getMessage());
            }
        }

        return redirect()->route('profiles.preprofile.coordinator.observation.list', [$request->profile_id])->with('success', 'La observación se ha guardado correctamente');
    }

    /**
     *
     * Metodos para estudiantes creación y edicion de preperfiles
     */
    public function profileIndex()
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

        if (!isset($group)) return redirect()->route('root')->with('error', 'No tienes un grupo activo.');

        $profiles = Profile::where('group_id', $group->id)
            ->where('type', 1)
            ->paginate(30);

        return view('profiles.index', compact('profiles'));
    }

    public function profileShow(Profile $profile)
    {
        return view('profiles.show', compact('profile'));
    }

    public function profileEdit(Profile $profile)
    {
        return view('profiles.edit', ['profile' => $profile]);
    }

    public function profileUpdate(Request $request, Profile $profiles)
    {
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'description'           => 'required|string',
            'path'                  => 'nullable|mimes:pdf', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
            'summary_path'          => 'nullable|mimes:pdf', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
            'vision_path'           => 'nullable|mimes:pdf', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
            'size_calculation_path' => 'nullable|mimes:pdf', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
        ]);

        try {
            $type = "";
            switch (session('protocol')['id']) {
                case 1:
                case 4:
                    $type = "perfil";
                    break;
                case 2:
                case 3:
                case 5:
                    $type = "planificación";
                    break;
                default:
                    break;
            }

            // Actualizar los campos del perfil
            $profiles->name = $request->input('name');
            $profiles->description = $request->input('description');

            // Procesar y guardar el nuevo archivo si se proporciona
            if ($request->hasFile('path')) {
                $path = $request->file('path')->store('preprofiles');
                $profiles->path = $path;
            }
            if ($request->hasFile('summary_path')) {
                $summary_path = $request->file('summary_path')->store('preprofiles');
                $profiles->summary_path = $summary_path;
            }
            if ($request->hasFile('vision_path')) {
                $vision_path = $request->file('vision_path')->store('preprofiles');
                $profiles->vision_path = $vision_path;
            }
            if ($request->hasFile('size_calculation_path')) {
                $size_calculation_path = $request->file('size_calculation_path')->store('preprofiles');
                $profiles->size_calculation_path = $size_calculation_path;
            }

            $profiles->update();

            $notification = Notification::create(['title' => "Alerta de $type", 'message' => "Te informamos que tu $type se ha actualizado", 'user_id' => Auth::user()->id]);
            // Envío de correo electrónico a cada estudiante del grupo
            $students = $profiles->group->users;
            foreach ($students as $student) {
                $mailData = [
                    'user'      => $student,
                    'profile'   => $profiles,
                    'status'    => $profiles->status,
                    'type'      => $type,
                ];

                try {
                    Mail::to($student->email)->send(
                        new SendMail(
                            'mail.profile-updated',
                            "Actualización de $type",
                            $mailData
                        )
                    );
                    UserNotification::create(['user_id' => $student->id, 'notification_id' => $notification->id, 'is_read' => 0]);
                } catch (\Throwable $th) {
                    // Log de errores o manejo adicional
                    // Log::error('Error al enviar correo electrónico: ' . $th->getMessage());
                }
            }

            return redirect()->route('profiles.index')->with('success', 'El perfil se ha actualizado correctamente');
        } catch (\Throwable $th) {
            // Log de errores o manejo adicional
            // Log::error('Error al actualizar el perfil: ' . $th->getMessage());
            return redirect()->route('profiles.index')->with('error', 'Hubo un error al actualizar el perfil. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     *
     * Metodos para coordinadores revision, cambio de estado y generacion de obseraciones de perfiles
     */
    public function coordinatorIndex()
    {
        //obtener grupo actual del user logueado
        $user = Auth::user();
        // Obtiene el año actual
        $year = date('Y');
        // Realiza una consulta para verificar si el usuario está en un grupo del año actual
        $protocolsWithStatus = $user->protocols()->wherePivot('status', 1)->first();
        $groups = Group::where('groups.year', $year)
            ->where('groups.status', 1)
            ->where('protocol_id', session('protocol')['id'])
            ->get(['id']);

        $preprofiles = Profile::join('groups as g', 'g.id', 'profiles.group_id')
            ->whereIn('group_id', $groups)
            ->where('type', 1)
            ->select('profiles.status', 'profiles.name', 'profiles.description', 'profiles.created_at', 'g.number', 'profiles.id')
            ->paginate(10);


        return view('profiles.coordinator.index', compact('preprofiles'));
    }

    public function CoordinatorShow(Profile $profile)
    {
        return view('profiles.coordinator.show', compact('profile'));
    }

    public function CoordinatorUpdate(Request $request, Profile $profile)
    {
        $validatedData = $request->validate([
            'decision' => 'required', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
        ]);

        $profile->status = $request->decision;
        if ($request->decision == 1) {
            //creare a partir del perfil el proyecto
            $project                = new Project();
            $project->name          = $profile->name;
            $project->group_id      = $profile->group_id;
            $project->profile_id    = $profile->profile_id;
            $project->save();
        }
        $profile->update();

        // Obtener estudiantes del grupo
        $students = $profile->group->users;

        $notification = Notification::create(['title' => 'Alerta de perfil', 'message' => "Te informamos que tu perfil se ha actualizado", 'user_id' => Auth::user()->id]);
        // Envío de correo electrónico a cada estudiante del grupo
        foreach ($students as $student) {
            $mailData = [
                'user'          => $student,
                'preprofile'    => $profile,
                'status'        => $profile->status,
            ];
            try {
                Mail::to($student->email)->send(
                    new SendMail(
                        'mail.profile-updated',
                        'Notificación de modificación de perfil',
                        $mailData
                    )
                );
                UserNotification::create(['user_id' => $student->id, 'notification_id' => $notification->id, 'is_read' => 0]);
            } catch (\Throwable $th) {
                //dd($th);
            }
        }

        return view('profiles.coordinator.show', compact('profile'));
    }

    public function coordinatorObservationsList(Profile $profile)
    {
        return view('profiles.coordinator.observations', ['profile' => $profile]);
    }

    public function coordinatorObservationCreate(Profile $profile)
    {
        return view('profiles.coordinator.create', ['profile' => $profile]);
    }

    public function coordinatorObservationStore(Request $request)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'description' => 'required|string',
            'profile_id' => 'required',
        ]);

        // Crear un nueva observación
        $observation                = new Observation();
        $observation->description   = $request->description;
        $observation->profile_id    = $request->profile_id;
        $observation->save();

        //Aqui debo enviar notificacion a estudiantes
        // Obtener estudiantes del grupo
        $profile                = Profile::find($request->profile_id);
        $students               = $profile->group->users;
        $notificationStudent    = Notification::create(['title' => 'Alerta de observación en perfil o planificación', 'message' => "Su planificación o perfil ha recibido una observación", 'user_id' => Auth::user()->id]);

        // Envío de correo electrónico a cada estudiante del grupo
        foreach ($students as $student) {
            try {
                $emailData = [
                    'user'      => $student,
                    'profile'   => $profile,
                ];

                Mail::to($student->email)->send(new SendMail('mail.observation', 'Observación en perfil o planificación', $emailData));
                UserNotification::create(['user_id' => $student->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
            } catch (Exception $th) {
                Log::info($th->getMessage());
            }
        }

        return redirect()->route('profiles.coordinator.observation.list', [$request->profile_id])->with('success', 'La observación se ha guardado correctamente');
    }
}
