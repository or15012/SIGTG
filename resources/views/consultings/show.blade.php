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
        <h1>Detalles de la asesoria</h1>
        <dl class="row">
            <dt class="col-sm-3">ID:</dt>
            <dd class="col-sm-9">{{ $consulting->id }}</dd>

            <dt class="col-sm-3">Nombre:</dt>
            <dd class="col-sm-9">{{ $consulting->topics }}</dd>

            <dt class="col-sm-3">Número:</dt>
            <dd class="col-sm-9">{{ $consulting->number }}</dd>

            <dt class="col-sm-3">Resumen:</dt>
            <dd class="col-sm-9">{{ $consulting->summary }}</dd>
        </dl>
        <a href="{{ route('consultings.index') }}" class="btn btn-primary">Volver a la Lista</a>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
