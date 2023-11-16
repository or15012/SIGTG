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
        <h1>Lista de ciclos</h1>
        <a href="{{ route('cycles.create') }}" class="btn btn-primary mb-3">Nuevo ciclo</a>
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
                            <a href="{{ route('cycles.show', $cycle->id) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('cycles.edit', $cycle->id) }}" class="btn btn-primary"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('cycles.destroy', $cycle->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger buttonDelete"><i class="fas fa-trash-alt"></i></button>
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
