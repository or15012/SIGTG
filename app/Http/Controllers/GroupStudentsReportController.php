<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\PDF;
use App\Models\Group;
use App\Models\Cycle;
use App\Models\EvaluationStage;
use App\Models\EvaluationStageNote;
use App\Models\Stage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

class GroupStudentsReportController extends Controller
{
    public function index(Request $request)
    {
        $actualCycle = Cycle::where('status', 1)->first();

        $detallesGrupos = DB::table('users')
        ->join('user_group', 'users.id', '=', 'user_group.user_id')
        ->join('groups', 'user_group.group_id', '=', 'groups.id')
        ->join('schools', 'users.school_id', '=', 'schools.id')
        ->join('protocols', 'groups.protocol_id', '=', 'protocols.id')
        ->select('protocols.name as protocol', DB::raw('COUNT(users.id) as cantidad_estudiantes'))
        ->groupBy('protocols.name')
        ->get();
    

        if (session('school')['id'] != -1) {
            $detallesGrupos->where('u.school_id', session('school')['id']); // Usar la columna correcta en la condición where
        }
        if (isset(session('protocol')['id']) && session('protocol')['id'] != -1) {
            $detallesGrupos->where('pt.id', session('protocol')['id']);
        }

        //dd($detallesGrupos);

        // Cargar los datos en la vista
        $ciclos = Cycle::latest()->take(10)->get();

        return view('reports.groupreports', compact('detallesGrupos', 'ciclos'));
    }

    /*
     $detallesGrupos = DB::table('users')
        ->join('user_group', 'users.id', '=', 'user_group.user_id')
        ->join('groups', 'user_group.group_id', '=', 'groups.id')
        ->join('schools', 'users.school_id', '=', 'schools.id')
        ->join('protocols', 'groups.protocol_id', '=', 'protocols.id')
        ->select('schools.name as school', 'protocols.name as protocol', 'groups.number as group', 'users.*')
        ->get();*/



}
