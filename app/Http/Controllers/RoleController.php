<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    const PERMISSIONS = [
        'index'    => 'Roles',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
    }

    public function index()
    {
        $roles = Role::paginate(10);
        return view('roles.index', compact('roles'));
    }

    public function show(Role $role)
    {
        return view('roles.show', compact('role'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|unique:roles',
            'description'   => 'nullable|string|max:255',
            'permissions'   => 'array',
        ]);

        $role = Role::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->input('permissions'));
        }

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([

            'description'   => 'nullable|string|max:255',
            'permissions'   => 'array',
        ]);

        if (!in_array($role->id, [1, 2, 3, 4, 5])) {
            $role->name = $request->input('name');
        }

        $role->description = $request->input('description');
        $role->save();

        if ($request->has('permissions')) {
            $role->syncPermissions($request->input('permissions'));
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }
}
