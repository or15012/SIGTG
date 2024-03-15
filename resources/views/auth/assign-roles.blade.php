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
            Asignar roles !
        @endslot
    @endcomponent
    <div class="container">
        <div class="contenedor">
            <a href="{{ route('users.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <form method="POST" class="form-horizontal" action="{{ route('users.assign.roles.store', $user->id) }}">
            @csrf

            <h1>Asignar roles</h1>
            <div class="row mb-3">
                @foreach ($roles as $role)
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header">{{ $role->name }}</div>
                            <div class="card-body">
                                <p>{{ $role->description }}</p>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheck{{ $role->id }}" name="roles[]" value="{{ $role->id }}"
                                    @if ($userRoles->contains('id', $role->id)) checked @endif>
                                    <label class="form-check-label" for="flexSwitchCheck{{ $role->id }}">Seleccionar</label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 text-end">
                <a href="{{ route('users.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
                <button class="btn btn-primary w-sm waves-effect waves-light" type="submit">Actualizar</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
