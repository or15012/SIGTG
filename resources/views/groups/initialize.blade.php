@extends('layouts.master')
@section('title')
    @lang('translation.Dashboard')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            SIGTG-FIA
        @endslot
        @slot('title')
            Welcome !
        @endslot
    @endcomponent
    <div class="container">
        <h1>Inicializar Grupo</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('groups.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-12 col-md-10 col-lg-10">
                    <input type="text" placeholder="carnet" name="carnet" id="carnet" class="form-control">
                </div>
                <div class="col-12 col-md-2 col-lg-2">
                    <button type="submit" class="btn btn-primary w-md">Agregar integrante</button>
                </div>
            </div>
            <div class="row mb-3">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header">{{ $user->carnet }} - {{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }} {{ $user->second_last_name }}</div>
                            <div class="card-body">
                                {{-- <p>{{ $permission->description }}</p> --}}
                                <div class="form-check form-switch">
                                    {{-- <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheck{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}">
                                    <label class="form-check-label" for="flexSwitchCheck{{ $permission->id }}">Seleccionar</label> --}}
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/initialize.js') }}"></script>
@endsection
