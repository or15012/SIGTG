@extends('layouts.master')

@section('title')
    @lang('translation.ShowPerfil')
@endsection

@section('content')
    <div class="container">
        <div class="contenedor">
            <a href="{{ route('events.coordinator.index', $project->id) }}" class="btn btn-danger regresar-button">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>
        <h1 class="mb-4">Consultar defensa</h1>
        <div class="row mb-1">
            <div class="col-12">
                <dt class="col-sm-3">ID:</dt>
                <dd class="col-sm-9">{{ $events->id }}</dd>    
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <dt class="col-sm-3">Nombre:</dt>
                <dd class="col-sm-9">{{ $events->name }}</dd>
            </div>
            <div class="mb-3 col-12 col-md-6">
                <label for="status" class="form-label">Estado:</label>
                    <p>
                        {{ $events->status() }}
                    </p>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="status" class="form-label">Descripción:</label>
                <p>
                    {{ $events->description }}
                </p>
            </div>
            <div class="row mb-1">
                <div class="col-12">
                    <dt class="col-sm-3">Lugar:</dt>
                    <dd class="col-sm-9">{{ $events->place }}</dd>
        
                    <dt class="col-sm-3">Fecha y Hora:</dt>
                    <dd class="col-sm-9">{{ $events->date }}</dd> 
                </div>
            </div>
        </div>
        @if ($events->status == 0)
            <form action="{{ route('events.coordinator.update', $events->id) }}" id="form-event-confirm"
                method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="event_id" value="{{ $events->id }}">
                <input type="hidden" id="decision" name="decision" value="">

                <button type="button" id="accept-withdrawal" class="btn btn-primary" data-bs-toggle="tooltip"
                    data-bs-placement="bottom" aria-label="Dark" data-bs-original-title="Aceptar defensa.">
                    <i class="fas fa-check"></i>
                </button>
                <button type="button" id="deny-withdrawal" class="btn btn-danger buttonDelete waves-effect waves-light"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Rechazar defensa.">
                    <i class="fas fa-window-close"></i>
                </button>
            </form>
        @endif

    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/withdrawal_coordinator_show.js') }}"></script>
@endsection
