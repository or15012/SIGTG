@extends('layouts.mail')
@section('content')
    <div style="padding: 20px;">
        <h1 style="color: #333;">Nueva asesoría agendada.</h1>
        <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name }}</p>
        <p>Se ha genereado una nueva asesoria para el grupo {{ $Info['group']->number }}. Aquí están los detalles:</p>
        <strong>Detalles de la asesoría:</strong>
        <ul>
            <li><strong>Fecha:</strong> {{ $Info['data']['date'] }}</li>
            <li><strong>Temas:</strong> {{ $Info['data']['topics'] }}</li>
        </ul>
        <p>¡Que tenga un buen día!</p>
    </div>
@endsection
