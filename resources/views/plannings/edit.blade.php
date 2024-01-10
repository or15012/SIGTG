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
            Editar planificación
        @endslot
    @endcomponent
    <div class="container">
        <div class="contenedor">
            <a href="{{ route('plannings.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Editar planificación</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('plannings.update', $planning->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $planning->name }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" required>{{ $planning->description }}</textarea>
            </div>

            {{-- <div class="mb-5">
                <label for="proposal_priority" class="form-label">Número de prioridad</label>
                <input type="integer" class="form-control" id="proposal_priority" name="proposal_priority"
                    value="{{ $planning->proposal_priority }}" required>
            </div> --}}

            <div class="mb-2">
                <label for="path" class="form-label">Archivo planificación actual</label>
                <a href="{{ route('plannings.download', [$planning->id, 'path']) }}"
                    class="btn btn-secondary archivo">Descargar archivo actual</a>
            </div>

            <div class="mb-5">
                <label for="path" class="form-label">Nuevo archivo planificación<nav></nav></label>
                <input type="file" class="form-control" id="path" name="path">
            </div>
            <div class="contenedor">
                <a href="{{ route('plannings.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
