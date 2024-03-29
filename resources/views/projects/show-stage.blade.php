@extends('layouts.master')
@section('title')
    @lang('translation.Projects')
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

        {{-- Poner permiso para asesor --}}
        @can('Project.adviser.back')
            <div class="contenedor">
                <a href="{{ route('projects.coordinator.show', $project->id) }}" class="btn btn-danger regresar-button"><i
                        class="fas fa-arrow-left"></i>
                    Regresar</a>
            </div>
        @endcan
        {{-- Poner permiso para estudiante --}}
        @can('Project.students.back')
            <div class="contenedor">
                <a href="{{ route('projects.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                    Regresar</a>
            </div>
        @endcan

        <h1>
            @if (session('protocol') != null)
                @switch(session('protocol')['id'])
                    @case(1)
                        Etapa:
                    @break

                    @case(5)
                        Área:
                    @break

                    @default
                @endswitch
            @endif
            {{ $stage->name }}
        </h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        <div class="row">
            @if ($status)
                @can('Projects.add.documents')
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card text-black  o-hidden h-100">
                            <div class="card-body">
                                <div class="card-body-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="mr-5">Documentos</div>
                            </div>
                            @if ($evaluationStages->status == 0 && $stage->type == 1)
                                <a class="card-footer text-black clearfix small z-1"
                                    href="{{ route('evaluations_documents.create', $evaluationStages->id) }}">
                                    <span class="float-left">Subir Documentos</span>
                                    <span class="float-right">
                                        <i class="fa fa-angle-right"></i>
                                    </span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endcan
                @can('Projects.send.stages')
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card text-black  o-hidden h-100">
                            <div class="card-body">
                                <div class="card-body-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="mr-5">Entregar etapa</div>
                            </div>
                            @if ($evaluationStages->status == 0 && $stage->type == 1)
                                <form action="{{ route('projects.submit.stage', $evaluationStages->id) }}"
                                    id="form-evaluation-stage-confirm" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" id="decision" name="decision" value="2">
                                    <button type="submit" class="btn btn-primary card-footer text-black clearfix small z-1">
                                        <span class="float-left">Realizar</span>
                                        <span class="float-right">
                                            <i class="fa fa-angle-right"></i>
                                        </span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
            </div>
        @endcan
        @can('Projects.add.notes')
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-black  o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="far fa-list-alt"></i>
                        </div>
                        <div class="mr-5">Cargar notas</div>
                    </div>
                    @if ($evaluationStages->status == 2 || $stage->type == 0)
                        <a class="card-footer text-black clearfix small z-1"
                            href="{{ route('grades.create', [$project->id, $stage->id]) }}">
                            <span class="float-left">Realizar</span>
                            <span class="float-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                    @endif
                </div>
            </div>
        @endcan
        @can('Projects.approve.stage')
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-black  o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="far fa-check-square"></i>
                        </div>
                        <div class="mr-5">Aprobar etapa</div>
                    </div>
                    @if ($evaluationStages->status == 2 || $stage->type == 0)
                        <form action="{{ route('projects.submit.stage', $evaluationStages->id) }}"
                            id="form-evaluation-stage-confirm" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" id="decision" name="decision" value="1">
                            <button type="submit" class="btn btn-primery card-footer text-black clearfix small z-1">
                                <span class="float-left">Realizar</span>
                                <span class="float-right">
                                    <i class="fa fa-angle-right"></i>
                                </span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endcan
    @else
        <div class="alert alert-info " role="alert">
            Este proyecto ya no está activo.
        </div>
        @endif
    </div>

    <div class="row text-center">
        <h3>Entregables de {{ $stage->name }}</h3>
    </div>
    <div class="row">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Fecha de subida</th>
                    <th>Descargar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($evaluationDocuments as $evaluation_document)
                    <tr>
                        <td>{{ $evaluation_document->id }}</td>
                        <td>{{ $evaluation_document->name }}</td>
                        <td>{{ $evaluation_document->created_at->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('evaluations_documents.download', [$evaluation_document->id, 'path']) }}"
                                target="_blank">
                                <i class="fas fa-cloud-download-alt"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    </div>

@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
