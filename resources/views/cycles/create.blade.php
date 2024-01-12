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
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="contenedor">
            <a href="{{ route('cycles.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Registrar ciclo</h1>
        <form action="{{ route('cycles.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-12">
                    <label for="number" class="form-label">Número</label>
                    <select class="form-select" id="number" name="number" required>
                        <option @if(old('number') == 1) selected @endif value="1">I</option>
                        <option @if(old('number') == 2) selected @endif value="2">II</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="year" class="form-label">Año</label>
                    <select class="form-select" id="year" name="year" required>
                        <option @if(old('year') == (date('Y') - 1)) selected @endif value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                        <option @if(old('year') ==  date('Y') ) selected @endif value="{{ date('Y') }}" selected>{{ date('Y') }}</option>
                        <option @if(old('year') == (date('Y') + 1)) selected @endif value="{{ date('Y') + 1 }}">{{ date('Y') + 1 }}</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="status" class="form-label">Estado</label>
                    <select class="form-select" name="status" required>
                        <option @if(old('status') == 1) selected @endif value="1">Activo</option>
                        <option @if(old('status') == 0) selected @endif value="0">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="status" class="form-label">Fecha de inicio</label>
                    <input value="{{ old('date_start') }}" type="date" class="form-control" id="date_start" name="date_start" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="status" class="form-label">Fecha de fin</label>
                    <input value="{{ old('date_end') }}" type="date" class="form-control" id="date_end" name="date_end" required>
                </div>
            </div>

            <div class="mt-3">
                <h2>Parámetros</h2>
                @foreach ($parameterNames as $key => $name)
                    <div class="mb-3">
                        <label for="parameter{{ $key }}" class="form-label">{{ $name }}</label>
                        <input type="text" class="form-control" id="parameter{{ $key }}"
                          name="parameters[{{ $key }}]" required>
                    </div>
                @endforeach
            </div>
            <div class="contenedor">
                <a href="{{ route('cycles.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary ">Guardar</button>
                <div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
