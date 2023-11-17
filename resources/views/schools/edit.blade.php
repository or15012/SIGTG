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
            Welcome !
        @endslot
    @endcomponent
    <div class="container">
        <div class="container">
            <div class="contenedor">
                <a href="{{ route('schools.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                    Regresar</a>
            </div>

            <h1>Editar Escuela</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('schools.update', $school->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ old('name', $school->name) }}" required>
                </div>
                <div class="contenedor">
                    <a href="{{ route('schools.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <div>
            </form>
        </div>
    @endsection

    @section('script')
        <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    @endsection
