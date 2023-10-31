@extends('layouts.master')

@section('title')
    @lang('translation.ShowPreperfil')
@endsection

@section('content')
    <div class="container">
        <h1 class="mb-5">Detalles del Preperfil</h1>

        <div class="mb-3">
            <label for="name" class="form-label">Nombre:</label>
            <p>{{ $preprofile->name }}</p>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descripción:</label>
            <p>{{ $preprofile->description }}</p>
        </div>

        <div class="mb-3">
            <label for="path" class="form-label">Archivo preperfil:</label>
            <p>
                <a href="{{ route('profiles.preprofile.download', $preprofile->id) }}" target="_blank">Ver archivo</a>

            </p>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
