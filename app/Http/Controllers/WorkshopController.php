<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use App\Models\Cycle;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        $workshop = Workshop::where('user_id', $user->id)->get();

        return view('workshop.index', compact('workshop'));
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
            'name'                  => 'required|string|max:255',
            'description'           => 'required|string',
            'path'                  => 'required|mimes:pdf',
        ]);

        //dd($validatedData);
        $user = Auth::user();
        // Procesar y guardar el archivo
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('workshop');
            //dd($path);
        }

        try {
            $workshop = new Workshop;
            $workshop->name              = $request->input('name');
            $workshop->description       = $request->input('description');
            $workshop->path              = $path;

            $workshop->save();

            //dd($workshop);
            return redirect()->route('workshop.index')->with('success', 'Se añadió el taller correctamente.');
        } catch (\Throwable $th) {
            dd($th->getMessage());
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
