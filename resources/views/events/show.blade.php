@extends('layouts.master')
@section('title')
    @lang('translation.Dashboard')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            SIGTG - FIA
        @endslot
        @slot('title')
            Welcome !
        @endslot
    @endcomponent
    <div class="container">
        <div class="contenedor">
            <a href="{{ route('events.index',$project->id) }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Consultar defensa</h1>
        <dl class="row">
            <dt class="col-sm-3">ID:</dt>
            <dd class="col-sm-9">{{ $events->id }}</dd>

            <dt class="col-sm-3">Nombre:</dt>
            <dd class="col-sm-9">{{ $events->name }}</dd>

            <dt class="col-sm-3">Lugar:</dt>
            <dd class="col-sm-9">{{ $events->place }}</dd>


            <dt class="col-sm-3">Fecha y Hora:</dt>
            <dd class="col-sm-9">{{ $events->date }}</dd>

        </dl>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
