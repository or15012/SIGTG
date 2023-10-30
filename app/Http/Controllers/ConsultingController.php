<?php

namespace App\Http\Controllers;

use App\Models\Consulting;
use App\Models\Group;
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

}
