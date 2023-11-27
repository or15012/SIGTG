@extends('layouts.mail')

@section('content')
    <div style="padding: 20px;">
        <h1 style="color: #333;">¡Perfil Modificado con Éxito!</h1>

        <p>Hola {{ $Info['user']->name }},</p>

        <p>Tu perfil ha sido modificado con éxito. Aquí están los detalles:</p>

        <strong>Detalles del perfil:</strong>
        <ul>
            <li><strong>Nombre:</strong> {{ $Info['profile']->name }}</li>
            <li><strong>Descripción:</strong> {{ $Info['profile']->description }}</li>
            <li><strong>Estado:</strong> {{ $Info['profile']->status }}</li>
        </ul>

        <p>Gracias por enviar tu preperfil. Estamos procesando la información y te informaremos sobre cualquier actualización.</p>

        <p>¡Gracias y que tengas un buen día!</p>
    </div>
@endsection
