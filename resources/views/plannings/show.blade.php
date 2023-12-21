@extends('layouts.master')

@section('title')
    @lang('translation.ShowPreperfil')
@endsection

@section('content')
    <div class="container">
        <div class="contenedor">
            <a href="{{ route('plannings.index') }}" class="btn btn-danger regresar-button"><i
                    class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1 class="mb-5">Consultar planificación</h1>
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="name" class="form-label">Nombre:</label>
                <p>{{ $planning->name }}</p>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="description" class="form-label">Descripción:</label>
                <p>{{ $planning->description }}</p>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="path" class="form-label">Archivo preperfil:</label>
                <p>
                    <a href="{{ route('plannings.download', [$planning->id, 'path']) }}" target="_blank"
                        class="btn btn-secondary archivo">Ver archivo</a>
                </p>
            </div>
        </div>

        {{-- <h3>Observaciones pre perfil: {{ $preprofile->name }}</h3>

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="table-danger">
                    <th style="width: 50%">Descripcion</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($preprofile->observations as $observation)
                    <tr>
                        <td style="width: 40%">{{ Illuminate\Support\Str::limit($observation->description, 100, '...') }}
                        </td>
                        <td>{{ $observation->created_at->format('d-m-Y') }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table> --}}
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
