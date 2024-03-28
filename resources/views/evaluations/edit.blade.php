@extends('layouts.master')
@section('title')
    Editar evaluación
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
            <a href="{{ route('stages.coordinator.evaluations.index', $stage->id) }}"
                class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        @if (session('protocol') !== null)
            @switch(session('protocol')['id'])
                @case(5)
                    <h1>Registrar evaluación</h1>
                @break

                @default
            @endswitch
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

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="m-4">
            <p>
                @if (session('protocol') !== null)
                    @switch(session('protocol')['id'])
                        @case(5)
                            Área:
                        @break

                        @default
                    @endswitch
                @endif
                {{ $stage->name }}
                <br>
                Porcentaje utilizado: {{ $sumatory }}%
                <br>
                Porcentaje máximo: {{ $stage->percentage }}%
            </p>
            <div class="progress">
                <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                    style="width: {{ $sumatory }}%" aria-valuenow="{{ $sumatory }}" aria-valuemin="0"
                    aria-valuemax="{{ $stage->percentage }}">
                </div>
            </div>
        </div>
        <form action="{{ route('stages.coordinator.evaluations.update', $evaluation->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $evaluation->name }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" required>{{ $evaluation->description }}</textarea>
            </div>
            <div class="mb-3">
                <label for="percentage" class="form-label">Porcentaje</label>
                <input type="text" class="form-control" id="percentage" name="percentage"
                    value="{{ $evaluation->percentage }}" required>
            </div>
            <input type="text" class="form-control" id="stage" name="stage" value="{{ $stage->id }}" hidden>
            @if (session('protocol') !== null)
                @switch(session('protocol')['id'])
                    @case(1)
                        <div class="mb-3">
                            <label for="stage" class="form-label">
                                Etapa evaluativa:
                            </label>
                            <select class="form-control" id="stage" name="stage" disabled>
                                <option value="{{ $stage->id }}"> {{ $stage->name }}</option>
                            </select>
                        </div>
                    @break

                    @case(5)
                    <div class="mb-3">
                        <label for="subareas" class="form-label">Seleccionar Subáreas</label>
                        <select class="form-control select2" id="subareas" name="subareas[]" multiple>
                            @foreach ($subareas as $subarea)
                                @php
                                    $selected = in_array($subarea->id, explode(',', $evaluation->subarea_id)) ? 'selected' : '';
                                @endphp
                                <option value="{{ $subarea->id }}" {{ $selected }}>{{ $subarea->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @break

                    @default
                @endswitch
            @endif
            @if (session('protocol') != null)
                @switch(session('protocol')['id'])
                    @case(5)
                        <div class="mb-3">
                            <label for="type" class="form-label">Tipo</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="-1"> Seleccione un tipo </option>
                                <option value="1" @if ($evaluation->type == 1) selected @endif>Con entrega de documentos
                                </option>
                                <option value="0" @if ($evaluation->type == 0) selected @endif>Sin entrega de documentos
                                </option>
                            </select>
                        </div>
                    @break

                    @default
                @endswitch
            @endif
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
