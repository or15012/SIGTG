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
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <h1>Lista de Cursos</h1>
        <a href="{{ route('courses.create') }}" class="btn btn-primary mb-3">Nuevo Curso</a>

        <div class="float-end d-flex justify-content-end align-items-center">
            <a href="{{ route('courses.download.template') }}" class="btn btn-primary">Descargar plantilla</a>

            <form style="margin-left: 5px;" class="d-flex justify-content-end align-items-end" method="POST"
                action="{{ route('courses.import.registrations') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group mr-2 d-none">
                    <input type="file" class="form-control" id="excelFile" name="excelFile" />
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-secondary" id="btnImportar"><i class="fa-solid fa-file-excel"></i>
                        Importar</button>
                </div>
                <div class="form-group d-none">
                    <button type="submit" class="btn btn-secondary" id="btnCargar"><i class="fa-solid fa-upload"></i> Subir
                        archivo</button>
                </div>
            </form>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
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
                        <td>{{ $course->id }}</td>
                        <td>{{ $course->name }}</td>
                        <td>{{ $course->description }}</td>
                        <td>{{ $course->cycle->number }}</td>
                        <td>{{ $course->school->name }}</td>
                        <td>
                            <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary"><i
                                    class="fas fa-eye"></i></a>
                            <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-primary"><i
                                    class="fas fa-pen"></i></a>
                            <form action="{{ route('courses.destroy', $course->id) }}" method="POST"
                                style="display: inline;">
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
        {!! $courses->withQueryString()->links('pagination::bootstrap-5') !!}
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('/js/course.js') }}"></script>
@endsection
