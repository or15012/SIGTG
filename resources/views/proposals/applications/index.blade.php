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
        <div class="contenedor">
            <a href="{{ route('home') }}" style="margin-left: 5px" class="btn btn-danger regresar-button"><i
                    class="fas fa-arrow-left"></i>
                Regresar</a>
    </div>
        <h1>Propuestas para aplicar</h1>

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

        <tbody>
            @foreach ($proposals as $proposal)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $proposal->name }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Entidad: {{ $proposal->entity->name }}</h6>
                        <p class="card-text">{{ $proposal->description }}</p>
                        <a href="{{ route('proposals.applications.create', $proposal->id) }}" class="btn btn-primary"><i
                                class="fas fa-hand-point-up"> Aplicar</i></a>
                    </div>
                </div>
            @endforeach
        </tbody>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
