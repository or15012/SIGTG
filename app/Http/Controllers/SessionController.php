<?php

namespace App\Http\Controllers;

use App\Models\Protocol;
use App\Models\School;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function setProtocol(Protocol $protocol)
    {

        $protocol = array(
            "id"    => $protocol->id,
            "name"  =>  $protocol->name
        );

        session(['protocol' => $protocol]);
        return redirect('home');
    }

    public function setSchool(School $school)
    {

        $school = array(
            "id"    => $school->id,
            "name"  =>  $school->name
        );

        session(['school' => $school]);
        return redirect('home');
    }

    public function setAllProtocol($protocol)
    {

        $protocol = array(
            "id"    => -1,
            "name"  =>  "Todos los protocolos"
        );

        session(['protocol' => $protocol]);
        return redirect('home');
    }

    public function setAllSchool($school)
    {

        $school = array(
            "id"    => -1,
            "name"  =>  "Todas las escuelas"
        );

        session(['school' => $school]);
        return redirect('home');
    }
}
