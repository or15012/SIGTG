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
        <h1>Pre-inscripción a cursos de especialización</h1>
        <br>
        <h5>Seleccione los cursos a los cuales desea inscribirse:</h5>
        <br>
        <form action="{{ route('courses.preregistrations.store') }}" method="POST">
            @csrf
            <div class="d-flex flex-wrap">
                @foreach ($courses as $course)
                    <div class="card" style="width: 18rem; margin-right: 10px; margin-bottom: 10px;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $course->name }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">Ciclo {{ $course->cycle_number }}</h6>
                            <p class="card-text">{{ $course->description }}</p>
                            <div class="text-end">
                                <input type="hidden" name="course_id[]" value="{{ $course->id }}" />
                                <input type="hidden" class="checkhidden" name="is_checked[]" value="0">
                                <input class="form-check-input" type="checkbox" name="is_checked[]"
                                    value="{{ old('is_checked', 0) }}" aria-label="...">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="contenedor">
                <a href="{{ route('home') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary ">Guardar</button>
                <div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/course_preregistration.js') }}"></script>
@endsection
