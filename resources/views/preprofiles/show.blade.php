@extends('layouts.master')

@section('title')
    @lang('translation.ShowPreperfil')
@endsection

@section('content')
    <div class="container">
        <div class="contenedor">
            <a href="{{ route('profiles.preprofile.index') }}" class="btn btn-danger regresar-button"><i
                    class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1 class="mb-5">Detalles del preperfil</h1>
        <h2>Propuesta prioridad # <b> {{ $preprofile->proposal_priority }}</b></h2>
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="name" class="form-label">Nombre:</label>
                <p>{{ $preprofile->name }}</p>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="description" class="form-label">Descripción:</label>
                <p>{{ $preprofile->description }}</p>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo preperfil:</label>
                <p>
                    <a href="{{ route('profiles.preprofile.download', [$preprofile->id, 'path']) }}" target="_blank"
                        class="btn btn-secondary archivo">Ver archivo</a>
                </p>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo resumen:</label>
                <p>
                    <a href="{{ route('profiles.preprofile.download', [$preprofile->id, 'summary_path']) }}" target="_blank"
                        class="btn btn-secondary archivo">Ver archivo</a>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo visión:</label>
                <p>
                    <a href="{{ route('profiles.preprofile.download', [$preprofile->id, 'vision_path']) }}" target="_blank"
                        class="btn btn-secondary archivo">Ver archivo</a>
                </p>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo calculo de tamaño:</label>
                <p>
                    <a href="{{ route('profiles.preprofile.download', [$preprofile->id, 'size_calculation_path']) }}"
                        target="_blank" class="btn btn-secondary archivo">Ver archivo</a>
                </p>
            </div>
        </div>


    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
