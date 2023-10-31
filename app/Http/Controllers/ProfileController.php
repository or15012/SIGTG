<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

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

        $preprofiles = Profile::where('group_id', $group->id)->paginate(10);

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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'path' => 'required|mimes:pdf', // Esto valida que el archivo sea un PDF (puedes ajustar según tus necesidades)
        ]);

        // Procesar y guardar el archivo
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('preprofiles'); // Define la carpeta de destino donde se guardará el archivo
        }

        $user = Auth::user();
        $year = date('Y');
        $group = Group::where('groups.year', $year)
            ->where('groups.status', 1)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->first();

        // Crear un nuevo perfil
        $profile                = new Profile;
        $profile->name          = $request->input('name');
        $profile->description   = $request->input('description');
        $profile->path          = $path; // Asigna el nombre del archivo (o null si no se cargó un archivo)
        $profile->type          = 0;
        $profile->group_id      = $group->id;
        $profile->save();

        return redirect()->route('profiles.preprofile.index')->with('success', 'El pre perfil se ha guardado correctamente');
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'new_path' => 'nullable|mimes:pdf', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
        ]);

        // Actualizar los campos del perfil
        $preprofile->name = $request->input('name');
        $preprofile->description = $request->input('description');

        // Procesar y guardar el nuevo archivo si se proporciona
        if ($request->hasFile('new_path')) {
            $newPath = $request->file('new_path')->store('preprofiles');
            $preprofile->path = $newPath;
        }
        $preprofile->update();

        return redirect()->route('profiles.preprofile.index')->with('success', 'El preperfil se ha actualizado correctamente');
    }

    public function preProfileDestroy(Profile $preprofile)
    {
        $preprofile->delete();

        return redirect()->route('profiles.preprofile.index')->with('success', 'Preperfil eliminado correctamente.');
    }

    public function preProfileDownload(Profile $preprofile)
    {
        $filePath = storage_path("app/{$preprofile->path}");
        return response()->download($filePath);
    }
}
