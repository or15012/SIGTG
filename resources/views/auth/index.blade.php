@extends('layouts.master')
@section('title')
    @lang('translation.UserList')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            SIGTG-FIA
        @endslot
        @slot('title')
            Welcome !
        @endslot
    @endcomponent

    <div class="container">
        <h1>Lista de Usuarios</h1>
        <a href="{{ route('register') }}" class="btn btn-primary mb-3">Agregar Usuario</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Correo electrónico</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Carnet</th>
                    <th>Escuela</th>
                    <th>Tipo</th>
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
