<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Cycle;
use App\Models\Parameter;
use App\Models\School;
use App\Models\TeacherCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $courses = Course::get();
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
            TeacherCourse::create(['course_id'=>$course->id, 'user_id'=>$user_id]);
        }

        DB::commit();

        return redirect()->route('courses.index')->with('success', 'Curso creado con éxito');
    }

    public function show($id)
    {
        // $cycle = Course::findOrFail($id);
        // return view('course.show', compact('cycle'));
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
            TeacherCourse::create(['course_id'=>$course->id, 'user_id'=>$user_id]);
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
}
