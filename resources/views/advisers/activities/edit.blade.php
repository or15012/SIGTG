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
            <a href="{{ route('advisers.activities.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Editar actividad</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('advisers.activities.update',$activity->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="name" name="name"
                value="{{ old('name', $activity->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <textarea class="form-control" id="description" name="description" required>{{ old('description', $activity->description) }}</textarea>
        </div>

        <div class="mb-3">
            <div class="col-12">
            <label for="id_cycle" class="form-label">Ciclo</label>
                <select class="form-select" name="id_cycle" required>
                    @foreach($ciclos as $ciclo)
                    <option value="{{$ciclo->id}}" {{ $activity->id_cycle == $ciclo->id ? 'selected' : '' }} >{{$ciclo->number}} - {{$ciclo->year}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label for="status" class="form-label">Estado</label>
                <select class="form-select" name="status" required>
                    <option value="1" {{ $activity->status == 1 ? 'selected' : '' }}>Activa</option>
                    <option value="0" {{ $activity->status == 0 ? 'selected' : '' }}>Finalizada</option>
                </select>
            </div>
        </div>


        <div class="contenedor">
            <a href="{{ route('advisers.activities.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <div>
                </form>
            </div>
        @endsection

        @section('script')
            <script src="{{ URL::asset('assets/js/app.js') }}"></script>
        @endsection
