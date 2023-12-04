@extends('layouts.mail')
@section('content')
    <div style="padding: 20px;">
        <h1 style="color: #333;">Notificacion de comite</h1>
        <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name}}</p>
        <p>Se le ha agregado al grupo de trabajo {{ $Info['group']->number }}:</p>
        <ul>
            <li>Su responsabilidad será ser: <strong>{{ $Info['committee'] }}</strong> </li>
        </ul>
        <p>¡Gracias y que tenga un buen día!</p>
    </div>
@endsection
