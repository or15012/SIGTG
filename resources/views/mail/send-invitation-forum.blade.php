@extends('layouts.mail')
@section('content')
    <div style=" padding: 20px;">
        <h1 style="color: #333;">Invitación a foro</h1>
        <p>¡Te damos la bienvenida al nuevo foro "{{ $Info['forum']->name }}"!</p>
        <p>Detalles del Foro:</p>
        <p>Nombre: {{ $Info['forum']->name }} </p>
        <p>Descripción: {{ $Info['forum']->description }} </p>
        <p>Fecha del Foro: {{ \Carbon\Carbon::parse($Info['forum']->date)->format('d-m-Y H:i:s') }}</p>
        <p>Lugar del Foro: {{ $Info['forum']->place }}</p>
        <br>
        <p>¡Esperamos verte activamente en el foro!</p>
    </div>
@endsection
