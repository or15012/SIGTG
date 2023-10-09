<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{

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
