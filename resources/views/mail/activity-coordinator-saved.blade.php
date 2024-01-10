@extends('layouts.mail')
@section('content')
<div style=" padding: 20px;">
    <h1 style="color: #333;">Seguimiento de actividades</h1>
    <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name}}</p>
    <p>Se han cargado actividades del estudiante para seguimiento.</p>

</div>
@endsection
