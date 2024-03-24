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

        <form action="{{ route('areas.update', $area->id) }}" id="form-area" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nombre de la área</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $area->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="protocol" class="form-label">Protocolo</label>
                <select class="form-control" name="protocol" id="protocol" disabled>
                    @foreach ($protocols as $protocol)
                        @if ($area->protocol_id == $protocol->id)
                            <option value="{{ $protocol->id }}" selected>
                                {{ $protocol->name }}
                            </option>
                        @else
                            <option value="{{ $protocol->id }}">
                                {{ $protocol->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="school" class="form-label">Escuela</label>
                <select class="form-control" name="school" id="school" disabled>
                    @foreach ($schools as $school)
                        @if ($area->school_id == $school->id)
                            <option value="{{ $school->id }}" selected>
                                {{ $school->name }}
                            </option>
                        @else
                            <option value="{{ $school->id }}">
                                {{ $school->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="contenedor">
                <a href="{{ route('areas.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/areas.js') }}"></script>
@endsection
