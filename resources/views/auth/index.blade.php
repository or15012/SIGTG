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
        <h1>Lista de usuarios</h1>
        <a href="{{ route('register') }}" class="btn btn-primary mb-3">Nuevo usuario</a>


        <div class="float-end d-flex justify-content-end align-items-center">
            <a href="{{ route('users.download.template') }}" class="btn btn-primary">Descargar plantilla</a>

            <form style="margin-left: 5px;" class="d-flex justify-content-end align-items-end" method="POST"
                action="{{ route('users.import') }}" enctype="multipart/form-data">
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
            </form>
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
                    <th>Correo electrónico</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Carnet</th>
                    <th>Escuela</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->first_name }} {{ $user->middle_name }}</td>
                        <td>{{ $user->last_name }} {{ $user->second_last_name }}</td>
                        <td>{{ $user->carnet }}</td>
                        <td>{{ $user->school->name }}</td>
                        <td>{{ $userTypes[$user->type] }}</td>
                        <td>
                            @if ($userTypes[$user->type] === 'Estudiante')
                                <a href="{{ route('users.agreements', $user->id) }}" title="Acuerdos de estudiante"
                                    class="btn btn-primary my-1">
                                    <i class="fas fa-cog"></i>
                                </a>
                            @endif

                            <a href="{{ route('users.assign.roles', $user->id) }}" title="Roles de usuario"
                                class="btn btn-primary my-1"><i class="fas fa-pen"></i></a>
                            <a href="{{ route('users.edit', $user->id) }}" title="Editar usuario"
                                class="btn btn-secondary my-1"><i class="fas fa-user-edit"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {!! $users->withQueryString()->links('pagination::bootstrap-5') !!}
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/users.js') }}"></script>
@endsection
