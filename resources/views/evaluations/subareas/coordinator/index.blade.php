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
        @endslot
    @endcomponent
    <div class="container">
        <h1>Lista de proyectos</h1>


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

        <table class="table table-bordered">
            <thead>
                <tr class="table-danger">
                    <th>Número de grupo</th>
                    <th>Nombre de proyecto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td>
                            @if (isset($project->number))
                                {{ $project->number }}
                            @else
                                No asignado
                            @endif

                        </td>
                        <td>{{ $project->name }}</td>

                        <td>
                            <a href="{{ route('evaluations.coordinator.show', $project->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {!! $projects->withQueryString()->links('pagination::bootstrap-5') !!}
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
