@extends('layouts.master')

@section('title')
    @lang('translation.ShowPerfil')
@endsection

@section('content')
    <div class="container">
        <div class="contenedor">
            <a href="{{ route('proposals.applications.coordinator.index') }}" class="btn btn-danger regresar-button">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>
        <h1 class="mb-4">Consultar perfil de aplicación</h1>
        <div class="row mb-1">
            <div class="col-12">
                <h5>
                Propuesta: {{ $application->proposal->name }}
                </h5>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="name" class="form-label">Nombre de archivo:</label>
                <p>{{ $application->name }}</p>
            </div>
            <div class="mb-3 col-12 col-md-6">
                <label for="status" class="form-label">Alumno:</label>
                <p>
                    {{ $application->user->first_name }}  {{ $application->user->middle_name }} {{ $application->user->last_name }} {{ $application->user->second_last_name }}
                </p>
            </div>

        </div>
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo perfil:</label>
                <p>
                    <a href="{{ route('proposals.applications.coordinator.download', ['application' => $application->id, 'file' => 'path']) }}"
                        target="_blank" class="btn btn-secondary archivo">Ver archivo</a>
                </p>
            </div>
            <div class="mb-3 col-12 col-md-6">
                <label for="status" class="form-label">Estado:</label>
                <p>
                    @switch($application->status)
                        @case(0)
                            Perfil presentado.
                        @break

                        @case(1)
                            Perfil aprobado.
                        @break

                        @case(2)
                            Perfil rechazado.
                        @break

                        @default
                    @endswitch
                </p>
            </div>
        </div>

        <form action="{{ route('proposals.applications.coordinator.update', $application->id) }}"
            id="form-application-confirm" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="application_id" value="{{ $application->id }}">
            <input type="hidden" id="decision" name="decision" value="">

            <button type="button" id="accept-application" class="btn btn-primary" data-bs-toggle="tooltip"
                data-bs-placement="bottom" aria-label="Dark" data-bs-original-title="Aceptar perfil.">
                <i class="fas fa-check"></i>
            </button>
            <button type="button" id="deny-application"
                class="btn btn-danger buttonDelete waves-effect waves-light" data-bs-toggle="tooltip"
                data-bs-placement="bottom" title="Rechazar perfil.">
                <i class="fas fa-window-close"></i>
            </button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/application_coordinator_show.js') }}"></script>
@endsection
