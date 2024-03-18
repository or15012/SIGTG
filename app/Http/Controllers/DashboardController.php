<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\Cycle;
use App\Models\EvaluationStage;
use App\Models\EvaluationStageNote;
use App\Models\Stage;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        $actualCycle = Cycle::where('status',1)->first();

        // Grupos
        $datos3 = DB::table('groups AS g')
        ->select('sp.school_id','sch.name as school_name','p.id as protocol_id','p.name as protocol_name',DB::raw('COUNT(*) as cantidad'))
        ->join('protocols AS p', 'g.protocol_id', '=', 'p.id')
        ->join('school_protocol AS sp','sp.protocol_id','p.id')
        ->join('schools as sch', 'sch.id','sp.school_id')
        ->groupBy('sp.school_id','sch.name','p.id','p.name');
        if(session('school')['id'] != -1){
            //dd(session('school')['id']);
            $datos3->where('sp.school_id', session('school')['id']);
        }
        $datos3->where('g.cycle_id', '=', $actualCycle->id);
        $datos3 = $datos3->get();

        // Extensiones
        $datos4 = DB::table('extensions as ext') 
            ->select('sp.school_id','sch.name as school_name','p.id as protocol_id','p.name as protocol_name',DB::raw('COUNT(*) as cantidad'))
            ->join('projects AS pj','pj.id','ext.project_id')
            ->join('groups as g', 'g.id','pj.group_id')
            ->join('protocols AS p', 'g.protocol_id', '=', 'p.id')
            ->join('school_protocol AS sp','sp.protocol_id','p.id')
            ->join('schools as sch', 'sch.id','sp.school_id')
            ->groupBy('sp.school_id','sch.name','p.id','p.name');

        if(session('school')['id'] != -1){
            $datos4->where('sp.school_id', session('school')['id']);
        }
        $datos4->where('g.cycle_id', '=', $actualCycle->id);
        $datos4 = $datos4->get();

        //  Estudiantes en un protocolo por escuela
            $datos = DB::table('groups as gro')
            ->join('cycles as cy', 'cy.id', '=', 'gro.cycle_id')
            ->join('protocols as proto', 'proto.id', '=', 'gro.protocol_id')
            ->join('user_protocol as up', 'up.protocol_id', '=', 'proto.id')
            ->join('users as u', 'u.id', '=', 'up.user_id')
            ->where('u.type', 1)
            ->where('u.state', 1);

            if(session('school')['id'] != -1){
                $datos->where('u.school_id', session('school')['id']);
            }

            if(isset(session('protocol')['id']) && session('protocol')['id'] != -1){
                $datos->where('proto.id', session('protocol')['id']);
            }
            //->where('proto.id', session('protocol')['id'])
            $datos->groupBy('cy.id', 'proto.id','proto.name', 'cy.year','cy.number')
            ->select('cy.id as cycle_id', 'proto.id as protocol_id', 'proto.name as protocol_name', 'cy.year as cycle_year', 'cy.number as cycle_number', 
                DB::raw('COUNT(u.id) as cantidad_estudiantes'))
                ->where('gro.cycle_id', '=', $actualCycle->id)
            ->get();  

            $datos= $datos->get();

        // Estudiantes en un curso por protocolo
            $datos2 = DB::table('groups as gro')
            ->join('cycles as cy', 'cy.id', '=', 'gro.cycle_id')
            ->join('protocols as proto', 'proto.id', '=', 'gro.protocol_id')
            ->join('user_protocol as up', 'up.protocol_id', '=', 'proto.id')
            ->join('users as u', 'u.id', '=', 'up.user_id')
            ->join('course_registrations as coure', 'coure.user_id','=', 'up.user_id')
            ->join('courses as cou', 'cou.id','=', 'coure.course_id')
            ->where('u.type', 1)
            ->where('u.state', 1);
            if(session('school')['id'] != -1){
                $datos2->where('u.school_id', session('school')['id']);
            }
            if(isset(session('protocol')['id']) && session('protocol')['id'] != -1){
                $datos2->where('proto.id', session('protocol')['id']);
            }
            $datos2->groupBy('cy.id', 'proto.id','cou.name', 'cy.year','cy.number')
            ->select('cy.id as cycle_id', 'proto.id as protocol_id', 'cou.name as course_name', 'cy.year as cycle_year', 'cy.number as cycle_number', 
                DB::raw('COUNT(u.id) as cantidad_estudiantes'))
                ->where('gro.cycle_id', '=', $actualCycle->id)
            ->get();

            $datos2 = $datos2->get();

            $ciclos = Cycle::latest()->take(10)->get();

            return view('dashboard', compact('datos','datos2','datos3','datos4','ciclos','actualCycle'));
    }

    public function ajaxProto($cycle_id){
        $datos = DB::table('groups as gro')
        ->join('cycles as cy', 'cy.id', '=', 'gro.cycle_id')
        ->join('protocols as proto', 'proto.id', '=', 'gro.protocol_id')
        ->join('user_protocol as up', 'up.protocol_id', '=', 'proto.id')
        ->join('users as u', 'u.id', '=', 'up.user_id')
        ->where('u.type', 1)
        ->where('u.state', 1);
        if(session('school')['id'] != -1){
            $datos->where('u.school_id', session('school')['id']);
        }
        //->where('proto.id', session('protocol')['id'])
        $datos->groupBy('cy.id', 'proto.id','proto.name', 'cy.year','cy.number')
        ->select('cy.id as cycle_id', 'proto.id as protocol_id', 'proto.name as protocol_name', 'cy.year as cycle_year', 'cy.number as cycle_number', 
            DB::raw('COUNT(u.id) as cantidad_estudiantes'))
        ->where('gro.cycle_id', '=', $cycle_id);

        $datos = $datos->get();
        
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
        ->where('u.state', 1);
        if(session('school')['id'] != -1){
            $datos->where('u.school_id', session('school')['id']);
        }
        if(isset(session('protocol')['id']) &&session('protocol')['id'] != -1){
            $datos->where('proto.id', session('protocol')['id']);
        }
        $datos->groupBy('cy.id', 'proto.id','cou.name', 'cy.year','cy.number')
        ->select('cy.id as cycle_id', 'proto.id as protocol_id', 'cou.name as course_name', 'cy.year as cycle_year', 'cy.number as cycle_number', 
            DB::raw('COUNT(u.id) as cantidad_estudiantes'))
        ->where('cou.cycle_id', '=', $cycle_id);

        $datos = $datos->get();
        
        return response()->json([
            'new_datos' => $datos
        ]);

    }


    public function ajaxGroup($cycle_id){
        $datos = DB::table('groups AS g')
        ->select('sp.school_id','sch.name as school_name','p.id as protocol_id','p.name as protocol_name',DB::raw('COUNT(*) as cantidad'))
        ->join('protocols AS p', 'g.protocol_id', '=', 'p.id')
        ->join('school_protocol AS sp','sp.protocol_id','p.id')
        ->join('schools as sch', 'sch.id','sp.school_id')
        ->groupBy('sp.school_id','sch.name','p.id','p.name');
        if(session('school')['id'] != -1){
            $datos->where('sp.school_id', session('school')['id']);
        }
        $datos->where('g.cycle_id', '=', $cycle_id);

        $datos = $datos->get();
        
        
        return response()->json([
            'new_datos' => $datos
        ]);

    }

    public function ajaxExtensions($cycle_id){
        $datos = DB::table('extensions as ext') 
        ->select('sp.school_id','sch.name as school_name','p.id as protocol_id','p.name as protocol_name',DB::raw('COUNT(*) as cantidad'))
        ->join('projects AS pj','pj.id','ext.project_id')
        ->join('groups as g', 'g.id','pj.group_id')
        ->join('protocols AS p', 'g.protocol_id', '=', 'p.id')
        ->join('school_protocol AS sp','sp.protocol_id','p.id')
        ->join('schools as sch', 'sch.id','sp.school_id')
        ->groupBy('sp.school_id','sch.name','p.id','p.name');

        if(session('school')['id'] != -1){
            $datos->where('sp.school_id', session('school')['id']);
        }
        $datos->where('g.cycle_id', '=', $cycle_id);
        $datos = $datos->get();
        
        
        return response()->json([
            'new_datos' => $datos
        ]);

    }


    public function ajaxExcelProto($cycle_id){

        $datos = DB::table('groups as gro')
        ->join('cycles as cy', 'cy.id', '=', 'gro.cycle_id')
        ->join('protocols as proto', 'proto.id', '=', 'gro.protocol_id')
        ->join('user_protocol as up', 'up.protocol_id', '=', 'proto.id')
        ->join('users as u', 'u.id', '=', 'up.user_id')
        //->join('user_group as ug', 'ug.group_id', 'groups.id') // Grupos
        ->where('u.type', 1)
        ->where('u.state', 1);
        if(session('school')['id'] != -1){
            $datos->where('u.school_id', session('school')['id']);
        }
        //->where('proto.id', session('protocol')['id'])
        $datos->select('u.id as user_id', 'u.first_name','u.middle_name','u.last_name', 'u.second_last_name', 'u.carnet') 
        ->where('gro.cycle_id', '=', $cycle_id);

        $datos = $datos->get();


        $data = [];
        foreach ($datos as $student) {
            
            $evaluationNotes = EvaluationStageNote::where('user_id', $student->user_id)->get();
            $totalFinalGrade = 0;
            $notaIndex = 1;
                foreach ($evaluationNotes as $note) {
                    $evaluationStage = EvaluationStage::find($note->evaluation_stage_id);
                    $stage = Stage::find($evaluationStage->stage_id);

                    $stagePercentage = $stage->percentage;
                    $stageGrade = $note->note;

                    $totalFinalGrade += ($stageGrade * $stagePercentage) / 100;
                    $data[$student->user_id]['Nota ' . $notaIndex] = $stageGrade;
                    $notaIndex++;
                }

                if (empty($evaluationNotes)) {
                    $data[$student->user_id] = [
                        'Nombre' => $student->first_name.' '.$student->middle_name.' '.$student->last_name.' '.$student->second_last_name,
                        'Carnet' => $student->carnet,
                        'Notas' => 0,
                        'Nota final' => 0,
                    ];
                }else{
                    $data[$student->user_id] = [
                        'Nombre' => $student->first_name.' '.$student->middle_name.' '.$student->last_name.' '.$student->second_last_name,
                        'Carnet' => $student->carnet,
                        'Notas' => count($evaluationNotes),
                        'Nota final' => $totalFinalGrade,
                    ];
                }
            

        }

        


        $data = array_values($data);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Añadir encabezados
        $columnIndex = 1;
        foreach (array_keys($data[0]) as $header) {
            $sheet->setCellValueByColumnAndRow($columnIndex++, 1, $header);
        }

        // Añadir datos
        $rowIndex = 2;
        foreach ($data as $row) {
            $columnIndex = 1;
            foreach ($row as $value) {
                $sheet->setCellValueByColumnAndRow($columnIndex++, $rowIndex, $value);
            }
            $rowIndex++;
        }

        // Crear un objeto de escritura
        $writer = new Xlsx($spreadsheet);

        // Guardar el archivo en un directorio temporal
        $filename = 'students.xlsx'; 
        $writer->save($filename);

        return response()->file($filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);

    }

    public function ajaxExcelCourse($cycle_id){

        $datos = DB::table('groups as gro')
        ->join('cycles as cy', 'cy.id', '=', 'gro.cycle_id')
        ->join('protocols as proto', 'proto.id', '=', 'gro.protocol_id')
        ->join('user_protocol as up', 'up.protocol_id', '=', 'proto.id')
        ->join('users as u', 'u.id', '=', 'up.user_id')
        //->join('user_group as ug', 'ug.group_id', 'groups.id') // Grupos
        ->join('course_registrations as coure', 'coure.user_id','=', 'up.user_id')
        ->join('courses as cou', 'cou.id','=', 'coure.course_id')
        ->where('u.type', 1)
        ->where('u.state', 1);
        if(session('school')['id'] != -1){
            $datos->where('u.school_id', session('school')['id']);
        }
        if(isset(session('protocol')['id']) && session('protocol')['id'] != -1){
            $datos->where('proto.id', session('protocol')['id']);
        }
        $datos->select('u.id as user_id', 'u.first_name','u.middle_name','u.last_name', 'u.second_last_name', 'u.carnet') 
        ->where('cou.cycle_id', '=', $cycle_id);

        $datos = $datos->get();

        $data = [];
        foreach ($datos as $student) {
            
            $evaluationNotes = EvaluationStageNote::where('user_id', $student->user_id)->get();
            $totalFinalGrade = 0;
            $notaIndex = 1;

                foreach ($evaluationNotes as $note) {
                    $evaluationStage = EvaluationStage::find($note->evaluation_stage_id);
                    $stage = Stage::find($evaluationStage->stage_id);

                    $stagePercentage = $stage->percentage;
                    $stageGrade = $note->note;

                    $totalFinalGrade += ($stageGrade * $stagePercentage) / 100;
                    $data[$student->user_id]['Nota ' . $notaIndex] = $stageGrade;
                    $notaIndex++;
                }

                if (empty($evaluationNotes)) {
                    $data[$student->user_id] = [
                        'Nombre' => $student->first_name.' '.$student->middle_name.' '.$student->last_name.' '.$student->second_last_name,
                        'Carnet' => $student->carnet,
                        'Notas' => 0,
                        'Nota final' => 0,
                    ];
                }else{
                    $data[$student->user_id] = [
                        'Nombre' => $student->first_name.' '.$student->middle_name.' '.$student->last_name.' '.$student->second_last_name,
                        'Carnet' => $student->carnet,
                        'Notas' => count($evaluationNotes),
                        'Nota final' => $totalFinalGrade,
                    ];
                }

        }

        

        $data = array_values($data);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Añadir encabezados
        $columnIndex = 1;
        foreach (array_keys($data[0]) as $header) {
            $sheet->setCellValueByColumnAndRow($columnIndex++, 1, $header);
        }

        // Añadir datos
        $rowIndex = 2;
        foreach ($data as $row) {
            $columnIndex = 1;
            foreach ($row as $value) {
                $sheet->setCellValueByColumnAndRow($columnIndex++, $rowIndex, $value);
            }
            $rowIndex++;
        }

        // Crear un objeto de escritura
        $writer = new Xlsx($spreadsheet);

        // Guardar el archivo en un directorio temporal
        $filename = 'students.xlsx'; 
        $writer->save($filename);

        return response()->file($filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);

    }


    public function ajaxExcelGroups($cycle_id){

        $datos = DB::table('groups AS g')
        ->select('sp.school_id','sch.name as school_name','p.id as protocol_id','p.name as protocol_name',DB::raw('COUNT(*) as cantidad'))
        ->join('protocols AS p', 'g.protocol_id', '=', 'p.id')
        ->join('school_protocol AS sp','sp.protocol_id','p.id')
        ->join('schools as sch', 'sch.id','sp.school_id')
        ->groupBy('sp.school_id','sch.name','p.id','p.name');
        if(session('school')['id'] != -1){
            $datos->where('sp.school_id', session('school')['id']);
        }
        $datos->where('g.cycle_id', '=', $cycle_id);
        $datos = $datos->get();




        $data =  json_decode(json_encode($datos), true);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Añadir encabezados
        $columnIndex = 1;
        foreach (array_keys($data[0]) as $header) {
            $sheet->setCellValueByColumnAndRow($columnIndex++, 1, $header);
        }

        // Añadir datos
        $rowIndex = 2;
        foreach ($data as $row) {
            $columnIndex = 1;
            foreach ($row as $value) {
                $sheet->setCellValueByColumnAndRow($columnIndex++, $rowIndex, $value);
            }
            $rowIndex++;
        }

        // Crear un objeto de escritura
        $writer = new Xlsx($spreadsheet);

        // Guardar el archivo en un directorio temporal
        $filename = 'students.xlsx'; 
        $writer->save($filename);

        return response()->file($filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);

    }

    public function ajaxExcelExtensions($cycle_id){

        $datos = DB::table('extensions as ext') 
        ->select('g.id AS id', 'pj.name AS nombre de proyecto')
        ->selectRaw("GROUP_CONCAT(CONCAT(u.first_name, ' ', COALESCE(u.middle_name, ''), ' ', u.last_name, ' ', COALESCE(u.second_last_name, '')) SEPARATOR ', ') AS Integrantes")
        ->selectRaw("CASE WHEN g.status = 0 THEN 'Presentado' WHEN g.status = 1 THEN 'Aprobado' WHEN g.status = 2 THEN 'Rechazado' ELSE 'Desconocido' END AS Estado")
        ->join('projects AS pj','pj.id','ext.project_id')
        ->join('groups as g', 'g.id','pj.group_id')
        ->join('protocols AS p', 'g.protocol_id', '=', 'p.id')
        ->join('user_group AS ug', 'g.id', '=', 'ug.group_id')
        ->join('users AS u', 'ug.user_id', '=', 'u.id')
        ->join('school_protocol AS sp','sp.protocol_id','p.id')
        ->groupBy('sp.school_id')
        ->groupBy('g.id', 'pj.name', 'g.status');
        if(session('school')['id'] != -1){
            $datos= $datos->where('sp.school_id', session('school')['id']);
        }
        $datos = $datos->where('g.cycle_id', '=', $cycle_id);
        $datos = $datos->get();



        $data =  json_decode(json_encode($datos), true);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Añadir encabezados
        $columnIndex = 1;
        foreach (array_keys($data[0]) as $header) {
            $sheet->setCellValueByColumnAndRow($columnIndex++, 1, $header);
        }

        // Añadir datos
        $rowIndex = 2;
        foreach ($data as $row) {
            $columnIndex = 1;
            foreach ($row as $value) {
                $sheet->setCellValueByColumnAndRow($columnIndex++, $rowIndex, $value);
            }
            $rowIndex++;
        }

        // Crear un objeto de escritura
        $writer = new Xlsx($spreadsheet);

        // Guardar el archivo en un directorio temporal
        $filename = 'students.xlsx'; 
        $writer->save($filename);

        return response()->file($filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);

    }
}
