<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Group;
use App\Models\Notification;
use Exception;
use App\Models\Profile;
use App\Models\Protocol;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PlanningController extends Controller
{
    const PERMISSIONS = [];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.protocol')->only(['index', 'create']);
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

        $plannings = Profile::join('groups as gr', 'profiles.group_id', 'gr.id')
            ->where('gr.protocol_id', session('protocol')['id'])
            ->where('profiles.group_id', $group->id)
            // ->where('type', 0)
            ->paginate(30);

//dd($plannings);
        return view('plannings.index', compact('plannings'));
    }

    public function create()
    {
        //obtener grupo actual del user logueado
        return view('plannings.create', []);
    }

    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'description'           => 'required|string',
            'path'                  => 'required|mimes:pdf,xlsx,xls', // Esto valida que el archivo sea un PDF o excel (puedes ajustar según tus necesidades)
        ]);

        // Procesar y guardar el archivo
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('plannings'); // Define la carpeta de destino donde se guardará el archivo
        }

        $user = Auth::user();
        $year = date('Y');
        $group = Group::where('groups.year', $year)
            ->where('groups.status', 1)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->first();
        $validatedPlanningPriority = Profile::where('group_id', $group->id)
            ->where('proposal_priority', 1)
            ->first();

        if (isset($validatedPlanningPriority)) {
            return redirect()->back()
                ->withErrors(['message' => 'Ya posee una planificación registrada.'])
                ->withInput();
        }


        // Crear una nueva planificación
        $planning                        = new Profile;
        $planning->name                  = $request->input('name');
        $planning->description           = $request->input('description');
        $planning->path                  = $path; // Asigna el nombre del archivo (o null si no se cargó un archivo)
        $planning->proposal_priority     = 1;
        $planning->type                  = 0;
        $planning->group_id              = $group->id;
        $planning->status                = 0;
        $planning->save();


        //  dd($planning);
        //Envio de correo a coordinador.
        $role = 'Coordinador General';
        $userRoles = User::role($role)->get(); //modificar para diferenciar por modalidades.

        $notificationStudent = Notification::create(['title' => 'Alerta de planificación', 'message' => "Su planificación ha sido enviada, y está pendiente de revisión", 'user_id' => Auth::user()->id]);
        $notificationCoordinator = Notification::create(['title' => 'Alerta de planificación', 'message' => "El estudiante ha cargado la planificación para su revisión", 'user_id' => Auth::user()->id]);
        foreach ($userRoles as $coordinator) {
            try {
                $emailData = [
                    'user'       => $coordinator,
                    'group'      => $group,
                    'planning'   => $planning,
                ];
                //dd($emailData);

                Mail::to($coordinator->email)->send(new SendMail('mail.planning-coordinator-saved', 'Notificación de planificación enviada', $emailData));
                UserNotification::create(['user_id' => $coordinator->id, 'notification_id' => $notificationCoordinator->id, 'is_read' => 0]);
            } catch (\Throwable $th) {
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
                    'planning'  => $planning,
                ];

                Mail::to($student->email)->send(new SendMail('mail.planning-saved', 'Planificación enviada con éxito', $emailData));
                UserNotification::create(['user_id' => $student->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
            } catch (Exception $th) {
                // Manejar la excepción
            }
        }
        return redirect()->route('plannings.index')->with('success', 'La planificación se ha guardado correctamente');
    }

    public function show(Profile $planning)
    {
        return view('plannings.show', compact('planning'));
    }

    public function edit(Profile $planning)
    {
        return view('plannings.edit', ['planning' => $planning]);
    }

    public function update(Request $request, Profile $planning)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'path' => 'nullable|mimes:pdf,xlsx,xls', // Esto valida que el nuevo archivo sea un PDF o excel (puedes ajustar según tus necesidades)

        ]);
        try {
            // Actualizar los campos del perfil
            $planning->name               = $request->input('name');
            $planning->description        = $request->input('description');
            // Procesar y guardar el nuevo archivo si se proporciona
            if ($request->hasFile('path')) {
                $path = $request->file('path')->store('plannings');
                $planning->path = $path;
            }

            $planning->update();

            // Envío de correo electrónico a coordinador
            $role = 'Coordinador General';
            $userRoles = User::role($role)->get();
            $notificationStudent = Notification::create(['title' => 'Alerta de actividad', 'message' => "Le informamos que su planificación ha sido actualizada", 'user_id' => Auth::user()->id]);
            $notificationCoordinator = Notification::create(['title' => 'Alerta de actividad', 'message' => "Le informamos que el estudiante ha realizado una actualizacion de la planificación", 'user_id' => Auth::user()->id]);

            foreach ($userRoles as $coordinator) {
                $mailData = [
                    'user' => $coordinator,
                    'planning' => $planning,
                    'status' => $planning->status,
                ];

                try {
                    Mail::to($coordinator->email)->send(
                        new SendMail(
                            'mail.planning-updated-coordinator',
                            'Actualización de planificación',
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
            $students = $planning->group->users;
            foreach ($students as $student) {
                $mailData = [
                    'user' => $student,
                    'planning' => $planning,
                    'status' => $planning->status,
                ];

                try {
                    Mail::to($student->email)->send(
                        new SendMail(
                            'mail.planning-updated',
                            'Actualización de planificación',
                            $mailData
                        )
                    );
                    UserNotification::create(['user_id' => $student->id, 'notification_id' => $notificationStudent->id, 'is_read' => 0]);
                } catch (\Throwable $th) {
                    // Log de errores o manejo adicional
                    // Log::error('Error al enviar correo electrónico: ' . $th->getMessage());
                }
            }

            return redirect()->route('plannings.index')->with('success', 'La planificación se ha actualizado correctamente');
        } catch (\Throwable $th) {
            // Log de errores o manejo adicional
            //Log::error('Error al actualizar el preperfil: ' . $th->getMessage());
            return redirect()->route('plannings.index')->with('error', 'Hubo un error al actualizar la planificación. Por favor, inténtelo de nuevo.');
        }
    }


    public function planningDownload(Profile $planning, $file)
    {
        $filePath = storage_path('app/' . $planning->$file);
        return response()->download($filePath);
    }
    public function destroy(Profile $planning)
    {
        $planning->delete();
        return redirect()->route('plannings.index')->with('success', 'Planificación eliminado correctamente.');
    }
}
