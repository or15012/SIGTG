@extends('layouts.master')
@section('title')
    @lang('translation.Dashboard')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            SIGTG-FIA
        @endslot
        @slot('title')
            Editar Perfil
        @endslot
    @endcomponent
    <div class="container">
        <h1>Editar Perfil</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profiles.update', $profile->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Usamos el método PUT para indicar que estamos actualizando -->

            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $profile->name }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" required>{{ $profile->description }}</textarea>
            </div>

            <div class="mb-3">
                <label for="path" class="form-label">Archivo Perfil actual</label>
                <a href="{{ route('profiles.preprofile.download', $profile->id) }}" class="btn btn-link">Descargar archivo actual</a>
            </div>

            <div class="mb-3">
                <label for="new_path" class="form-label">Nuevo archivo Perfil</label>
                <input type="file" class="form-control" id="new_path" name="new_path">
            </div>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
