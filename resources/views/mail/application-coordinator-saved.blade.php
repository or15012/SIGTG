@extends('layouts.mail')
@section('content')
<div style=" padding: 20px;">
    <h1 style="color: #333;">CV pendiente de revisión</h1>
    <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name}}</p>
    <p>El estudiante ha enviado su CV para aplicar a la pasantía profesional: {{ $Info['application']->proposal->name }}</p>
    <p>Los detalles del cv enviado son:</p>
    <ul>
        <li><strong>Nombre:</strong> {{ $Info['application']->name }}</li>
        <li><strong>Estado:</strong>
            @switch($Info['application']->status)
                @case(0)
                    CV presentado
                    @break
                @case(1)
                    CV aceptado
                    @break
                @case(2)
                    CV rechazado
                    @break
                @default
                    Estado desconocido
            @endswitch
        </li>
    </ul>
    <p>¡Que tenga un buen día!</p>
</div>
@endsection
