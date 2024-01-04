<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Mail\SendMail;
use App\Models\Entity;
use App\Models\Group;
use App\Models\Notification;
use App\Models\User;
use App\Models\Observation;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Protocol;
use App\Models\UserNotification;
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

    public function proposalIndex()
    {
        $user = Auth::user();
        $proposals = Proposal::where('user_id', $user)->get();

        return view('proposals.index', compact ('proposals'));
    }

    public function proposalCreate()
    {
        $entities = Entity::all();
        return view('proposals.create', ['entities'=>$entities]);
    }

    public function proposalStore(Request $request)
    {
         // Validación de los datos del formulario
         $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'description'           => 'required|string',
            'path'                  => 'required|mimes:pdf', 
            'amount_student'        => 'required|integer',
            'entity_id'             => 'requiered|integer',
            'status'                => 'requiered|integer',
            'user_id'               => 'requiered|integer'
        ]);

        // Procesar y guardar el archivo
        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('proposals'); 
        }

        $user = Auth::user();
    }

    public function proposalShow()
    {

    }

    public function proposalEdit()
    {
        
    }

    public function proposalUpdate()
    {

    }

    public function proposalDestroy()
    {

    }

    public function proposalDownload(Proposal $proposal, $file)
    {
        $filePath = storage_path('app/' . $proposal->$file);
        return response()->download($filePath);
    }



}
