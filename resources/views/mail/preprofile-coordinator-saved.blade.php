@extends('layouts.mail')
@section('content')
<div style=" padding: 20px;">
    <h1 style="color: #333;">Pre-Perfil pendiente de revisión</h1>
    <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name}}</p>
    <p>Se ha enviado un pre-perfil.</p>
    <p>Los detalles del pre-perfil enviado son:</p>
    <ul>
        <li><strong>Nombre:</strong> {{ $Info['preprofile']->name }}</li>
        <li><strong>Descripción:</strong> {{ $Info['preprofile']->description }}</li>
    </ul>
    <p>Gracias por enviar su preperfil. Estamos procesando la información y te informaremos sobre cualquier actualización.</p>
    <p>¡Que tenga un buen día!</p>
</div>
@endsection