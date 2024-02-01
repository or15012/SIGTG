<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\Cycle;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        //  Estudiantes en un protocolo por escuela
            $datos = DB::table('groups as gro')
            ->join('cycles as cy', 'cy.id', '=', 'gro.cycle_id')
            ->join('protocols as proto', 'proto.id', '=', 'gro.protocol_id')
            ->join('user_protocol as up', 'up.protocol_id', '=', 'proto.id')
            ->join('users as u', 'u.id', '=', 'up.user_id')
            ->where('u.type', 1)
            ->where('u.state', 1)
            ->where('u.school_id', session('school')['id'])
            //->where('proto.id', session('protocol')['id'])
            ->groupBy('cy.id', 'proto.id','proto.name', 'cy.year','cy.number')
            ->select('cy.id as cycle_id', 'proto.id as protocol_id', 'proto.name as protocol_name', 'cy.year as cycle_year', 'cy.number as cycle_number', 
                DB::raw('COUNT(u.id) as cantidad_estudiantes'))
            ->get();

        // Estudiantes en un curso por protocolo
            $datos2 = DB::table('groups as gro')
            ->join('cycles as cy', 'cy.id', '=', 'gro.cycle_id')
            ->join('protocols as proto', 'proto.id', '=', 'gro.protocol_id')
            ->join('user_protocol as up', 'up.protocol_id', '=', 'proto.id')
            ->join('users as u', 'u.id', '=', 'up.user_id')
            ->join('course_registrations as coure', 'coure.user_id','=', 'up.user_id')
            ->join('courses as cou', 'cou.id','=', 'coure.course_id')
            ->where('u.type', 1)
            ->where('u.state', 1)
            ->where('u.school_id', session('school')['id'])
            ->where('proto.id', session('protocol')['id'])
            ->groupBy('cy.id', 'proto.id','cou.name', 'cy.year','cy.number')
            ->select('cy.id as cycle_id', 'proto.id as protocol_id', 'cou.name as course_name', 'cy.year as cycle_year', 'cy.number as cycle_number', 
                DB::raw('COUNT(u.id) as cantidad_estudiantes'))
            ->get();

            $ciclos = Cycle::latest()->take(10)->get();

            return view('dashboard', compact('datos','datos2','ciclos'));
    }

    public function ajaxProto($cycle_id){
        $datos = DB::table('groups as gro')
        ->join('cycles as cy', 'cy.id', '=', 'gro.cycle_id')
        ->join('protocols as proto', 'proto.id', '=', 'gro.protocol_id')
        ->join('user_protocol as up', 'up.protocol_id', '=', 'proto.id')
        ->join('users as u', 'u.id', '=', 'up.user_id')
        ->where('u.type', 1)
        ->where('u.state', 1)
        ->where('u.school_id', session('school')['id'])
        //->where('proto.id', session('protocol')['id'])
        ->groupBy('cy.id', 'proto.id','proto.name', 'cy.year','cy.number')
        ->select('cy.id as cycle_id', 'proto.id as protocol_id', 'proto.name as protocol_name', 'cy.year as cycle_year', 'cy.number as cycle_number', 
            DB::raw('COUNT(u.id) as cantidad_estudiantes'))
        ->where('gro.cycle_id', '=', $cycle_id)
        ->get();
        
        return response()->json([
            'new_datos' => $datos
        ]);

    }


    public function ajaxCourse($cycle_id){
        $datos = DB::table('groups as gro')
        ->join('cycles as cy', 'cy.id', '=', 'gro.cycle_id')
        ->join('protocols as proto', 'proto.id', '=', 'gro.protocol_id')
        ->join('user_protocol as up', 'up.protocol_id', '=', 'proto.id')
        ->join('users as u', 'u.id', '=', 'up.user_id')
        ->join('course_registrations as coure', 'coure.user_id','=', 'up.user_id')
        ->join('courses as cou', 'cou.id','=', 'coure.course_id')
        ->where('u.type', 1)
        ->where('u.state', 1)
        ->where('u.school_id', session('school')['id'])
        ->where('proto.id', session('protocol')['id'])
        ->groupBy('cy.id', 'proto.id','cou.name', 'cy.year','cy.number')
        ->select('cy.id as cycle_id', 'proto.id as protocol_id', 'cou.name as course_name', 'cy.year as cycle_year', 'cy.number as cycle_number', 
            DB::raw('COUNT(u.id) as cantidad_estudiantes'))
        ->where('cou.cycle_id', '=', $cycle_id)
        ->get();
        
        return response()->json([
            'new_datos' => $datos
        ]);

    }
}
