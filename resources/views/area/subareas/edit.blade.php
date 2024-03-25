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
            <a href="{{ route('areas.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Editar área</h1>

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

        <form action="{{ route('areas.subareas.update', $subarea->id) }}" id="form-area" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nombre de subárea</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $subarea->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="area_id" class="form-label">Área</label>
                <select class="form-control" name="area_id" id="area_id">
                    @foreach ($areas as $area)
                        @if ($subarea->area_id == $area->id)
                            <option value="{{ $area->id }}" selected>
                                {{ $area->name }}
                            </option>
                        @else
                            <option value="{{ $area->id }}">
                                {{ $area->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="contenedor">
                <a href="{{ route('areas.subareas.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
