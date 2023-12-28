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
                    <select class="form-select select2" id="status" name="status" required style="width: 100%">
                        <option value="1" @if(old('status', $entity->status) == 1) selected @endif>Activo</option>
                        <option value="0" @if(old('status', $entity->status) == 0) selected @endif>Inactivo</option>
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
                    @foreach($entity->entity_contacts as $contact)
                        <tr>
                            <td><input type="text" name="contact_name[]" class="form-control" required value="{{$contact->name}}"/></td>
                            <td><input type="text" name="contact_phone_number[]" required class="form-control" value="{{$contact->phone_number}}"/></td>
                            <td><input type="text" name="contact_email[]" class="form-control" value="{{$contact->email}}"/></td>
                            <td><input type="text" name="contact_position[]" class="form-control" value="{{$contact->position}}"/></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="contenedor">
                <a href="{{ route('entities.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/entities.js') }}"></script>
@endsection
