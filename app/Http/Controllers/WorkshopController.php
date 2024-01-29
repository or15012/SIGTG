<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\Workshop;
use App\Models\Cycle;
use App\Models\Group;
use App\Models\School;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class WorkshopController extends Controller
{
    const PERMISSIONS = [
        'index'    => 'Workshop',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $workshops = Workshop::get();
        return view('workshop.index', compact('workshops'));
    }

    public function create()
    {
        $schools   = School::all();
        $cycles    = Cycle::all();

        return view('workshop.create')->with(compact('schools', 'cycles'));
    }

    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'required|string',
            'place'         => 'required|string',
            'date'          => 'required|date',
            'path'          => 'required|mimes:pdf',
            'cycle_id'      => 'required',
        ]);

        // Procesar y guardar el archivo
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('workshop');
            //dd($path);
        }

        try {
            $workshop               = new Workshop;
            $workshop->name         = $request->input('name');
            $workshop->description  = $request->input('description');
            $workshop->place        = $request->input('place');
            $workshop->date         = date('Y-m-d H:i:s', strtotime($validatedData['date']));
            $workshop->path         = $path;
            $workshop->school_id    = session('school')['id'];
            $workshop->cycle_id     = $request->input('cycle_id');
            $workshop->save();


            //obtendre estudiantes de la escuela del protocolo y del ciclo activo
            $getStudents        = Group::join('user_group as ug', 'groups.id', 'ug.group_id')
                ->join('users as u', 'u.id', 'ug.user_id')
                ->join('user_protocol as up', 'up.user_id', 'u.id')
                ->where('groups.cycle_id', $request->input('cycle_id'))
                ->where('u.school_id', session('school', ['id']))
                ->where('up.status', true)
                ->where('up.protocol_id', 5)
                ->select('u.email', 'u.first_name', 'u.last_name')
                ->get();

            // Preparar un conjunto de datos común para todos los estudiantes
            $emailData = [
                'workshop' => $workshop, // Puedes agregar otros datos que necesites en la vista del correo
            ];

            try {
                Mail::bcc($getStudents->pluck('email')->toArray())->send(new SendMail('mail.send-invitation-workshop', 'Notificación de taller', $emailData));
            } catch (Exception $th) {
                //throw $th;
            }
            return redirect()->route('workshop.index')->with('success', 'Se añadió el taller correctamente.');
        } catch (\Throwable $th) {
            return redirect()->route('workshop.index')->with('error', 'El taller no pudo ser añadido.');
        }
    }

    public function show(Workshop $workshop)
    {
        //dd($workshop);
        return view('workshop.show', compact('workshop'));
    }

    public function destroy(Workshop $workshop)
    {
        $workshop->delete();
        return redirect()->route('workshop.index')->with('success', 'Taller eliminado exitosamente.');
    }

    public function workshopDownload(Workshop $workshop, $file)
    {
        $filePath = storage_path('app/' . $workshop->$file);
        //dd($filePath);
        return response()->download($filePath);
    }
}
