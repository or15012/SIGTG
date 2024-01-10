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
}
