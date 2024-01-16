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
            <a href="{{ route('extensions.index', $project->id) }}" class="btn btn-danger regresar-button"><i
                    class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Registrar notificación</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('extensions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="project_id" class="form-label">Proyecto: {{$project->name}} </label>
                {{-- <select class="form-control" id="project_id" name="project_id">
                    <option value=""> Seleccione un proyecto </option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}"> {{ $project->name }}</option>
                    @endforeach
                </select> --}}
               <input type="hidden" name="project_id" value="{{$project->id}}">
            </div>
            <div class="mb-3">
                <label for="type_extension_id" class="form-label">Tipo de extensión</label>
                <select class="form-control" id="type_extension_id" name="type_extension_id">
                    <option value=""> Seleccione un tipo de extensión</option>
                    @foreach ($type_extensions as $type_extension)
                        <option value="{{ $type_extension->id }}"> {{ $type_extension->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" name="description" id="description" rows="2" cols="1">{{ old('description') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="extension_status" class="form-label">Estado</label>
                <select class="form-control" id="extension_status" name="status">
                    <option value="0" @if (old('status') == 0) selected @endif>Presentada</option>
                    <option value="1" @if (old('status') == 1) selected @endif>Aceptada</option>
                    <option value="2" @if (old('status') == 2) selected @endif>Rechazada</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="extension_request_path" class="form-label">Solicitud de prórroga</label>
                <input type="file" class="form-control" id="extension_request_path" name="extension_request_path">
            </div>
            <div class="mb-3">
                <label for="schedule_activities_path" class="form-label">Cronograma de actividades</label>
                <input type="file" class="form-control" id="schedule_activities_path" name="schedule_activities_path">
            </div>
            <div class="mb-3">
                <label for="approval_letter_path" class="form-label">Carta de aprobación de asesor</label>
                <input type="file" class="form-control" id="approval_letter_path" name="approval_letter_path">
            </div>
            <br />
            <div class="contenedor">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('extensions.index', $project->id) }}" class="btn btn-danger regresar-button">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
