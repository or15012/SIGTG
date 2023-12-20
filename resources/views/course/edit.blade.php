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
            <a href="{{ route('courses.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Editar ciclo</h1>
        <form action="{{ route('courses.update', $course->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-12">
                    <label for="name" class="form-label">Nombre</label>
                    <input value="{{ old('name', $course->name) }}" type="text" class="form-control" id="name" name="name" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea cols="1" rows="2" class="form-control" id="description" name="description" required>{{ old('description', $course->description) }}</textarea>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="teachers" class="form-label">Docentes</label>
                    <select multiple class="form-select select2" id="teachers" name="teachers[]" required>
                        @foreach ($teachers as $teacher)
                            <option @if(in_array($teacher->id, $course->teacher_courses->pluck('id')->toArray())) selected @endif value="{{$teacher->id}}">{{$teacher->first_name.' '.$teacher->middle_name.' '.$teacher->last_name.' '.$teacher->second_last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="cycle_id" class="form-label">Ciclo</label>
                    <select class="form-select" id="cycle_id" name="cycle_id" required>
                        @foreach ($cycles as $cycle)
                            <option @if($cycle->id == old('cycle_id', $course->cycle_id)) selected @endif value="{{$cycle->id}}">{{$cycle->number.' - '.$cycle->year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="school_id" class="form-label">Escuela</label>
                    <select class="form-control" id="school_id" name="school_id" required>
                        @foreach ($schools as $school)
                            <option @if($school->id == old('school_id', $course->school_id)) selected @endif value="{{$school->id}}">{{$school->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="contenedor">
                <a href="{{ route('courses.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
