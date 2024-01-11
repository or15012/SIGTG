@extends('layouts.mail')
@section('content')
<div style=" padding: 20px;">
    <h1 style="color: #333;">Planificación actualizada</h1>
    <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name}}</p>
    <p>Su planificación ha sido actualizada con éxito.</p>
    <p>Detalles de la planificación actualizada:</p>
    <ul>
        <li><strong>Nombre:</strong> {{ $Info['planning']->name }}</li>
        <li><strong>Descripción:</strong> {{ $Info['planning']->description }}</li>
        <li><strong>Estado:</strong> {{ $Info['planning']->status == 0 ? 'Pendiente de revisión' : 'Aprobado' }}</li>
    </ul>
    <p>Gracias por enviar su planificación. Estamos procesando la información y le informaremos sobre cualquier actualización.</p>
    <p>¡Que tengas un buen día!</p>
</div>
@endsection
