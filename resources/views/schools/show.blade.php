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
        <div class="container">
            <div class="contenedor">
                <a href="{{ route('schools.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                    Regresar</a>
            </div>
            <h1>Detalles de la Escuela</h1>
            <dl class="row">
                <dt class="col-sm-3">ID:</dt>
                <dd class="col-sm-9">{{ $school->id }}</dd>

                <dt class="col-sm-3">Nombre:</dt>
                <dd class="col-sm-9">{{ $school->name }}</dd>
            </dl>
           {{--  <a href="{{ route('schools.index') }}" class="btn btn-primary">Volver a la Lista</a> --}}
        </div>
    @endsection

    @section('script')
        <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    @endsection
