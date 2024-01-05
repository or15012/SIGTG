<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Mail\SendMail;
use App\Models\Entity;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ProposalController extends Controller
{
    const PERMISSIONS = [
        'index'    => 'Proposal',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
    }

    public function index()
    {
        $user = Auth::user();
        $proposals = Proposal::where('user_id', $user->id)->get();

        return view('proposals.index', compact ('proposals'));
    }

    public function create()
    {
        $entities = Entity::all();
        return view('proposals.create', ['entities'=>$entities]);
    }

    public function store(Request $request)
    {
         // Validación de los datos del formulario
         $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'description'           => 'required|string',
            'path'                  => 'required|mimes:pdf', 
            'amount_student'        => 'required|integer',
            'entity_id'             => 'required|integer',
        ]);

        //dd($validatedData);
        $user = Auth::user();
        // Procesar y guardar el archivo
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('proposals'); 
            //dd($path);
        }

        try {
            $proposals = new Proposal;

            $proposals->name              = $request->input('name');
            $proposals->description       = $request->input('description');
            $proposals->path              = $path;
            $proposals->amount_student    = $request->input('amount_student');
            $proposals->entity_id         = $request->input('entity_id');
            $proposals->status            = 0;
            $proposals->user_id           = $user->id;
            
            $proposals->save();

            //dd($proposals);
            return redirect()->route('proposals.index')->with('success', 'Se añadió la propuesta correctamente.');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->route('proposals.index')->with('error', 'La propuesta no pudo ser añadida.');
        }
    }

    public function show(Proposal $proposal)
    {
        //dd($proposal);
        return view('proposals.show', compact('proposal'));
    }

    /*public function proposalUpdate(Request $request, Proposal $proposal)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'description'           => 'required|string',
            'path'                  => 'required|mimes:pdf', 
            'amount_student'        => 'required|integer',
            'entity_id'             => 'requiered|integer',
            'user_id'               => 'requiered|integer'
        ]);

        $user = Auth::user();

        try {
            $proposal->update([
                'name'                  => $request['name'],
                'description'           => $request['description'],
                'path'                  => $request['path'],
                'amount_student'        => $request['amount_student'],
                'entity_id'             => $request['entity_id'],
                'status'                => $user->id,
                'user_id'               => $request['user_id'],
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }*/

    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        return redirect()->route('proposals.index')->with('success', 'Propuesta eliminada exitosamente.');
    }

    public function proposalDownload(Proposal $proposal, $file)
    {
        $filePath = storage_path('app/' . $proposal->$file);
        //dd($filePath);
        return response()->download($filePath);
    }
    



}
