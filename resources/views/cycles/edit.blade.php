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

            <h2>Parámetros</h2>
            @foreach ($cycle->parameters as $parameter)
                <div class="mb-3">
                    <label for="parameter{{ $parameter->id }}"
                        class="form-label">{{ $parameterNames[$parameter->name] }}</label>
                    <input type="text" class="form-control" id="parameter{{ $parameter->id }}"
                        name="parameters[{{ $parameter->name }}]" value="{{ $parameter->value }}" required>
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
