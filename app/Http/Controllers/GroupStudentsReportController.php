<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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

class GroupStudentsReportController extends Controller
{

    public function generateReport()
    {
        $reportData = Users::join('user_group', 'users.id', '=', 'user_group.user_id')
            ->join('groups', 'user_group.group_id', '=', 'groups.id')
            ->join('protocols', 'groups.protocol_id', '=', 'protocols.id')
            ->join('schools', 'protocols.school_id', '=', 'schools.id')
            ->join('cycles', 'groups.cycle_id', '=', 'cycles.id')
            ->where('users.type', 1) // Agrega esta condición para filtrar por 'type' igual a 1
            ->select(
                'schools.name as Escuela',
                'protocols.name as Protocolo',
                'groups.number as Grupo',
                DB::raw("CONCAT(users.first_name, ' ', users.second_name, ' ', users.last_name, ' ', users.second_last_name) as Estudiante")
            )
            ->get();

        // $reportData ahora contiene la información necesaria para tus reportes.

        // Puedes utilizar $reportData para generar tu reporte en Excel o PDF, 
        // o devolverlo a la vista para mostrarlo en HTML, según tus necesidades.

        // Ejemplo de exportación a Excel utilizando el paquete Maatwebsite\Excel
        Excel::create('reporte_excel', function($excel) use ($reportData) {
            $excel->sheet('Hoja 1', function($sheet) use ($reportData) {
                $sheet->fromArray($reportData);
            });
        })->download('xlsx');

        // Ejemplo de exportación a PDF utilizando el paquete barryvdh/laravel-dompdf
        $pdf = PDF::loadView('reporte_pdf', ['reportData' => $reportData]);
        return $pdf->download('reporte_pdf.pdf');
    }
}
