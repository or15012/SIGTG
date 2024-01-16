@extends('layouts.master')
@section('title')
    @lang('translation.Logs')
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
        <h1>Lista de bitacoras</h1>


        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="table-danger">
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Tabla</th>
                    <th>Acción</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->id_user }} | {{ $log->nombre }} {{ $log->apellido }}</td>
                        <td>{{ $log->correo }}</td>
                        <td>{{ $log->table_name }}</td>
                        <td>
                            @if ($log->action == 0)
                                Insert
                            @elseif($log->action == 1)
                                Update
                            @else
                                Delete
                            @endif
                        </td>
                        <td>{{ $log->created_at->format('d/m/Y h:i:s a') }}</td>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
