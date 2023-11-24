<?php

namespace App\Http\Controllers;

use App\Models\Consulting;
use App\Models\Group;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function authorizationLetter($id)
    {
        $grupo = Group::find($id);
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $users = $grupo->users()->wherePivot('status', 1)->get();
        $school = $users[0]->school;
        $section = $phpWord->addSection();
        $imgPath = 'img\header.jpg';
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $dia = date('d');
        $mes = date('n');
        $nuevoMes =  $meses[$mes-1];
        $anio = date('Y');

        $section->addText("Ref: ".($school->acronym??'')."-".str_pad($grupo->number, 3, '0', STR_PAD_LEFT).'-'.$grupo->year,array('name' => 'New York', 'size' => 10),array('align'=>'right'));
        $section->addText(" ");
        $section->addText(" ");

        $section->addText("Ciudad Universitaria,".$dia." de ".$nuevoMes." de ".$anio.".",array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        $section->addText(" ");


        $section->addText("Señores",array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        $section->addText("MIEMBROS DE LA JUNTA DIRECTIVA",array('name' => 'New York', 'size' => 10,'bold' => true),array('align'=>'left'));
        $section->addText("FACULTAD DE INGENIERIA Y ARQUITECTURA",array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        $section->addText("Presente",array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        $section->addText(" ");
        $section->addText("Respetables Señores",array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        $section->addText(" ");

        $section->addText("Reciban un saludo afectuoso, deseándoles  éxitos en el desempeño de sus labores. ",array('name' => 'New York', 'size' => 10),array('align'=>'left'));


        $section->addText(" ");

        $cuerpo = "Para dar cumplimiento al artículo 193 de Reglamento de la Gestión Académico – Administrativa de la UES, solicito la autorización de los siguientes estudiantes como parte del grupo {$grupo->number} para Trabajos de Graduación de la ".($school->name??'').", y que han sido inscritos en el Ciclo {$grupo->cycle->number}-{$grupo->cycle->year}. Los estudiantes han sido conformados de esa forma, debido a la complejidad de los proyectos que van a desarrollar. Los estudiantes se detallan en la lista anexa.";
        $section->addText($cuerpo,array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        $section->addText(" ");
        $section->addText(" ");
        $section->addText("Agradeciendo su atención prestada, me suscribo de ustedes. ",array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        $section->addText(" ");
        $section->addText(" ");

        $section->addText('"HACIA LA LIBERTAD POR LA CULTURA"',array('name' => 'New York', 'size' => 10, 'bold' => true),array('align'=>'center'));

        $section->addText(" ");
        $section->addText(" ");
        $section->addText(" ");

        $section->addText($school->director??'', array('name' => 'New York', 'size' => 10, 'bold' => false),array('align'=>'center'));
        $section->addText('Director '.$school->name??'',array('name' => 'New York', 'size' => 10, 'bold' => false),array('align'=>'center'));

        $section->addText(" ");
        $section->addText(" ");
        $section->addText(" ");
        $section->addText(" ");
        $section->addText(" ");

        $section->addText("Anexo. Lista de estudiantes para Trabajos de Graduación. ",array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        $section->addText(" ");
        $section->addText(" ");

        foreach ($users as $user) {
            $section->addText('• '.$user->first_name.' '.$user->middle_name.' '.$user->last_name.' '.$user->second_last_name.' '.$user->carnet.' '.($user->pivot->is_leader==1?'(Líder)':'(Miembro)'), array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        }

        // $section->addText(" ");
        // $section->addText('Lista de grupos de cuatro y cinco estudiantes',array('name' => 'New York', 'size' => 10, 'bold' => false),array('align'=>'center'));
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path('frmt-autoriza-grupos-cuatro-cinco.docx'));
        } catch (Exception $e) {
        }

        return response()->download(storage_path('frmt-autoriza-grupos-cuatro-cinco.docx'));
    }



    public function approvement_report($project_id){
    	$phpWord = new \PhpOffice\PhpWord\PhpWord();

        $project = Project::find($project_id);

        $advisers = $project->group->teacherUsers()->selectRaw("concat(first_name, ' ', ifnull(middle_name, ''), ' ', last_name, ifnull(second_last_name, '')) as name")->wherePivot('type', 0)->get();

        $users = $project->user_project_note()->get();

        $school = $users[0]->school;
        $section = $phpWord->addSection();
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $dia = date('d');
        $mes = date('n');
        $nuevoMes =  $meses[$mes-1];
        $anio = date('Y');
        // $section->addImage(public_path($imgPath),array(
        // 'width'            => 430,
        // 'wrappingStyle'    => 'square',
        // 'marginTop'        => 180,
        // 'positioning'      => 'relative',
        // 'posHorizontalRel' => 'margin',
        // 'posVerticalRel'   => 'line',
        // ));

        $titulo = "ACTA DE APROBACIÓN DE TRABAJO DE GRADUACIÓN";
        $section->addText($titulo,array('name' => 'Times New Roman', 'size' => 14, 'bold' => true),array('align'=>'center')); //  FONT /PARRAFO
        $section->addText(" ");
        $section->addText(" ");
        $tituloDatosGenerales = "DATOS GENERALES";
        $section->addText($tituloDatosGenerales,array('name' => 'Times New Roman', 'size' => 10, 'bold' => true),array('align'=>'center'));

       $tableStyle = array('borderSize' => 6, 'borderColor' => '999999');
       $phpWord->addTableStyle('Datos Generales', $tableStyle);


       $table = $section->addTable('Datos Generales');
       $table->addRow();
       $cell = $table->addCell(10000);
       $cell->addText("CARRERA:",array('name' => 'Arial', 'size' => 10, 'bold' => true));
       $cell->addText("\n");
       $cell->addText($school->name, array('name' => 'Calibri', 'size' => 14, 'bold' => true),array('align'=>'center'));
       $table->addRow();
       $cell = $table->addCell(10000);
       $cell->addText("CICLO DE INSCRIPCION DEL TRABAJO: Ciclo ".$project->group->cycle->number." - ".$project->group->cycle->year, array('name' => 'Calibri', 'size' => 11, 'bold' => true));
       $section->addText(" ");

       $tituloTrabajo = "NOMBRE DEL TRABAJO DE GRADUACION:";
       $section->addText($tituloTrabajo, array('name' => 'Times New Roman', 'size' => 10, 'bold' => true),array('align'=>'center'));

       $phpWord->addTableStyle('Nombre TDG', $tableStyle);
       $table = $section->addTable('Nombre TDG', $tableStyle);
       $table->addRow();
       $cell = $table->addCell(10000);
       $cell->addText($project->name, array('name' => 'Arial', 'size' => 10, 'bold' => true));
       $section->addText(" ");

       $phpWord->addTableStyle('Datos Grupo', $tableStyle);
       $table = $section->addTable('Datos Grupo');
       $table->addRow();
       $cell = $table->addCell(10000);
       $cell->addText("DOCENTE ASESOR: ".implode(', ', $advisers->pluck('name')->toArray()),array('name' => 'Arial', 'size' => 10, 'bold' => true));
       $table->addRow();
       $cell = $table->addCell(10000);
       $cell->addText("ESTUDIANTES ",array('name' => 'Arial', 'size' => 10, 'bold' => true));

       foreach ($users as $key => $user) {
            $cell->addText('• '.$user->first_name.' '.$user->middle_name.' '.$user->last_name.' '.$user->second_last_name.' '.$user->carnet.' '.($user->pivot->is_leader==1?'(Líder)':'(Miembro)'), array('name' => 'New York', 'size' => 10),array('align'=>'left'));
       }

       $section->addText(" ");
       $section->addText(" Habiendo sido subsanadas las observaciones se otorgar la nota de aprobación del trabajo de graduación de:",array('name' => 'Times New Roman', 'size' => 12, 'bold' => false),array('align'=>'left'));

      $section->addText(" ");
      $section->addText(" ");
      $table = $section->addTable('Detalle Grupo');
      $table->addRow();
      $cell = $table->addCell(7000);
      $cell->addText("NOMBRES",array('name' => 'Arial', 'size' => 11, 'underline' => 'single'),array('align'=>'left'));
      $cell = $table->addCell(3000);
      $cell->addText("NOTA FINAL",array('name' => 'Arial', 'size' => 11, 'underline' => 'single'),array('align'=>'right'));

      foreach ($users as $user) {
        $table->addRow();
        $cell = $table->addCell(7000);
        $cell->addText($user->carnet." - ".strtoupper($user->first_name).' '.strtoupper($user->middle_name).' '.strtoupper($user->last_name).' '.strtoupper($user->second_last_name), array('name' => 'Arial', 'size' => 11),array('align'=>'left'));
        $cell = $table->addCell(3000);
        $cell->addText($user->pivot->note, array('name' => 'Arial', 'size' => 11),array('align'=>'right'));
      }

      $section->addText(" ");
      $section->addText(" ");
      $section->addText("Ciudad Universitaria, el día ".$dia." del mes de ".$nuevoMes." de ".$anio.".",array('name' => 'Times New Roman', 'size' => 12));
      $section->addText(" ");
      $section->addText(" ");
      $section->addText('"HACIA LA LIBERTAD POR LA CULTURA"',array('name' => 'Calibri', 'size' => 11, 'bold' => true),array('align'=>'center'));
      $section->addText(" ");
      $section->addText(" ");
      $section->addText(" ");
      $section->addText(" ");
      $section->addText(" ");
      $section->addText($school->director??'', array('name' => 'Calibri', 'size' => 12, 'bold' => false),array('align'=>'center'));
      $section->addText($school->name??'', array('name' => 'Calibri', 'size' => 12, 'bold' => false),array('align'=>'center'));

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path('frmt-acta-aprobacion-1.docx'));
        } catch (Exception $e) {
        }


        return response()->download(storage_path('frmt-acta-aprobacion-1.docx'));
    }

    public function downloadDocument(Request $request){
        return response()->download(storage_path('app/' . $request->file));
    }
}
