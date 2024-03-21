@extends('layouts.mail')
@section('content')
    <div style=" padding: 20px;">
        <h1 style="color: #333;">Solicitud de prorroga de trabajo de grado pendiente de revisión</h1>
        <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name }}</p>
        <p>El grupo N° {{ $Info['group'] }} ha enviado su prorroga de trabajo de grado: </p>
        <p>Los detalles del prorroga enviado son:</p>
        <ul>
            <li><strong>Tipo de prorroga:</strong> {{ $Info['name'] }}</li>
            <li><strong>Estado:</strong> {{ $Info['extension']->status() }} </li>
        </ul>
        <p>¡Que tenga un buen día!</p>
    </div>
@endsection
