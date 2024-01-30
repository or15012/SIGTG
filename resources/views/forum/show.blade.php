@extends('layouts.master')

@section('title')
    @lang('translation.ShowPerfil')
@endsection

@section('content')
    <div class="container">
        <div class="contenedor">
            <a href="{{ route('forum.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1 class="mb-5">Consultar foro</h1>

        <div class="row">
            <h4>Cantidad de asistentes esperada a foro: {{ $forum->assistences->count() }}</h4>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="name" class="form-label">Nombre:</label>
                <p>{{ $forum->name }}</p>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="description" class="form-label">Descripción:</label>
                <p>{{ $forum->description }}</p>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="date" class="form-label">Fecha:</label>
                <p>{{ \Carbon\Carbon::parse($forum->date)->format('d-m-Y H:i:s') }}</p>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="place" class="form-label">Lugar:</label>
                <p>{{ $forum->place }}</p>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo de foro:</label>
                <p>
                    <a href="{{ route('forum.download', [$forum->id, 'path']) }}" target="_blank"
                        class="btn btn-secondary archivo">Ver archivo</a>
                </p>
            </div>
        </div>
    </div>





@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
