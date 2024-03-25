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

class StudentsCousesReportController extends Controller
{
    
    public function index(Request $request)
    {
        $actualCycle = Cycle::where('status', 1)->first();

        //Obtener grupos de protocolo 4
        $inscritosProtocoloCuatro = DB::table('users')
            ->join('user_group', 'users.id', '=', 'user_group.user_id')
            ->join('groups', 'user_group.group_id', '=', 'groups.id')
            ->join('schools', 'users.school_id', '=', 'schools.id')
            ->join('protocols', 'groups.protocol_id', '=', 'protocols.id')
            ->where('protocols.id', '=', '4') // Cambia 'Protocolo 4' por el nombre real del protocolo
            ->select('schools.name as escuela', 'protocols.name as protocolo', 'groups.number as grupo', 'users.*')
            ->get();

            if (session('school')['id'] != -1) {
                $inscritosProtocoloCuatro->where('u.school_id', session('school')['id']); // Usar la columna correcta en la condición where
            }
            if (isset(session('protocol')['id']) && session('protocol')['id'] != -1) {
                $inscritosProtocoloCuatro->where('pt.id', session('protocol')['id']);
            }

        // Obtener estudiantes inscritos en el protocolo 4
        $todosProtocoloCuatro = DB::table('groups as gro')
            ->join('cycles as cy', 'cy.id', '=', 'gro.cycle_id')
            ->join('protocols as proto', 'proto.id', '=', 'gro.protocol_id')
            ->join('user_protocol as up', 'up.protocol_id', '=', 'proto.id')
            ->join('users as u', 'u.id', '=', 'up.user_id')
            ->join('schools as sch', 'u.school_id', 'sch.id')
            ->leftJoin('course_registrations as cour', function ($join) {
                $join->on('cour.user_id', '=', 'up.user_id')
                    ->on('cour.course_id', '=', 'gro.protocol_id');
            })
            ->where('u.type', 1)
            ->where('u.state', 1)
            ->where('proto.id', 4)
            ->where('cy.status', 1);

        //dd($inscritosProtocoloCuatro);

        if (session('school')['id'] != -1) {
            $todosProtocoloCuatro->where('u.school_id', session('school')['id']); // Usar la columna correcta en la condición where
        }
        if (isset(session('protocol')['id']) && session('protocol')['id'] != -1) {
            $todosProtocoloCuatro->where('pt.id', session('protocol')['id']);
        }
        $todosProtocoloCuatro = $todosProtocoloCuatro->count();

        // Calcular estudiantes no inscritos restando el total menos los inscritos
        $noInscritosProtocoloCuatro = $todosProtocoloCuatro - count($inscritosProtocoloCuatro);

        //dd($todosProtocoloCuatro);
        //dd($noInscritosProtocoloCuatro);

        // Cargar los datos en la vista
        $ciclos = Cycle::latest()->take(10)->get();

        return view('reports.coursereports', compact('inscritosProtocoloCuatro', 'noInscritosProtocoloCuatro', 'ciclos'));
    }
    
    public function ajaxExcelCourses($cycle_id)
    {
        $inscritosProtocoloCuatro = DB::table('groups as gro')
            ->join('cycles as cy', 'cy.id', '=', 'gro.cycle_id')
            ->join('protocols as proto', 'proto.id', '=', 'gro.protocol_id')
            ->join('user_protocol as up', 'up.protocol_id', '=', 'proto.id')
            ->join('users as u', 'u.id', '=', 'up.user_id')
            ->join('schools as sch', 'u.school_id', 'sch.id')
            ->leftJoin('course_registrations as cour', function ($join) {
                $join->on('cour.user_id', '=', 'up.user_id')
                    ->on('cour.course_id', '=', 'gro.protocol_id');
            })
            ->where('u.type', 1)
            ->where('u.state', 1)
            ->where('proto.id', 4)
            ->where('cy.status', 1);
    
        if (session('school')['id'] != -1) {
            $inscritosProtocoloCuatro->where('u.school_id', session('school')['id']);
        }
    
        if (isset(session('protocol')['id']) && session('protocol')['id'] != -1) {
            $inscritosProtocoloCuatro->where('proto.id', session('protocol')['id']);
        }
    
        $inscritosProtocoloCuatro = $inscritosProtocoloCuatro->get();
    
        $todosProtocoloCuatro = DB::table('groups as gro')
            ->join('cycles as cy', 'cy.id', '=', 'gro.cycle_id')
            ->join('protocols as proto', 'proto.id', '=', 'gro.protocol_id')
            ->join('user_protocol as up', 'up.protocol_id', '=', 'proto.id')
            ->join('users as u', 'u.id', '=', 'up.user_id')
            ->join('schools as sch', 'u.school_id', 'sch.id')
            ->where('u.type', 1)
            ->where('u.state', 1)
            ->where('proto.id', 4)
            ->where('cy.status', 1);
    
        if (session('school')['id'] != -1) {
            $todosProtocoloCuatro->where('u.school_id', session('school')['id']);
        }
    
        if (isset(session('protocol')['id']) && session('protocol')['id'] != -1) {
            $todosProtocoloCuatro->where('proto.id', session('protocol')['id']);
        }
    
        $todosProtocoloCuatro = $todosProtocoloCuatro->count();
    
        $noInscritosProtocoloCuatro = $todosProtocoloCuatro - count($inscritosProtocoloCuatro);
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        $sheet->setCellValue('A1', 'Estado');
        $sheet->setCellValue('B1', 'Cantidad');
    
        $sheet->setCellValue('A2', 'Inscritos');
        $sheet->setCellValue('B2', count($inscritosProtocoloCuatro));
    
        $sheet->setCellValue('A3', 'No Inscritos');
        $sheet->setCellValue('B3', $noInscritosProtocoloCuatro);
    
        // Establecer anchos de columna (opcional)
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
    
        // Crear un objeto de escritura
        $writer = new Xlsx($spreadsheet);
    
        // Guardar el archivo en un directorio temporal
        $filename = 'inscritos_no_inscritos.xlsx';
        $writer->save($filename);
    
        return response()->file($filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }
}
