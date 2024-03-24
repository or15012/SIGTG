@extends('layouts.master')
@section('title')
    @lang('translation.Areas')
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
            <a href="{{ route('areas.subareas.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Registrar área</h1>

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

        <form action="{{ route('areas.store') }}" id="form-area" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre de subárea</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="protocol" class="form-label">Área</label>
                <select class="form-control" id="area_id" name="area_id">
                    <option value="0"> Seleccione una area </option>
                    @foreach ($protocols as $protocol)
                        <option value="{{ $protocol->id }}" @if ($protocol->id == session('protocol')['id']) selected @endif>
                            {{ $protocol->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="contenedor">
                <a href="{{ route('areas.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/areas.js') }}"></script>
@endsection