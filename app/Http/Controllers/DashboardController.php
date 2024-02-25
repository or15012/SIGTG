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

    public function ajaxExcelProto($cycle_id){

        $datos = DB::table('groups as gro')
        ->join('cycles as cy', 'cy.id', '=', 'gro.cycle_id')
        ->join('protocols as proto', 'proto.id', '=', 'gro.protocol_id')
        ->join('user_protocol as up', 'up.protocol_id', '=', 'proto.id')
        ->join('users as u', 'u.id', '=', 'up.user_id')
        //->join('user_group as ug', 'ug.group_id', 'groups.id') // Grupos
        ->where('u.type', 1)
        ->where('u.state', 1)
        ->where('u.school_id', session('school')['id'])
        //->where('proto.id', session('protocol')['id'])
        ->select('u.id as user_id', 'u.first_name','u.middle_name','u.last_name', 'u.second_last_name', 'u.carnet') 
        ->where('gro.cycle_id', '=', $cycle_id)
        ->get();


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
            $data[$student->user_id] = [
                'Nombre' => $student->first_name.' '.$student->middle_name.' '.$student->last_name.' '.$student->second_last_name,
                'Carnet' => $student->carnet,
            ] + $data[$student->user_id] + [
                'Notas' => count($evaluationNotes),
                'Nota final' => $totalFinalGrade,
            ];

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
        ->where('u.state', 1)
        ->where('u.school_id', session('school')['id'])
        ->where('proto.id', session('protocol')['id'])
        ->select('u.id as user_id', 'u.first_name','u.middle_name','u.last_name', 'u.second_last_name', 'u.carnet') 
        ->where('cou.cycle_id', '=', $cycle_id)
        ->get();


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
            $data[$student->user_id] = [
                'Nombre' => $student->first_name.' '.$student->middle_name.' '.$student->last_name.' '.$student->second_last_name,
                'Carnet' => $student->carnet,
            ] + $data[$student->user_id] + [
                'Notas' => count($evaluationNotes),
                'Nota final' => $totalFinalGrade,
            ];

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
}
