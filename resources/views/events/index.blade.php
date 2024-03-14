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
        <div class="contenedor">
            <a href="{{ route('projects.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>

        <h1>Lista de defensas</h1>

        <a href="{{ route('events.create', $project->id) }}" class="btn btn-primary mb-3">Registrar defensa</a>
        
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
                    <th>Nombre</th>
                    <th style="width: 20%">Fecha</th>
                    <th style="width: 20%">Lugar</th>
                    <th style="width: 30%">Descripcion</th>
                    <th style="width: 20%">Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($event->date)->format('d-m-Y H:i:s') }}</td>
                        <td>{{ $event->place }}</td>
                        <td>{{ $event->description }}</td>
                        <td>{{ $withdrawal->status() }}</td>
                        <td>
                            <a href="{{ route('events.edit', ['events' => $event->id, 'project' => $project->id]) }}"class="btn btn-primary"> 
                                <i class="fas fa-pen"></i></a>
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
