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
            <a href="{{ route('extensions.coordinator.index') }}" class="btn btn-danger regresar-button"><i
                    class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>

        <h1>Lista de prórrogas</h1>


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
                        <td>{{ $extension->name_type }}</td>
                        <td>{{ $extension->name }}</td>
                        <td>{{ $extension->description }}</td>
                        <td>
                            @switch ($extension->status)
                                @case(0)
                                    {{ 'Presentado' }}
                                @break

                                @case(1)
                                    {{ 'Aprobado' }}
                                @break

                                @case(2)
                                    {{ 'Rechazado' }}
                                @break
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('extensions.coordinator.show', $extension->id_extension) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i></a>

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
