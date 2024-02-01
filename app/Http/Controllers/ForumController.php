<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\Forum;
use App\Models\Cycle;
use App\Models\Group;
use App\Models\School;
use App\Models\UserForumWorkshop;
use App\Models\Workshop;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ForumController extends Controller
{
    const PERMISSIONS = [
        'index'    => 'Forum',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $forums = Forum::where('school_id', session('school', ['id']))
            ->get();

        return view('forum.index', compact('forums'));
    }

    public function create()
    {
        $schools    = School::all();
        $cycles     = Cycle::all();

        return view('forum.create')->with(compact('schools', 'cycles'));
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
            $path = $request->file('path')->store('forum');
        }

        try {
            $forum = new Forum;
            $forum->name        = $request->input('name');
            $forum->description = $request->input('description');
            $forum->place       = $request->input('place');
            $forum->date        = date('Y-m-d H:i:s', strtotime($validatedData['date']));
            $forum->path        = $path;
            $forum->school_id   = session('school')['id'];
            $forum->cycle_id    = $request->input('cycle_id');
            $forum->save();

            //obtendre estudiantes de la escuela del protocolo y del ciclo activo
            $getStudents        = Group::join('user_group as ug', 'groups.id', 'ug.group_id')
                ->join('users as u', 'u.id', 'ug.user_id')
                ->join('user_protocol as up', 'up.user_id', 'u.id')
                ->where('groups.cycle_id', $request->input('cycle_id'))
                ->where('u.school_id', session('school', ['id']))
                ->where('up.status', true)
                ->where('up.protocol_id', 3)
                ->select('u.email', 'u.first_name', 'u.last_name')
                ->get();

            // Preparar un conjunto de datos común para todos los estudiantes
            $emailData = [
                'forum' => $forum, // Puedes agregar otros datos que necesites en la vista del correo
            ];

            try {
                Mail::bcc($getStudents->pluck('email')->toArray())->send(new SendMail('mail.send-invitation-forum', 'Notificación de foro', $emailData));
            } catch (Exception $th) {
                //throw $th;
            }

            return redirect()->route('forum.index')->with('success', 'Se añadió el foro correctamente.');
        } catch (\Throwable $th) {
            return redirect()->route('forum.index')->with('error', 'El foro no pudo ser añadido.');
        }
    }

    public function show(Forum $forum)
    {
        $users = UserForumWorkshop::where('forum_id', $forum->id)
            ->with('user') // Cargar la relación con el modelo User
            ->get();

        return view('forum.show', compact('forum', 'users'));
    }

    public function destroy(Forum $forum)
    {
        $forum->delete();
        return redirect()->route('forum.index')->with('success', 'Taller eliminado exitosamente.');
    }

    public function forumDownload(Forum $forum, $file)
    {
        $filePath = storage_path('app/' . $forum->$file);
        return response()->download($filePath);
    }


    public function showListForumsWorkshops()
    {
        $forums = Forum::join('cycles as c', 'forums.cycle_id', 'c.id')
            ->select('forums.id', 'forums.name', 'forums.description', 'forums.place', 'forums.date')
            ->where('school_id', session('school', ['id']))
            ->where('c.status', 1)
            ->where('forums.date', '>', now())  // Agrega esta línea para filtrar por fecha actual
            ->get();

        $workshops = Workshop::join('cycles as c', 'workshops.cycle_id', 'c.id')
            ->select('workshops.id', 'workshops.name', 'workshops.description', 'workshops.place', 'workshops.date')
            ->where('school_id', session('school', ['id']))
            ->where('c.status', 1)
            ->where('workshops.date', '>', now())  // Agrega esta línea para filtrar por fecha actual
            ->get();

        return view('forum.show-list-all', compact('forums', 'workshops'));
    }

    public function confirmAssistanceForumsWorkshops($id, $type)
    {

        $insertModel            = new UserForumWorkshop();
        $insertModel->user_id   = auth()->user()->id;

        $type == 1 ? $insertModel->forum_id = $id : $insertModel->workshop_id = $id;
        $insertModel->save();

        return redirect()->back()->with('success', 'Asistencia registrada.');
    }

    public function assistenceStore(Request $request)
    {
        $values = array();
        if (isset($request->students)) {
            foreach ($request->students as $key => $value) {
                $values[] = $key;
            };

            DB::table('user_forum_workshop')->where('forum_id', $request->forum_id)->update(['status' => 0]);
            DB::table('user_forum_workshop')->whereIn('id', $values)->update(['status' => 1]);
        } else {
            DB::table('user_forum_workshop')->where('forum_id', $request->forum_id)->update(['status' => 0]);
        }

        return redirect()->back()->with('success', 'Asistencias registrada.');
    }
}
