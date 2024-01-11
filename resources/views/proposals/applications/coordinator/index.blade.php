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
                    <th>Entidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $application)
                    <tr>
                        <td>{{ $application->name }}</td>
                        <td>
                            @switch($application->status)
                                @case(0)
                                    Hoja de vida presentada.
                                @break

                                @case(1)
                                    Hoja de vida aprobada.
                                @break

                                @case(2)
                                    Hoja de vida rechazada.
                                @break

                                @default
                            @endswitch
                            <td>{{ $application->users->name}}</td>
                            {{-- <td>{{ $application->proposal->name}}</td> --}}
                        </td>
                        <td>
                            {{-- <a href="{{ route('proposals.show', $appli->id) }}" class="btn btn-primary"><i
                                    class="fas fa-eye"></i>
                            </a> --}}
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
