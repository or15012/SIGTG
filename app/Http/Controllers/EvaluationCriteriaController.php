<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\EvaluationCriteria;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluationCriteriaController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $stage     = Stage::findOrfail($id);
        $criterias = EvaluationCriteria::where('stage_id', $stage->id)->get();
        
        
        return view('criteria.index', compact('criterias', 'stage'));
    }

    public function create($id)
    {
        
        $stage = Stage::findOrfail($id);
        $sumatory = DB::table('evaluation_criteria')->where('stage_id', $stage->id)->sum('percentage');

        return view('criteria.create', compact('stage', 'sumatory'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'percentage'    => 'required|integer|min:1|max:100',
            'stage'         => 'required|integer|min:1|exists:stages,id',
        ]);

        $stage_id   = $data['stage'];
        $percentage = $data['percentage'];
        $sumatory = DB::table('evaluation_criteria')->where('stage_id', $stage_id)->sum('percentage');
        
        if ($sumatory + $percentage > 100) {
            return redirect()->route('stages.index')->with('error', 'No se pudo completar la acción. El porcentaje supera el 100%.');
        }

        try {
            $criteria = EvaluationCriteria::create([
                    'name'          => $request['name'],
                    'percentage'    => $percentage,
                    'stage_id'      => $stage_id,
            ]);
            return redirect()->route('stages.index')->with('success', 'Se añadió el criterio de evaluación correctamente.');
        }
        catch (QueryException $e) {
            return redirect()->route('stages.index')->with('error', 'El criterio de evaluacion está duplicado.');
        }
    }

    public function edit(EvaluationCriteria $criteria)
    {
          $stage = Stage::findOrfail($criteria->stage_id);

        return view('criteria.edit') ->with(compact('criteria', 'stage'));
    }

    public function update(Request $request, EvaluationCriteria $criteria)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'percentage'    => 'required|integer|min:1|max:100',
            'stage'         => 'required|integer|min:1|exists:stages,id',
        ]);

        $stage_id   = $data['stage'];
        $percentage = $data['percentage'];
        $sumatory = DB::table('evaluation_criteria')->where('stage_id', $stage_id)->where('id', '!=', $criteria->id)->sum('percentage');
        
        if ($sumatory + $percentage > 100) {
            return redirect()->route('stages.index')->with('error', 'No se pudo completar la acción. El porcentaje supera el 100%.');
        }

        try {
            $criteria->update([
                'name'           => $data['name'],
                'percentage'     => $data['percentage'],
            ]);

            return redirect()->route('criterias.index', ['id'=>$criteria->stage_id])->with('success', 'Criterio de Evaluación actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('criterias.edit', ['criteria' => $criteria])->with('error', 'El criterio de evaluación ya se encuentra registrado, revisar.');
        }
    }

    public function destroy(EvaluationCriteria $criteria)
    {
        $criteria->delete();

        return redirect()->route('criterias.index', ['id'=>$criteria->stage_id])->with('success', 'Criterio de Evaluación eliminada exitosamente.');
    }


}
