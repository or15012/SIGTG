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
        <h1>Lista de asesores</h1>
        {{--<a href="{{ route('advisers.activities.create') }}" class="btn btn-primary mb-3">Nueva actividad</a>--}}


        {{--
        <div class="float-end d-flex justify-content-end align-items-center">

            <button class="btn btn-secondary ajax-modal my-1" data-title="Carga de actividades" title="Cargar de actividades"
                href="{{ route('activities.modal.load.activities') }}">
                <i class="fas fa-file"></i> Importar actividades
            </button>
        </div>--}}

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
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Acitividades</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($advisers as $adviser)
                    <tr>
                        <td>{{ $adviser->first_name }} {{ $adviser->middle_name }} </td>
                        <td> {{ $adviser->last_name }} {{ $adviser->second_last_name }}</td>
                        <td>
                            <a href="{{ route('advisers.activities.show', $adviser->id) }}" class="btn btn-primary"><i
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
