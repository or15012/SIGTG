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
        <h1>Agregar Etapa Evaluativa</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('stages.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre de Etapa Evaluativa</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label for="cycle" class="form-label">Ciclo</label>
                <select class="form-control" id="cycle" name="cycle">
                <option value="0"> Seleccione un ciclo </option>
                    @foreach ($cycles as $cycle)
                        <option value="{{$cycle->id}}"> {{$cycle->number}}-{{$cycle->year}} </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="protocol" class="form-label">Protocolo</label>
                <select class="form-control" id="protocol" name="protocol">
                <option value="0"> Seleccione un protocolo </option>
                    @foreach ($protocols as $protocol)
                        <option value="{{$protocol->id}}"> {{$protocol->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="school" class="form-label">Escuela</label>
                <select class="form-control" id="school" name="school">
                <option value="0"> Seleccione una escuela </option>
                    @foreach ($schools as $school)
                        <option value="{{$school->id}}"> {{$school->name}}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
