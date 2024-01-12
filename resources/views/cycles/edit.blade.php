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
        <h1>Editar ciclo</h1>
        <form action="{{ route('cycles.update', $cycle->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="year" class="form-label">Año</label>
                <select class="form-select" id="year" name="year" required>
                    <option value="{{ date('Y') - 1 }}" {{ $cycle->year == date('Y') - 1 ? 'selected' : '' }}>
                        {{ date('Y') - 1 }}</option>
                    <option value="{{ date('Y') }}" {{ $cycle->year == date('Y') ? 'selected' : '' }}>
                        {{ date('Y') }}</option>
                    <option value="{{ date('Y') + 1 }}" {{ $cycle->year == date('Y') + 1 ? 'selected' : '' }}>
                        {{ date('Y') + 1 }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="number" class="form-label">Número</label>
                <select class="form-select" id="number" name="number" required>
                    <option value="1" {{ $cycle->number == 1 ? 'selected' : '' }}>I</option>
                    <option value="2" {{ $cycle->number == 2 ? 'selected' : '' }}>II</option>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="status" class="form-label">Estado</label>
                    <select class="form-select" name="status" required>
                        <option value="1" {{ $cycle->status == 1 ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ $cycle->status == 0 ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="status" class="form-label">Fecha de inicio</label>
                    <input type="date" value="{{ $cycle->date_start }}" class="form-control" id="date_start"
                        name="date_start" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="status" class="form-label">Fecha de fin</label>
                    <input type="date" value="{{ $cycle->date_end }}" class="form-control" id="date_end"
                        name="date_end" required>
                </div>
            </div>

            <h2>Parámetros</h2>
            @foreach ($parameterNames as $key => $name)
                @php
                    $parameterValue = '';
                    foreach ($cycle->parameters as $parameter) {
                        if ($parameter->name === $key) {
                            $parameterValue = $parameter->value;
                            break;
                        }
                    }
                @endphp
                <div class="mb-3">
                    <label for="parameter{{ $key }}" class="form-label">{{ $name }}</label>
                    <input type="text" class="form-control" id="parameter{{ $key }}"
                        name="parameters[{{ $key }}]" value="{{ $parameterValue }}" required>
                </div>
            @endforeach
            <div class="contenedor">
                <a href="{{ route('cycles.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
