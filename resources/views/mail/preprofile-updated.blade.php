@extends('layouts.mail')

@section('content')
    <div style="padding: 20px;">
        <h1 style="color: #333;">¡Preperfil Modificado con Éxito!</h1>

        <p>Hola {{ $Info['user']->name }},</p>

        <p>Tu preperfil ha sido modificado con éxito. Aquí están los detalles:</p>

        <strong>Detalles del preperfil:</strong>
        <ul>
            <li><strong>Nombre:</strong> {{ $Info['preprofile']->name }}</li>
            <li><strong>Descripción:</strong> {{ $Info['preprofile']->description }}</li>
        </ul>

        <p>Gracias por enviar tu preperfil. Estamos procesando la información y te informaremos sobre cualquier actualización.</p>

        <p>¡Gracias y que tengas un buen día!</p>
    </div>
@endsection
