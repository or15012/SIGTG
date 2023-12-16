<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Cycle;
use App\Models\Extension;
use App\Models\Project;
use App\Models\Protocol;
use App\Models\School;
use Illuminate\Http\Request;
use App\Models\Stage;
use App\Models\TypeExtension;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class ExtensionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Project $project)
    {

        $extensions = [];
        $extensions = Extension::get();

          // Creando una instancia del controlador Project
          $projectController = new ProjectController();

          //Llamando a la funcion disableProject

          $status = $projectController->disableProject($project);
          //dd($status);

        return view('extension.index', compact('extensions', 'project', 'status'));


    }

    public function create(Project $project)
    {

        $type_extensions = TypeExtension::all();

        return view('extension.create') ->with(compact('project','type_extensions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id'          => 'required|exists:projects,id',
            'type_extension_id'    => 'required|exists:type_extensions,id',
            'description'          => 'required|string|max:255',
            'status'      => 'required|integer|min:0|max:2',
        ]);


        try {
            if ($request->hasFile('extension_request_path')) {
                $extension_request_path = $request->file('extension_request_path')->store('extensions'); // Define la carpeta de destino donde se guardará el archivo
            }
            if ($request->hasFile('schedule_activities_path')) {
                $schedule_activities_path = $request->file('schedule_activities_path')->store('extensions'); // Define la carpeta de destino donde se guardará el archivo
            }
            if ($request->hasFile('approval_letter_path')) {
                $approval_letter_path = $request->file('approval_letter_path')->store('extensions'); // Define la carpeta de destino donde se guardará el archivo
            }
            $extension = Extension::create([
                'project_id'          => $request['project_id'],
                'type_extension_id'   => $request['type_extension_id'],
                'description'      => $request['description'],
                'status'     => $request['status'],
                'extension_request_path'     => $extension_request_path,
                'schedule_activities_path'     => $schedule_activities_path,
                'approval_letter_path'     => $approval_letter_path,
            ]);

             // Obtener información adicional para el correo electrónico
            $project = Project::find($request['project_id']);

            // Obtener usuarios asignados al proyecto
            $recipients = $project->group->users;

            // Envío de correo electrónico a cada destinatario
            foreach ($recipients as $recipient) {
                try {
                    $emailData = [
                        'user'      => $recipient,
                        'extension' => $extension,
                        'project'   => $project,
                    ];
                    Mail::to($recipient->email)->send(new SendMail('mail.extension-created', 'Nueva extensión creada', $emailData));
                } catch (\Throwable $th) {
                    // Manejar la excepción
                }
            }

            return redirect()->route('extensions.index', $request['project_id'])->with('success', 'Prórroga creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }

    public function edit(Extension $extension, Project $project)
    {

        $type_extensions = TypeExtension::all();

        return view('extension.edit') ->with(compact('extension','project','type_extensions'));
    }

    public function update(Request $request, Extension $extension)
    {
        $data = $request->validate([
            'project_id'          => 'required|exists:projects,id',
            'type_extension_id'    => 'required|exists:type_extensions,id',
            'description'          => 'required|string|max:255',
            'status'      => 'required|integer|min:0|max:2',
        ]);

        try {
            $fields = [
                'project_id'          => $request['project_id'],
                'type_extension_id'   => $request['type_extension_id'],
                'description'      => $request['description'],
                'status'     => $request['status'],
            ];

            if ($request->hasFile('extension_request_path')) {
                if(is_file(storage_path('app/' . $extension->extension_request_path)))
                {
                    Storage::delete($extension->extension_request_path);
                }
                $fields['extension_request_path'] = $request->file('extension_request_path')->store('extensions');
            }
            if ($request->hasFile('schedule_activities_path')) {
                if(is_file(storage_path('app/' . $extension->schedule_activities_path)))
                {
                    Storage::delete($extension->schedule_activities_path);
                }
                $fields['schedule_activities_path'] = $request->file('schedule_activities_path')->store('extensions');
            }
            if ($request->hasFile('approval_letter_path')) {
                if(is_file(storage_path('app/' . $extension->approval_letter_path)))
                {
                    Storage::delete($extension->approval_letter_path);
                }
                $fields['approval_letter_path'] = $request->file('approval_letter_path')->store('extensions');
            }

            $extension->update($fields);

            // Obtener información adicional para el correo electrónico
            $project = Project::find($request['project_id']);

            // Obtener usuarios asignados al proyecto
            $recipients = $project->group->users;

            // Envío de correo electrónico a cada destinatario
            foreach ($recipients as $recipient) {
                try {
                    $emailData = [
                        'user'      => $recipient,
                        'extension' => $extension,
                        'project'   => $project,
                        'status'    => $extension->status,
                    ];
                    Mail::to($recipient->email)->send(new SendMail('mail.extension-updated', 'Extensión actualizada', $emailData));
                } catch (\Throwable $th) {
                    // Manejar la excepción
                }
            }

            return redirect()->route('extensions.index', $request['project_id'])->with('success', 'Prórroga actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }
    public function destroy(Extension $extension)
    {
        //
    }
}
