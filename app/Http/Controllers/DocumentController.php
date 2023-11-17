<?php

namespace App\Http\Controllers;

use App\Models\Consulting;
use App\Models\Group;
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

    public function authorization_letter($id)
    {
        $grupo = Group::find($id);
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        // $estudiantesGrupo = $grupo->getDetalleGrupo($grupo->id);
        $section = $phpWord->addSection();
        $imgPath = 'img\header.jpg';
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $dia = date('d');
        $mes = date('n');
        $nuevoMes =  $meses[$mes-1];
        $anio = date('Y');
       
        $section->addText("Ref: EISI-".str_pad($grupo->number, 3, '0', STR_PAD_LEFT).'-'.$grupo->year,array('name' => 'New York', 'size' => 10),array('align'=>'right'));
        $section->addText(" ");
        $section->addText(" ");

        $section->addText("Ciudad Universitaria,".$dia." de ".$nuevoMes." de ".$anio.".",array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        $section->addText(" ");
      

        $section->addText("Señores",array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        $section->addText("MIEMBROS DE LA JUNTA DIRECTIVA",array('name' => 'New York', 'size' => 10,'bold' => true),array('align'=>'left'));
        $section->addText("FACULRAD DE INGENIERIA Y ARQUITECTURA",array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        $section->addText("Presente",array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        $section->addText(" ");
        $section->addText("Respetables Señores",array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        $section->addText(" ");
    
        $section->addText("Reciban un saludo afectuoso, deseándoles  éxitos en el desempeño de sus labores. ",array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        
       
        $section->addText(" "); 

        $cuerpo = "Para dar cumplimiento al artículo 193 de Reglamento de la Gestión Académico – Administrativa de la UES, solicito la autorización de los siguientes estudiantes como parte del grupo {$grupo->number} para Trabajos de Graduación de la Escuela de Ingeniería de Sistemas Informáticos, y que han sido inscritos en el Ciclo {$grupo->cycle->number}-{$grupo->cycle->year}. Los estudiantes han sido conformados de esa forma, debido a la complejidad de los proyectos que van a desarrollar. Los estudiantes se detallan en la lista anexa. ";
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
     
        $section->addText('Ing. José María Sánchez Cornejo',array('name' => 'New York', 'size' => 10, 'bold' => false),array('align'=>'center'));
        $section->addText('Director Escuela de Ingeniería de Sistemas Informáticos',array('name' => 'New York', 'size' => 10, 'bold' => false),array('align'=>'center'));

        $section->addText(" ");
        $section->addText(" ");
        $section->addText(" ");
        $section->addText(" ");
        $section->addText(" ");

        $section->addText("Anexo. Lista de estudiantes para Trabajos de Graduación. ",array('name' => 'New York', 'size' => 10),array('align'=>'left'));
        $section->addText(" ");
        $section->addText(" ");

        foreach ($grupo->users as $user) {
            if($user->pivot->status != 1){
                continue;
            }
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

    public function downloadDocument(Request $request){
        return response()->download(storage_path('app/' . $request->file));
    }
}
