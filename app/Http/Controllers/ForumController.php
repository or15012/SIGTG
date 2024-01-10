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
        $forum = Forum::get();
        return view('forum.index', compact('forum'));
    }

    public function create()
    {
        $schools   = School::all();
        $cycles    = Cycle::all();

        return view('forum.create')->with(compact('schools', 'cycles')); 
    }

    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'description'           => 'required|string',
            'path'                  => 'required|mimes:pdf',
        ]);

        //dd($validatedData);
        $user = Auth::user();
        // Procesar y guardar el archivo
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('forum');
            //dd($path);
        }

        try {
            $forum = new Forum;
            $forum->name              = $request->input('name');
            $forum->description       = $request->input('description');
            $forum->path              = $path;

            $forum->save();

            //dd($forum);
            return redirect()->route('forum.index')->with('success', 'Se añadió el taller correctamente.');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->route('forum.index')->with('error', 'El taller no pudo ser añadido.');
        }
    }

    public function show(Forum $forum)
    {
        //dd($forum);
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
        //dd($filePath);
        return response()->download($filePath);
    }



}
