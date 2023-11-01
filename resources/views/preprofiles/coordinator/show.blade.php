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

        <div class="mb-3 ">
            <label for="description" class="form-label">Descripción:</label>
            <p>{{ $preprofile->description }}</p>
        </div>

        <form action="{{ route('profiles.preprofile.coordinator.update', $preprofile->id) }}" id="form-preprofile-confirm" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="profile_id" value="{{ $preprofile->id }}">

            <button type="button" id="accept-preprofile" class="btn btn-primary  waves-effect waves-light"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Aceptar preperfil.">
                <i class="fas fa-check"></i>
            </button>
            <button type="button" id="review-preprofile" class="btn btn-secondary  waves-effect waves-light"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Observar preperfil.">
                <i class="fas fa-exclamation-triangle"></i>
            </button>
            <button type="button" id="deny-preprofile" class="btn btn-danger  waves-effect waves-light"
                title="Rechazar preprefil.">
                <i class="fas fa-window-close"></i>
            </button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/preprofille_observation_show.js') }}"></script>
@endsection
