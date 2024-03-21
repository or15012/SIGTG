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

        // Obtener la ruta de origen
        $previousRoute = url()->previous();

        if (strpos($previousRoute, '/dashboard') !== false) {
            // Si la ruta de origen es "/dashboard", redireccionar a "/dashboard"
            return redirect()->route('dashboard.index');
        } else if (strpos($previousRoute, '/dashboards') !== false) {
            // Si la ruta de origen es "/dashboards", redireccionar a "/dashboards"
            return redirect()->route('dashboards.index');
        } else {
            // Si la ruta de origen no es "/roles", redireccionar a "/home" por defecto
            return redirect('home');
        }
    }

    public function setSchool(School $school)
    {

        $school = array(
            "id"    => $school->id,
            "name"  =>  $school->name
        );

        session(['school' => $school]);
        // Obtener la ruta de origen
        $previousRoute = url()->previous();
        if (strpos($previousRoute, '/dashboard') !== false) {
            // Si la ruta de origen es "/dashboard", redireccionar a "/dashboard"
            return redirect()->route('dashboard.index');
        } else if (strpos($previousRoute, '/dashboards') !== false) {
            // Si la ruta de origen es "/dashboards", redireccionar a "/dashboards"
            return redirect()->route('dashboards.index');
        } else {
            // Si la ruta de origen no es "/roles", redireccionar a "/home" por defecto
            return redirect('home');
        }
    }

    public function setAllProtocol($protocol)
    {

        $protocol = array(
            "id"    => -1,
            "name"  =>  "Todos los protocolos"
        );

        session(['protocol' => $protocol]);

        // Obtener la ruta de origen
        $previousRoute = url()->previous();
        if (strpos($previousRoute, '/dashboard') !== false) {
            // Si la ruta de origen es "/dashboard", redireccionar a "/dashboard"
            return redirect()->route('dashboard.index');
        } else if (strpos($previousRoute, '/dashboards') !== false) {
            // Si la ruta de origen es "/dashboards", redireccionar a "/dashboards"
            return redirect()->route('dashboards.index');
        } else {
            // Si la ruta de origen no es "/roles", redireccionar a "/home" por defecto
            return redirect('home');
        }
    }

    public function setAllSchool($school)
    {

        $school = array(
            "id"    => -1,
            "name"  =>  "Todas las escuelas"
        );

        session(['school' => $school]);
        // Obtener la ruta de origen
        $previousRoute = url()->previous();
        if (strpos($previousRoute, '/dashboard') !== false) {
            // Si la ruta de origen es "/dashboard", redireccionar a "/dashboard"
            return redirect()->route('dashboard.index');
        } else if (strpos($previousRoute, '/dashboards') !== false) {
            // Si la ruta de origen es "/dashboards", redireccionar a "/dashboards"
            return redirect()->route('dashboards.index');
        } else {
            // Si la ruta de origen no es "/roles", redireccionar a "/home" por defecto
            return redirect('home');
        }
    }
}
