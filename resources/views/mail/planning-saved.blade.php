@extends('layouts.mail')
@section('content')
<div style=" padding: 20px;">
    <h1 style="color: #333;">Planificación enviada</h1>
    <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name}}</p>
    <p>Su planificación ha sido enviada con éxito.</p>
    <p>Detalles de la planificación enviada:</p>
    <ul>
        <li><strong>Nombre:</strong> {{ $Info['planning']->name }}</li>
        <li><strong>Descripción:</strong> {{ $Info['planning']->description }}</li>
        <li><strong>Estado:</strong> {{ $Info['planning']->status == 0 ? 'Pendiente de revisión' : 'Aprobado' }}</li>
    </ul>
    <p>Gracias por enviar su planificación. Estamos procesando la información y le informaremos sobre cualquier actualización.</p>
    <p>¡Que tengas un buen día!</p>
</div>
@endsection
