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
            <a href="{{ route('roles.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Registrar rol</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="description" name="description"
                    value="{{ old('description') }}">
            </div>
            <div class="row mb-3">
                @foreach ($permissions as $permission)
                    <div class="col-md-3">
                        <div class="card mb-4">
                            <div class="card-header">{{ $permission->name }}</div>
                            <div class="card-body">
                                <p>{{ $permission->description }}</p>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="flexSwitchCheck{{ $permission->id }}" name="permissions[]"
                                        value="{{ $permission->id }}">
                                    <label class="form-check-label"
                                        for="flexSwitchCheck{{ $permission->id }}">Seleccionar</label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="contenedor">
                <a href="{{ route('roles.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
