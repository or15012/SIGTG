@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalles de la Escuela</h1>
    <dl class="row">
        <dt class="col-sm-3">ID:</dt>
        <dd class="col-sm-9">{{ $protocol->id }}</dd>

        <dt class="col-sm-3">Nombre:</dt>
        <dd class="col-sm-9">{{ $protocol->name }}</dd>
    </dl>
    <a href="{{ route('protocols.index') }}" class="btn btn-primary">Volver a la Lista</a>
</div>
@endsection
