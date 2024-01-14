<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Mail\SendMail;
use App\Models\Application;
use App\Models\Cycle;
use App\Models\Entity;
use App\Models\Group;
use App\Models\Profile;
use App\Models\Project;
use App\Models\School;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ProposalController extends Controller
{
    const PERMISSIONS = [
        'index'    => 'Proposal',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
    }

    public function index()
    {
        $user = Auth::user();
        $proposals = Proposal::where('user_id', $user->id)->get();

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
            'entity_id'             => 'required|integer',
        ]);

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
            $proposals->entity_id         = $request->input('entity_id');
            $proposals->status            = 0;
            $proposals->user_id           = $user->id;

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

    public function indexApplication()
    {
        $user = Auth::user();
        $proposals = Proposal::all();

        return view('proposals.applications.index', compact('proposals'));
    }

    public function indexApplicationCoordinator()
    {
        $user = Auth::user();
        $proposals = Proposal::with('entity')->get();
        $applications = Application::with('user')->get();


        return view('proposals.applications.coordinator.index', compact(['proposals', 'applications']));
    }
    public function createApplication(Proposal $proposal)
    {

        return view('proposals.applications.create', compact('proposal'));
    }

    public function storeApplication(Request $request)
    {

        $user = Auth::user();
        // Validar si el usuario ya ha aplicado a esta propuesta

        if ($user->applications()->where('proposal_id', $request->proposal_id)->exists()) {
            //dd("entre");
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
        //dd($validatedData);
        return redirect()->route('proposals.applications.index', [$proposal->proposal_id])->with('success', 'Has aplicado correctamente a la pasantía.');
    }


    //El coorinador acepta o rechaza CV
    public function coordinatorUpdate(Request $request, Application $application)
    {
        $validatedData = $request->validate([
            'decision' => 'required',
        ]);


        $user = $application->user; //Obteniendo usuario que ha aplicado
        $school = $user->school; //Obteniendo escuela a la que pertenece
        $protocol = $user->protocol->first(); //obteniendo protocolo al que pertenece
        $cycle = Cycle::where('status', 1)->first(); //Obteniendo ciclo activo
        $year = date('Y');
        //dd($user,$school,$cycle,$protocol);

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

            // Obtener el grupo recién creado
          //  $currentGroup = Group::find($group->id);
           // dd($currentGroup);

            //creandole el perfil internamente
            $profile                        = new Profile();
            $profile->name                  = "Protocolo PPP";
            $profile->description           = "Protocolo PPP";
            $profile->group_id              = $group->id;
            $profile->status                = 1;
            $profile->type                  = 1;
            $profile->save();


            //creandole el proyecto internamente
            $project                = new Project();
            $project->name          =  "Pasantía profesional";
            $project->group_id      = $group->id;
            $project->profile_id    = $profile->id;
            $project->save();
        }



        $application->update();


        return view('proposals.applications.coordinator.show', compact('application'));
    }

    public function showApplicationCoordinator(Application $application)
    {
        //dd($proposal);
        return view('proposals.applications.coordinator.show', compact('application'));
    }

    public function applicationDownload(Application $application, $file)
    {
        $filePath = storage_path('app/' . $application->$file);
        //dd($filePath);
        return response()->download($filePath);
    }
}
