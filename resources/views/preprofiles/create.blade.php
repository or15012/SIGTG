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
        <h1>{{$protocols[0]=='Examen General Técnico Profesional' ? 'Iniciar Trabajo de Graduación':'Conformar grupos'}}</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($protocols[0]=='Examen General Técnico Profesional')
            <div class="contenedor">
                <a href="{{ route('profiles.preprofile.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>Regresar</a>
            </div>
            <h1>Registrar plaanificacion</h1>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profiles.preprofile.storeExg') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea class="form-control" id="description" name="description" required>{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="path" class="form-label">Archivo de planificación</label>
                    <input type="file" class="form-control" accept=".pdf,.PDF" id="path" name="path" required>
                </div>

                <div class="contenedor">
                    <a href="{{ route('profiles.preprofile.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>


        @else
            <div class="contenedor">
                <a href="{{ route('profiles.preprofile.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>Regresar</a>
            </div>
            <h1>Registrar preperfil</h1>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profiles.preprofile.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea class="form-control" id="description" name="description" required>{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="path" class="form-label">Archivo preperfil</label>
                    <input type="file" class="form-control" accept=".pdf,.PDF" id="path" name="path" required>
                </div>

                <div class="mb-3">
                    <label for="path" class="form-label">Resumen preperfil</label>
                    <input type="file" class="form-control" accept=".pdf,.PDF" id="summary_path" name="summary_path" required>
                </div>

                <div class="mb-3">
                    <label for="path" class="form-label">Archivo visión</label>
                    <input type="file" class="form-control" accept=".pdf,.PDF" id="vision_path" name="vision_path" required>
                </div>

                <div class="mb-3">
                    <label for="path" class="form-label">Archivo calculo de tamaño</label>
                    <input type="file" class="form-control" accept=".pdf,.PDF" id="size_calculation_path" name="size_calculation_path" required>
                </div>

                <div class="mb-3">
                    <label for="path" class="form-label">Prioridad de propuesta</label>
                    <input type="number" class="form-control" id="proposal_priority" name="proposal_priority" required>
                </div>
                <div class="contenedor">
                    <a href="{{ route('profiles.preprofile.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
