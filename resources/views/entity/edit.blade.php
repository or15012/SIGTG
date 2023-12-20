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
            <a href="{{ route('entities.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Editar entidad</h1>
        <form action="{{ route('entities.update', $entity->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-12">
                    <label for="name" class="form-label">Nombre</label>
                    <input value="{{ old('name', $entity->name) }}" type="text" class="form-control" id="name" name="name" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="address" class="form-label">Dirección</label>
                    <textarea cols="1" rows="2" class="form-control" id="address" name="address" required>{{ old('address', $entity->address) }}</textarea>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="status" class="form-label">Estado</label>
                    <select class="form-select select2" id="status" name="status" required>
                        <option value="1" @if(old('status', $entity->status) == 1) selected @endif>Activo</option>
                        <option value="0" @if(old('status', $entity->status) == 0) selected @endif>Inactivo</option>
                    </select>
                </div>
            </div>


            <h2>Contacto</h2>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="contact_name" class="form-label">Nombre del contacto</label>
                    <input value="{{ old('contact_name', $contact->name) }}" type="text" class="form-control" id="contact_name" name="contact_name" >
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <label for="contact_phone_number" class="form-label">Teléfono del contacto</label>
                    <input value="{{ old('contact_phone_number', $contact->phone_number) }}" type="text" class="form-control" id="contact_phone_number" name="contact_phone_number">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <label for="contact_position" class="form-label">Cargo/Puesto del contacto</label>
                    <input value="{{ old('contact_position', $contact->position) }}" type="text" class="form-control" id="contact_position" name="contact_position">
                </div>
            </div>

            <div class="contenedor">
                <a href="{{ route('entities.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
