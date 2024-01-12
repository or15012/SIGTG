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

        <h1>Lista de estudiantes que han aplicado</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="table-danger">
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Estudiante</th>
                    <th>Propuesta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $application)
                    <tr>
                        <td>{{ $application->name }}</td>
                        <td>
                            @switch($application->status)
                                @case(0)
                                    CV presentado.
                                @break

                                @case(1)
                                    CV aprobado.
                                @break

                                @case(2)
                                    CV rechazado.
                                @break

                                @default
                            <td>
                            @endswitch

                        <td>{{ $application->user->first_name }} {{ $application->user->last_name }}</td>
                        <td>{{ $application->proposal->name }}</td>
                        <td>
                            <a href="{{ route('proposals.applications.coordinator.show', $application->id) }}"
                                class="btn btn-primary"><i class="fas fa-eye"></i>
                            </a>
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
