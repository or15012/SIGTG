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
        <h1>Consultar proyecto</h1>

        <h5>Nombre:</h5>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Progreso</h4>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75"
                        aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
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

                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <h3>Etapas evaluativas</h3>
        </div>
        {{-- @forelse ( as )

        @empty

        @endforelse --}}
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-black  o-hidden h-100" style="background-color: #008fc5">
                    <div class="card-body">
                        <div class="mr-5 text-white">Etapa 1</div>
                        <div class="card-body-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>

                    </div>
                    <a class="card-footer text-gray clearfix small z-1" href="" style="background-color: #008fc5">
                        <span class="float-left text-white ">Ver Detalles</span>
                        <span class="float-right text-white">
                            <i class="fa fa-angle-right"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-black  o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="mr-5">Etapa 2</div>
                    </div>
                    <a class="card-footer text-black clearfix small z-1"
                        href="">
                        <span class="float-left">Ver detalles</span>
                        <span class="float-right">
                            <i class="fa fa-angle-right"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
