@extends('layouts.master')
@section('title')
    @lang('translation.Dashboard')
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
        <h1>Lista de Asesorias</h1>
        <a href="{{ route('consultings.create') }}" class="btn btn-primary mb-3">Agregar asesoria</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tema</th>
                    <th>Número</th>
                    <th>Resumen</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($consultings as $consulting)
                    <tr>
                        <td>{{ $consulting->id }}</td>
                        <td>{{ $consulting->topics }}</td>
                        <td>{{ $consulting->number }}</td>
                        <td>{{ $consulting->summary }}</td>
                        <td>{{ $consulting->date }}</td>
                        <td>
                            <a href="{{ route('consultings.edit', $consulting->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('consultings.destroy', $consulting->id) }}" method="POST" style="display: inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
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
@endsection