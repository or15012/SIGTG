@extends('layouts.mail')
@section('content')
<div style=" padding: 20px;">
    <h1 style="color: #333;">Planificación actualizada</h1>
    <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name}}</p>
    <p>El estudiante ha actualizado su planificación para revisión.</p>
    <p>Los detalles del planificación actualizada son:</p>
    <ul>
        <li><strong>Nombre:</strong> {{ $Info['planning']->name }}</li>
        <li><strong>Descripción:</strong> {{ $Info['planning']->description }}</li>
        <li><strong>Estado:</strong> {{ $Info['planning']->status == 0 ? 'Pendiente de revisión' : 'Aprobado' }}</li>
    </ul>
    <p>¡Que tenga un buen día!</p>
</div>
@endsection
