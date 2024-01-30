<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Activity;
use App\Models\AdviserActivity;
use App\Models\Cycle;
use App\Models\Group;
use App\Models\Notification;
use App\Models\Project;
use App\Models\TeacherGroup;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserNotification;
use DateTime;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Termwind\Components\Dd;

class AdviserController extends Controller
{
    const PERMISSIONS = [
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $advisers = TeacherGroup::join('users as u', 'teacher_group.user_id', 'u.id')
            ->where('u.type', 2)
            ->where('teacher_group.type',0)
            ->where('u.school_id', session('school')['id'])
            ->select('u.first_name','u.middle_name','u.last_name','u.second_last_name','u.id')
            ->get();

        
        return view('advisers.index', compact('advisers'));
    }

    public function show($id)
    {
        $activities = AdviserActivity::where('id_user','=',$id)->get();
        $user = User::where('id','=',$id)->first();

        return view('advisers.activities.show', compact('activities','user'));
    }


    public function index_actividades()
    {

        

        $activities = AdviserActivity::where('id_user','=',Auth::user()->id)->get();

        return view('advisers.activities.index', compact('activities'));
    }


    public function create()
    {
        $ciclos = Cycle::latest()->take(10)->get();
        return view('advisers.activities.create', compact('ciclos'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'          => 'required|max:255',
            'description'   => 'required|max:255',
            'cycle'         => 'required',
            'status'        => 'required',
        ]);

        // Crear un nueva actividad
        $activity = AdviserActivity::create([
            'id_user'       => Auth::user()->id,
            'id_cycle'      => $validatedData['cycle'],
            'name'          => $validatedData['name'],
            'description'   => $validatedData['description'],
            'status'        => $validatedData['status'],
        ]);

       

        return redirect()->route('advisers.activities.index')->with('success', 'Actividad creado con éxito');
    }
    public function edit(AdviserActivity $activity)
    {
        $ciclos = Cycle::latest()->take(10)->get();
        return view('advisers.activities.edit', compact('activity','ciclos'));
    }


    public function update(Request $request, AdviserActivity $activity)
    {

        $validatedData = $request->validate([
            'name'          => 'required|max:255',
            'description'   => 'required|max:255',
            'id_cycle'         => 'required',
            'status'        => 'required',
        ]);

        // Actualizar actividad
        $activity->update($validatedData);

        return redirect()->route('advisers.activities.index')->with('success', 'Actividad actualizada con éxito');
    }


    public function destroy(AdviserActivity $activity)
    {
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Actividad eliminada correctamente.');
    }

    
    public function modalStatus(AdviserActivity $activity)
    {
        return view('activities.modal.change_status', compact('activity'));
    }

}
