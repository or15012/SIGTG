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
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="contenedor">
            <a href="{{ route('entities.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Nueva entidad</h1>
        <form action="{{ route('entities.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-12">
                    <label for="name" class="form-label">Nombre</label>
                    <input value="{{ old('name') }}" type="text" class="form-control" id="name" name="name" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="address" class="form-label">Dirección</label>
                    <textarea cols="1" rows="2" class="form-control" id="address" name="address" required>{{ old('address') }}</textarea>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="status" class="form-label">Estado</label>
                    <select class="form-select select2" id="status" name="status" required style="width: 100%">
                        <option value="1" @if(old('status', 1) == 1) selected @endif>Activo</option>
                        <option value="0" @if(old('status', 1) == 0) selected @endif>Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <h2>Contactos</h2>
                <button class="btn btn-secondary" type="button" onclick="addContact()"><i class="fas fa-plus"></i></button>
            </div>
            <table class="table table-bordered table-striped" id="table-contacts">
                <thead>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Cargo</th>
                    <th>Acción</th>
                </thead>
                <tbody>
                    <tr><td class="empty-table text-center" colspan="100%">Vacío</td></tr>
                </tbody>
            </table>

            <div class="contenedor">
                <a href="{{ route('entities.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary ">Guardar</button>
                <div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/entities.js') }}"></script>
@endsection
