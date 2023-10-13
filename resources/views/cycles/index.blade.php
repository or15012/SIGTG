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
        <h1>Listado de Ciclos</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Año</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cycles as $cycle)
                    <tr>
                        <td>{{ $cycle->number }}</td>
                        <td>{{ $cycle->year }}</td>
                        <td>{{ $cycle->status ? 'Activo' : 'Inactivo' }}</td>
                        <td>
                            <a href="{{ route('cycles.show', $cycle->id) }}" class="btn btn-primary">Ver</a>
                            <a href="{{ route('cycles.edit', $cycle->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('cycles.destroy', $cycle->id) }}" method="POST" style="display: inline;">
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
