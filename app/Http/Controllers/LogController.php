<?php

namespace App\Http\Controllers;

use App\Models\Cycle;
use App\Models\Parameter;
use App\Models\Log;
use App\Models\Stage;
use Illuminate\Http\Request;

class LogController extends Controller
{
    const PERMISSIONS = [
        'index'    => 'Logs',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:' . self::PERMISSIONS['index'])->only(['index']);
    }


    public function index()
    {
        $logs = Log::join('users', 'users.id', '=', 'logs.id_user')
        ->get(['logs.*', 'users.first_name as nombre', 'users.last_name as apellido', 'users.email as correo']);
        return view('logs.index', compact('logs'));
    }



}
