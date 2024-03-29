<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Course;
use App\Models\CoursePreregistration;
use App\Models\CourseRegistration;
use App\Models\Cycle;
use App\Models\Group;
use App\Models\Parameter;
use App\Models\Profile;
use App\Models\Project;
use App\Models\School;
use App\Models\TeacherCourse;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class CourseController extends Controller
{

    const PERMISSIONS = [
        'index'    => 'Courses',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
    }


    public function index()
    {
        $courses = Course::orderBy('id', 'desc')
            ->where('school_id', session('school', ['id']))
            ->paginate(10);

        return view('course.index', compact('courses'));
    }

    public function create()
    {
        $teachers = User::where('type', 2)->get();
        $cycles = Cycle::where('status', 1)->get();
        $schools = School::all();
        return view('course.create', compact('teachers', 'cycles', 'schools'));
    }

    public function store(Request $request)
    {
        // Validación de los datos del ciclo y los parámetros
        $validatedData = $request->validate([
            'name'        => 'required|max:255',
            'description'          => 'required|max:255',
            'school_id'        => 'required',
            'cycle_id'        => 'required',
        ]);

        DB::beginTransaction();

        $course = Course::create([
            'name'        => $validatedData['name'],
            'description'          => $validatedData['description'],
            'school_id'        => $validatedData['school_id'],
            'cycle_id'        => $validatedData['cycle_id'],
        ]);
        foreach ($request->teachers as $user_id) {
            TeacherCourse::create(['course_id' => $course->id, 'user_id' => $user_id]);
        }

        DB::commit();

        return redirect()->route('courses.index')->with('success', 'Curso creado con éxito');
    }

    public function show($id)
    {
        $course = Course::findOrFail($id);
        return view('course.show', compact('course'));
    }

    public function edit($id)
    {
        $teachers = User::where('type', 2)->get();
        $cycles = Cycle::where('status', 1)->get();
        $schools = School::all();
        $course = Course::find($id);
        return view('course.edit', compact('course', 'teachers', 'cycles', 'schools'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'        => 'required|max:255',
            'description'          => 'required|max:255',
            'school_id'        => 'required',
            'cycle_id'        => 'required',
        ]);

        DB::beginTransaction();
        $course = Course::find($id);
        $course->name           = $request->name;
        $course->description    = $request->description;
        $course->school_id      = $request->school_id;
        $course->cycle_id       = $request->cycle_id;

        DB::table('teacher_courses')->where('course_id', $id)->delete();
        foreach ($request->teachers as $user_id) {
            TeacherCourse::create(['course_id' => $course->id, 'user_id' => $user_id]);
        }

        if (isset($request->user_id_preregistration)) {

            CoursePreregistration::where('course_id', $id)->delete();
            foreach ($request->user_id_preregistration as $user_id) {
                $registration = CoursePreregistration::create([
                    'user_id'        => $user_id,
                    'course_id'       => $id,
                ]);
            }
        }


        if (isset($request->$request->user_id_registration)) {

            CourseRegistration::where('course_id', $id)->delete();
            foreach ($request->user_id_registration as $user_id) {
                $registration = CourseRegistration::create([
                    'user_id'        => $user_id,
                    'course_id'       => $id,
                    'status'       => 1,
                ]);
            }
        }

        $course->save();

        DB::commit();
        return redirect()->route('courses.index')->with('success', 'Curso actualizado con éxito');
    }

    public function destroy($id)
    {
        // Encontrar el ciclo que se desea eliminar
        $course = Course::findOrFail($id);

        // Eliminar los parámetros asociados al ciclo
        // $course->teacher_courses()->delete();
        TeacherCourse::where('course_id', $id)->delete();

        // Eliminar el ciclo
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Ciclo eliminado con éxito');
    }


    public function downloadTemplate()
    {
        return response()->download(public_path('uploads/courses/formato-importacion-inscripcion-cursos.xlsx'));
    }

    public function importRegistrations(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'excelFile.required' => 'Selecciona un archivo de tipo .xslx',
            ]
        );

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('courses.index')
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        try {
            if (!is_dir(Storage::path('public/uploads/courses'))) {
                mkdir(Storage::path('public/uploads/courses'), 0755, true);
            }

            $extension = $request->file('excelFile')->getClientOriginalExtension();
            $request->file('excelFile')->storeAs('uploads/courses', 'file.' . $extension, 'public');

            $reader = new Xlsx();
            $spreadsheet = $reader->load(Storage::path('public/uploads/courses/file.' . $extension));
            $worksheet = $spreadsheet->getActiveSheet();
            $listado = $worksheet->toArray(null, true);

            DB::beginTransaction();
            $withErrors = [];
            $registered = 0;

            // $notification = Notification::create(['title'=>'Alerta de usuario', 'message'=>"Tu usuario ha sido creado exitosamente.", 'user_id'=>Auth::user()->id]);
            for ($i = 1; $i < count($listado); $i++) {
                if ($listado[$i][0] == null) {
                    continue;
                }

                try {
                    $user = User::where('email', trim(strval($listado[$i][0])))->first();

                    $registration = CourseRegistration::create([
                        'user_id'        => $user->id,
                        'course_id'       => intval($listado[$i][1]),
                        'status'       => 1,
                    ]);
                    $cycle_id = Cycle::where('status', 1)->first()->id ?? 1;
                    // Crear un nuevo grupo
                    $group = Group::create([
                        'year'          => date("Y"),
                        'status'        => 1,
                        'state_id'      => 1,
                        'protocol_id'   => 4,
                        'cycle_id'      => $cycle_id
                    ]);

                    $userData = array([
                        'user_id'   => intval($user->id),
                        'status'    => 1, // Establecer status = 1 para el primer    usuario, 0 para los demás
                        'is_leader' => 1, // Establecer is_leader = 1 para el primer     usuario, 0 para los demás
                    ]);
                    $group->users()->attach($userData);

                    //creandole el perfil internamente
                    $profile                        = new Profile();
                    $profile->name                  = "Curso de especialización";
                    $profile->description           = "Curso de especialización";
                    $profile->type                  = 1;
                    $profile->group_id              = $group->id;
                    $profile->proposal_priority     = 1;
                    $profile->status                = 1;
                    $profile->save();


                    //creandole el proyecto internamente
                    $project                = new Project();
                    $project->name          =  "Curso de especialización";
                    $project->group_id      = $group->id;
                    $project->profile_id    = $profile->id;
                    $project->save();

                    $registered += 1;
                } catch (\Throwable $th) {
                    array_push($withErrors, $listado[$i][0]);
                }

                // try {
                //     Mail::to($user->email)->send(new SendMail('mail.user-created', 'Creación de usuario', ['user' => $user]));
                //     UserNotification::create(['user_id'=>$user->id, 'notification_id'=>$notification->id, 'is_read'=>0]);
                // } catch (Exception $th) {
                //     Log::info($th->getMessage());
                // }
            }

            DB::commit();

            if (count($withErrors) > 0) {
                return redirect()->route('courses.index')
                    ->with(['success' => 'Importación correcta. Inscritos: ' . $registered])
                    ->withErrors(['Los siguientes correos no pudieron inscribirse: ' . implode(',', $withErrors)]);
            }

            Storage::disk('public')->delete('courses/file.' . $extension);

            return redirect()->route('courses.index')->with('success', 'Importación correcta.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('courses.index')
                ->withErrors(['Sorry, Error Occured !', 'Asegúrese que el archivo tenga el formato correcto.'])
                ->withInput();
        }
    }


    public function getByCycle(Cycle $cycle)
    {
        // Encontrar el ciclo que se desea eliminar
        $courses = Course::where('cycle_id', $cycle->id)->get();
        return response()->json(
            [
                'success' => true,
                'courses' => $courses
            ]
        );
    }
}
