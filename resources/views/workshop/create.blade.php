@extends('layouts.master')
@section('title')
    @lang('translation.Stages')
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
        <div class="contenedor">
            <a href="{{ route('proposals.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Registrar propuesta</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('proposals.store') }}" id="form-proposals" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input class="form-control" type="text" id="name" name="name">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción:</label>
                <input class="form-control" type="text" id="description" name="description">
            </div>

            <div class="mb-3">
                <label for="path" class="form-label">Archivo de propuesta:</label>
                <input type="file" class="form-control" accept=".pdf,.PDF" id="path" name="path" required>
            </div>

            <div class="mb-3">
                <label for="amount_student" class="form-label">Cantidad de Estudiantes:</label>
                <input class="form-control" type="number" id="amount_student" name="amount_student">
            </div>

            <div class="mb-3">
                <label for="entity_id" class="form-label">Entidad:</label>
                <select class="form-control" id="entity_id" name="entity_id">
                    <option value="0"> Seleccione una entidad </option>
                    @foreach ($entities as $entity)
                        <option value="{{ $entity->id }}"> {{ $entity->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="contenedor">
                <a href="{{ route('proposals.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/stages.js') }}"></script>
@endsection
