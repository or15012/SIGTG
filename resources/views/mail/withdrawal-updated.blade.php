@extends('layouts.mail')
@section('content')
    <div style="padding: 20px;">
        <h1 style="color: #333;">Estado de solicitud de retiro de trabajo de grado actualizado</h1>
        <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name }}</p>
        <p>El estado de su solicitud de retiro de trabajo de grado ha sido actualizado.</p>
        <strong>Detalles de la solicitud de retiro de trabajo de grado:</strong>
        <ul>
            <li><strong>Tipo de retiro:</strong> {{ $Info['name'] }}</li>
            <li><strong>Estado:</strong> {{ $Info['withdrawal']->status() }} </li>
            @switch($Info['withdrawal']->status)
                @case(1)
                    Solicitud de retiro de trabajo de grado aprobada
                @break

                @case(2)
                    Solicitud de retiro de trabajo de grado rechazada
                @break
            @endswitch
            </li>
        </ul>
        <p>¡Que tenga un buen día!</p>
    </div>
@endsection
