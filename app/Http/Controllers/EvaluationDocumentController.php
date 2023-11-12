<?php

namespace App\Http\Controllers;
use App\Models\EvaluationDocument;
use App\Models\EvaluationStage;


use Illuminate\Http\Request;

class EvaluationDocumentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $evaluations_documents = EvaluationDocument::all();
        return view('evaluations_documents.index', compact('evaluations_documents'));
    }

    public function create()
    {
        return view('evaluations_documents.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'path'                  => 'required|mimes:pdf', // Esto valida que el archivo sea un PDF (puedes ajustar según tus necesidades)
        ]);

         // Procesar y guardar el archivo
         if ($request->hasFile('path')) {
            $path = $request->file('path')->store('evaluations_documents'); // Define la carpeta de destino donde se guardará el archivo
        }

        $evaluations_documents = EvaluationDocument::create([
            'name'                  => $request['name'],
            'evaluation_stage_id'   => 1,
            'path'                  => $path
        ]);

        return redirect()->route('evaluations_documents.index')->with('success', 'Documento guardado correctamente.');
    }
}
