<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Group;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
            $datos = DB::table('groups as gro')
            ->join('cycles as cy', 'cy.id', '=', 'gro.cycle_id')
            ->join('protocols as proto', 'proto.id', '=', 'gro.protocol_id')
            ->join('user_protocol as up', 'up.protocol_id', '=', 'proto.id')
            ->join('users as u', 'u.id', '=', 'up.user_id')
            ->where('u.type', 1)
            ->where('u.state', 1)
            ->groupBy('cy.id', 'proto.id','proto.name', 'cy.year','cy.number')
            ->select('cy.id as cycle_id', 'proto.id as protocol_id', 'proto.name as protocol_name', 'cy.year as cycle_year', 'cy.number as cycle_number', 
                DB::raw('COUNT(u.id) as cantidad_estudiantes'))
            ->get();

            return view('dashboard', compact('datos'));
    }
}
