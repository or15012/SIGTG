@extends('layouts.mail')
@section('content')
<div style=" padding: 20px;">
    <h1 style="color: #333;">¡Preperfil Enviado con Éxito!</h1>

    <p>Hola {{ $Info['user']->name }},</p>

    <p>Tu preperfil ha sido guardado con éxito.</p>

    <p>Detalles del preperfil:</p>
    <ul>
        <li><strong>Nombre:</strong> {{ $Info['preprofile']->name }}</li>
        <li><strong>Descripción:</strong> {{ $Info['preprofile']->description }}</li>
    </ul>

</div>
@endsection