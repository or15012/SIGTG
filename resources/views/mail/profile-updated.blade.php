@extends('layouts.mail')
@section('content')
    <div style="padding: 20px;">
        <h1 style="color: #333;">{{ $Info['type'] }} modificado</h1>
        <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name }}</p>
        <p>Su {{ $Info['type'] }} ha sido modificado. Aquí están los detalles:</p>
        <strong>Detalles del {{ $Info['type'] }} :</strong>
        <ul>
            <li><strong>Nombre:</strong> {{ $Info['profile']->name }}</li>
            <li><strong>Descripción:</strong> {{ $Info['profile']->description }}</li>
            <li><strong>Estado:</strong> {{ $Info['profile']->status }}</li>
        </ul>
        <p>Gracias por enviar su {{ $Info['type'] }} .</p>
        <p>¡Gracias y que tenga un buen día!</p>
    </div>
@endsection
