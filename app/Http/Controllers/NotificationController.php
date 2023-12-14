<?php

namespace App\Http\Controllers;

use App\Models\Consulting;
use App\Models\Group;
use App\Models\Notification;
use App\Models\Project;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class NotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $notifications = getNotifications();
        return view('notification.index', compact('notifications'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('notification.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'message'          => 'required|string|max:255',
            'role_ids'          => 'required',
        ]);

        try {
            DB::beginTransaction();
            $role_ids = implode(',', $request->role_ids);
            $notification = Notification::create([
                'title'          => $request['title'],
                'message'   => $request['message'],
                'user_id' => Auth::user()->id,
                'role_ids' => $role_ids
            ]);

            DB::insert("insert into user_notifications (user_id, notification_id, is_read, created_at, updated_at) select u.id, $notification->id as notification_id, 0 as is_read, '".date('Y-m-d H:i:s')."' as created_at, '".date('Y-m-d H:i:s')."' as updated_at from users u join model_has_roles mhr on u.id = mhr.model_id where mhr.role_id in ($role_ids)");

            DB::commit();
            return redirect()->route('notifications.index')->with('success', 'Notificación creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('notifications.create')->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }

    // public function edit(Extension $extension)
    // {
    //     $projects = Project::all();
    //     $type_extensions = TypeExtension::all();

    //     return view('extension.edit') ->with(compact('extension','projects','type_extensions'));
    // }

    // public function update(Request $request, Extension $extension)
    // {
    //     $data = $request->validate([
    //         'project_id'          => 'required|exists:projects,id',
    //         'type_extension_id'    => 'required|exists:type_extensions,id',
    //         'description'          => 'required|string|max:255',
    //         'status'      => 'required|integer|min:0|max:2',
    //     ]);

    //     try {
    //         $fields = [
    //             'project_id'          => $request['project_id'],
    //             'type_extension_id'   => $request['type_extension_id'],
    //             'description'      => $request['description'],
    //             'status'     => $request['status'],
    //         ];

    //         if ($request->hasFile('extension_request_path')) {
    //             if(is_file(storage_path('app/' . $extension->extension_request_path)))
    //             {
    //                 Storage::delete($extension->extension_request_path);
    //             }
    //             $fields['extension_request_path'] = $request->file('extension_request_path')->store('extensions');
    //         }
    //         if ($request->hasFile('schedule_activities_path')) {
    //             if(is_file(storage_path('app/' . $extension->schedule_activities_path)))
    //             {
    //                 Storage::delete($extension->schedule_activities_path);
    //             }
    //             $fields['schedule_activities_path'] = $request->file('schedule_activities_path')->store('extensions');
    //         }
    //         if ($request->hasFile('approval_letter_path')) {
    //             if(is_file(storage_path('app/' . $extension->approval_letter_path)))
    //             {
    //                 Storage::delete($extension->approval_letter_path);
    //             }
    //             $fields['approval_letter_path'] = $request->file('approval_letter_path')->store('extensions');
    //         }

    //         $extension->update($fields);

    //         // Obtener información adicional para el correo electrónico
    //         $project = Project::find($request['project_id']);

    //         // Obtener usuarios asignados al proyecto
    //         $recipients = $project->group->users;

    //         // Envío de correo electrónico a cada destinatario
    //         foreach ($recipients as $recipient) {
    //             try {
    //                 $emailData = [
    //                     'user'      => $recipient,
    //                     'extension' => $extension,
    //                     'project'   => $project,
    //                     'status'    => $extension->status,
    //                 ];
    //                 Mail::to($recipient->email)->send(new SendMail('mail.extension-updated', 'Extensión actualizada', $emailData));
    //             } catch (\Throwable $th) {
    //                 // Manejar la excepción
    //             }
    //         }

    //         return redirect()->route('extensions.index')->with('success', 'Prórroga actualizada exitosamente.');
    //     } catch (\Exception $e) {
    //         return redirect()->route('extensions.edit', ['extension' => $extension])->with('error', 'Algo salió mal. Intente nuevamente.');
    //     }
    // }
    // public function destroy(Extension $extension)
    // {
    //     // 
    // }

    public function markAsRead(Request $request){
        if (isset($request->all)) {
            UserNotification::where('user_id', Auth::user()->id)->update(['is_read'=>1]);
        }else{
            UserNotification::where('id', $request->usernoti_id)->update(['is_read'=>1]);
        }
        return redirect()->back();
    }
}
