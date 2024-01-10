@extends('layouts.mail')
@section('content')
    <div style="padding: 20px;">
        <h1 style="color: #333;">Estado de actividad actualizado</h1>
        <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name}}</p>
        <p>El estudiante ha actualizado el estado de la actividad. Aquí están los detalles:</p>
        <strong>Detalles de la actividad:</strong>
        <ul>
            <li><strong>Nombre:</strong> {{ $Info['activity']->name }}</li>
            <li><strong>Descripción:</strong> {{ $Info['activity']->description }}</li>
            <li><strong>Estado:</strong> {{ $Info['activity']->status }}</li>
        </ul>
        <p>¡Que tenga un buen día!</p>
    </div>
@endsection
