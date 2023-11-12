@extends('layouts.mail')
@section('content')
    <div style=" padding: 20px;">
        <h1 class="text-center fw-bolder">{{$Info['title']}}</h1>
        <p>{!! $Info['body'] !!}</p>
        <br>
    </div>
@endsection