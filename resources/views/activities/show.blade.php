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
            <a href="{{ route('activities.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Consultar actividad</h1>
        <dl class="row">

            <dt class="col-sm-3">Nombre:</dt>
            <dd class="col-sm-9">{{ $activity->name }}</dd>

            <dt class="col-sm-3">Descripción:</dt>
            <dd class="col-sm-9">{{ $activity->description }}</dd>

            <dt class="col-sm-3">Estado:</dt>
            <dd class="col-sm-9">{{ $activity->status }}</dd>

            <dt class="col-sm-3">Fecha de inicio:</dt>
            <dd class="col-sm-9">{{ $activity->date_start }}</dd>

            <dt class="col-sm-3">Fecha de fin:</dt>
            <dd class="col-sm-9">{{ $activity->date_end }}</dd>


        </dl>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
