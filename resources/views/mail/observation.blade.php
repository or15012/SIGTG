@extends('layouts.mail')
@section('content')
<div style=" padding: 20px;">
    <h1 style="color: #333;">Ha recibido una observación</h1>
    <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name}}</p>
    <p>Su perfil o planificación ha recibido un comentario de observación.</p>
    <p>Puede revisar detalles en su perfil.</p>

    <p>¡Que tengas un buen día!</p>
</div>
@endsection
