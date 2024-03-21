@extends('layouts.mail')
@section('content')
    <div style="padding: 20px;">
        <h1 style="color: #333;">Estado de solicitud de prorroga de trabajo de grado actualizado</h1>
        <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name }}</p>
        <p>El estado de su solicitud de prorroga de trabajo de grado ha sido actualizado.</p>
        <strong>Detalles de la solicitud de prorroga de trabajo de grado:</strong>
        <ul>
            <li><strong>Prorroga:</strong> {{ $Info['name'] }}</li>
            <li><strong>Estado:</strong> {{ $Info['extension']->status() }} </li>
            @switch($Info['extension']->status)
                @case(1)
                    Solicitud de prorroga de trabajo de grado aprobada
                @break

                @case(2)
                    Solicitud de prorroga de trabajo de grado rechazada
                @break
            @endswitch
            </li>
        </ul>
        <p>¡Que tenga un buen día!</p>
    </div>
@endsection
