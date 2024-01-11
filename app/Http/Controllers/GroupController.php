<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Cycle;
use App\Models\Group;
use App\Models\Notification;
use App\Models\Parameter;
use App\Models\Protocol;
use App\Models\TeacherGroup;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserNotification;
use App\Models\Project;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Exp;

class GroupController extends Controller
{
    const PERMISSIONS = [
        'index'                => 'Groups.students',
        'index.adviser'        => 'Groups.advisers',
        'assigned.group'       => 'Assigned.groups',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['initialize']);
        $this->middleware('permission:' . self::PERMISSIONS['index.adviser'])->only(['index']);
        $this->middleware('permission:' . self::PERMISSIONS['assigned.group'])->only(['assignedGroup']);

        $this->middleware('check.protocol')->only(['index','initialize']);
        $this->middleware('check.school')->only(['index','initialize']);
    }
    public function index(Project $project)
    {
        // dd($project);
        $year = date('Y');
        $groups = Group::select(
            'groups.id',
            'groups.number',
            'u.first_name',
            'u.middle_name',
            'u.last_name',
            'u.second_last_name',
            's.name'
        )
            ->addSelect([
                'user_count' => UserGroup::selectRaw('COUNT(user_id)')
                    ->whereColumn('group_id', 'groups.id')
                    ->where('status', 1)
            ])
            ->leftJoin('user_group as ug', 'groups.id', '=', 'ug.group_id')
            ->join('users as u', 'ug.user_id', '=', 'u.id')
            ->join('states as s', 'groups.state_id', '=', 's.id')
            ->where('groups.year', $year)
            ->where('ug.is_leader', 1)
            // ->where('groups.status', 1)
            ->where('groups.protocol_id', session('protocol')['id'])
            ->paginate(20);

        $user = Auth::user();
        $protocols = $user->protocol()->wherePivot('status', 1)->pluck('protocols.id');


        return view('groups.index', compact('groups', 'project', 'protocols'));
    }



    /**
     * Vista para inicializar grupo.
     *
     * @return \Illuminate\Http\Response
     */
    public function initialize()
    {
        $user = Auth::user();
        // Obtiene el año actual
        $year = date('Y');
        // Realiza una consulta para verificar si el usuario está en un grupo del año actual
        $group = Group::where('year', $year)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->first();
        $groupUsers = array();

        $protocols = $user->protocol()
            ->wherePivot('status', 1)
            ->pluck('protocols.id');

        //dd($protocols);

        if ($group) {
            // Obtener la información de los usuarios relacionados al grupo
            $groupUsers = $group->users;
            foreach ($groupUsers as $item) {
                if ($item->id === $user->id) {
                    if ($item->pivot->is_leader === 1) {
                        return view('groups.initialize', compact('user', 'group', 'groupUsers', 'protocols'));
                    } else {
                        return view('groups.confirm', compact('user', 'group', 'groupUsers', 'protocols'));
                    }
                }
            }
        }

        //vere si el usuario tiene un grupo
        return view('groups.initialize', compact('user', 'group', 'groupUsers', 'protocols'));
    }

    public function create()
    {
        $parameterNames = Parameter::PARAMETERS;
        return view('groups.create', compact('parameterNames'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'users'    => 'array', // Campo que contendrá los parámetros
        ]);
        try {
            //revisare si viene algo en el request de ciclo
            if (isset($request->group_id)) {
                //voy a buscar grupo para actualizarlo
                $group = Group::find($request->group_id);
            } else {
                //recuperando el protocolo del estudiante que inicializar el grupo
                $user = Auth::user();
                $protocol = $user->protocols()
                    ->where('user_protocol.status', true)
                    ->first();
                // dd($protocol->id);
                $cycle_id = Cycle::where('status', 1)->first()->id ?? 1;
                // Crear un nuevo grupo
                $group = Group::create([
                    'year'          => date("Y"),
                    'status'        => 0,
                    'state_id'      => 1,
                    'protocol_id'   => $protocol->id,
                    'cycle_id'      => $cycle_id
                ]);
            }
            $existing_users_ids = $group->users()->pluck('user_group.user_id')->toArray();
            $existing_users = array_column($group->users->toArray(), 'pivot');

            for ($i = 0; $i < count($existing_users); $i++) {
                $existing_users[$i]['created_at'] = Carbon::parse($existing_users[$i]['created_at'])->format('Y-m-d H:i:s');
                $existing_users[$i]['updated_at'] = Carbon::parse($existing_users[$i]['created_at'])->format('Y-m-d H:i:s');
            }

            $group->users()->detach();
            $users = $request->users;
            $notification = Notification::create(['title' => 'Alerta', 'message' => 'Has sido invitado al grupo ' . $group->number . ' por ' . Auth::user()->first_name, 'user_id' => Auth::user()->id]);
            // Preparar datos para la sincronización
            foreach ($users as $key => $userId) {
                $userData = [
                    'user_id'   => intval($userId),
                    'status'    => ($key === 0) ? 1 : 0, // Establecer status = 1 para el primer    usuario, 0 para los demás
                    'is_leader' => ($key === 0) ? 1 : 0, // Establecer is_leader = 1 para el primer     usuario, 0 para los demás
                ];
                $syncData[] = $userData;

                if (!in_array(intval($userId), $existing_users_ids) && $key > 0) {
                    try {
                        $user = User::find(intval($userId));
                        Mail::to($user->email)->send(new SendMail('mail.user-invited-to-group', 'Invitación a grupo', ['user' => $user, 'group' => $group]));
                        UserNotification::create(['user_id' => $user->id, 'notification_id' => $notification->id, 'is_read' => 0]);
                    } catch (Exception $e) {
                        Log::info($e->getMessage());
                    }
                }
            }
            // Insertar los nuevos usuarios
            $group->users()->attach($syncData);
            $user = Auth::user();
            $protocols = $user->protocol()
                ->wherePivot('status', 1)
                ->pluck('name');

            return redirect()->back()->with(['success' => 'Grupo inicializado con éxito.', $protocols]);
        } catch (Exception $e) {
            Log::info('GroupController.store');
            Log::info($e->getMessage());

            $protocols = $user->protocol()
                ->wherePivot('status', 1)
                ->pluck('name');

            return redirect()->back()->with(['error' => 'Hubo un error intente de nuevo.', $protocols]);
        }
    }

    public function storeExg(Request $request)
    {
        $data = $request->validate([
            'protocol'      => 'required|string|exists:protocols,id'
        ]);

        $user = Auth::user();
        $actual_date = Carbon::now();
        $cycle_id = Cycle::where('year', $actual_date->year)->where('status', 1)->first()->id ?? 1;
        $protocols = Protocol::where('id', $data['protocol'])->first();

        //dd($protocols);

        $group = Group::create([
            'year'          => $actual_date->year,
            'status'        => 1,
            'state_id'      => 3,
            'protocol_id'   => $protocols->id,
            'cycle_id'      => $cycle_id
        ]);

        $user_group = UserGroup::create([
            'status'        => 1,
            'is_leader'     => 1,
            'user_id'       => $user->id,
            'group_id'      => $group->id

        ]);

        return redirect()->back()->with(['success' => 'Trabajo inicializado con éxito.', $protocols]);
    }

    public function edit($id)
    {
        $group = Group::select(
            'groups.id',
            'p.name',
            'u.first_name',
            'u.middle_name',
            'u.last_name',
            'u.second_last_name',
            'u.carnet',
            'ug.is_leader',
            'groups.authorization_letter',
            'groups.status',
            'groups.authorization_letter_higher_members',
        )
            ->join('user_group as ug', 'groups.id', 'ug.group_id')
            ->join('users as u', 'ug.user_id', 'u.id')
            ->join('protocols as p', 'groups.protocol_id', 'p.id')
            ->where('groups.id', $id)
            ->where('ug.status', 1)
            ->get();

        return view('groups.edit', compact('group', 'id'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Validación de los datos del ciclo y los parámetros
            $validatedData = $request->validate([
                'group_id'        => 'required|integer',
                'decision'        => 'required|integer',
                // Campo que contendrá los parámetros
            ]);

            $group = Group::findOrFail($id);

            // Actualizar los datos del ciclo
            // 1 = aceptado
            // 2 = denegado

            $stateId = 3;

            DB::beginTransaction();

            DB::statement("DELETE FROM user_group WHERE group_id = ? AND status != 1", [$id]);

            if ($request->decision == 1) {
                $stateId = 2;
                $consultingGroup = Group::where('number', '!=', null)
                    ->where('protocol_id', $group->protocol_id)
                    ->orderBy('id', 'desc')
                    ->first();

                if (isset($consultingGroup)) {
                    $number = $consultingGroup->number + 1;
                } else {
                    $number = 1;
                }
                $data = [
                    'status'    => $request->decision,
                    'state_id'  => $stateId,
                    'number'    => $number
                ];

                // Establecer el deadline al final del ciclo activo
                //Falta validarlo.
                $cycleEndDate = Cycle::where('status', 1)->first()->end_date ?? now();
                $data['deadline'] = $cycleEndDate;

                $notification = Notification::create(['title' => 'Alerta de grupo', 'message' => "Te informamos que tu grupo ha sido: ACEPTADO", 'user_id' => Auth::user()->id]);

                foreach ($group->users as $user) {
                    Mail::to($user->email)->send(new SendMail('mail.notification', 'Notificacion de grupo', ['title' => "Notificacion del grupo $group->number", 'body' => "Hola $user->first_name, te informamos que tu grupo ha sido <b>ACEPTADO</b>."]));
                    UserNotification::create(['user_id' => $user->id, 'notification_id' => $notification->id, 'is_read' => 0]);
                }
            } else {
                $data = [
                    'status'    => $request->decision,
                    'state_id'  => $stateId,
                ];

                $notification = Notification::create(['title' => 'Alerta de grupo', 'message' => "Lamentamos informarte que tu grupo ha sido: RECHAZADO", 'user_id' => Auth::user()->id]);

                foreach ($group->users as $user) {
                    Mail::to($user->email)->send(new SendMail('mail.notification', 'Notificacion de grupo', ['title' => "Notificacion del grupo $group->number", 'body' => "Hola $user->first_name, lamentamos informarte que tu grupo ha sido <b>RECHAZADO</b>."]));
                    UserNotification::create(['user_id' => $user->id, 'notification_id' => $notification->id, 'is_read' => 0]);
                }
            }
            $group->update($data);

            DB::commit();
            return redirect()->route('groups.index')->with('success', 'Grupo actualizado con éxito');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(['¡Ups! Lo sentimos, algo salió mal.', $th->getMessage()]);
        }
    }

    public function destroy($id)
    {
        // Encontrar el ciclo que se desea eliminar
        $group = Group::findOrFail($id);

        // Eliminar los parámetros asociados al ciclo
        $group->parameters()->delete();

        // Eliminar el ciclo
        $group->delete();

        return redirect()->route('groups.index')->with('success', 'Ciclo eliminado con éxito');
    }

    public function confirmStore(Request $request)
    {
        try {
            $group = Group::find($request->input('group_id'));
            $parameterMaxGroup = Parameter::where('name', 'max_group')->where('cycle_id', $group->cycle_id)->first();

            $usersGroup = UserGroup::where('group_id', $request->input('group_id'))->where('status', 1)->get();

            if ($parameterMaxGroup != null && count($usersGroup) >= $parameterMaxGroup->value) {
                return redirect()->back()->withErrors(['message' => 'Lo sentimos el grupo ya ha sido completado.']);
            }

            //Obteniendo info de user logueado
            $user = Auth::user();
            $year = date("Y"); // Año actual
            // Obtener el ID del grupo desde la solicitud y el valor de la variable 'decision' desde la solicitud
            $groupId = $request->input('group_id');
            $decision = intval($request->input('decision'));
            if ($decision === 1) {
                $isActiveInOtherGroup = Group::join('user_group', 'user_group.group_id', '=', 'groups.id')
                    ->where('user_group.user_id', $user->id)
                    ->where('groups.year', $year)
                    ->where('user_group.status', 1) // '1' representa el estado confirmado
                    ->exists();
                if ($isActiveInOtherGroup) {
                    // El usuario está activo en otro grupo este año
                    return redirect()->back()->withErrors(['message' => 'Ya te encuentras activo en otro grupo.']);
                }
            }
            $user->groups()
                ->wherePivot('group_id', $groupId)
                ->updateExistingPivot($groupId, ['status' => $decision]);

            return redirect()->back()->with('success', 'Respuesta guardada con éxito');
        } catch (Exception $th) {
            Log::info($th->getMessage());
            return redirect()->back()->withErrors(['message' => 'Error al actualizar.']);
        }
    }

    public function evaluatingCommitteeIndex(Group $group)
    {
        $groupCommittees = Group::select(
            'groups.id',
            'groups.number',
            'u.first_name',
            'u.middle_name',
            'u.last_name',
            'u.second_last_name',
            'u.email',
            'u.id',
            'tg.type',
            'tg.id as tg_id'
        )
            ->join('teacher_group as tg', 'groups.id', 'tg.group_id')
            ->join('users as u', 'tg.user_id', 'u.id')
            ->join('protocols as p', 'groups.protocol_id', 'p.id')
            ->where('u.type', 2)
            ->where('groups.id', $group->id)
            ->get();
        // dd($groupCommittees);
        $teachers = User::where('type', 2)->get();

        return view('groups.evaluationCommittees.index', compact('groupCommittees', 'teachers', 'group'));
    }

    public function evaluatingCommitteeGet(Request $request)
    {
        if (isset($request)) {
            $palabra = $request["term"]["term"];
            $teachersResult =  $teachers = User::where('type', 2)->get()->toArray();
        }
        $teachers = array(
            'results' => array(
                [
                    "id" => 1,
                    "text" => "hola"
                ]
            ),
        );

        return response()->json($teachers);
    }

    public function evaluatingCommitteeUpdate(Request $request, Group $group)
    {

        $validatedData = $request->validate([
            'teachers'          => 'array|required', // Campo que contendrá los parámetros
            'type_committee'    => 'required',
            // 'agreement'         => 'required|mimes:pdf',
        ]);

        // Procesar y guardar el archivo
        // if ($request->hasFile('agreement')) {
        //     $path = $request->file('agreement')->store('agreement'); // Define la carpeta de destino donde se guardará el archivo
        // }

        $syncData = [];
        $notification = Notification::create(['title' => 'Alerta de comité', 'message' => "Te informamos que has sido agregado/a al comité de evaluación", 'user_id' => Auth::user()->id]);
        foreach ($request->teachers as $key => $userId) {
            $userData = [
                'user_id'           => intval($userId),
                'status'            => 1, // Establecer status = 1
                'type'              => $request->type_committee, // Establecer asesor = 0 , jurados = 1
                // 'path_agreement'    => $path
            ];
            $syncData[] = $userData;


            // Enviar correo electrónico notificando la adición al comité de evaluación
            try {
                $user = User::find(intval($userId));
                $committeeType = $request->type_committee == 0 ? "Asesor(a)" : "Jurado(a)";
                $mailData = [
                    'user'      => $user,
                    'group'     => $group,
                    'committee' => $committeeType,
                ];
                Mail::to($user->email)->send(new SendMail('mail.committee-added', 'Notificación de Comité', $mailData));
                UserNotification::create(['user_id' => $user->id, 'notification_id' => $notification->id, 'is_read' => 0]);
            } catch (\Throwable $th) {
                // working...

            }
            //¿Y para notificar al estudiante?
        }

        // Insertar los nuevos usuarios
        $group->teacherUsers()->attach($syncData);
        $text = $request->type_committee == 0 ? "Asesor(a)" : "Jurado(a)";

        return redirect()->back()->with('success', $text . "agregada con exito.");
    }

    public function evaluatingCommitteeDestroy($user, $type, Group $group)
    {
        $group->teacherUsers()->wherePivot('type', $type)->detach($user);

        return redirect()->back()->with('success', "Jurado(a) eliminada con exito.");
    }

    public function modalAuthorizationLetter(Request $request)
    {

        $countUserGroup = Group::find($request->group_id)
            ->users()
            ->wherePivot('status', 1)
            ->count();

        return view(
            'groups.modal.attach_authorization_letter',
            [
                'group_id'          => $request->group_id,
                'countUserGroup'    => $countUserGroup
            ]
        );
    }

    public function storeAuthorizationLetter(Request $request)
    {
        try {
            $group = Group::find($request->group_id);
            if ($request->hasFile('authorization_letter')) {
                if (is_file(storage_path('app/' . $group->authorization_letter))) {
                    Storage::delete($group->authorization_letter);
                }
                $group->authorization_letter = $request->file('authorization_letter')->storeAs('groups', $group->id . '-' . $request->file('authorization_letter')->getClientOriginalName());
            }

            if ($request->hasFile('authorization_letter_higher_members')) {
                if (is_file(storage_path('app/' . $group->authorization_letter_higher_members))) {
                    Storage::delete($group->authorization_letter_higher_members);
                }
                $group->authorization_letter_higher_members = $request->file('authorization_letter_higher_members')->storeAs('groups_authorization', $group->id . '-' . $request->file('authorization_letter_higher_members')->getClientOriginalName());
            }
            $group->save();
            return redirect()->action([GroupController::class, 'index'])->with('success', 'Carta de autorización subida exitosamente.');
        } catch (\Throwable $th) {

            return redirect()->action([GroupController::class, 'index'])->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }

    public function modalAuthorizationAgreement(Request $request)
    {
        return view('groups.modal.attach_authorization_agreement', ['group_committee_id' => $request->group_committee_id]);
    }

    public function storeAuthorizationAgreement(Request $request)
    {
        try {
            $group = TeacherGroup::find($request->group_committee_id);
            if ($request->hasFile('path_agreement')) {
                if (is_file(storage_path('app/' . $group->path_agreement))) {
                    Storage::delete($group->path_agreement);
                }
                $group->path_agreement = $request->file('path_agreement')->storeAs('agreement', $group->id . '-' . $request->file('path_agreement')->getClientOriginalName());

                $group->save();

                return redirect()->action([GroupController::class, 'index'])->with('success', 'Carta de acuerdo subida exitosamente.');
            }
        } catch (Exception $th) {
            return redirect()->action([GroupController::class, 'index'])->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }

    public function teacherGroupDownload(TeacherGroup $teachergroup, $file)
    {

        $filePath = storage_path('app/' . $teachergroup->$file);
        return response()->file($filePath);
    }

    public function assignedGroup()
    {
        $groups = Group::select('groups.id', 'groups.number', 'groups.status', 'st.name as state_name', 'pj.name')
            ->join('teacher_group as tg', 'groups.id', 'tg.group_id')
            ->join('projects as pj', 'groups.id', 'pj.group_id')
            ->join('states as st', 'groups.state_id', 'st.id')
            ->where('tg.user_id', auth()->user()->id)
            ->get();

        return view('groups.assigned-group', compact('groups'));
    }
}
