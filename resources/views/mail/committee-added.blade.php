@extends('layouts.mail')
@section('content')
    <div style="padding: 20px;">
        <h1 style="color: #333;">Notificacion de comite</h1>

        <p>Hola {{ $Info['user']->name }},</p>

        <strong>Se ha agregado al grupo {{ $Info['group']->name }}:</strong>
        <ul>
            <li><strong>Nombre del comité:</strong> {{ $Info['group']->name }}</li>
            <li><strong>Tipo de comité:</strong> {{ $Info['committee'] }}</li>
        </ul>

        <p>¡Gracias y que tengas un buen día!</p>
    </div>
@endsection
