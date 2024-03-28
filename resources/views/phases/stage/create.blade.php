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
            <a href="{{ route('stages.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>

            Registrar área temática

        </h1>

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

        <form action="{{ route('phases.stage.store') }}" id="form-stage" method="POST">
            @csrf
            {{-- <div class="mb-3">
                <label for="name" class="form-label">

                    Nombre área temática
                </label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                    required>
            </div> --}}

            <div class="mb-3">
                <label for="areas" class="form-label">Seleccionar áreas</label>
                <select class="form-control" id="areas" name="areas[]" multiple>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="cycle" class="form-label">Ciclo</label>
                <select class="form-control" id="cycle" name="cycle">
                    <option value="0"> Seleccione un ciclo </option>
                    @foreach ($cycles as $cycle)
                        <option value="{{ $cycle->id }}"> {{ $cycle->number }}-{{ $cycle->year }} </option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="phase_id" value="{{ $phase->id }}">
            <div class="mb-3">
                <label for="protocol" class="form-label">Protocolo</label>
                <select class="form-control" id="protocol" name="protocol" disabled>
                    <option value="0"> Seleccione un protocolo </option>
                    @foreach ($protocols as $protocol)
                        <option value="{{ $protocol->id }}" @if ($protocol->id == session('protocol')['id']) selected @endif>
                            {{ $protocol->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="school" class="form-label">Escuela</label>
                <select class="form-control" id="school" name="school" disabled>
                    <option value="0"> Seleccione una escuela </option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}" @if ($school->id == session('school')['id']) selected @endif>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="sort" class="form-label">Orden</label>
                <input type="number" class="form-control" id="sort" name="sort" value="{{ old('sort') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="percentage" class="form-label">Porcentaje</label>
                <input type="number" class="form-control" id="percentage" name="percentage"
                    value="{{ old('percentage') }}" required>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="status" class="form-label">Fecha de inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                        min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="status" class="form-label">Fecha de fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                        min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                </div>
            </div>

            <div class="contenedor">
                <a href="{{ route('stages.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/stages.js') }}"></script>
@endsection

<div id="overlay" style="display: none!important; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.8); z-index: 9999; display: flex; justify-content: center; align-items: center;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
