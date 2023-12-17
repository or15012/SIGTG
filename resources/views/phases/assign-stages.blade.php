@extends('layouts.master')
@section('title')
    @lang('translation.Phases')
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
        <h1>Asignación de áreas a fases</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('phases.store.assig.stages', $phase->id) }}" enctype="multipart/form-data" method="POST" id="assignment-form">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Áreas disponibles</h5>
                        </div>

                        <ul class="card-body" id="stages">
                            @foreach ($stages as $key => $item)
                                <li class="card bg-secondary bg-gradient bg-opacity-50" data-id="{{$item->id}}">
                                    <div class="card-body">
                                        {{ $item->name }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Áreas asignadas</h5>
                        </div>

                        <ul class="card-body" id="assign-stages">
                            @foreach ($stagesAssigned as $key => $item)
                                <li class="card bg-secondary bg-gradient bg-opacity-50" data-id="{{$item->id}}">
                                    <div class="card-body">
                                        {{ $item->name }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div id="input-container">

            </div>

            <div>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>

    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/phases.js') }}"></script>
@endsection
