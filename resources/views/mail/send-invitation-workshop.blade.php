@extends('layouts.mail')
@section('content')
    <div style=" padding: 20px;">
        <h1 style="color: #333;">Invitación a taller</h1>
        <p>¡Te damos la bienvenida al nuevo taller "{{ $Info['workshop']->name }}"!</p>
        <p>Detalles del taller:</p>
        <p>Nombre: {{ $Info['workshop']->name }} </p>
        <p>Descripción: {{ $Info['workshop']->description }} </p>
        <p>Fecha del taller: {{ \Carbon\Carbon::parse($Info['workshop']->date)->format('d-m-Y H:i:s') }}</p>
        <p>Lugar del taller: {{ $Info['workshop']->place }}</p>
        <br>
        <p>¡Esperamos verte activamente en el taller!</p>
    </div>
@endsection
