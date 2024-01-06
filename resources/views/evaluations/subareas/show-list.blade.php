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
        <div>
            <h3>Subáreas evaluativas</h3>
        </div>
        <div class="row">
            @php
                $flag = false;
            @endphp
            @forelse ($evaluationSubareas as  $stage)
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
                                href="{{ route('evaluations.show.subarea', [$project->id, $stage->id]) }}">
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
                                <a class="card-footer text-black clearfix small z-1 bg-primary"
                                    href="{{ route('evaluations.show.subarea', [$project->id, $stage->id]) }}">
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
                                    href="{{ route('evaluations.show.subarea', [$project->id, $stage->id]) }}">
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
                    Sin subáreas evaluativas
                </h3>
            @endforelse
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
