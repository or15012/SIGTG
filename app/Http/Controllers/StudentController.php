<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function getStudent($carnet)
    {
        // Buscar al estudiante por número de carnet
        $student = User::where('carnet', $carnet)->first();

        if ($student) {
            // Estudiante encontrado, devolver los datos en formato JSON
            return response()->json(['success' => true, 'student' => $student]);
        } else {
            // Estudiante no encontrado
            return response()->json(['success' => false, 'message' => 'Estudiante no encontrado']);
        }
    }
}
