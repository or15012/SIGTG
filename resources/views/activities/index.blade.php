@extends('layouts.master')
@section('title')
    @lang('translation.UserList')
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
        <h1>Lista de actividades</h1>
        <a href="{{ route('activities.create') }}" class="btn btn-primary mb-3">Registrar actividad</a>


        <div class="float-end d-flex justify-content-end align-items-center">
            <a href="{{ route('activities.download.template') }}" class="btn btn-primary me-2">Descargar plantilla</a>

            {{-- <form style="margin-left: 5px;" class="d-flex justify-content-end align-items-end" method="POST"
                action="{{ route('activities.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group mr-2 d-none">
                    <input type="file" class="form-control" id="excelFile" name="excelFile" />
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-secondary" id="btnImportar"><i class="fa-solid fa-file-excel"></i>
                        Importar</button>
                </div>
                <div class="form-group d-none">
                    <button type="submit" class="btn btn-secondary" id="btnCargar"><i class="fa-solid fa-file-import"></i>
                        Cargar archivo</button>
                </div>
            </form> --}}

            <button class="btn btn-secondary ajax-modal my-1" data-title="Carga de actividades" title="Cargar de actividades"
                href="{{ route('activities.modal.load.activities') }}">
                <i class="fas fa-file"></i> Importar actividades
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
        @endif

        <table class="table table-bordered table-striped table-hover table-border-custom table-rounded">
            <thead>
                <tr class="red-student">
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Fecha de inicio</th>
                    <th>Fecha de fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activities as $activity)
                    <tr>
                        <td>{{ $activity->name }}</td>
                        <td>{{ $activity->description }}</td>
                        <td>{{ $activity->status }}</td>
                        <td>{{ $activity->date_start }}</td>
                        <td>{{ $activity->date_end }}</td>
                        <td>
                            <a href="{{ route('activities.show', $activity->id) }}" class="btn btn-primary"><i
                                    class="fas fa-eye"></i></a>
                            <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-primary"><i
                                    class="fas fa-pen"></i></a>
                            <button class="btn btn-secondary ajax-modal my-1" data-title="Cambio de estado"
                                href="{{ route('activities.modal.status.activities', $activity->id) }}">
                                <i class="fas fa-exchange-alt"></i>
                            </button>

                            <form action="{{ route('activities.destroy', $activity->id) }}" method="POST"
                                style="display: inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger buttonDelete"><i
                                        class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/users.js') }}"></script>
@endsection
