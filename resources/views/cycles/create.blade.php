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
        <h1>Crear Ciclo</h1>
        <form action="{{ route('cycles.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-12">
                    <label for="number" class="form-label">Número</label>
                    <input type="number" class="form-control" id="number" name="number" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="year" class="form-label">Año</label>
                    <input type="number" class="form-control" id="year" name="year" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="status" class="form-label">Estado</label>
                    <select class="form-select" name="status" required>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
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

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
