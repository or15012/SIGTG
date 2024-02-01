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
        <h1>Lista de entidades</h1>
        <a href="{{ route('entities.create') }}" class="btn btn-primary mb-3">Nueva entidad</a>
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="table-danger">
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($entities as $entity)
                    <tr>
                        <td>{{ $entity->name }}</td>
                        <td>{{ $entity->address }}</td>
                        <td>{{ $entity->status() }}</td>
                        <td>
                            <a href="{{ route('entities.show', $entity->id) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('entities.edit', $entity->id) }}" class="btn btn-primary"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('entities.destroy', $entity->id) }}" method="POST" style="display: inline;">
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
