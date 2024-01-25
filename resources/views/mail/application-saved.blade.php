@extends('layouts.mail')
@section('content')
<div style=" padding: 20px;">
    <h1 style="color: #333;">CV enviado</h1>
    <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name}}</p>
    <p>Su CV ha sido enviada con éxito.</p>
    <p>Detalles del CV enviado para aplicar a la pasantia profesional: {{ $Info['application']->proposal->name }}</p>
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
    <p>Gracias por enviar su CV. Revisaremos su cv y le informaremos sobre cualquier actualización.</p>
    <p>¡Que tengas un buen día!</p>
</div>
@endsection
