<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Cycle;
use App\Models\Withdrawal;
use App\Models\Project;
use App\Models\Protocol;
use App\Models\School;
use Illuminate\Http\Request;
use App\Models\Stage;
use App\Models\TypeWithdrawal;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;
use App\Mail\SendMail;
use App\Models\Group;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\UserNotification;
use Database\Seeders\TypeWithdrawalSeeder;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

        $withdrawals = Withdrawal::join('groups as gr', 'withdrawals.group_id', 'gr.id')
            ->where('gr.protocol_id', session('protocol')['id'])
            ->where('withdrawals.group_id', $group->id)
            ->select('withdrawals.*') // Seleccionar todos los campos de retiros
            ->paginate(30);


        //   // Creando una instancia del controlador Project
        //   $projectController = new ProjectController();

        //   //Llamando a la funcion disableProject

        //   $status = $projectController->disableProject($project);
        //   //dd($status);

        //dd($withdrawals);

        return view('withdrawals.index', compact('withdrawals'));
    }

    public function create()
    {
        $type_withdrawals = TypeWithdrawal::all();

        return view('withdrawals.create')->with(compact('type_withdrawals'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type_withdrawals_id'  => 'required|exists:type_withdrawals,id',
            'description'          => 'required|string|max:255',
        ]);


        try {
            if ($request->hasFile('withdrawal_request_path')) {
                $withdrawal_request_path = $request->file('withdrawal_request_path')->store('withdrawals'); // Define la carpeta de destino donde se guardará el archivo
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
            //dd($request['type_withdrawals_id'] );
            //      dd($data, $user, $group, $withdrawal_request_path);
            $withdrawals = Withdrawal::create([
                'user_id'                   => $user->id,
                'group_id'                  => $group->id,
                'type_withdrawals_id'       => $request['type_withdrawals_id'],
                'description'               => $request['description'],
                'status'                    => 0,
                'withdrawal_request_path'   => $withdrawal_request_path,

            ]);


            return redirect()->route('withdrawals.index')->with('success', 'Retiro creado exitosamente.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }

    public function edit(Withdrawal $withdrawal)
    {

        $type_withdrawals = TypeWithdrawal::all();

        return view('withdrawals.edit')->with(compact('withdrawal', 'type_withdrawals'));
    }

    public function update(Request $request, Withdrawal $withdrawal)
    {
        $data = $request->validate([
            'type_withdrawals_id'  => 'required|exists:type_withdrawals,id',
            'description'          => 'required|string|max:255',
        ]);

        try {
            $user = Auth::user();
            // Obtiene el año actual
            $year = date('Y');
            $group = Group::where('groups.year', $year)
                ->where('groups.status', 1)
                ->whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->first();
            $fields = [
                'group_id'                  => $group->id,
                'type_withdrawals_id'       => $request['type_withdrawals_id'],
                'description'               => $request['description'],
            ];

            if ($request->hasFile('withdrawal_request_path')) {
                if (is_file(storage_path('app/' . $withdrawal->withdrawal_request_path))) {
                    Storage::delete($withdrawal->withdrawal_request_path);
                }
                $fields['withdrawal_request_path'] = $request->file('withdrawal_request_path')->store('withdrawals');
            }

            $withdrawal->update($fields);


            return redirect()->route('withdrawals.index')->with('success', 'Retiro actualizada exitosamente.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }

    public function coordinatorIndex()
    {

        $withdrawals = [];
        $withdrawals = Withdrawal::with("type_withdrawal")->get();
        //dd($withdrawals);

        return view('withdrawals.coordinator.index', compact('withdrawals'));
    }
}
