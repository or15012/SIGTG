<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Cycle;
use App\Models\Group;
use App\Models\Parameter;
use App\Models\User;
use App\Models\UserGroup;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    const PERMISSIONS = [
        'index'                => 'Groups.students',
        'index.adviser'        => 'Groups.advisers',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['initialize']);
        $this->middleware('permission:' . self::PERMISSIONS['index.adviser'])->only(['index']);
    }


    public function index()
    {
        $year = date('Y');
        $groups = Group::select('groups.id', 'groups.number', 'u.first_name', 'u.middle_name', 'u.last_name', 'u.second_last_name', 's.name')->addSelect([
            'user_count' => UserGroup::selectRaw('COUNT(user_id)')
                ->whereColumn('group_id', 'groups.id')
                ->where('status', 1)
        ])
            ->join('user_group as ug', 'groups.id', 'ug.group_id')
            ->join('users as u', 'ug.user_id', 'u.id')
            ->join('states as s', 'groups.state_id', 's.id')
            ->where('groups.year', $year)
            ->where('ug.is_leader', 1)
            ->paginate(20);

        //dd($groups);
        return view('groups.index', compact('groups'));
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

        if ($group) {
            // Obtener la información de los usuarios relacionados al grupo
            $groupUsers = $group->users;
            foreach ($groupUsers as $item) {
                if ($item->id === $user->id) {
                    if ($item->pivot->is_leader === 1) {
                        return view('groups.initialize', compact('user', 'group', 'groupUsers'));
                    } else {
                        return view('groups.confirm', compact('user', 'group', 'groupUsers'));
                    }
                }
            }
        }

        //vere si el usuario tiene un grupo
        return view('groups.initialize', compact('user', 'group', 'groupUsers'));
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
                    } catch (Exception $e) {
                        Log::info($e->getMessage());
                    }
                }
            }
            // Insertar los nuevos usuarios
            $group->users()->attach($syncData);

            return redirect()->back()->with('success', 'Grupo inicializado con éxito.');
        } catch (Exception $e) {
            Log::info('GroupController.store');
            Log::info($e->getMessage());
            return redirect()->back()->with('error', 'Hubo un error intente de nuevo.');
        }
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
            'groups.status'
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

                foreach ($group->users as $user) {
                    Mail::to($user->email)->send(new SendMail('mail.notification', 'Notificacion de grupo', ['title' => "Notificacion del grupo $group->number", 'body' => "Hola $user->first_name, te informamos que tu grupo ha sido <b>ACEPTADO</b>."]));
                }
            } else {
                $data = [
                    'status'    => $request->decision,
                    'state_id'  => $stateId,
                ];

                foreach ($group->users as $user) {
                    Mail::to($user->email)->send(new SendMail('mail.notification', 'Notificacion de grupo', ['title' => "Notificacion del grupo $group->number", 'body' => "Hola $user->first_name, lamentamos informarte que tu grupo ha sido <b>RECHAZADO</b>."]));
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
                return redirect()->back()->withErrors(['mensaje' => 'Lo sentimos el grupo ya ha sido completado.']);
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
                    return redirect()->back()->withErrors(['mensaje' => 'Ya te encuentras activo en otro grupo.']);
                }
            }
            $user->groups()
                ->wherePivot('group_id', $groupId)
                ->updateExistingPivot($groupId, ['status' => $decision]);

            return redirect()->back()->with('success', 'Respuesta guardada con éxito');
        } catch (Exception $th) {
            Log::info($th->getMessage());
            return redirect()->back()->withErrors(['mensaje' => 'Error al actualizar.']);
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
            'agreement'         => 'required|mimes:pdf',
        ]);

        // Procesar y guardar el archivo
        if ($request->hasFile('agreement')) {
            $path = $request->file('agreement')->store('agreement'); // Define la carpeta de destino donde se guardará el archivo
        }

        $syncData = [];
        foreach ($request->teachers as $key => $userId) {
            $userData = [
                'user_id'           => intval($userId),
                'status'            => 1, // Establecer status = 1
                'type'              => $request->type_committee, // Establecer asesor = 0 , jurados = 1
                'path_agreement'    => $path
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
        return view('groups.modal.attach_authorization_letter', ['group_id' => $request->group_id]);
    }

    public function storeAuthorizationLetter(Request $request)
    {
        try {
            DB::beginTransaction();
            $group = Group::find($request->group_id);
            if ($request->hasFile('authorization_letter')) {
                if (is_file(storage_path('app/' . $group->authorization_letter))) {
                    Storage::delete($group->authorization_letter);
                }
                $group->authorization_letter = $request->file('authorization_letter')->storeAs('groups', $group->id . '-' . $request->file('authorization_letter')->getClientOriginalName());
                $group->save();
                DB::commit();
                return redirect()->action([GroupController::class, 'index'])->with('success', 'Carta de autorización subida exitosamente.');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->action([GroupController::class, 'index'])->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }


    public function assignedGroup()
    {
        $groups = Group::select('groups.id', 'groups.number', 'groups.status','groups.protocol_id')
        ->join('teacher_group as tg', 'groups.id', 'tg.group_id')
        ->where('tg.user_id', auth()->user()->id)
        ->get();
//dd($groups);
        return view('groups.assigned-group', compact('groups'));
    }
}
