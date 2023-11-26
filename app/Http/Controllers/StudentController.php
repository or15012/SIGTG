<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getStudent($carnet)
    {
        try {
            //Obteniendo info de user logueado
            $user = Auth::user();
            $protocol = $user->protocols()
                ->where('user_protocol.status', true)
                ->first();

            // Buscar al estudiante por número de carnet
            $student = User::join('user_protocol AS up', 'users.id', 'up.user_id')
                ->select("users.id", "users.carnet", "users.first_name", "users.middle_name", "users.last_name", "users.second_last_name")
                ->where('users.id', "!=", Auth::id())
                ->where('up.protocol_id', $protocol->id)
                ->where('up.status', true)
                ->where('carnet', $carnet)
                ->first();

            if ($student) {
                // Estudiante encontrado, devolver los datos en formato JSON
                return response()->json(['success' => true, 'student' => $student]);
            } else {
                // Estudiante no encontrado
                return response()->json(['success' => false, 'message' => 'Estudiante no encontrado']);
            }
        } catch (Exception $th) {
            Log::info($th->getMessage());
            return response()->json(['success' => false, 'message' => 'Estudiante no encontrado']);
        }
    }
}
