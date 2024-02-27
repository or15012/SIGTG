@extends('layouts.mail')
@section('content')
    <div style=" padding: 20px;">
        <h1 style="color: #333;">Solicitud de retiro de trabajo de grado enviada</h1>
        <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name }}</p>
        <p>Su solicitud de retiro de trabajo de grado ha sido enviada con éxito.</p>
        <p>Detalles de la solicitud de retiro de trabajo de grado enviada.
        <ul>
            <li><strong>Tipo de retiro:</strong> {{ $Info['name'] }}</li>
            <li><strong>Estado:</strong> {{ $Info['withdrawal']->status() }} </li>
        </ul>
        <p>Gracias por enviar su solicitud de retiro,le informaremos sobre cualquier actualización.</p>
        <p>¡Que tengas un buen día!</p>
    </div>
@endsection
