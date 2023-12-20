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
        <h1>Lista de cursos</h1>
        <a href="{{ route('courses.create') }}" class="btn btn-primary mb-3">Nuevo curso</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Ciclo</th>
                    <th>Escuela</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courses as $course)
                    <tr>
                        <td>{{ $course->name }}</td>
                        <td>{{ $course->description }}</td>
                        <td>{{ $course->cycle->number }}</td>
                        <td>{{ $course->school->name }}</td>
                        <td>
                            {{-- <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a> --}}
                            <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-primary"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('courses.destroy', $course->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger buttonDelete"><i class="fas fa-trash-alt"></i></button>
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
@endsection
