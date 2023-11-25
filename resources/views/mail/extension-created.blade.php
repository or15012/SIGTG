@extends('layouts.mail')

@section('content')
    <div style="padding: 20px;">
        <h1 style="color: #333;">Prorroga guardada con éxito!</h1>

        <p>Hola {{ $emailData['user']->name }},</p>

        <p>Se ha creado una nueva prórroga para el proyecto "{{ $emailData['project']->name }}". Aquí están los detalles:</p>

        <strong>Detalles de la prórroga:</strong>
        <ul>
            <li><strong>Descripción:</strong> {{ $emailData['extension']->description }}</li>
        </ul>


        <p>¡Gracias y que tengas un buen día!</p>
    </div>
@endsection