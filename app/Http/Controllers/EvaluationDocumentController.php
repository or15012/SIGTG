<?php

namespace App\Http\Controllers;

use App\Models\EvaluationDocument;
use App\Models\EvaluationStage;
use App\Models\EvaluationSubarea;
use App\Models\Project;
use App\Models\Stage;
use App\Models\SubareaDocument;
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

    public function create(EvaluationStage $evaluation_stage)
    {
        return view('evaluations_documents.create', [
            "evaluation_stage" => $evaluation_stage,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'path'                  => 'required|mimes:pdf,rar,zip,docx', // Esto valida que el archivo sea un PDF, rar o zip (puedes ajustar según tus necesidades)
            'evaluation_stage_id'   => 'required|integer'
        ]);

        // Procesar y guardar el archivo
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('evaluations_documents'); // Define la carpeta de destino donde se guardará el archivo
        }

        $evaluations_documents = EvaluationDocument::create([
            'name'                  => $request['name'],
            'evaluation_stage_id'   => $request['evaluation_stage_id'],
            'path'                  => $path
        ]);

        $evaluation_stage = EvaluationStage::find($request['evaluation_stage_id']);

        return redirect()
            ->route('projects.show.stage', [$evaluation_stage->project_id, $evaluation_stage->stage_id])
            ->with('success', 'Documento guardado correctamente.');
    }

    public function edit(EvaluationDocument $evaluation_document)
    {

        return view('evaluations_documents.edit', ['evaluation_document' => $evaluation_document]);
    }

    public function Update(Request $request, EvaluationDocument $evaluation_document)
    {
        $validatedData = $request->validate([
            'name'   => 'required|string|max:255',
            'path'   => 'nullable|mimes:pdf,rar,zip', // Esto valida que el archivo sea un PDF, rar o zip (puedes ajustar según tus necesidades)

        ]);

        // Actualizar los campos de adjuntar etapa
        $evaluation_document->name  = $request->input('name');


        // Procesar y guardar el nuevo archivo si se proporciona
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('evaluations_documents');
            $evaluation_document->path = $path;
        }

        $evaluation_document->update();

        return redirect()->route('evaluations_documents.index')->with('success', 'El documento de etapa se ha actualizado correctamente');
    }

    public function evaluationsDownload(EvaluationDocument $evaluation_document, $file)
    {

        $filePath = storage_path('app/' . $evaluation_document->$file);
        return response()->download($filePath);
    }

    public function Show(EvaluationDocument $evaluation_document)
    {
        return view('evaluations_documents.show', compact('evaluation_document'));
    }

    public function Destroy(EvaluationDocument $evaluation_document)
    {
        $evaluation_document->delete();

        return redirect()->route('evaluations_documents.index')->with('success', 'Documento de etapa eliminado correctamente.');
    }


    public function subareaIndex()
    {
        $evaluations_documents = SubareaDocument::all();
        return view('evaluations_documents.subareas.index', compact('evaluations_documents'));
    }

    public function subareaCreate(EvaluationSubarea $evaluation_subarea)
    {
        return view('evaluations_documents.subareas.create', [
            "evaluation_stage" => $evaluation_subarea,
        ]);
    }

    public function subareaStore(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'path'                  => 'required|mimes:pdf,rar,zip,docx', // Esto valida que el archivo sea un PDF, rar o zip (puedes ajustar según tus necesidades)
            'evaluation_stage_id'   => 'required|integer'
        ]);

        // Procesar y guardar el archivo
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('subarea_documents'); // Define la carpeta de destino donde se guardará el archivo
        }

        $evaluations_documents = SubareaDocument::create([
            'name'                      => $request['name'],
            'evaluation_subarea_id'     => $request['evaluation_stage_id'],
            'path'                      => $path
        ]);

        $evaluation_stage = EvaluationSubarea::find($request['evaluation_stage_id']);

        return redirect()
            ->route('evaluations.show.subarea', [$evaluation_stage->project_id, $evaluation_stage->evaluation_criteria_id])
            ->with('success', 'Documento guardado correctamente.');
    }


    public function subareaEvaluationsDownload(SubareaDocument $evaluation_document, $file)
    {

        $filePath = storage_path('app/' . $evaluation_document->$file);
        return response()->download($filePath);
    }

}
