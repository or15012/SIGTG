@extends('layouts.mail')
@section('content')
<div style=" padding: 20px;">
                <h1 align="justify" class="text-center fw-bolder">Invitación al grupo {{$Info['group']->number}} | UES</h1>

                <p><b>{{$Info['user']->first_name.' '.$Info['user']->last_name }}<b> has sido invitado al grupo {{$Info['group']->number}}. Has clic <a href="{{env('APP_URL')}}/groups/initialize" target="_blank">aquí</a> para aceptar la invitación</p>
                <br>
            </div>
@endsection