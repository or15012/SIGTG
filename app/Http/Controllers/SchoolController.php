<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    const PERMISSIONS = [
        'index'    => 'Schools',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
    }


    public function index()
    {
        $schools = School::all();
        return view('schools.index', compact('schools'));
    }

    public function create()
    {
        return view('schools.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        School::create($data);

        return redirect()->route('schools.index')->with('success', 'Escuela creada correctamente.');
    }


    public function show(School $school)
    {

        // Devuelve la vista 'schools.show' pasando la asesoría como una variable compacta
        return view('schools.show', compact('school'));
    }

    public function edit(School $school)
    {
        return view('schools.edit', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $school->update($data);

        return redirect()->route('schools.index')->with('success', 'Escuela actualizada correctamente.');
    }

    public function destroy(School $school)
    {
        $school->delete();

        return redirect()->route('schools.index')->with('success', 'Escuela eliminada correctamente.');
    }
}
