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
            Welcome !
        @endslot
    @endcomponent
    <div class="container">
        <h1>Editar tipo de acuerdo</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('type_agreements.update', $TypeAgreement->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $TypeAgreement->name) }}" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card mb-4">
                        <div class="card-body">
                            <p>Estudiante</p>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="radio" id="radioPermission1" name="selectedAffect"
                                    value="1" @if ($TypeAgreement->affect == 1)
                                        checked
                                    @endif>
                                <label class="form-check-label" for="radioPermission1">Seleccionar</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card mb-4">
                        <div class="card-body">
                            <p>Grupo</p>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="radio" id="radioPermission2" name="selectedAffect"
                                    value="2" @if ($TypeAgreement->affect == 2)
                                    checked
                                @endif >
                                <label class="form-check-label" for="radioPermission2">Seleccionar</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card mb-4">
                        <div class="card-body">
                            <p>Protocolo</p>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="radio" id="radioPermission3" name="selectedAffect"
                                    value="3" @if ($TypeAgreement->affect == 3)
                                    checked
                                @endif>
                                <label class="form-check-label" for="radioPermission3">Seleccionar</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-md-3">
                    <div class="card mb-4">
                        <div class="card-body">
                            <p>Escuela</p>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="radio" id="radioPermission4" name="selectedAffect"
                                    value="4" @if ($TypeAgreement->affect == 4)
                                    checked
                                @endif>
                                <label class="form-check-label" for="radioPermission4">Seleccionar</label>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="contenedor">
                <a href="{{ route('type_agreements.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
