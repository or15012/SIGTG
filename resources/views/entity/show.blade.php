@extends('layouts.master')

@section('title')
    @lang('translation.ShowPerfil')
@endsection

@section('content')
    <div class="container">
        <div class="contenedor">
            <a href="{{ route('entities.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>

        <h1 class="mb-5">Consultar entidad</h1>

        <h3>Entidad</h3>

        <dl class="row">
            <dt class="col-sm-3">Nombre:</dt>
            <dd class="col-sm-9">{{ $entity->name }}</dd>

            <dt class="col-sm-3">Dirección:</dt>
            <dd class="col-sm-9">{{ $entity->address }}</dd>

            <dt class="col-sm-3">Estado:</dt>
            <dd class="col-sm-9">
                @if($entity->status == 1)
                    Activo
                @elseif($entity->status == 0)
                    Inactivo
                @else
                @endif

        </dl>

        <h3>Contactos</h3>

        @if ($contacts->count() > 0)
            <table class="table table-bordered table-striped" id="table-contacts">
                <thead>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Cargo</th>
                </thead>
                <tbody>
                    @foreach ($contacts as $contact)
                        <tr>
                            <td>{{ $contact->name }}</td>
                            <td>{{ $contact->phone_number }}</td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ $contact->position }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay contactos disponibles.</p>
        @endif
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
