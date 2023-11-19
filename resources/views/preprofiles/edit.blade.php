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
            Editar Preperfil
        @endslot
    @endcomponent
    <div class="container">
        <div class="contenedor">
            <a href="{{ route('profiles.preprofile.index') }}" class="btn btn-danger regresar-button"><i
                    class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Editar Preperfil</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profiles.preprofile.update', $preprofile->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Usamos el método PUT para indicar que estamos actualizando -->

            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $preprofile->name }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" required>{{ $preprofile->description }}</textarea>
            </div>

            <div class="mb-5">
                <label for="proposal_priority" class="form-label">Número de prioridad</label>
                <input type="integer" class="form-control" id="proposal_priority" name="proposal_priority"
                    value="{{ $preprofile->proposal_priority }}" required>
            </div>

            <div class="mb-2">
                <label for="path" class="form-label">Archivo preperfil actual</label>
                <a href="{{ route('profiles.preprofile.download', [$preprofile->id, 'path']) }}"
                    class="btn btn-secondary archivo">Descargar archivo actual</a>
            </div>

            <div class="mb-5">
                <label for="path" class="form-label">Nuevo archivo preperfil</label>
                <input type="file" class="form-control" id="path" name="path">
            </div>


            <div class="mb-2">
                <label for="summary_path" class="form-label">Archivo resumen actual</label>
                <a href="{{ route('profiles.preprofile.download', [$preprofile->id, 'summary_path']) }}"
                    class="btn btn-secondary archivo">Descargar archivo actual</a>
            </div>

            <div class="mb-5">
                <label for="summary_path" class="form-label">Nuevo archivo resumen</label>
                <input type="file" class="form-control" id="summary_path" name="summary_path">
            </div>


            <div class="mb-2">
                <label for="path" class="form-label">Archivo visión actual</label>
                <a href="{{ route('profiles.preprofile.download', [$preprofile->id, 'vision_path']) }}"
                    class="btn btn-secondary archivo">Descargar archivo actual</a>
            </div>

            <div class="mb-5">
                <label for="vision_path" class="form-label">Nuevo archivo visión</label>
                <input type="file" class="form-control" id="vision_path" name="vision_path">
            </div>

            <div class="mb-2">
                <label for="size_calculation_path" class="form-label">Archivo calculo de tamaño actual</label>
                <a href="{{ route('profiles.preprofile.download', [$preprofile->id, 'size_calculation_path']) }}"
                    class="btn btn-secondary archivo">Descargar archivo actual</a>
            </div>

            <div class="mb-3">
                <label for="size_calculation_path" class="form-label">Nuevo archivo calculo de tamaño</label>
                <input type="file" class="form-control" id="size_calculation_path" name="size_calculation_path">
            </div>
            <div class="contenedor">
                <a href="{{ route('profiles.preprofile.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
