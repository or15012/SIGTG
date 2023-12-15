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
        <h1>Lista de asesorias</h1>
        @if (!$status)
        <div class="alert alert-info mt-3">
            No se puede registrar ni realizar cambios en asesorias. Proyecto inactivo.
        </div>
    @endif
        @if ($userType === 1 && $status)
            <a href="{{ route('consultings.create', $project->id) }}" class="btn btn-primary mb-3">Registrar asesoria</a>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="table-danger">
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
                        <td>{{ \Carbon\Carbon::parse($consulting->date)->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('consultings.show', [$consulting->id, $project->id]) }}" class="btn btn-primary"><i
                                    class="fas fa-eye"></i></a>

                            @if ($status)
                                <a href="{{ route('consultings.edit', [$consulting->id, $project->id]) }}" class="btn btn-primary"><i
                                        class="fas fa-pen"></i></a>

                                <form action="{{ route('consultings.destroy', $consulting->id) }}" method="POST"
                                    style="display: inline">
                                    @csrf
                                    @method('DELETE')
                                    @if ($consulting->number !== null)
                                        <button type="submit" class="btn btn-danger buttonDelete"><i
                                                class="fas fa-trash-alt"></i></button>
                                    @endif
                                </form>
                            @endif
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
