<?php

namespace App\Http\Controllers;

use App\Models\Protocol;
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
}
