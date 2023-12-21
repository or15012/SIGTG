<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Cycle;
use App\Models\EntityContact;
use App\Models\Parameter;
use App\Models\School;
use App\Models\TeacherCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntityController extends Controller
{

    const PERMISSIONS = [
        'index'    => 'Entities',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
    }


    public function index()
    {
        $entities = Entity::get();
        return view('entity.index', compact('entities'));
    }

    public function create()
    {
        return view('entity.create');
    }

    public function store(Request $request)
    {
        // Validación de los datos del ciclo y los parámetros
        $validatedData = $request->validate([
            'name'        => 'required|max:255',
            'address'          => 'required|max:255',
            'status'        => 'required'
        ]);

        DB::beginTransaction();
        
        $entity = Entity::create([
            'name'        => $validatedData['name'],
            'address'          => $validatedData['address'],
            'status'        => $validatedData['status'],
        ]);

        for ($i=0; $i < count($request->contact_name); $i++) { 
            EntityContact::create(['entity_id'=>$entity->id, 'name'=>$request->contact_name[$i], 'phone_number'=>$request->contact_phone_number[$i], 'email'=>$request->contact_email[$i], 'position'=>$request->contact_position[$i]]);
        }

        DB::commit();

        return redirect()->route('entities.index')->with('success', 'Entidad creada con éxito');
    }

    public function show($id)
    {
        // $cycle = Entity::findOrFail($id);
        // return view('entity.show', compact('cycle'));
    }

    public function edit($id)
    {
        $entity = Entity::find($id);
        $contact = EntityContact::where('entity_id', $id)->first();
        return view('entity.edit', compact('entity', 'contact'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'        => 'required|max:255',
            'address'          => 'required|max:255',
            'status'        => 'required'
        ]);

        DB::beginTransaction();
        $entity = Entity::find($id);
        $entity->name           = $request->name;
        $entity->address    = $request->address;
        $entity->status      = $request->status;
        $entity->save();

        EntityContact::where('entity_id', $id)->delete();
        for ($i=0; $i < count($request->contact_name); $i++) { 
            EntityContact::create(['entity_id'=>$id, 'name'=>$request->contact_name[$i], 'phone_number'=>$request->contact_phone_number[$i], 'email'=>$request->contact_email[$i], 'position'=>$request->contact_position[$i]]);
        }

        DB::commit();
        return redirect()->route('entities.index')->with('success', 'Entidad actualizada con éxito');
    }

    public function destroy($id)
    {
        // Encontrar el ciclo que se desea eliminar
        $entity = Entity::findOrFail($id);

        // Eliminar los parámetros asociados al ciclo
        // $entity->teacher_courses()->delete();
        // TeacherCourse::where('course_id', $id)->delete();

        // Eliminar el ciclo
        $entity->delete();

        return redirect()->route('entities.index')->with('success', 'Entidad eliminada con éxito');
    }
}
