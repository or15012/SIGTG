@extends('layouts.master')

@section('title')
    @lang('translation.ShowPerfil')
@endsection

@section('content')
    <div class="container">
        <div class="contenedor">
            <a href="{{ route('forum.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1 class="mb-5">Consultar foro</h1>

        <div class="row">
            <h4>Cantidad de asistentes esperada a foro: {{ $forum->userForumWorkshops->count() }}</h4>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="name" class="form-label">Nombre:</label>
                <p>{{ $forum->name }}</p>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="description" class="form-label">Descripción:</label>
                <p>{{ $forum->description }}</p>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="date" class="form-label">Fecha:</label>
                <p>{{ \Carbon\Carbon::parse($forum->date)->format('d-m-Y H:i:s') }}</p>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="place" class="form-label">Lugar:</label>
                <p>{{ $forum->place }}</p>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo de foro:</label>
                <p>
                    <a href="{{ route('forum.download', [$forum->id, 'path']) }}" target="_blank"
                        class="btn btn-secondary archivo">Ver archivo</a>
                </p>
            </div>
        </div>

        <h4>Lista de estudiantes inscritos</h4>
        <div>
            <form action="{{ route('forum.assistence.store') }}" method="POST">
                @csrf
                <input type="hidden" name="forum_id" value="{{ $forum->id }}">
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
                                <td>{{ $user->user->carnet }}</td>
                                <td>{{ $user->user->first_name }}
                                    {{ $user->user->middle_name }}
                                    {{ $user->user->last_name }}
                                    {{ $user->user->second_last_name }}</td>
                                <td>
                                    <input type="checkbox" name="students[{{ $user->id }}]"
                                        @if ($user->status == 1) checked @endif>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">No hay estudiantes inscritos</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
