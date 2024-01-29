<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\Cycle;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'date'          => 'required|date_format:Y-m-d H:i:s',
            'path'          => 'required|mimes:pdf',
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
            $forum->date        = $request->input('date');
            $forum->path        = $path;

            $forum->save();

            return redirect()->route('forum.index')->with('success', 'Se añadió el taller correctamente.');
        } catch (\Throwable $th) {
            return redirect()->route('forum.index')->with('error', 'El taller no pudo ser añadido.');
        }
    }

    public function show(Forum $forum)
    {
        return view('forum.show', compact('forum'));
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
}
