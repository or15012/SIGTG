@extends('layouts.mail')
@section('content')
    <div style=" padding: 20px;">
        <h1 style="color: #333;">Solicitud de defensa de trabajo de grado pendiente de revisión</h1>
        <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name }}</p>
        <p>El estudiante ha enviado su defensa de trabajo de grado: </p>
        <p>Los detalles de la defensa enviada son:</p>
        <ul>
            <li><strong>Defensa:</strong> {{ $Info['name'] }}</li>
            <li><strong>Estado:</strong> {{ $Info['status']->status() }} </li>
        </ul>
        <p>¡Que tenga un buen día!</p>
    </div>
@endsection
