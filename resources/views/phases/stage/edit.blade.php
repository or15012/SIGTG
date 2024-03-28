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
            <a href="{{ route('stages.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        @if (session('protocol') != null)
            @switch(session('protocol')['id'])
                @case(5)
                    <h1>Editar área tematica</h1>
                @break
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

        <form action="{{ route('phases.stages.update', $stage->id) }}" id="form-stage" method="POST">
            @csrf
            @method('PUT')
            {{-- <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $stage->name) }}" required>
            </div> --}}

            <div class="mb-3">
                <label for="areas" class="form-label">Seleccionar áreas</label>
                <select class="form-control" id="areas" name="areas[]" multiple>
                    @foreach ($areas as $area)
                        @php
                            $selected = in_array($area->id, explode(',', $stage->area_id)) ? 'selected' : '';
                        @endphp
                        <option value="{{ $area->id }}" {{ $selected }}>{{ $area->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="cycle" class="form-label">Ciclo</label>
                <select class="form-control" name="cycle" id="cycle">
                    @foreach ($cycles as $cycle)
                        @if ($stage->cycle_id == $cycle->id)
                            <option value="{{ $cycle->id }}" selected>
                                {{ $cycle->number }}-{{ $cycle->year }}
                            </option>
                        @else
                            <option value="{{ $cycle->id }}">
                                {{ $cycle->number }}-{{ $cycle->year }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="protocol" class="form-label">Protocolo</label>
                <select class="form-control" name="protocol" id="protocol" disabled>
                    @foreach ($protocols as $protocol)
                        @if ($stage->protocol_id == $protocol->id)
                            <option value="{{ $protocol->id }}" selected>
                                {{ $protocol->name }}
                            </option>
                        @else
                            <option value="{{ $protocol->id }}">
                                {{ $protocol->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="school" class="form-label">Escuela</label>
                <select class="form-control" name="school" id="school" disabled>
                    @foreach ($schools as $school)
                        @if ($stage->school_id == $school->id)
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

            <div class="mb-3">
                <label for="sort" class="form-label">Orden</label>
                <input type="text" class="form-control" id="sort" name="sort"
                    value="{{ old('sort', $stage->sort) }}" required>
            </div>

            <div class="mb-3">
                <label for="percentage" class="form-label">Porcentaje</label>
                <input type="text" class="form-control" id="percentage" name="percentage"
                    value="{{ old('percentage', $stage->percentage) }}" required>
            </div>



            @if (session('protocol') != null)
                @switch(session('protocol')['id'])
                    @case(5)
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="status" class="form-label">Fecha de inicio</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ old('start_date', $stage->start_date) }}"   required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="status" class="form-label">Fecha de fin</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                     value="{{ old('end_date', $stage->end_date) }}"   required>
                            </div>
                        </div>
                    @break

                    @default
                @endswitch
            @endif

            <div class="contenedor">
                <a href="{{ route('stages.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/stages.js') }}"></script>
@endsection
