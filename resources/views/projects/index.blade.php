@extends('layouts.master')
@section('title')
    @lang('translation.Projects')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            SIGTG-FIA
        @endslot
        @slot('title')
        @endslot
    @endcomponent
    <div class="container">
        <div class="w-100 d-flex justify-content-between align-items-center">
            <h1>Consultar proyecto</h1>

            @php
                $today = new DateTime();
                $date_end = new DateTime($group->cycle->date_end);
                $date_end_mod = clone $date_end;
                $date_end_mod->modify('-20 days');
            @endphp

            @if ($today >= $date_end_mod && $today <= $date_end)
                <a href="{{ route('extensions.index') }}" class="btn btn-secondary float-end">
                    <i class="fa fa-plus"></i>&nbsp; Solicitar prórroga
                </a>
            @endif
        </div>

        <h5>Nombre: {{ $project->name }}</h5>
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
                            <th>Integrantes</th>
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
        <div>
            <h3>Etapas evaluativas</h3>
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
                                <div class="mr-5">{{ $stage->name }}</div>
                            </div>
                            <a class="card-footer text-black clearfix small z-1"
                                href="{{ route('projects.show.stage', [$project->id, $stage->id]) }}">
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
                                    <div class="mr-5 text-white">{{ $stage->name }}</div>
                                    <div class="card-body-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>

                                </div>
                                <a class="card-footer text-black clearfix small z-1 bg-primary"
                                    href="{{ route('projects.show.stage', [$project->id, $stage->id]) }}">
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
                                    <div class="mr-5 text-black">{{ $stage->name }}</div>
                                    <div class="card-body-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                </div>
                                <a class="card-footer text-black clearfix small z-1"
                                    href="{{ route('projects.show.stage', [$project->id, $stage->id]) }}">
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
                    Sin etapas evaluativas
                </h3>
            @endforelse

        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-transparent border-bottom text-uppercase">
                    Notas de estudiantes
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
                                {{-- <td>Nota Final</td> --}}
                            </tr>
                        </thead>
                        <tbody>

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
                                            <label>{{ $existingGrade ? $existingGrade->note : 0 }}
                                            </label>
                                        </td>
                                    @endforeach
                                    {{-- <td class="final-grade">
                                        <input class="final-note-{{ $user->id }}" min="0" max="10"
                                            step="0.01" type="number" name="finalnote[{{ $user->id }}]"
                                            value="" required readonly>
                                    </td> --}}
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
@endsection
