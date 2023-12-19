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
        <h1>Editar prórroga</h1>

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

        <form action="{{ route('extensions.update', $extension->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="project_id" class="form-label">Proyecto {{ $project->id }}</label>
                {{-- <select class="form-control" id="project_id" name="project_id">
                    <option value=""> Seleccione un proyecto </option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" @if ($project->id == $extension->project_id) selected @endif>
                            {{ $project->name }}</option>
                    @endforeach
                </select> --}}
                <input type="hidden" name="project_id" value="{{ $project->id }}">
            </div>
            <div class="mb-3">
                <label for="type_extension_id" class="form-label">Tipo de extensión</label>
                <select class="form-control" id="type_extension_id" name="type_extension_id">
                    <option value=""> Seleccione un tipo de extensión</option>
                    @foreach ($type_extensions as $type_extension)
                        <option value="{{ $type_extension->id }}" @if ($type_extension->id == $extension->type_extension_id) selected @endif>
                            {{ $type_extension->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" name="description" id="description" rows="2" cols="1">{{ old('description', $extension->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="extension_status" class="form-label">Estado</label>
                <select class="form-control" id="extension_status" name="status">
                    <option value="0" @if (old('status', $extension->status) == 0) selected @endif>Presentada</option>
                    <option value="1" @if (old('status', $extension->status) == 1) selected @endif>Aceptada</option>
                    <option value="2" @if (old('status', $extension->status) == 2) selected @endif>Rechazada</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="extension_request_path" class="form-label">Solicitud de prórroga <a
                        href="{{ route('download', ['file' => $extension->extension_request_path]) }}"
                        class="btn btn-secondary archivo">Ver archivo</a></label>
                <input type="file" class="form-control" id="extension_request_path" name="extension_request_path">
            </div>
            <div class="mb-3">
                <label for="schedule_activities_path" class="form-label">Cronograma de actividades <a
                        href="{{ route('download', ['file' => $extension->schedule_activities_path]) }}"
                        class="btn btn-secondary archivo">Ver archivo</a></label>
                <input type="file" class="form-control" id="schedule_activities_path" name="schedule_activities_path">
            </div>
            <div class="mb-3">
                <label for="approval_letter_path" class="form-label">Carta de aprobación de asesor <a
                        href="{{ route('download', ['file' => $extension->approval_letter_path]) }}"
                        class="btn btn-secondary archivo">Ver achivo</a></label>
                <input type="file" class="form-control" id="approval_letter_path" name="approval_letter_path">
            </div>
            <div class="contenedor">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('extensions.index', $project->id) }}" class="btn btn-danger regresar-button">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
