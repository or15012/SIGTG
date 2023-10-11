@extends('layouts.master')
@section('title')
    @lang('translation.Dashboard')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            SIGTG-FIA
        @endslot
        @slot('title')
            Welcome !
        @endslot
    @endcomponent
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

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
