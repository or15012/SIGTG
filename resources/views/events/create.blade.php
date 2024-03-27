@extends('layouts.master')
@section('title')
    @lang('translation.Stages')
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
        <div class="contenedor">
            <a href="{{ route('events.index', $project->id) }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Registrar Defensa</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('events.store', ['project' => $project->id]) }}" id="form-events" method="POST" enctype="multipart/form-data">
            @csrf

    
            <div class="mb-3">
                <label for="project_id" class="form-label">Proyecto: {{$project->name}} </label>
               <input type="hidden" name="project_id" value="{{$project->id}}">
            </div>

            <div class="mb-3">
                <label for="group" class="form-label">Grupo: {{$project->group->number}}</label>
                <input type="hidden" name="group_id" value="{{$project->group->id}}">
            </div>

            <div class="mb-3">
                <label for="cycle" class="form-label">Ciclo: {{$project->group->cycle->number}}-{{$project->group->cycle->year}}</label>
                <input type="hidden" name="cycle_id" value="{{$project->group->cycle->id}}">
            </div>
            
            <div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input class="form-control" type="text" id="name" name="name">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción:</label>
                <input class="form-control" type="text" id="description" name="description">
            </div>

            <div class="mb-3">
                <label for="place" class="form-label">Lugar:</label>
                <input class="form-control" type="text" id="place" name="place" required>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Fecha:</label>
                <input class="form-control" type="datetime-local" id="date" name="date"
                    value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}"
                    min="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" required>
            </div>

            @php
                $currentUser = Auth::user();
            @endphp

            <input type="hidden" name="user_id" value="{{ $currentUser->id }}">
            <input type="hidden" name="school_id" value="{{ $currentUser->school_id }}">    


            <div class="contenedor">
                <a href="{{ route('events.index', $project->id) }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/stages.js') }}"></script>
@endsection
