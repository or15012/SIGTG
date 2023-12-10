<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cycle;
use App\Models\Protocol;
use App\Models\School;
use App\Models\Area; 

class AreaController extends Controller 
{
    const PERMISSIONS = [
        'index' => 'Areas', 
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
    }

    public function index()
    {
        $areas = Area::with('protocol', 'cycle', 'school')->get(); 
        return view('area.index', compact('areas')); 
    }

    public function create()
    {
        $protocols = Protocol::all();
        $schools   = School::all();
        $cycles    = Cycle::all();

        return view('area.create')->with(compact('protocols', 'schools', 'cycles')); 
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'protocol'   => 'required|integer|min:1|exists:protocols,id',
            'cycle'      => 'required|integer|min:1|exists:cycles,id',
            'school'     => 'required|integer|min:1|exists:schools,id',
            'sort'       => 'required|integer',
            'percentage' => 'required|integer|min:1|max:100',
        ]);

        try {
            $area = Area::create([ 
                'name'        => $request['name'],
                'protocol_id' => $request['protocol'],
                'cycle_id'    => $request['cycle'],
                'school_id'   => $request['school'],
                'sort'        => $request['sort'],
                'percentage'  => $request['percentage'],
            ]);

            return redirect()->route('areas.index')->with('success', 'Área Evaluativa creada exitosamente.'); 
        } catch (\Exception $e) {
            return redirect()->route('areas.create')->with('error', 'El Área Evaluativa ya se encuentra registrada, revisar.'); 
        }
    }

    public function edit(Area $area) 
    {
        $protocols = Protocol::all();
        $schools   = School::all();
        $cycles    = Cycle::all();

        return view('area.edit')->with(compact('area', 'protocols', 'schools', 'cycles')); 
    }

    public function update(Request $request, Area $area) 
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'protocol'   => 'required|integer|min:1|exists:protocols,id',
            'cycle'      => 'required|integer|min:1|exists:cycles,id',
            'school'     => 'required|integer|min:1|exists:schools,id',
            'sort'       => 'required|integer',
            'percentage' => 'required|integer|min:1|max:100',
        ]);

        try {
            $area->update([
                'name'        => $request['name'],
                'protocol_id' => $request['protocol'],
                'cycle_id'    => $request['cycle'],
                'school_id'   => $request['school'],
                'sort'        => $request['sort'],
                'percentage'  => $request['percentage'],
            ]);

            return redirect()->route('areas.index')->with('success', 'Área Evaluativa actualizada exitosamente.'); 
        } catch (\Exception $e) {
            return redirect()->route('areas.edit', ['area' => $area])->with('error', 'El Área Evaluativa ya se encuentra registrada, revisar.'); 
        }
    }

    public function destroy(Area $area) 
    {
        $area->delete(); 

        return redirect()->route('areas.index')->with('success', 'Área Evaluativa eliminada exitosamente.');
    }
}
