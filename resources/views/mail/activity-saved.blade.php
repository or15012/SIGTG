@extends('layouts.mail')
@section('content')
<div style=" padding: 20px;">
    <h1 style="color: #333;">Actividades cargadas para seguimiento</h1>
    <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name}}</p>
    <p>Sus actividades han sido cargadas con éxito para darle seguimiento.</p>

    <p>Gracias por cargar sus actividades.</p>
    <p>¡Que tengas un buen día!</p>
</div>
@endsection
