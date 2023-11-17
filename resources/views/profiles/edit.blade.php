@extends('layouts.master')
@section('title')
    @lang('translation.Dashboard')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            SIGTG - FIA
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

            <div class="mb-5">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" required>{{ $profile->description }}</textarea>
            </div>

            <div class="mb-3">
                <label for="path" class="form-label">Archivo perfil actual</label>
                <a href="{{ route('profiles.preprofile.download', [$profile->id, 'path']) }}" class="btn btn-link">Descargar archivo actual</a>
            </div>

            <div class="mb-5">
                <label for="path" class="form-label">Nuevo archivo perfil</label>
                <input type="file" class="form-control" id="path" name="path">
            </div>

            <div class="mb-3">
                <label for="summary_path" class="form-label">Archivo resumen actual</label>
                <a href="{{ route('profiles.preprofile.download', [$profile->id, 'summary_path']) }}" class="btn btn-link">Descargar archivo actual</a>
            </div>

            <div class="mb-5">
                <label for="summary_path" class="form-label">Nuevo archivo resumen</label>
                <input type="file" class="form-control" id="summary_path" name="summary_path">
            </div>

            <div class="mb-3">
                <label for="vision_path" class="form-label">Archivo visión actual</label>
                <a href="{{ route('profiles.preprofile.download', [$profile->id, 'vision_path']) }}" class="btn btn-link">Descargar archivo actual</a>
            </div>

            <div class="mb-5">
                <label for="vision_path" class="form-label">Nuevo archivo vision</label>
                <input type="file" class="form-control" id="vision_path" name="vision_path">
            </div>

            <div class="mb-3">
                <label for="size_calculation_path" class="form-label">Archivo calculo de tamaño actual</label>
                <a href="{{ route('profiles.preprofile.download', [$profile->id, 'size_calculation_path']) }}" class="btn btn-link">Descargar archivo actual</a>
            </div>

            <div class="mb-5">
                <label for="size_calculation_path" class="form-label">Nuevo archivo calculo de tamaño</label>
                <input type="file" class="form-control" id="size_calculation_path" name="size_calculation_path">
            </div>



            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
