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
        <h1>Editar Ciclo</h1>
        <form action="{{ route('cycles.update', $cycle->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="number" class="form-label">Número</label>
                <input type="number" class="form-control" id="number" name="number" value="{{ $cycle->number }}" required>
            </div>
            <div class="mb-3">
                <label for="year" class "form-label">Año</label>
                <input type="number" class="form-control" id="year" name="year" value="{{ $cycle->year }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Estado</label>
                <select class="form-select"  name="status" required>
                    <option value="1" {{ $cycle->status ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ !$cycle->status ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
            <h2>Parámetros</h2>
            @foreach ($cycle->parameters as $parameter)
                <div class="mb-3">
                    <label for="parameter{{ $parameter->id }}" class="form-label">{{ $parameterNames[$parameter->name] }}</label>
                    <input type="text" class="form-control" id="parameter{{ $parameter->id }}"
                        name="parameters[{{ $parameter->name }}]" value="{{ $parameter->value }}" required>
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
