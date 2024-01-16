@extends('layouts.master')
@section('title')
    @lang('translation.UserList')
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
        <h1>Lista de actividades</h1>


    <h5>Número de grupo: {{ $group->number }}</h5>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
    @endif

    <table class="table table-bordered table-striped table-hover table-border-custom table-rounded">
        <thead>
            <tr class="red-student">
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Fecha de inicio</th>
                <th>Fecha de fin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activities as $activity)
                <tr>
                    <td>{{ $activity->name }}</td>
                    <td>{{ $activity->description }}</td>
                    <td>{{ $activity->status }}</td>
                    <td>{{ $activity->date_start }}</td>
                    <td>{{ $activity->date_end }}</td>
                    <td>
                        <a href="{{ route('activities.show', $activity->id) }}" class="btn btn-primary"><i
                                class="fas fa-eye"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/users.js') }}"></script>
@endsection
