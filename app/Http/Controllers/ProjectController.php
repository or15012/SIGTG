<?php

namespace App\Http\Controllers;

use App\Models\Cycle;
use App\Models\EvaluationDocument;
use App\Models\EvaluationStage;
use App\Models\EvaluationStageNote;
use App\Models\Group;
use App\Models\Project;
use App\Models\School;
use App\Models\Stage;
use App\Models\User;
use App\Models\UserProjectNote;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;


class ProjectController extends Controller
{
    const PERMISSIONS = [
        'index.student.project'    => 'Projects.students',
        'index.adviser.project'    => 'Projects.advisers'

    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index.student.project'])->only(['index']);
        $this->middleware('permission:' . self::PERMISSIONS['index.adviser.project'])->only(['coordinator.Index']);
    }

    public function index()
    {
        //obtendre el proyecto del usuario logueado
        $user = Auth::user();
        // Obtiene el año actual
        $year = date('Y');
        // Realiza una consulta para verificar si el usuario está en un grupo del año actual
        $group = Group::where('groups.year', $year)
            ->where('groups.status', 1)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->first();

        if (!isset($group)) return redirect()->route('root')->with('error', 'No tienes un grupo activo.');


        $project = Project::join('profiles as p', 'projects.profile_id', 'p.id')
            ->select('projects.id', 'projects.name', 'projects.approvement_report', 'projects.status')
            ->where('projects.group_id', $group->id)
            ->first();

        if (!isset($project)) return redirect()->route('root')->with('error', 'No tienes un proyecto activo.');

        $projectUsers = Project::join('profiles as p', 'projects.profile_id', 'p.id')
            ->join('user_group as ug', 'ug.group_id', 'projects.group_id')
            ->join('users as u', 'ug.user_id', 'u.id')
            ->where('projects.group_id', $group->id)
            ->get();

        $stages = Stage::where("protocol_id", $group->protocol_id)
            ->where('cycle_id', $group->cycle_id)
            ->where('school_id', $user->school_id)
            ->orderBy('stages.sort', 'asc')
            ->get();

        $evaluationStages = EvaluationStage::where('project_id', $project->id)
            ->select('stg.id')
            ->where('status', 1)
            ->join('stages as stg', 'evaluation_stages.stage_id', 'stg.id')
            ->get();

        $evaluationStagesNotes = EvaluationStage::where('project_id', $project->id)
            ->select('esn.user_id', 'esn.evaluation_stage_id', 'esn.note', 'stg.id')
            ->join('stages as stg', 'evaluation_stages.stage_id', 'stg.id')
            ->join('evaluation_stage_note as esn', 'evaluation_stages.id', 'esn.evaluation_stage_id')
            ->get();

        $groupCommittees = Group::select(
            'groups.id',
            'groups.number',
            'u.first_name',
            'u.middle_name',
            'u.last_name',
            'u.second_last_name',
            'u.email',
            'u.id',
            'tg.type',
        )
            ->join('teacher_group as tg', 'groups.id', 'tg.group_id')
            ->join('users as u', 'tg.user_id', 'u.id')
            ->join('protocols as p', 'groups.protocol_id', 'p.id')
            ->where('u.type', 2)
            ->where('groups.id', $group->id)
            ->get();

        $totalStages = $stages->count(); // Total de etapas
        $presentedStages = $evaluationStages->count(); // Etapas ya presentadas
        if ($totalStages > 0) {
            $progressPercentage = ($presentedStages / $totalStages) * 100;
        } else {
            $progressPercentage = 0; // En caso de que no haya etapas totales
        }

        return view('projects.index', [
            'projectUsers'          => $projectUsers,
            'project'               => $project,
            'stages'                => $stages,
            'evaluationStages'      => $evaluationStages,
            'groupCommittees'       => $groupCommittees,
            'progressPercentage'    => $progressPercentage,
            'group'                 => $group,
            'evaluationStagesNotes' => $evaluationStagesNotes,
        ]);
    }

    public function showStage(Project $project, Stage $stage)
    {
        $status = $this->disableProject($project);
      // dd($status);

        $evaluationStages = EvaluationStage::where('project_id', $project->id)
            ->where('stage_id', $stage->id)
            ->first();
        $evaluationDocuments = array();


        if (isset($evaluationStages)) {
            $evaluationDocuments = EvaluationDocument::where('evaluation_stage_id', $evaluationStages->id)
                ->get();
        } else {
            $evaluationStages                = new EvaluationStage();
            $evaluationStages->project_id    = $project->id;
            $evaluationStages->stage_id      = $stage->id;
            $evaluationStages->save();
        }


        return view('projects.show-stage', [
            "stage"                 => $stage,
            "project"               => $project,
            "evaluationStages"      => $evaluationStages,
            "evaluationDocuments"   => $evaluationDocuments,
            "status"                => $status
        ]);
    }

    public function submitStage(Request $request, EvaluationStage $evaluation_stage)
    {
        $evaluation_stage->status = $request->decision;
        $evaluation_stage->update();

        if ($evaluation_stage->status ==2) {
        // Envía el correo electrónico al asesor.
        // Le va a caer correo a todo el que tenga este rol.
        // Cuando tenga número de grupo, se manda a llamar al teacher.
        $role = 'Coordinador';
        $userRoles = User::role($role)->get();
            foreach ($userRoles as $coordinator) {
                try {
                    $emailData = [
                        'user'                  => $coordinator,
                        'evaluation_stage'      => $evaluation_stage,
                    ];
                    //dd($emailData);
                    Mail::to($coordinator->email)->send(new SendMail('mail.stage-submitted', 'Notificación de etapa enviada', $emailData));
                } catch (\Throwable $th) {
                    // Manejar la excepción
                    //dd($th);
                }
            }
        }
        if ($evaluation_stage->status == 1){
            //Envio de correo a estudiantes.
            $group = EvaluationStage::join('projects', 'projects.id', 'evaluation_stages.project_id')
                                        ->join('groups', 'groups.id', 'projects.group_id')
                                        ->where('evaluation_stages.id', $evaluation_stage->id)
                                        ->select('groups.*')
                                        ->first();

            $users = User::join('user_group as ug', 'ug.user_id', 'users.id')
                        ->where('ug.group_id', $group->id)
                        ->select('users.id', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.second_last_name', 'users.email')
                        ->get();

            foreach($users as  $students){
                try {
                    $emailData = [
                        'user'                  => $students,
                        'evaluation_stage'      => $evaluation_stage,
                    ];
                    //dd($emailData);
                    Mail::to($students->email)->send(new SendMail('mail.stage-approved', 'Notificación de etapa aprobada', $emailData));
                } catch (\Throwable $th) {
                    // Manejar la excepción
                    //dd($th);
                }
            }

        }

        //identificare si es la ultima etapa para cargar las notas finales
        return redirect()
            ->route('projects.show.stage', [$evaluation_stage->project_id, $evaluation_stage->stage_id])
            ->with('success', 'Documento guardado correctamente.');
    }

    public function modalApprovementReport(Request $request)
    {
        return view('projects.modal.attach_approvement_report', ['project_id' => $request->project_id]);
    }

    public function storeApprovementReport(Request $request)
    {
        try {
            DB::beginTransaction();
            $project = Project::find($request->project_id);
            if ($request->hasFile('approvement_report')) {
                if (is_file(storage_path('app/' . $project->approvement_report))) {
                    Storage::delete($project->approvement_report);
                }
                $project->approvement_report = $request->file('approvement_report')->storeAs('projects', $project->id . '-' . $request->file('approvement_report')->getClientOriginalName());
                $project->save();
                DB::commit();
                return redirect()->action([ProjectController::class, 'coordinatorShow'], $project->id)->with('success', 'Acta de aprobación subida exitosamente.');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->action([ProjectController::class, 'index'])->with('error', 'Algo salió mal. Intente nuevamente.');
        }
    }
    public function finish(Project $project)
    {
        return view('projects.show-finish', [
            "project"               => $project
        ]);
    }

    public function finalVolume(Project $project)
    {
        return view('projects.final-volume', [
            "project"               => $project
        ]);
    }

    public function finalVolumeStore(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'summary' => 'required|string|max:255',
            'path_final_volume' => 'required|mimes:pdf', // Esto valida que el nuevo archivo sea un PDF (puedes ajustar según tus necesidades)
        ]);

        $project->summary           = $request->summary;

        if ($request->hasFile('path_final_volume')) {
            $path_final_volume = $request->file('path_final_volume')->store('path_final_volume');
            $project->path_final_volume = $path_final_volume;
        }
        $project->update();

        return redirect()->action([ProjectController::class, 'index'])->with('success', 'Tomo final guardado.');
    }

    public function download(Project $project, $file)
    {

        $filePath = storage_path('app/' . $project->$file);
        return response()->download($filePath);
    }

    public function coordinatorIndex()
    {
        $user = Auth::user();
        $projects = Project::join('groups as g', 'g.id', 'projects.group_id')
            ->join('teacher_group as tg', 'tg.group_id', 'g.id')
            ->where('tg.user_id', $user->id)
            ->select('projects.id', 'projects.name')
            ->get();

        return view('projects.coordinator.index', [
            "projects"  => $projects
        ]);
    }

    public function coordinatorShow(Project $project)
    {
        $group = Group::find($project->group_id);
        $user = User::join('user_group as ug', 'ug.user_id', 'users.id')
            ->where('ug.group_id', $project->group_id)
            ->first();

        if (!isset($project)) return redirect()->route('root')->with('error', 'No tienes un proyecto activo.');

        $projectUsers = Project::join('profiles as p', 'projects.profile_id', 'p.id')
            ->join('user_group as ug', 'ug.group_id', 'projects.group_id')
            ->join('users as u', 'ug.user_id', 'u.id')
            ->where('projects.group_id', $group->id)
            ->get();



        $stages = Stage::where("protocol_id", $group->protocol_id)
            ->where('cycle_id', $group->cycle_id)
            ->where('school_id', $user->school_id)
            ->orderBy('stages.sort', 'asc')
            ->get();

        $evaluationStages = EvaluationStage::where('project_id', $project->id)
            ->select('stg.id')
            ->where('status', 1)
            ->join('stages as stg', 'evaluation_stages.stage_id', 'stg.id')
            ->get();

        $evaluationStagesNotes = EvaluationStage::where('project_id', $project->id)
            ->select('esn.user_id', 'esn.evaluation_stage_id', 'esn.note', 'stg.id')
            ->join('stages as stg', 'evaluation_stages.stage_id', 'stg.id')
            ->join('evaluation_stage_note as esn', 'evaluation_stages.id', 'esn.evaluation_stage_id')
            ->get();

        $groupCommittees = Group::select(
            'groups.id',
            'groups.number',
            'u.first_name',
            'u.middle_name',
            'u.last_name',
            'u.second_last_name',
            'u.email',
            'u.id',
            'tg.type',
        )
            ->join('teacher_group as tg', 'groups.id', 'tg.group_id')
            ->join('users as u', 'tg.user_id', 'u.id')
            ->join('protocols as p', 'groups.protocol_id', 'p.id')
            ->where('u.type', 2)
            ->where('groups.id', $group->id)
            ->get();

        $totalStages = $stages->count(); // Total de etapas
        $presentedStages = $evaluationStages->count(); // Etapas ya presentadas
        if ($totalStages > 0) {
            $progressPercentage = ($presentedStages / $totalStages) * 100;
        } else {
            $progressPercentage = 0; // En caso de que no haya etapas totales
        }

        return view('projects.index', [
            'projectUsers'          => $projectUsers,
            'project'               => $project,
            'stages'                => $stages,
            'evaluationStages'      => $evaluationStages,
            'groupCommittees'       => $groupCommittees,
            'progressPercentage'    => $progressPercentage,
            'group'                 => $group,
            'evaluationStagesNotes' => $evaluationStagesNotes,
        ]);
    }
    public function coordinatorSubmitFinalStage(Request $request, Project $project)
    {

        $project->status = $request->decision;
        $project->update();


        if ($request->decision == 3) {
            $group = $project->group;
            $students = Group::join('user_group as ug', 'ug.group_id', 'groups.id')
                ->where('groups.id', $group->id)
                ->get();

            foreach ($students as $student) {
                // Obtener el ID del estudiante
                $studentId = $student->user_id;

                // Encuentra todas las notas de las etapas evaluativas para el estudiante dado
                $evaluationNotes = EvaluationStageNote::where('user_id', $studentId)->get();

                $totalFinalGrade = 0;

                // Calcular la nota final ponderada para el estudiante actual
                foreach ($evaluationNotes as $note) {
                    // Obtén el porcentaje de la etapa evaluativa actual
                    $evaluationStage = EvaluationStage::find($note->evaluation_stage_id);
                    $stage = Stage::find($evaluationStage->stage_id);

                    $stagePercentage = $stage->percentage;
                    $stageGrade = $note->note;

                    // Aplica el porcentaje de la etapa evaluativa a la nota y suma al total
                    $totalFinalGrade += ($stageGrade * $stagePercentage) / 100;
                }

                // Realiza acciones con la $totalFinalGrade del estudiante si es necesario
                // Por ejemplo, guardarla en la base de datos o realizar otras operaciones

                // Puedes hacer algo como guardar la nota final en un campo del estudiante o en otra tabla
                $userProjectNote = UserProjectNote::create([
                    'user_id'       => $studentId,
                    'project_id'    => $project->id,
                    'note'          => $totalFinalGrade,

                ]);
            }
        }
        //identificare si es la ultima etapa para cargar las notas finales

        return redirect()
            ->route('projects.coordinator.show', [$project->id])
            ->with('success', 'Proyecto actualizado correctamente.');
    }

    public function disableProject(Project $project)
    {

        // Obtener fecha actual
        $status = TRUE;
        $today = new DateTime();

        $deadline = new DateTime($project->deadline);
        //dd($deadline);

        // Ver si fecha actual es menor o igual que la fecha de finalización de proyecto

        if ($today >= $deadline) {
            $status = FALSE;
        }

        return $status;
    }

}
