@extends('layouts.mail')
@section('content')
<div style=" padding: 20px;">
    <h1 style="color: #333;">¡Preperfil Modificado con Éxito!</h1>

    <p>Hola {{ $Info['user']->name }},</p>

    <p>Tu preperfil ha sido modificiado con éxito.</p>

    <p>Detalles del preperfil:</p>
    <ul>
        <li>Nombre: {{ $Info['preprofile']->name }}</li>
        <li>Descripción: {{ $Info['preprofile']->description }}</li>
    </ul>

</div>
@endsection
