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
    <div class="contenedor">
            <a href="{{ route('advisers.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
    <div class="container">
        
        <h1>Lista de actividades de {{ $user->first_name }} {{ $user->last_name }} </h1>
        
        <table class="table table-bordered table-striped table-hover table-border-custom table-rounded">
            <thead>
                <tr class="red-student">
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activities as $activity)
                    <tr>
                        <td>{{ $activity->name }}</td>
                        <td>{{ $activity->description }}</td>
                        @if($activity->status == 1)
                        <td>Activa</td>
                        @else
                        <td>Finalizada</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
