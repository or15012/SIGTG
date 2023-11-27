@extends('layouts.mail')
@section('content')
    <div style="padding: 20px;">
        <h1 style="color: #333;">Notificacion de comite</h1>

        <p>Hola, buen día {{ $Info['user']->name }},</p>

        <p>Se le ha agregado al grupo {{ $Info['group']->name }}:</p>
        <ul>
            <li>Su responsabilidad será ser: <strong>{{ $Info['committee'] }}</strong> </li>
        </ul>

        <p>¡Gracias y que tengas un buen día!</p>
    </div>
@endsection
