<?php

namespace App\Http\Controllers;

use App\Models\Consulting;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConsultingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $consultings = Consulting::all();
        return view('consultings.index', compact('consultings'));
    }

    public function create()
    {
        return view('consultings.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'topics'    => 'required',
            'number'    => 'required|integer',
            'summary'   => 'required',
            'date'      => 'required|date', // Campo 'fecha' es obligatorio y debe ser una fecha válida'

        ]);

        $consulting = Consulting::create([
            'topics'    => $request->input('topics'),
            'number'    => $request->input('number'),
            'summary'   => $request->input('summary'),
            'date'      => $request->input('date'),
            'group_id'  => 2

        ]);

        return redirect()->route('consultings.index')->with('success', 'Asesoria creada correctamente.');
    }

    public function edit(Consulting $consulting)
    {

        $consulting->date = Carbon::parse($consulting->date)->format('Y-m-d');
        return view('consultings.edit', compact('consulting'));
    }

    public function update(Request $request, Consulting $consulting)
    {
        $data = $request->validate([
            'topics'    => 'required',
            'number'    => 'required|integer',
            'summary'   => 'required',
            'date'      => 'required|date', // Campo 'fecha' es obligatorio y debe ser una fecha válida'
        ]);

        $consulting->update($data);

        return redirect()->route('consultings.index')->with('success', 'Asesoria actualizada correctamente.');
    }

    public function destroy(Consulting $consulting)
    {
        $consulting->delete();

        return redirect()->route('consultings.index')->with('success', 'Asesoria eliminada correctamente.');
    }
}
