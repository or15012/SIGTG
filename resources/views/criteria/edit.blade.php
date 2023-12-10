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
        <h1>Editar Criterio de Evaluación</h1>

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

        <form action="{{ route('criterias.update', $criteria->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nombre del Criterio de Evaluación</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $criteria->name }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" required>{{ $criteria->description }}</textarea>
            </div>

            <div class="mb-3">
                <label for="percentage" class="form-label">Porcentaje</label>
                <input type="text" class="form-control" id="percentage" name="percentage"
                    value="{{ $criteria->percentage }}" required>
            </div>

            <input type="text" class="form-control" id="stage" name="stage" value="{{ $stage->id }}" hidden>

            <div class="mb-3">
                <label for="stage" class="form-label">Etapa Evaluativa</label>
                <select class="form-control" id="stage" name="stage" disabled>
                    <option value="{{ $stage->id }}"> {{ $stage->name }}</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
