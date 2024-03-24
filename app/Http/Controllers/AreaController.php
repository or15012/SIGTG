<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Protocol;
use App\Models\School;
use App\Models\Area;
use App\Models\SubArea;

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
        $areas = Area::with('protocol', 'school')->get();
        return view('area.index', compact('areas'));
    }

    public function create()
    {
        $protocols = Protocol::all();
        $schools   = School::all();

        return view('area.create')->with(compact('protocols', 'schools'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'protocol'   => 'required|integer|min:1|exists:protocols,id',
            'school'     => 'required|integer|min:1|exists:schools,id',
        ]);

        try {
            $area = Area::create([
                'name'        => $request['name'],
                'protocol_id' => $request['protocol'],
                'school_id'   => $request['school'],

            ]);

            return redirect()->route('areas.index')->with('success', 'Área creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('areas.create')->with('error', 'El Área ya se encuentra registrada, revisar.');
        }
    }

    public function edit(Area $area)
    {
        $protocols = Protocol::all();
        $schools   = School::all();

        return view('area.edit')->with(compact('area', 'protocols', 'schools'));
    }

    public function update(Request $request, Area $area)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'protocol'   => 'required|integer|min:1|exists:protocols,id',
            'school'     => 'required|integer|min:1|exists:schools,id',
        ]);

        try {
            $area->update([
                'name'        => $request['name'],
                'protocol_id' => $request['protocol'],
                'school_id'   => $request['school'],
            ]);

            return redirect()->route('areas.index')->with('success', 'Área actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('areas.edit', ['area' => $area])->with('error', 'El Área ya se encuentra registrada, revisar.');
        }
    }

    public function destroy(Area $area)
    {
        $area->delete();

        return redirect()->route('areas.index')->with('success', 'Área eliminada exitosamente.');
    }


    public function subareasIndex()
    {
        $subareas = SubArea::with('area')->get();
        return view('area.subareas.index', compact('subareas'));
    }

    public function subareasCreate()
    {
        $areas = Area::where('protocol_id', session('protocol')['id'])
            ->where('school_id', session('school')['id'])->get();

        return view('area.subareas.create')->with(compact('areas'));
    }

    public function subareasStore(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'area_id'   => 'required|integer|min:1|exists:areas,id',
        ]);

        try {
            $area = SubArea::create([
                'name'      => $request['name'],
                'area_id'   => $request['area_id'],
            ]);

            return redirect()->route('areas.subareas.index')->with('success', 'subarea creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('areas.subareas.create')->with('error', 'El subarea ya se encuentra registrada, revisar.');
        }
    }

    public function subareasEdit(SubArea $subarea)
    {
        $areas = Area::all();
        return view('area.subareas.edit')->with(compact('subarea', 'areas'));
    }

    public function subareasUpdate(Request $request, SubArea $subarea)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'area_id'   => 'required|integer|min:1',
        ]);

        try {
            $subarea->update([
                'name'      => $request['name'],
                'area_id'   => $request['area_id'],
            ]);

            return redirect()->route('areas.subareas.index')->with('success', 'Subárea actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('areas.subareas.edit', ['subarea' => $subarea])->with('error', 'Subárea ya se encuentra registrada, revisar.');
        }
    }

    public function subareasDestroy(SubArea $subarea)
    {
        $subarea->delete();

        return redirect()->route('areas.subareas.index')->with('success', 'Subárea eliminada exitosamente.');
    }
}
