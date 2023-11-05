@extends('layouts.master')

@section('title')
    @lang('translation.ShowPreperfil')
@endsection

@section('content')
    <div class="container">
        <h1 class="mb-5">Detalles del Preperfil</h1>

        <div class="row">
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label for="name" class="form-label">Nombre:</label>
                <p>{{ $preprofile->name }}</p>
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label for="path" class="form-label">Archivo preperfil:</label>
                <p>
                    <a href="{{ route('profiles.preprofile.download', $preprofile->id) }}" target="_blank">Ver archivo</a>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label for="description" class="form-label">Descripción:</label>
                <p>{{ $preprofile->description }}</p>
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label for="status" class="form-label">Estado:</label>
                <p>
                    @switch($preprofile->status)
                        @case(0)
                            Pre perfil presentado.
                        @break

                        @case(1)
                            Pre perfil aprobado.
                        @break

                        @case(2)
                            Pre perfil observado.
                        @break

                        @case(3)
                            Pre perfil rechazado.
                        @break

                        @default
                    @endswitch
                </p>
            </div>
        </div>

        <form action="{{ route('profiles.preprofile.coordinator.update', $preprofile->id) }}" id="form-preprofile-confirm"
            method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="profile_id" value="{{ $preprofile->id }}">
            <input type="hidden" id="decision" name="decision" value="">
            <div>
                @switch($preprofile->status)
                    @case(0)
                        <button type="button" id="accept-preprofile" class="btn btn-primary " data-bs-toggle="tooltip"
                            data-bs-placement="bottom" aria-label="Dark" data-bs-original-title="Aceptar preperfil.">
                            <i class="fas fa-check"></i>
                        </button>
                        <button type="button" id="review-preprofile" class="btn btn-secondary waves-effect waves-light"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Observar preperfil.">
                            <i class="fas fa-exclamation-triangle"></i>
                        </button>
                        <button type="button" id="deny-preprofile" class="btn btn-danger  waves-effect waves-light" |
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Rechazar preprefil.">
                            <i class="fas fa-window-close"></i>
                        </button>
                    @break

                    @case(1)

                    @break

                    @case(2)
                        <button type="button" id="accept-preprofile" class="btn btn-primary " data-bs-toggle="tooltip"
                            data-bs-placement="bottom" aria-label="Dark" data-bs-original-title="Aceptar preperfil.">
                            <i class="fas fa-check"></i>
                        </button>
                        <button type="button" id="deny-preprofile" class="btn btn-danger  waves-effect waves-light" |
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Rechazar preprefil.">
                            <i class="fas fa-window-close"></i>
                        </button>
                    @break

                    @case(3)
                        <button type="button" id="accept-preprofile" class="btn btn-primary " data-bs-toggle="tooltip"
                            data-bs-placement="bottom" aria-label="Dark" data-bs-original-title="Aceptar preperfil.">
                            <i class="fas fa-check"></i>
                        </button>
                        <button type="button" id="review-preprofile" class="btn btn-secondary waves-effect waves-light"
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
    <script src="{{ URL::asset('js/preprofille_observation_show.js') }}"></script>
@endsection
