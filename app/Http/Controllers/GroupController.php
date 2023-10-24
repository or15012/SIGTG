<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Parameter;
use App\Models\UserGroup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    public function index()
    {
        $year = date('Y');
        $groups = Group::select('*')->addSelect([
            'user_count' => UserGroup::selectRaw('COUNT(user_id)')
                ->whereColumn('group_id', 'groups.id')
                ->where('status', 1)
        ])
            ->join('user_group as ug', 'groups.id', 'ug.group_id')
            ->join('users as u', 'ug.user_id', 'u.id')
            ->where('groups.year', $year)
            ->where('ug.is_leader', 1)
            ->paginate(20);

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
        //revisare si viene algo en el request de ciclo
        if (isset($request->group_id)) {
            //voy a buscar grupo para actualizarlo
            $group = Group::find($request->group_id);
        } else {
            // Crear un nuevo grupo
            $group = Group::create([
                'year'      => date("Y"),
                'status'    => 0,
            ]);
        }
        $group->users()->detach();
        $users = $request->input('users');
        // Preparar datos para la sincronización
        $syncData = [];
        foreach ($users as $key => $userId) {
            $userData = [
                'user_id'   => intval($userId),
                'status'    => ($key === 0) ? 1 : 0, // Establecer status = 1 para el primer usuario, 0 para los demás
                'is_leader' => ($key === 0) ? 1 : 0, // Establecer is_leader = 1 para el primer usuario, 0 para los demás
            ];
            $syncData[] = $userData;
        }
        // Insertar los nuevos usuarios
        $group->users()->attach($syncData);

        return redirect()->back()->with('success', 'Grupo inicializado con éxito');
    }

    public function show($id)
    {
        $group = Group::findOrFail($id);
        return view('groups.show', compact('group'));
    }

    public function edit($id)
    {
        $group = Group::findOrFail($id);
        $parameterNames = Parameter::PARAMETERS;
        return view('groups.edit', compact('group', 'parameterNames'));
    }

    public function update(Request $request, $id)
    {
        // Validación de los datos del ciclo y los parámetros

        $validatedData = $request->validate([
            'number'        => 'required|integer',
            'year'          => 'required|integer',
            'status'        => 'required|boolean',
            'parameters'    => 'array', // Campo que contendrá los parámetros
        ]);

        // Encontrar el ciclo que se desea actualizar
        $group = Group::findOrFail($id);

        // Actualizar los datos del ciclo
        $group->update([
            'number'    => $validatedData['number'],
            'year'      => $validatedData['year'],
            'status'    => $validatedData['status'],
        ]);
        // dd($group);
        // Actualizar o crear los parámetros
        $parameterNames = Parameter::PARAMETERS; // Obtén el array de nombres de parámetros

        foreach ($parameterNames as $key => $name) {
            // Encuentra el parámetro por su nombre
            $parameter = $group->parameters()->where('name', $key)->first();

            // Si el parámetro existe, actualízalo
            if ($parameter) {
                $parameter->update(['value' => $validatedData['parameters'][$key]]);
            } else {
                // Si no existe, crea un nuevo parámetro
                Parameter::create([
                    'name'      => $key,
                    'value'     => $validatedData['parameters'][$key],
                    'group_id'  => $group->id,
                ]);
            }
        }
        return redirect()->route('groups.index')->with('success', 'Ciclo actualizado con éxito');
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
                    ->where('user_group.status', 1) // Supongo que '1' representa el estado activo
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
}
