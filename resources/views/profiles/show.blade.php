@extends('layouts.master')

@section('title')
    @lang('translation.ShowPerfil')
@endsection

@section('content')
    <div class="container">
        <h1 class="mb-5">Detalles del Perfil</h1>
        <h2>Propuesta prioridad # <b> {{ $profile->proposal_priority }}</b></h2>
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="name" class="form-label">Nombre:</label>
                <p>{{ $profile->name }}</p>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="description" class="form-label">Descripción:</label>
                <p>{{ $profile->description }}</p>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo perfil:</label>
                <p>
                    <a href="{{ route('profiles.preprofile.download', [$profile->id, 'path']) }}" target="_blank">Ver
                        archivo</a>
                </p>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo resumen:</label>
                <p>
                    <a href="{{ route('profiles.preprofile.download', [$profile->id, 'summary_path']) }}"
                        target="_blank">Ver archivo</a>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo visión:</label>
                <p>
                    <a href="{{ route('profiles.preprofile.download', [$profile->id, 'vision_path']) }}"
                        target="_blank">Ver archivo</a>
                </p>
            </div>
            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo calculo de tamaño:</label>
                <p>
                    <a href="{{ route('profiles.preprofile.download', [$profile->id, 'size_calculation_path']) }}"
                        target="_blank">Ver archivo</a>
                </p>
            </div>
        </div>




    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
