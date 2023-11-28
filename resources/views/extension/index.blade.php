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

        <h1>Lista de prórrogas</h1>
        <a href="{{ route('extensions.create') }}" class="btn btn-primary mb-3">Registrar prórroga</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="table-danger">
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Proyecto</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($extensions as $extension)
                    <tr>
                        <td>{{ $extension->id }}</td>
                        <td>{{ $extension->type_extension->name }}</td>
                        <td>{{ $extension->project->name }}</td>
                        <td>{{ $extension->description }}</td>
                        <td>{{ $extension->status() }}</td>
                        <td>
                            <a href="{{ route('extensions.edit', $extension->id) }}" class="btn btn-primary"><i
                                    class="fas fa-pen"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
