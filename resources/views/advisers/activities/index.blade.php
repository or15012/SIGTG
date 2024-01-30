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
        <h1>Lista de actividades de asesor</h1>
        <a href="{{ route('advisers.activities.create') }}" class="btn btn-primary mb-3">Nueva actividad</a>


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
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
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
                        <td>
                            {{--<a href="{{ route('advisers.activities.show', $activity->id) }}" class="btn btn-primary"><i
                                    class="fas fa-eye"></i></a>--}}
                            <a href="{{ route('advisers.activities.edit', $activity->id) }}" class="btn btn-primary"><i
                                    class="fas fa-pen"></i></a>

                            <form action="{{ route('activities.destroy', $activity->id) }}" method="POST"
                                style="display: inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger buttonDelete"><i
                                        class="fas fa-trash-alt"></i></button>
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
    <script src="{{ URL::asset('js/users.js') }}"></script>
@endsection
