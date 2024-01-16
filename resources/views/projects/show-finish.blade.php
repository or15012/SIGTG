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

        <h1>Cierre de proyecto</h1>
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
                        <a class="card-footer text-black clearfix small z-1"
                            href="{{ route('projects.final.volume', $project->id) }}">
                            <span class="float-left">Subir tomo final</span>
                            <span class="float-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
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
                        @if ($project->status == 2)
                            <form action="{{ route('projects.coordinator.submit.final.stage', $project->id) }}"
                                id="projects-approve-stage" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" id="decision" name="decision" value="3">
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

            @can('Projects.send.stages')
                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card text-black  o-hidden h-100">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="mr-5">Entregar etapa</div>
                        </div>
                        @if ($project->status == 1 || $project->status == 2)
                            <form action="{{ route('projects.coordinator.submit.final.stage', $project->id) }}"
                                id="projects-submit-final-stage" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" id="decision" name="decision" value="2">
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
            <h3>Tomo final</h3>
        </div>
        <div class="row">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Resumen</th>
                        <th>Descargar</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($project->summary))
                        <tr>
                            <td>{{ $project->id }}</td>
                            <td>{{ $project->summary }}</td>
                            <td>
                                <a href="{{ route('projects.download', [$project->id, 'path_final_volume']) }}"
                                    target="_blank">
                                    <i class="fas fa-cloud-download-alt"></i>
                                </a>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
