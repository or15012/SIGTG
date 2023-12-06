@extends('layouts.mail')
@section('content')
    <div style="padding: 20px;">
        <h1 style="color: #333;">Etapa Evaluativa Aprobada</h1>
        <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name}}</p>
        <p>Se ha aprobado la etapa evaluativa número: {{ $Info['evaluation_stage']->stage_id }}</p>
        <p>¡Gracias y que tenga un buen día!</p>
    </div>
@endsection
