<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Activity;
use App\Models\Group;
use App\Models\Notification;
use App\Models\Project;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserNotification;
use DateTime;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Termwind\Components\Dd;

class ActivityController extends Controller
{
    const PERMISSIONS = [
        'index'                => 'Activities.students',
        'index.adviser'       => 'Activities.advisers',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
        $this->middleware('permission:' . self::PERMISSIONS['index.adviser'])->only(['indexGroup']);
        $this->middleware('check.protocol')->only(['indexGroup']);
    }

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

        $project = Project::join('profiles as p', 'projects.profile_id', 'p.id')
            ->select('projects.id', 'projects.name', 'projects.approvement_report', 'projects.status')
            ->where('projects.group_id', $group->id)
            ->first();

        if (!isset($project)) return redirect()->route('home')->with('error', 'No tienes planificación aprobada y proyecto activo.');

        $activities = $group->activities;
        return view('activities.index', compact('activities'));
    }


    public function indexGroup()
    {
        // dd($project);
        $year = date('Y');
        $groups = Group::select('groups.id', 'groups.number', 'u.first_name', 'u.middle_name', 'u.last_name', 'u.second_last_name', 's.name')
            ->addSelect([
                'user_count' => UserGroup::selectRaw('COUNT(user_id)')
                    ->whereColumn('group_id', 'groups.id')
                    ->where('status', 1)
            ])
            ->with('activities')  // Cargar las actividades relacionadas
            ->join('user_group as ug', 'groups.id', 'ug.group_id')
            ->join('users as u', 'ug.user_id', 'u.id')
            ->join('states as s', 'groups.state_id', 's.id')
            ->where('groups.protocol_id', session('protocol')['id'])
            ->where('groups.year', $year)
            ->where('ug.is_leader', 1)
            ->paginate(30);


        return view('activities.coordinator.index-groups', compact('groups'));
    }

    public function indexCoordinator(Group $group)
    {

        // //obtener grupo actual del user logueado
        // $user = Auth::user();
        // // Obtiene el año actual
        // $year = date('Y');
        // // Realiza una consulta para verificar si el usuario está en un grupo del año actual

        // $group = Group::where('groups.year', $year)
        //     ->where('groups.status', 1)
        //     ->first();

        $activities = $group->activities;
        return view('activities.coordinator.index', compact('activities', 'group'));
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

        //Envio de correo a coordinador.
        $role = 'Coordinador General';
        $userRoles = User::role($role)->get(); //modificar para diferenciar por modalidades.
        $notificationCoordinator = Notification::create(['title' => 'Alerta de actividades', 'message' => "Han sido cargadas las actividades del estudiante", 'user_id' => Auth::user()->id]);
        $notificationStudent = Notification::create(['title' => 'Alerta de actividades', 'message' => "Las actividades han sido cargadas correctamente", 'user_id' => Auth::user()->id]);
        foreach ($userRoles as $coordinator) {
            try {
                $emailData = [
                    'user'       => $coordinator,
                    'group'      => $group,
                    'activity'   => $activity,
                ];
                //dd($emailData);

                Mail::to($coordinator->email)->send(new SendMail('mail.activity-coordinator-saved', 'Notificación de actividades enviada', $emailData));
                UserNotification::create(['user_id' => $coordinator->id, 'notification_id' => $notificationCoordinator->id, 'is_read' => 0]);
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
                    'user'       => $student,
                    'group'      => $group,
                    'activity'   => $activity,
                ];

                Mail::to($student->email)->send(new SendMail('mail.activity-saved', 'Actividades cargadas con éxito', $emailData));
                UserNotification::create(['user_id' => $student->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
            } catch (Exception $th) {
                // Manejar la excepción
            }
        }

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

            //Envio de correo a coordinador.
            $role = 'Coordinador General';
            $userRoles = User::role($role)->get(); //modificar para diferenciar por modalidades.
            $notificationCoordinator = Notification::create(['title' => 'Alerta de actividades', 'message' => "Han sido cargadas las actividades del estudiante", 'user_id' => Auth::user()->id]);
            $notificationStudent = Notification::create(['title' => 'Alerta de actividades', 'message' => "Las actividades han sido cargadas correctamente", 'user_id' => Auth::user()->id]);
            foreach ($userRoles as $coordinator) {
                try {
                    $emailData = [
                        'user'       => $coordinator,
                        'group'      => $group,
                        'data'       => $data,
                    ];
                    //dd($emailData);

                    Mail::to($coordinator->email)->send(new SendMail('mail.activity-coordinator-saved', 'Notificación de actividades enviada', $emailData));
                    UserNotification::create(['user_id' => $coordinator->id, 'notification_id' => $notificationCoordinator->id, 'is_read' => 0]);
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
                        'user'       => $student,
                        'group'      => $group,
                        'data'       => $data,
                    ];

                    Mail::to($student->email)->send(new SendMail('mail.activity-saved', 'Actividades cargadas con éxito', $emailData));
                    UserNotification::create(['user_id' => $student->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
                } catch (Exception $th) {
                    // Manejar la excepción
                }
            }

            return redirect()->route('activities.index')->with('success', 'Actividades cargadas exitosamente');
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->route('activities.index')
                ->with('error', 'Error, las actividades no pudieron cargarse.')
                ->withInput();
        }
    }

    public function modalStatus(Activity $activity)
    {
        return view('activities.modal.change_status', compact('activity'));
    }

    public function changeStatus(Request $request, Activity $activity)
    {

        // Validación de los datos de la actividad
        $validatedData = $request->validate([
            'status'        => 'required',
        ]);

        // Actualizar actividad
        $activity->update($validatedData);

        // Envío de correo electrónico a coordinador
        $role = 'Coordinador General';
        $userRoles = User::role($role)->get();
        $notificationStudent = Notification::create(['title' => 'Alerta de actividad', 'message' => "Te informamos que el estado de tu actividad se ha actualizado", 'user_id' => Auth::user()->id]);
        $notificationCoordinator = Notification::create(['title' => 'Alerta de actividad', 'message' => "Te informamos que el estudiante ha realizado una actualización en el estado de la actividad", 'user_id' => Auth::user()->id]);

        foreach ($userRoles as $coordinator) {
            $mailData = [
                'user' => $coordinator,
                'activity' => $activity,
                'status' => $activity->status,
            ];

            try {
                Mail::to($coordinator->email)->send(
                    new SendMail(
                        'mail.activity-updated-status',
                        'Actualización del estado de actividad',
                        $mailData
                    )
                );
                UserNotification::create(['user_id' => $coordinator->id, 'notification_id' => $notificationCoordinator->id, 'is_read' => 0]);
            } catch (\Throwable $th) {
                // Log de errores o manejo adicional
                // Log::error('Error al enviar correo electrónico: ' . $th->getMessage());
            }
        }


        // Envío de correo electrónico a cada estudiante del grupo
        $students = $activity->group->users;
        foreach ($students as $student) {
            $mailData = [
                'user' => $student,
                'activity' => $activity,
                'status' => $activity->status,
            ];

            try {
                Mail::to($student->email)->send(
                    new SendMail(
                        'mail.activity-updated-status-coordinator',
                        'Actualización del estado de actividad',
                        $mailData
                    )
                );
                UserNotification::create(['user_id' => $student->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
            } catch (\Throwable $th) {
                // Log de errores o manejo adicional
                // Log::error('Error al enviar correo electrónico: ' . $th->getMessage());
            }
        }

        return redirect()->route('activities.index')->with('success', 'Estado de la actividad cambiado con éxito');
    }
}
