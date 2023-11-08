@extends('layouts.mail')
@section('content')
<div style=" padding: 20px;">
                <h1 align="justify" class="text-center fw-bolder">Usuario creado | UES</h1>

                <p><b>Nombres:</b> {{$Info['user']->first_name.' '.$Info['user']->middle_name }}</p>
                <p><b>Apellidos:</b> {{$Info['user']->last_name.' '.$Info['user']->second_last_name }}</p>
                <p><b>Email:</b> {{$Info['user']->email }}</p>
                <p><b>Contraseña:</b> {{$Info['user']->password }}</p>
                <br>
            </div>
@endsection