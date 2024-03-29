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
        @endslot
    @endcomponent
    <div class="container">
        <div class="contenedor">
            <a href="{{ route('groups.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Tribunal evaluador</h1>

        <!-- Agregar el botón para abrir el modal -->
        {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchModal">
            Abrir Modal
        </button> --}}
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
        <h6>Lista de tribunal asignado a grupo</h6>
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="table-danger">
                    <th>Nombre completo</th>
                    <th>Rol</th>
                    <th>Contacto</th>
                    <th>Acuerdo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupCommittees as $groupCommittee)
                    <tr>
                        <td>{{ $groupCommittee->first_name }} {{ $groupCommittee->middle_name }}
                            {{ $groupCommittee->last_name }} {{ $groupCommittee->second_last_name }}</td>
                        <td>
                            @if ($groupCommittee->type == 0)
                                Asesor
                            @else
                                Jurado
                            @endif
                        </td>
                        <td>{{ $groupCommittee->email }}</td>
                        <td><a href="{{ route('groups.download', [$groupCommittee->tg_id, 'path_agreement']) }}" target="_blank"
                            class="btn btn-secondary archivo">Ver archivo</a></td>
                        <td>
                            <form
                                action="{{ route('groups.evaluating.committee.destroy', [$groupCommittee->id, $groupCommittee->type, $group->id]) }}"
                                method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger buttonDelete"><i
                                        class="fas fa-trash-alt"></i></button>
                            </form>
                            <button class="btn btn-secondary ajax-modal" data-title="Carta de acuerdo"
                                href="{{ route('groups.modal.autorization.agreement', ['group_committee_id' => $groupCommittee->tg_id]) }}">
                                <i class="fas fa-file"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap align-items-center">
                    <h5 class="card-title mb-0">Asignación de tribunal evaluador</h5>

                </div>
            </div>
            <div class="card-body px-4">
                <form action="{{ route('groups.evaluating.committee.update', $group->id) }}" method="post"
                    enctype="multipart/form-data" id="evaluating-committee-update">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <label class="col-12 col-md-4 col-lg-4" for="teachers">Docentes:</label>
                        <div class="col-12 col-md-8 col-lg-8">
                            <select name="teachers[]" id="teachers" multiple="multiple">
                                <option></option>
                                @forelse ($teachers as  $teacher)
                                    <option value="{{ $teacher->id }}"
                                        {{ in_array($teacher->id, old('teachers', [])) ? 'selected' : '' }}>
                                        {{ $teacher->first_name }} {{ $teacher->middle_name }}
                                        {{ $teacher->last_name }} {{ $teacher->second_last_name }}
                                    </option>
                                @empty
                                    <option disabled selected>Sin opciones disponibles</option>
                                @endforelse
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-12 col-md-4 col-lg-4" for="type">Tipo:</label>
                        <div class="col-12 col-md-8 col-lg-8">
                            <select class="form-select" name="type_committee" id="type_committee" required>
                                <option value="" selected disabled>Seleccione tipo</option>
                                <option value="0" {{ old('type_committee') == '0' ? 'selected' : '' }}>Asesor</option>
                                <option value="1" {{ old('type_committee') == '1' ? 'selected' : '' }}>Jurado</option>
                            </select>
                        </div>
                    </div>


                    {{-- <div class="row mb-3">
                        <label class="col-12 col-md-4 col-lg-4" for="agreement">Acuerdo:</label>
                        <div class="col-12 col-md-8 col-lg-8">
                            <input class="form-control" type="file" name="agreement" id="agreement">
                        </div>
                    </div> --}}

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <div class="contenedor">
                            <a href="{{ route('groups.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                            <button type="submit" class="btn btn-primary me-md-2" type="button">Guardar</button>
                            <div>
                            </div>

                </form>
            </div>
        </div>

    </div>


    @include('groups.evaluationCommittees.search-teacher-modal')
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/evaluation_committee_search.js') }}"></script>
@endsection
