@extends('layouts.master')

@section('title')
    @lang('translation.ShowPerfil')
@endsection

@section('content')
    <div class="container">

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


        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="contenedor">
            <a href="{{ route('extensions.coordinator.index') }}" class="btn btn-danger regresar-button">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>
        <h1 class="mb-4">Consultar prorroga</h1>
        <div class="row mb-1">
            <div class="col-12">

            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="name" class="form-label">Proyecto:</label>
                <p>{{ $project->name }}</p>
            </div>
            <div class="mb-3 col-12 col-md-6">
                <label for="status" class="form-label">Estado:</label>
                <p>
                    {{ $extension->status() }}
                </p>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="status" class="form-label">Descripción:</label>
                <p>
                    {{ $extension->description }}
                </p>
            </div>
            <div class="mb-3 col-12 col-md-6">
                <label for="extension_request_path" class="form-label">Solicitud de prorroga:
                    <p> <a href="{{ route('download', ['file' => $extension->extension_request_path]) }}"
                            class="btn btn-secondary archivo">Ver archivo</a>
                    </p>
                </label>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="extension_request_path" class="form-label">Cronograma de actividades:
                    <p> <a href="{{ route('download', ['file' => $extension->schedule_activities_path]) }}"
                            class="btn btn-secondary archivo">Ver archivo</a>
                    </p>
                </label>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="extension_request_path" class="form-label">Carta de aprobación de asesor:
                    <p> <a href="{{ route('download', ['file' => $extension->approval_letter_path]) }}"
                            class="btn btn-secondary archivo">Ver archivo</a>
                    </p>
                </label>
            </div>
        </div>
        @if ($extension->status == 0)
            <form action="{{ route('extensions.coordinator.update', $extension->id) }}" id="form-extension-confirm"
                method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="extension_id" value="{{ $extension->id }}">
                <input type="hidden" id="decision" name="decision" value="">

                <a class="btn btn-primary ajax-modal" style="margin-left: 5px" data-title="Acuerdo de retiro"
                    data-bs-toggle="tooltip" data-bs-title="Acuerdo de retiro"
                    href="{{ route('extensions.coordinator.modal.approvement', ['extension_id' => $extension->id]) }}">
                    <i class="fas fa-check"></i>
                </a>
                <button type="button" id="deny-extension" class="btn btn-danger buttonDelete waves-effect waves-light"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Rechazar retiro.">
                    <i class="fas fa-window-close"></i>
                </button>
            </form>
        @endif

    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/extension_coordinator_show.js') }}"></script>
@endsection
