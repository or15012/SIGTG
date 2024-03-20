<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\SendMail;
use App\Models\Agreement;
use App\Models\Group;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\TypeWithdrawal;
use App\Models\Withdrawal;

use Exception;


class WithdrawalController extends Controller
{

    const PERMISSIONS = [
        'index.advisers'    => 'Withdrawals.advisers',
        'index.students'    => 'Withdrawals.students',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index.advisers'])->only(['coordinatorIndex']);
        $this->middleware('permission:' . self::PERMISSIONS['index.students'])->only(['index']);
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

            // Cargar el modelo TypeWithdrawal correspondiente
            $typeWithdrawal = TypeWithdrawal::findOrFail($withdrawals->type_withdrawals_id);

            //Envio de correo a coordinador.
            $role = 'Coordinador General';
            $userRoles = User::role($role)->get(); //modificar para diferenciar por modalidades.

            $notificationStudent = Notification::create(['title' => 'Alerta de retiro de trabajo de grado', 'message' => "Se ha enviado su solicitud de retiro de trabajo de grado exitosamente, está pendiente de revisión", 'user_id' => Auth::user()->id]);
            $notificationCoordinator = Notification::create(['title' => 'Alerta de retiro de trabajo de grado', 'message' => "El estudiante ha enviado su solicitud de retiro de trabajo de grado para revisión",  'user_id' => $user->id]);
            foreach ($userRoles as $coordinator) {
                try {

                    $emailData = [
                        'user'         => $coordinator,
                        'withdrawal'  => $withdrawals,
                        'name'         => $typeWithdrawal->name
                    ];
                    //dd($emailData);

                    Mail::to($coordinator->email)->send(new SendMail('mail.withdrawal-coordinator-saved', 'Notificación de retiro de trabajo de grado presentado', $emailData));
                    UserNotification::create(['user_id' => $coordinator->id, 'notification_id' => $notificationCoordinator->id, 'is_read' => 0]);
                } catch (\Throwable $th) {
                    dd($th);
                }
            }

            // Envío de correo electrónico a estudiante

            try {
                $emailData = [
                    'user' => $user,
                    'withdrawal'  => $withdrawals,
                    'name'         => $typeWithdrawal->name
                ];

                Mail::to($user->email)->send(new SendMail('mail.withdrawal-saved', 'Retiro de trabajo de grado enviado con éxito', $emailData));
                UserNotification::create(['user_id' => $user->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
            } catch (Exception $th) {
                // Manejar la excepción
            }

            return redirect()->route('withdrawals.index')->with('success', 'Retiro creado exitosamente.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }

    public function edit(Withdrawal $withdrawal)
    {
        $status = $withdrawal->status();
        //dd($status);
        if ($status === 'Aprobada') {
            return redirect()->back()->with('error', 'No puedes editar este retiro porque ya ha sido aceptado.');
        } elseif ($status == 'Rechazada') {
            return redirect()->back()->with('error', 'No puedes editar este retiro porque ya ha sido rechazado.');
        }

        $type_withdrawals = TypeWithdrawal::all();

        // Verificar si el retiro está en un estado que permite la edición

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

            // Cargar el modelo TypeWithdrawal correspondiente
            $typeWithdrawal = TypeWithdrawal::findOrFail($withdrawal->type_withdrawals_id);

            //Envio de correo a coordinador.
            $role = 'Coordinador General';
            $userRoles = User::role($role)->get(); //modificar para diferenciar por modalidades.

            $notificationStudent = Notification::create(['title' => 'Alerta de retiro de trabajo de grado', 'message' => "Se ha enviado una actualización de la solicitud de retiro de trabajo de grado exitosamente, está pendiente de revisión", 'user_id' => Auth::user()->id]);
            $notificationCoordinator = Notification::create(['title' => 'Alerta de retiro de trabajo de grado', 'message' => "El estudiante ha enviado una actualización de su solicitud de retiro de trabajo de grado para revisión",  'user_id' => $user->id]);
            foreach ($userRoles as $coordinator) {
                try {

                    $emailData = [
                        'user'         => $coordinator,
                        'withdrawal'   => $withdrawal,
                        'name'         => $typeWithdrawal->name
                    ];
                    //dd($emailData);

                    Mail::to($coordinator->email)->send(new SendMail('mail.withdrawal-coordinator-updated', 'Modificación de retiro de trabajo de grado presentado', $emailData));
                    UserNotification::create(['user_id' => $coordinator->id, 'notification_id' => $notificationCoordinator->id, 'is_read' => 0]);
                } catch (\Throwable $th) {
                    dd($th);
                }
            }

            // Envío de correo electrónico a estudiante

            try {
                $emailData = [
                    'user' => $user,
                    'withdrawal'  => $withdrawal,
                    'name'        => $typeWithdrawal->name
                ];

                Mail::to($user->email)->send(new SendMail('mail.withdrawal-update', 'Modificación de retiro de trabajo de grado enviado con éxito', $emailData));
                UserNotification::create(['user_id' => $user->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
            } catch (Exception $th) {
                // Manejar la excepción
            }
            return redirect()->route('withdrawals.index')->with('success', 'Retiro actualizada exitosamente.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }

    public function coordinatorIndex()
    {
        // Obtener el coordinador en sesión
        $coordinator = Auth::user();

        // Filtrar retiros por protocolo y escuela
        $withdrawals = Withdrawal::with(['user', 'group'])
            ->whereHas('user', function ($query) use ($coordinator) {
                $query->where('school_id', session('school')['id']);
            })
            ->whereHas('group', function ($query) use ($coordinator) {
                $query->where('protocol_id', session('protocol')['id']);
            })
            ->get();

        //dd($withdrawals);
        return view('withdrawals.coordinator.index', compact('withdrawals'));
    }

    public function coordinatorShow(Withdrawal $withdrawal)
    {

        $type_withdrawals = TypeWithdrawal::all();

        return view('withdrawals.coordinator.show')->with(compact('withdrawal', 'type_withdrawals'));
    }

    //Coordinador aprueba o rechaza el retiro
    public function coordinatorUpdate(Request $request, Withdrawal $withdrawal)
    {
        $user       = $withdrawal->user; //Obteniendo usuario que ha presentado retiro

        $validatedData = $request->validate([
            'decision' => 'required',
        ]);

        $withdrawal->status = $request->decision;

        $withdrawal->update();

        // Cargar el modelo TypeWithdrawal correspondiente
        $typeWithdrawal = TypeWithdrawal::findOrFail($withdrawal->type_withdrawals_id);


        // Envío de notificación y correo electrónico al estudiante deñ estado del retiro
        $notificationStudent = Notification::create([
            'title' => 'Alerta de solicitud de retiro de trabajo de grado',
            'message' => "Se ha recibido una actualización sobre el estado de su solicitud de retiro de trabajo de grado",
            'user_id' => $user->id,
        ]);

        try {
            $emailData = [
                'user' => $user,
                'withdrawal' => $withdrawal,
                'name'        => $typeWithdrawal->name
            ];

            //dd($emailData);

            Mail::to($user->email)->send(new SendMail('mail.withdrawal-updated', 'Estado de solicitud de retiro de trabajo de grado actualizada con éxito', $emailData));
            UserNotification::create(['user_id' => $user->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
        } catch (Exception $th) {
            // Manejar la excepción
            // dd($th);
        }

        return redirect()->route('withdrawals.coordinator.index')->with('success', 'Estado de retiro actualizado.');
    }

    public function modalApprovement(Request $request)
    {
        return view('withdrawals.modal.attach_approvement', ['withdrawal_id' => $request->withdrawal_id]);
    }

    public function storeApprovement(Request $request)
    {
        try {

            $withdrawal = Withdrawal::find($request->withdrawal_id);
            $withdrawal->status = 1;
            $withdrawal->update();

            //Insertare el acuerdo del estudiante
            $agreement                     = new Agreement();
            $agreement->number             = $request->number_agreement;
            $agreement->approval_date      = $request->date_agreement;
            $agreement->description        = $request->description;
            $agreement->user_id            = $withdrawal->user_id;
            $agreement->user_load_id       = auth()->user()->id;
            $agreement->type_agreement_id  = 3;
            $agreement->save();
            return redirect()->back()->with('success', 'Retiro aceptado.');
        } catch (\Throwable $th) {

            return redirect()->back()->with('error', 'Algo salió mal, intente nuevamente.');
        }
    }
}
