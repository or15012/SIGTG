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
            <a href="{{ route('events.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Registrar defensa</h1>

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

        <form action="{{ route('events.store') }}" id="form-forum" method="POST" enctype="multipart/form-data">
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
                <label for="place" class="form-label">Lugar:</label>
                <input class="form-control" type="text" id="place" name="place" required>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Fecha:</label>
                <input class="form-control" type="datetime-local" id="date" name="date"
                    value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}"
                    min="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" required>
            </div>

            

            <div class="row mb-3">
                <div class="col-12">
                    <label for="cycle_id" class="form-label">Ciclo:</label>
                    <select class="form-select" id="cycle_id" name="cycle_id" disabled>
                        @foreach ($cycles as $cycle)
                            <option @if ($cycle->id == old('cycle_id')) selected @endif value="{{ $cycle->id }}">
                                {{ $cycle->number . ' - ' . $cycle->year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="school_id" class="form-label">Escuela:</label>
                    <select class="form-select" id="school_id" name="school_id" disabled>
                        @foreach ($schools as $school)
                            <option value="{{ $school->id }}" @if ($school->id == session('school')['id']) selected @endif>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>




            <div class="contenedor">
                <a href="{{ route('forum.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/stages.js') }}"></script>
@endsection
