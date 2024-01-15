@extends('layouts.master')

@section('title')
    @lang('translation.ShowPerfil')
@endsection

@section('content')
    <div class="container">
        <div class="contenedor">
            <a href="{{ route('profiles.coordinator.index') }}" class="btn btn-danger regresar-button"><i
                    class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1 class="mb-5">Consultar perfil</h1>

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
                <label for="path" class="form-label">Archivo preperfil:</label>
                <p>
                    <a href="{{ route('profiles.preprofile.download', [$profile->id, 'path']) }}" target="_blank"
                        class="btn btn-secondary archivo">Ver
                        archivo </a>
                </p>
            </div>
            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo resumen:</label>
                <p>
                    <a href="{{ route('profiles.preprofile.download', [$profile->id, 'summary_path']) }}"
                        target="_blank" class="btn btn-secondary archivo">Ver archivo</a>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo visión:</label>
                <p>
                    <a href="{{ route('profiles.preprofile.download', [$profile->id, 'vision_path']) }}" target="_blank"
                        class="btn btn-secondary archivo">Ver
                        archivo</a>
                </p>
            </div>
            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo calculo de tamaño:</label>
                <p>
                    <a href="{{ route('profiles.preprofile.download', [$profile->id, 'size_calculation_path']) }}"
                        target="_blank" class="btn btn-secondary archivo">Ver archivo</a>
                </p>
            </div>
        </div>
        <form action="{{ route('profiles.coordinator.update', $profile->id) }}" id="form-profile-confirm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="profile_id" value="{{ $profile->id }}">
            <input type="hidden" id="decision" name="decision" value="">
            <div>
                @switch($profile->status)
                    @case(0)
                        <button type="button" id="accept-profile" class="btn btn-primary " data-bs-toggle="tooltip"
                            data-bs-placement="bottom" aria-label="Dark" data-bs-original-title="Aceptar preperfil.">
                            <i class="fas fa-check"></i>
                        </button>
                        <button type="button" id="review-profile" class="btn btn-secondary waves-effect waves-light"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Observar preperfil.">
                            <i class="fas fa-exclamation-triangle"></i>
                        </button>
                    @break
                    @case(2)
                        <button type="button" id="accept-profile" class="btn btn-primary " data-bs-toggle="tooltip"
                            data-bs-placement="bottom" aria-label="Dark" data-bs-original-title="Aceptar preperfil.">
                            <i class="fas fa-check"></i>
                        </button>
                        <button type="button" id="review-profile" class="btn btn-secondary waves-effect waves-light"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Observar preperfil.">
                            <i class="fas fa-exclamation-triangle"></i>
                        </button>
                    @break
                    @default
                @endswitch
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/profile_coordinator_show.js') }}"></script>
@endsection
