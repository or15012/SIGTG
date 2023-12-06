@extends('layouts.mail')
@section('content')
    <div style="padding: 20px;">
        <h1 style="color: #333;">Etapa Evaluativa Enviada</h1>
        <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name}}</p>
        <p>Se ha enviado la etapa evaluativa número: {{ $Info['evaluation_stage']->stage_id }}</p>
        <p>Queda al pendiente la revisión.</p>
        <p>¡Gracias y que tenga un buen día!</p>
    </div>
@endsection
