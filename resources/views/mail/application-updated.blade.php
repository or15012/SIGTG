@extends('layouts.mail')
@section('content')
    <div style="padding: 20px;">
        <h1 style="color: #333;">Estado de CV actualizado</h1>
        <p>Buen día, {{ $Info['user']->first_name }} {{ $Info['user']->last_name }}</p>
        <p>El estado de su CV ha sido actualizado.</p>
        <strong>Detalles del CV:</strong>
        <ul>
            <li><strong>Nombre:</strong> {{ $Info['application']->name }}</li>
            <li><strong>Estado:</strong>
                @switch($Info['application']->status)
                    @case(1)
                        CV aceptado
                        <p>Ha sido seleccionado/a para la pasantía profesional: {{ $Info['application']->proposal->name }}</p>
                    @break

                    @case(2)
                        CV rechazado
                        <p>Lamentablemente, su CV no ha sido aceptado en esta ocasión.</p>
                    @break
                @endswitch
            </li>
        </ul>
        <p>¡Que tengas un buen día!</p>
    </div>
@endsection
