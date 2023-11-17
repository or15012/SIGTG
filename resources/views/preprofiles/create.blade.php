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
            Welcome !
        @endslot
    @endcomponent
    <div class="container">
        <h1>Agregar Preperfil</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profiles.preprofile.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" required>{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="path" class="form-label">Archivo preperfil</label>
                <input type="file" class="form-control" id="path" name="path" required>
            </div>

            <div class="mb-3">
                <label for="path" class="form-label">Resumen preperfil</label>
                <input type="file" class="form-control" id="summary_path" name="summary_path" required>
            </div>

            <div class="mb-3">
                <label for="path" class="form-label">Archivo visión</label>
                <input type="file" class="form-control" id="vision_path" name="vision_path" required>
            </div>

            <div class="mb-3">
                <label for="path" class="form-label">Archivo calculo de tamaño</label>
                <input type="file" class="form-control" id="size_calculation_path" name="size_calculation_path" required>
            </div>

            <div class="mb-3">
                <label for="path" class="form-label">Prioridad de propuesta</label>
                <input type="number" class="form-control" id="proposal_priority" name="proposal_priority" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
