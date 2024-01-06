<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePreregistration;
use App\Models\Cycle;
use App\Models\Parameter;
use App\Models\School;
use App\Models\TeacherCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoursePreRegistrationController extends Controller
{

    const PERMISSIONS = [
        'index'    => 'Courses.preregistrations',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
    }


    public function index()
    {
        $courses = Course::get();
        return view('course.preregistration.index', compact('courses'));
    }

    public function create()
    {
        // $courses = Course::where('school_id', Auth::user()->school_id)->get();
        $courses = DB::select("select c.id, c.name, c.description, c.cycle_id, c.school_id, cyc.number as cycle_number FROM cycles cyc join courses c on cyc.id = c.cycle_id where cyc.status = 1 and c.school_id = ? GROUP BY c.id, c.name, c.description, c.cycle_id, c.school_id, cycle_number", [Auth::user()->school_id]);

        $alreadyPreregistered = DB::select("SELECT count(*) count FROM cycles cyc join courses c on cyc.id = c.cycle_id join course_preregistrations cp on c.id = cp.course_id where cyc.status = 1 and cp.user_id = ?", [Auth::user()->id])[0]->count;

        if ($alreadyPreregistered > 0) {
            $courses = [];
        }
        return view('course.preregistration.create', compact('courses'));
    }

    public function store(Request $request)
    {
        // Validación de los datos del ciclo y los parámetros
        $validatedData = $request->validate([
            'course_id' =>'required'
        ], ['course_id.required'=>'Elija al menos un curso']);

        try {
            DB::beginTransaction();

        $checkeds = 0;
        for ($i=0; $i < count($request->course_id); $i++) {
            if ($request->is_checked[$i] == 0) {
                continue;
            }
            CoursePreregistration::create(['user_id'=>Auth::user()->id, 'course_id'=>$request->course_id[$i]]);
            $checkeds += 1;
        }

        if ($checkeds == 0) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Debe elegir al menos un curso.']);    
        }

        DB::commit();

        return redirect()->route('home')->with('success', 'Se ha pre-inscrito a los cursos correctamente.');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['Algo salió mal. Intente nuevamente.']);
        }
    }

}
