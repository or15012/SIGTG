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
        <div class="w-100 d-flex justify-content-between align-items-center">
            <h1>Seguimientos de evaluaciones</h1>

            @php
                $today = new DateTime();
                $date_end = new DateTime($group->cycle->date_end);
                $date_end_mod = clone $date_end;
                $date_end_mod->modify('-20 days');
            @endphp


            <div class="d-flex justify-content-end align-items-center">

                @if ($project->status === 3)
                    @can('Projects.manage.approvement.report')
                        <a class="btn btn-secondary" href="{{ route('document.approvement.report', $project->id) }}"><i
                                class="fa fa-file"></i>&nbsp;&nbsp;Acta de aprobación</a>

                        <a class="btn btn-primary ajax-modal" style="margin-left: 5px" data-title="Acta de aprobación de proyecto"
                            data-bs-toggle="tooltip" data-bs-title="Subir Acta de aprobación"
                            href="{{ route('projects.modal.approvement.report', ['project_id' => $project->id]) }}"><i
                                class="fa fa-upload"></i></a>
                    @endcan
                    @if ($project->approvement_report)
                        <a class="btn btn-primary" style="margin-left: 5px" data-bs-toggle="tooltip"
                            data-bs-title="Descargar Acta de aprobación"
                            href="{{ route('download', ['file' => $project->approvement_report]) }}"><i
                                class="fa fa-download"></i></a>
                    @endif
                @endif
                @can('Extensions.student.create')
                    @if ($today >= $date_end_mod && $today <= $date_end)
                        <a href="{{ route('extensions.index', $project->id) }}" style="margin-left: 5px" class="btn btn-primary float-end">
                            <i class="fa fa-plus"></i>&nbsp; Solicitar prórroga
                        </a>
                    @endif
                @endcan

                @can('Consultings.student.create')
                    <a href="{{ route('consultings.index', $project->id) }}" style="margin-left: 5px"
                        class="btn btn-primary float-end"><i class="bx bx-file icon nav-icon"></i>Solicitar asesoría</a>
                @endcan

                @can('Consultings.adviser.show')
                    <a href="{{ route('consultings.index', $project->id) }}" style="margin-left: 5px"
                        class="btn btn-primary float-end"><i class="bx bx-file icon nav-icon"></i>Ver asesorías</a>
                @endcan
                <a href="{{ route('home') }}" style="margin-left: 5px" class="btn btn-danger regresar-button"><i
                        class="fas fa-arrow-left"></i>
                    Regresar</a>

            </div>
        </div>

        <h5></h5>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Progreso {{ number_format($progressPercentage, 2) }}%</h4>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                        aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"
                        style="width: {{ $progressPercentage }}%"></div>
                </div>
            </div><!-- end card-body -->
        </div><!-- end card -->

        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projectUsers as $user)
                            <tr>
                                <td>{{ $user->first_name }} {{ $user->middle_name }}
                                    {{ $user->last_name }} {{ $user->second_last_name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Docente asesor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupCommittees as $groupCommittee)
                            @if ($groupCommittee->type == 0)
                                <tr>
                                    <td>{{ $groupCommittee->first_name }} {{ $groupCommittee->middle_name }}
                                        {{ $groupCommittee->last_name }} {{ $groupCommittee->second_last_name }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Jurados</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupCommittees as $groupCommittee)
                            @if ($groupCommittee->type == 1)
                                <tr>
                                    <td>{{ $groupCommittee->first_name }} {{ $groupCommittee->middle_name }}
                                        {{ $groupCommittee->last_name }} {{ $groupCommittee->second_last_name }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div id="phase" class="phase d-none">
            <h2>Fase actual: <span id="name-phase"></span></h4>
        </div>
        <div>
            <h3>Areas evaluativas:</h3>
        </div>
        <div class="row">
            @php
                $flag = false;
            @endphp
            @forelse ($stages as  $stage)
                @if ($evaluationStages->contains('id', $stage->id))
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card text-black  o-hidden h-100">
                            <div class="card-body">
                                <div class="card-body-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="mr-5 w-50 text-black">{{ $stage->name }}</div>
                                <div class="mr-5 w-50 text-black">{{ $stage->percentage }}%</div>
                            </div>
                            <a class="card-footer text-black clearfix small z-1"
                                href="{{ route('evaluations.show.subareas', [$project->id, $stage->id]) }}">
                                <span class="float-left">Ver detalles</span>
                                <span class="float-right">
                                    <i class="fa fa-angle-right"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                @else
                    @if ($flag == false)
                        <div class="col-xl-3 col-sm-6 mb-3">
                            <div class="card text-black o-hidden h-100 bg-primary">
                                <div class="card-body">
                                    <div class="mr-5 w-50 text-white">{{ $stage->name }}</div>
                                    <div class="mr-5 w-50 text-white">{{ $stage->percentage }}%</div>
                                    <div class="card-body-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>

                                </div>
                                <a class="card-footer text-black clearfix small z-1 bg-primary enabled" data-value="{{$stage->id}}"
                                    href="{{ route('evaluations.show.subareas', [$project->id, $stage->id]) }}">
                                    <span class="float-left text-white ">Ver Detalles</span>
                                    <span class="float-right text-white">
                                        <i class="fa fa-angle-right"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                        @php
                            $flag = true;
                        @endphp
                    @else
                        <div class="col-xl-3 col-sm-6 mb-3">
                            <div class="card text-black o-hidden h-100">
                                <div class="card-body">
                                    <div class="mr-5 w-50 text-black">{{ $stage->name }}</div>
                                    <div class="mr-5 w-50 text-black">{{ $stage->percentage }}%</div>
                                    <div class="card-body-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                </div>
                                <a class="card-footer text-black clearfix small z-1"
                                    href="{{ route('evaluations.show.subareas', [$project->id, $stage->id]) }}">
                                    <span class="float-left">Ver detalles</span>
                                    <span class="float-right">
                                        <i class="fa fa-angle-right"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
            @empty
                <h3>
                    Sin áreas evaluativas
                </h3>
            @endforelse

            @if ($stages->count() === $evaluationStages->count())
                <div class="col-xl-3 col-sm-6 mb-3 red-student">
                    <div class="card text-black o-hidden h-100 red-student">
                        <div class="card-body">
                            <div class="mr-5 w-50 text-black">Cierre de trabajo de grado</div>
                            <div class="mr-5 w-50 text-black"></div>
                            <div class="card-body-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        </div>
                        <a class="card-footer text-black clearfix small z-1 red-student"
                            href="{{ route('projects.finish', [$project->id]) }}">
                            <span class="float-left">Ver detalles</span>
                            <span class="float-right">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-transparent border-bottom text-uppercase">
                    Notas de estudiantes por area
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre de alumno</th>
                                @foreach ($stages as $item)
                                    <th>
                                        {{ $item->name }}
                                        {{-- <br>
                                        {{ $item->percentage }}% --}}
                                    </th>
                                @endforeach
                                <td>Nota final</td>
                            </tr>
                        </thead>
                        <tbody class="notes">

                            @foreach ($projectUsers as $user)
                                <tr id="user-{{ $user->id }}" data-value="{{ $user->id }}">
                                    <td>{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}
                                        {{ $user->second_last_name }}</td>
                                    @foreach ($stages as $item)
                                        <td>
                                            @php
                                                $existingGrade = $evaluationStagesNotes->first(function ($evaluationStagesNotes) use ($user, $item) {
                                                    return $evaluationStagesNotes->user_id === $user->id && $evaluationStagesNotes->id === $item->id;
                                                });
                                            @endphp
                                            <label class="note"
                                                data-percentage="{{ $item->percentage }}">{{ $existingGrade ? $existingGrade->note : 0 }}
                                            </label>
                                        </td>
                                    @endforeach
                                    <td class="final-grade">
                                        <label class="final-note-{{ $user->id }}">
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- end col -->

    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/evaluation_index.js') }}"></script>
@endsection
