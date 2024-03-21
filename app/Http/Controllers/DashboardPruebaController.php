<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

class DashboardPruebaController extends Controller
{
    public function index(Request $request)
    {

        $datos5 = DB::table('projects as pj')
            ->select(
                'pj.status',
                DB::raw('COUNT(*) as count')
            )
            ->join('groups AS gr', 'gr.id', 'pj.group_id')
            ->join('protocols as pt', 'pt.id', 'gr.protocol_id')
            ->join('user_group as ug', 'ug.group_id', 'gr.id')
            ->join('user_protocol as up', 'up.protocol_id', 'pt.id')
            ->join('users as us', 'us.id', 'up.user_id')
            ->join('schools as sch', 'us.school_id', 'sch.id')
            ->groupBy('pj.status')
            ->orderBy('pj.status');

        if (session('school')['id'] != -1) {
            $datos5->where('us.school_id', session('school')['id']); // Usar la columna correcta en la condición where
        }
        if (isset(session('protocol')['id']) && session('protocol')['id'] != -1) {
            $datos5->where('pt.id', session('protocol')['id']);
        }
        $datos5 = $datos5->get();
        //dd($datos5);
        $ciclos = Cycle::latest()->take(10)->get();

        return view('dashboards.index', compact('datos5', 'ciclos'));
    }


    // Estados de proyecto
    public function ajaxExcelStatus($cycle_id)
    {
        $datos = DB::table('projects as pj')
            ->select(
                'pt.name as protocol_name',
                'sch.name as name_school',
                'gr.number as group_number',
                'pj.name as project_name',
                'us.first_name as name_student',
                'us.middle_name as second_name_student',
                'us.last_name as last_name_student',
                'us.second_last_name as second_last_name_student',
                'us.carnet as carnet_student',
                'pj.status'
            )
            ->join('groups AS gr', 'gr.id', 'pj.group_id')
            ->join('protocols as pt', 'pt.id', 'gr.protocol_id')
            ->join('user_group as ug', 'ug.group_id', 'gr.id')
            ->join('user_protocol as up', 'up.protocol_id', 'pt.id')
            ->join('users as us', 'us.id', 'up.user_id')
            ->join('schools as sch', 'us.school_id', 'sch.id')
            ->groupBy(
                'pt.name',
                'sch.name',
                'gr.number',
                'pj.name',
                'pj.status',
                'us.first_name',
                'us.middle_name',
                'us.last_name',
                'us.second_last_name',
                'us.carnet'
            )
            ->orderBy('pj.status');

        if (session('school')['id'] != -1) {
            $datos->where('us.school_id', session('school')['id']);
        }

        $datos->where('gr.cycle_id', '=', $cycle_id);

        $datos = $datos->get();

        // Convertir los estados a texto
        foreach ($datos as $dato) {
            if ($dato->status == 1) {
                $dato->status_text = 'Iniciado';
            } elseif ($dato->status == 2) {
                $dato->status_text = 'En proceso';
            } elseif ($dato->status == 3) {
                $dato->status_text = 'Finalizado';
            } else {
                $dato->status_text = ''; // Manejar otro caso si es necesario
            }
        }

        $data =  json_decode(json_encode($datos), true);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $rowIndex = 1; // Comenzar desde la primera fila

        if (count($datos) > 1 && session('protocol')['id'] === -1) {
            // Mostrar una hoja por protocolo cuando se seleccionan múltiples protocolos
            $currentProtocol = ''; // Variable para rastrear el protocolo actual
            $sheetIndex = 0; // Índice de la hoja en el libro

            foreach ($datos as $row) {
                if ($row->protocol_name !== $currentProtocol) {
                    $currentProtocol = $row->protocol_name;
                    $sheetIndex++;
                    $sheet = $spreadsheet->createSheet($sheetIndex); // Crear una nueva hoja
                    // Asignar nombres a las hojas según el protocolo
                    switch ($currentProtocol) {
                        case 'Trabajo de Investigación':
                            $sheet->setTitle('TDI');
                            break;
                        case 'Pasantía de Práctica Profesional':
                            $sheet->setTitle('PPP');
                            break;
                        case 'Pasantía de Investigación':
                            $sheet->setTitle('PPI');
                            break;
                        case 'Cursos de Especialización':
                            $sheet->setTitle('CDE');
                            break;
                        case 'Examen General Técnico Profesional':
                            $sheet->setTitle('EXG');
                            break;
                    }
                    $rowIndex = 1; // Reiniciar el índice de fila para la nueva hoja

                    // Agregar el nombre de la escuela con formato y estilo
                    $sheet->getStyle('A' . $rowIndex)->getFont()->setBold(true);
                    $sheet->setCellValue('A' . $rowIndex, 'Nombre de escuela: ' . $row->name_school);
                    $sheet->mergeCells('A' . $rowIndex . ':E' . $rowIndex); // Fusionar celdas para la escuela
                    $sheet->getStyle('A' . $rowIndex)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); // Alinear a la izquierda
                    $rowIndex++;

                    // Agregar el nombre del protocolo con formato y estilo
                    $sheet->getStyle('A' . $rowIndex)->getFont()->setBold(true);
                    $sheet->setCellValue('A' . $rowIndex, 'Nombre de protocolo: ' . $row->protocol_name);
                    $sheet->mergeCells('A' . $rowIndex . ':E' . $rowIndex); // Fusionar celdas para el protocolo
                    $sheet->getStyle('A' . $rowIndex)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); // Alinear a la izquierda
                    $rowIndex++;


                    // Añadir encabezados de columna para la nueva hoja
                    $sheet->setCellValue('A' . $rowIndex, 'Número de grupo');
                    $sheet->setCellValue('B' . $rowIndex, 'Nombre de proyecto');
                    $sheet->setCellValue('C' . $rowIndex, 'Estado de proyecto');
                    $sheet->setCellValue('D' . $rowIndex, 'Nombres de estudiante');
                    $sheet->setCellValue('E' . $rowIndex, 'Apellidos de estudiante');
                    $sheet->setCellValue('F' . $rowIndex, 'CARNET');
                    $rowIndex++;
                }

                // Añadir datos correspondientes en la hoja actual
                $sheet->setCellValue('A' . $rowIndex, $row->group_number);
                $sheet->setCellValue('B' . $rowIndex, $row->project_name);
                $sheet->setCellValue('C' . $rowIndex, $row->status_text); // Usar el nuevo campo 'status_text' en lugar de 'status'
                $sheet->setCellValue('D' . $rowIndex, $row->name_student . ' ' . $row->second_name_student);
                $sheet->setCellValue('E' . $rowIndex, $row->last_name_student . ' ' . $row->second_last_name_student);
                $sheet->setCellValue('F' . $rowIndex, $row->carnet_student);
                $rowIndex++;
            }
        } else {
            // Mostrar una sola hoja cuando se selecciona una escuela y un solo protocolo
            // o se seleccionan todas las escuelas y protocolos simultáneamente
            // Agregar el nombre de la escuela con formato y estilo
            $sheet->getStyle('A' . $rowIndex)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $rowIndex, 'Nombre de escuela: ' . $datos[0]->name_school);
            $sheet->mergeCells('A' . $rowIndex . ':E' . $rowIndex); // Fusionar celdas para la escuela
            $sheet->getStyle('A' . $rowIndex)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); // Alinear a la izquierda
            $rowIndex++;

            // Agregar el nombre del protocolo con formato y estilo
            $sheet->getStyle('A' . $rowIndex)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $rowIndex, 'Nombre de protocolo: ' . $datos[0]->protocol_name);
            $sheet->mergeCells('A' . $rowIndex . ':E' . $rowIndex); // Fusionar celdas para el protocolo
            $sheet->getStyle('A' . $rowIndex)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); // Alinear a la izquierda
            $rowIndex++;

            // Añadir encabezados de columna para la hoja única
            $sheet->setCellValue('A' . $rowIndex, 'Número de grupo');
            $sheet->setCellValue('B' . $rowIndex, 'Nombre de proyecto');
            $sheet->setCellValue('C' . $rowIndex, 'Estado de proyecto');
            $sheet->setCellValue('D' . $rowIndex, 'Nombres de estudiante');
            $sheet->setCellValue('E' . $rowIndex, 'Apellidos de estudiante');
            $sheet->setCellValue('F' . $rowIndex, 'CARNET');
            $rowIndex++;

            // Añadir datos correspondientes en la hoja única
            foreach ($datos as $row) {
                if ($row->protocol_name == session('protocol')['name']) { // Filtrar por el protocolo seleccionado
                    // Añadir datos correspondientes en la hoja
                    $sheet->setCellValue('A' . $rowIndex, $row->group_number);
                    $sheet->setCellValue('B' . $rowIndex, $row->project_name);
                    $sheet->setCellValue('C' . $rowIndex, $row->status_text);
                    $sheet->setCellValue('D' . $rowIndex, $row->name_student . ' ' . $row->second_name_student);
                    $sheet->setCellValue('E' . $rowIndex, $row->last_name_student . ' ' . $row->second_last_name_student);
                    $sheet->setCellValue('F' . $rowIndex, $row->carnet_student);
                    $rowIndex++;
                }
            }
        }

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            // Establecer anchos de columna en cada hoja
            $sheet->getColumnDimension('A')->setWidth(20);
            $sheet->getColumnDimension('B')->setWidth(50);
            $sheet->getColumnDimension('C')->setWidth(25);
            $sheet->getColumnDimension('D')->setWidth(40);
            $sheet->getColumnDimension('E')->setWidth(40);
            $sheet->getColumnDimension('F')->setWidth(40);
        }
        // Crear un objeto de escritura
        $writer = new Xlsx($spreadsheet);

        // Guardar el archivo en un directorio temporal -
        $filename = 'projects.xlsx';
        $writer->save($filename);

        return response()->file($filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])->deleteFileAfterSend(true);
    }
}
