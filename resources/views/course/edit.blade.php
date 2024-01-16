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
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h1>Editar Curso</h1>
        <form  id="course-form" action="{{ route('courses.update', $course->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-12">
                    <label for="name" class="form-label">Nombre</label>
                    <input value="{{ old('name', $course->name) }}" type="text" class="form-control" id="name"
                        name="name" required>
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
                            <option @if (in_array($teacher->id, $course->teacher_courses->pluck('id')->toArray())) selected @endif value="{{ $teacher->id }}">
                                {{ $teacher->first_name . ' ' . $teacher->middle_name . ' ' . $teacher->last_name . ' ' . $teacher->second_last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="cycle_id" class="form-label">Ciclo</label>
                    <select class="form-select" id="cycle_id" name="cycle_id" required>
                        @foreach ($cycles as $cycle)
                            <option @if ($cycle->id == old('cycle_id', $course->cycle_id)) selected @endif value="{{ $cycle->id }}">
                                {{ $cycle->number . ' - ' . $cycle->year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="school_id" class="form-label">Escuela</label>
                <select class="form-control" name="school_id" id="school_id" disabled>
                    @foreach ($schools as $school)
                        @if ($course->school_id == $school->id)
                            <option value="{{ $school->id }}" selected>
                                {{ $school->name }}
                            </option>
                        @else
                            <option value="{{ $school->id }}">
                                {{ $school->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>


            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="presuscriptions-tab" data-bs-toggle="tab"
                        data-bs-target="#presuscriptions-tab-pane" type="button" role="tab"
                        aria-controls="presuscriptions-tab-pane" aria-selected="true">Pre-inscripciones</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="suscriptions-tab" data-bs-toggle="tab"
                        data-bs-target="#suscriptions-tab-pane" type="button" role="tab"
                        aria-controls="suscriptions-tab-pane" aria-selected="false">Inscripciones</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="presuscriptions-tab-pane" role="tabpanel"
                    aria-labelledby="presuscriptions-tab" tabindex="0">
                    <div class="mt-3">
                        <div class="form-group select-product-container mt-2">
                            <select class="form-control select2-ajax" data-route="students/get-students"
                                id="selectPreregistration" onchange="getStudentPreregistration(event)"
                                name="selectPreregistration">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <table class="table" id="tablePreregistrations">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Carnet</th>
                                    <th>Correo</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($course->preregistrations as $preregistration)
                                    <tr>
                                        <td>{{ $preregistration->full_name() }}</td>
                                        <td>{{ $preregistration->carnet }}</td>
                                        <td>{{ $preregistration->email }}</td>
                                        <td><button type="button" class="btn btn-danger btn-xs remove-preregistration"><i
                                                    class='bx bx-trash'></i></button>
                                            <input type="hidden" class="input-user_id_preregistration"
                                                name="user_id_preregistration[]" value="{{ $preregistration->id }}" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center"><b>¡Aún no hay pre-inscripciones!</b></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="suscriptions-tab-pane" role="tabpanel" aria-labelledby="suscriptions-tab"
                    tabindex="0">
                    <div class="mt-3">
                        <div class="form-group select-product-container mt-2">
                            <select style="width:100%" class="form-control select2-ajax" data-route="students/get-students"
                                id="selectRegistration" onchange="getStudentRegistration(event)"
                                name="selectRegistration">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <table class="table" id="tableRegistrations">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Carnet</th>
                                    <th>Correo</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($course->registrations as $registration)
                                    <tr>
                                        <td>{{ $registration->full_name() }}</td>
                                        <td>{{ $registration->carnet }}</td>
                                        <td>{{ $registration->email }}</td>
                                        <td><button type="button" class="btn btn-danger btn-xs remove-registration"><i
                                                    class='bx bx-trash'></i></button>
                                            <input type="hidden" class="input-user_id_registration"
                                                name="user_id_registration[]" value="{{ $registration->id }}" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center"><b>¡Aún no hay inscripciones!</b></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
    <script src="{{ URL::asset('/js/course.js') }}"></script>
    <script src="{{ URL::asset('/js/courses.js') }}"></script>
@endsection
