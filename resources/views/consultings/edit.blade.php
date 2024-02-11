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
            <a href="{{ route('consultings.index', $project->id) }}" class="btn btn-danger regresar-button"><i
                    class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Editar asesoría</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('consultings.update', [$consulting->id, $project->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="topics" class="form-label">Tema</label>
                <input type="text" class="form-control" id="topics" name="topics"
                    value="{{ old('topics', $consulting->topics) }}" @if ($user->type == 2) disabled @endif
                    required>
            </div>
            <div class="mb-3">
                <label for="summary" class="form-label">Resumen</label>
                <input type="text" class="form-control" id="summary" name="summary"
                    value="{{ old('summary', $consulting->summary) }}" @if ($user->type == 1) disabled @endif
                    required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="date" name="date"
                    min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ old('date', $consulting->date) }}"
                    required>
            </div>
            <input type="hidden" name="group_id" value="{{ $consulting->group_id }}">

            <h3>Asistencias a asesoría</h3>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr class="table-danger">
                        <th>Carnet</th>
                        <th>Nombre</th>
                        <th>Marcar asistencia</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->carnet }}</td>
                            <td>{{ $user->first_name }}
                                {{ $user->middle_name }}
                                {{ $user->last_name }}
                                {{ $user->second_last_name }}
                            </td>
                            <td>
                                <input type="checkbox" name="students[{{ $user->id }}]"
                                    @if (in_array($user->id, explode(',', $consulting->attendance))) checked @endif>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No hay estudiantes inscritos</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

            <div class="contenedor">
                <a href="{{ route('consultings.index', $project->id) }}"
                    class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>

        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
