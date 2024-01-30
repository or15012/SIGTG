@extends('layouts.master')

@section('title')
    @lang('translation.ShowPerfil')
@endsection

@section('content')
    <div class="container">
        <div class="contenedor">
            <a href="{{ route('workshop.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1 class="mb-5">Consultar taller de Investigación</h1>

        <div class="row">
            <h4>Cantidad de asistentes esperada a taller: {{ $workshop->assistences->count() }}</h4>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="name" class="form-label">Nombre:</label>
                <p>{{ $workshop->name }}</p>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="description" class="form-label">Descripción:</label>
                <p>{{ $workshop->description }}</p>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo de propuesta:</label>
                <p>
                    <a href="{{ route('workshop.download', ['workshop' => $workshop->id, 'file' => 'path']) }}"
                        target="_blank" class="btn btn-secondary archivo">Ver archivo</a>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="date" class="form-label">Fecha:</label>
                <p>{{ \Carbon\Carbon::parse($workshop->date)->format('d-m-Y H:i:s') }}</p>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="place" class="form-label">Lugar:</label>
                <p>{{ $workshop->place }}</p>
            </div>
        </div>


    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
