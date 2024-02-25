@extends('layouts.master')

@section('title')
    @lang('translation.ShowPerfil')
@endsection

@section('content')
    <div class="container">
        <div class="contenedor">
            <a href="{{ route('withdrawals.coordinator.index') }}" class="btn btn-danger regresar-button">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>
        <h1 class="mb-4">Consultar retiro</h1>
        <div class="row mb-1">
            <div class="col-12">
                <h5>
                    Retiro presentado del estudiante: {{ $withdrawal->user->first_name }}
                    {{ $withdrawal->user->second_last_name }}
                </h5>
                <h5>
                    Grupo al que pertenece el estudiante: {{ $withdrawal->group->number }}
                </h5>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="name" class="form-label">Tipo de retiro:</label>
                <p>{{ $withdrawal->type_withdrawal->name }}</p>
            </div>
            <div class="mb-3 col-12 col-md-6">
                <label for="status" class="form-label">Estado:</label>
                <p>
                    {{ $withdrawal->status() }}
                </p>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="status" class="form-label">Descripción:</label>
                <p>
                    {{ $withdrawal->description }}
                </p>
            </div>
            <div class="mb-3 col-12 col-md-6">
                <label for="withdrawal_request_path" class="form-label">Solicitud de retiro:
                    <p> <a href="{{ route('download', ['file' => $withdrawal->withdrawal_request_path]) }}"
                            class="btn btn-secondary archivo">Ver archivo</a>
                    </p>
                </label>
            </div>
        </div>
        @if ($withdrawal->status == 0)
            <form action="{{ route('withdrawals.coordinator.update', $withdrawal->id) }}" id="form-withdrawal-confirm"
                method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="withdrawal_id" value="{{ $withdrawal->id }}">
                <input type="hidden" id="decision" name="decision" value="">

                <button type="button" id="accept-withdrawal" class="btn btn-primary" data-bs-toggle="tooltip"
                    data-bs-placement="bottom" aria-label="Dark" data-bs-original-title="Aceptar retiro.">
                    <i class="fas fa-check"></i>
                </button>
                <button type="button" id="deny-withdrawal" class="btn btn-danger buttonDelete waves-effect waves-light"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Rechazar retiro.">
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
