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
        @endslot
    @endcomponent
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h1 class="mb-3">Lista de foros proximos</h1>

        <div class="row">
            @forelse ($forums as $forum)
                <div class="col-md-6 col-xl-3">
                    <!-- Simple card -->
                    <div class="card">
                        {{-- <img class="card-img-top img-fluid" src="{{ URL::asset('assets/images/small/img-1.jpg') }}"
                            alt="Card image cap"> --}}
                        <div class="card-body">
                            <h4 class="card-title">Nombre: {{ $forum->name }}</h4>
                            <p class="card-text">Descripción: {{ $forum->description }}</p>
                            <p class="card-text">Hora y lugar:</p>
                            <p class="card-text">{{ $forum->date }}</p>
                            <p class="card-text">{{ $forum->place }}</p>
                            <a href="{{ route('forum.confirm.assistance.forums.workshops', [$forum->id, 1]) }}"
                                class="btn btn-primary waves-effect waves-light">Marcar asistencia a foro</a>
                        </div>
                    </div>
                </div><!-- end col -->
            @empty
                <h5>No hay talleres proximos a desarrollarse</h5>
            @endforelse
        </div>

        <h1 class="mb-3">Lista de talleres proximos</h1>
        <div class="row">
            @forelse ($workshops as $workshop)
                <div class="col-md-6 col-xl-3">
                    <!-- Simple card -->
                    <div class="card">
                        {{-- <img class="card-img-top img-fluid" src="{{ URL::asset('assets/images/small/img-1.jpg') }}"
                            alt="Card image cap"> --}}
                        <div class="card-body">
                            <h4 class="card-title">Nombre: {{ $workshop->name }}</h4>
                            <p class="card-text">Descripción: {{ $workshop->description }}</p>
                            <p class="card-text">Hora y lugar:</p>
                            <p class="card-text">{{ $workshop->date }}</p>
                            <p class="card-text">{{ $workshop->place }}</p>
                            <a href="{{ route('forum.confirm.assistance.forums.workshops', [$workshop->id, 2]) }}"
                                class="btn btn-primary waves-effect waves-light">Marcar asistencia a taller</a>                        </div>
                    </div>
                </div><!-- end col -->
            @empty
                <h5>No hay talleres proximos a desarrollarse</h5>
            @endforelse
        </div>

    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
