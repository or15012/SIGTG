@extends('layouts.mail')
@section('content')
    <div style=" padding: 20px;">
        <h1 style="color: #333;">Solicitud de defensa de trabajo de grado enviada</h1>
        <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name }}</p>
        <p>Su solicitud de defensa de trabajo de grado ha sido enviada con éxito.</p>
        <p>Detalles de la solicitud de defensa de trabajo de grado enviada.
        <ul>
            <li><strong>Defensa:</strong> {{ $Info['name'] }}</li>
            <li><strong>Estado:</strong> {{ $Info['status']->status() }} </li>
        </ul>
        <p>Gracias por enviar su solicitud de defensa,le informaremos sobre cualquier actualización.</p>
        <p>¡Que tengas un buen día!</p>
    </div>
@endsection
