<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EvaluationCriteria;
use App\Models\CriteriaStage;
use App\Models\Group;
use App\Models\Stage;
use App\Models\TeacherGroup;
use App\Models\UserGroup;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class CriteriaStageController extends Controller
{
    //
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*public function index()
    {
        $user       = auth()->user();
        $protocols  = TeacherGroup::where('user_id', $user->id)->with('groups')->get();
        
        
        return view('criteria.index', compact('criterias', 'stage'));
    }*/

    public function index()
    {
        $user   = auth()->user();
        //dd($user);
        $groups = TeacherGroup::where('user_id', $user->id)->with('user', 'group')->get(); //Definición de métodos del modelo.

        
        return view('evaluation_stage.index', compact('groups'));
    }

    public function create($id){

        $group      = Group::where('id', $id)->first();
        //dd($grupo);
        $etapas     = Stage::where('protocol_id', $group->protocol_id)->get();
        return view('evaluation_stage.create', compact('group', 'etapas'));
        
    }

    public function edit($id, $etapa){

        $group      = Group::where('id', $id)->first();
        $criterios  = EvaluationCriteria::where('stage_id', $etapa)->get();
        $users      = UserGroup::where('group_id', $id)->with('user')->get();

        $notas      = CriteriaStage::join('users','criteria_stage.user_id','=','users.id')
                        ->join('user_group','users.id','=','user_group.user_id')
                        ->join('evaluation_criteria','criteria_stage.evaluation_criteria_id','=','evaluation_criteria.id')
                        ->where('user_group.group_id', $group->id)
                        ->select('criteria_stage.*')
                        ->orderBy('criteria_stage.user_id','asc')
                        ->get();
        //dd($notas, $users,);
        return view('evaluation_stage.edit', compact('users', 'group', 'criterios', 'notas'));
        
    }

    public function save(Request $request){

        $data = $request->input('notas');
        $rules = [];
        $messages = [];

        try {
            foreach ($data as $alumnoId => $notas) {
                foreach ($notas as $criterioId => $valor) {
    
                    CriteriaStage::create([
                        'user_id'                   => $alumnoId,
                        'evaluation_criteria_id'    => $criterioId,
                        'note'                      => $valor,
                    ]);
                }
            }
    
            return redirect()->route('grades.index')->with('success', 'Notas guardadas exitosamente.');
        } catch (\Throwable $th) {
            return redirect()->route('grades.index')->with('error', $th->getMessage());
        }
        

    }
}
