<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Observation;
use App\Models\Profile;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
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
            return redirect('home')->withErrors(['mensaje' => 'Debe tener un grupo activo.']);
        }

        $preprofiles = Profile::where('group_id', $group->id)
            ->where('type', 0)
            ->paginate(10);

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
            'proposal_priority' => 'required|integer',
            'path' => 'nullable|mimes:pdf', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
            'summary_path' => 'nullable|mimes:pdf', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
            'vision_path' => 'nullable|mimes:pdf', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
            'size_calculation_path' => 'nullable|mimes:pdf', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
        ]);

        // Actualizar los campos del perfil
        $preprofile->name               = $request->input('name');
        $preprofile->description        = $request->input('description');
        $preprofile->proposal_priority  = $request->input('proposal_priority');
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

        return redirect()->route('profiles.preprofile.index')->with('success', 'El preperfil se ha actualizado correctamente');
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
            ->where('protocol_id', $protocolsWithStatus->pivot->protocol_id)
            ->get(['id']);

        $preprofiles = Profile::join('groups as g', 'g.id', 'profiles.group_id')
            ->whereIn('group_id', $groups)
            ->where('type', 0)
            ->select('profiles.status', 'profiles.name', 'profiles.description', 'profiles.created_at', 'g.number', 'profiles.id')
            ->paginate(10);



        return view('preprofiles.coordinator.index', compact('preprofiles'));
    }

    public function preProfileCoordinatorShow(Profile $preprofile)
    {
        return view('preprofiles.coordinator.show', compact('preprofile'));
    }


    public function preProfileCoordinatorUpdate(Request $request, Profile $preprofile)
    {
        $validatedData = $request->validate([
            'decision' => 'required', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
        ]);

        $preprofile->status = $request->decision;
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
        $preprofile->update();

        return view('preprofiles.coordinator.show', compact('preprofile'));
    }


    public function preProfileCoordinatorObservationsList(Profile $preprofile)
    {
        return view('preprofiles.coordinator.observations', ['preprofile' => $preprofile]);
    }


    public function preProfileCoordinatorObservationCreate(Profile $preprofile)
    {
        return view('preprofiles.coordinator.create', ['preprofile' => $preprofile]);
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

        $profiles = Profile::where('group_id', $group->id)
            ->where('type', 1)
            ->paginate(10);

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

        return redirect()->route('profiles.index')->with('success', 'El preperfil se ha actualizado correctamente');
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
            ->where('protocol_id', $protocolsWithStatus->pivot->protocol_id)
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
            'profile_id' => 'required', // Esto valida que el archivo sea un PDF (puedes ajustar según tus necesidades)
        ]);

        // Crear un nueva observación
        $observation                = new Observation();
        $observation->description   = $request->description;
        $observation->profile_id    = $request->profile_id;
        $observation->save();

        return redirect()->route('profiles.coordinator.observation.list', [$request->profile_id])->with('success', 'La observación se ha guardado correctamente');
    }
}