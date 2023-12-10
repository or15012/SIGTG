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
        <h1>Registrar criterio de evaluación</h1>

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

        <div class="m-4">
            <p>Etapa evaluativa: {{ $stage->name }}
                <br>
                Porcentaje utilizado: {{ $sumatory }}%
                <br>
                Porcentaje máximo: 100%
            </p>
            <div class="progress">
                <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                    style="width: {{ $sumatory }}%" aria-valuenow="{{ $sumatory }}" aria-valuemin="0"
                    aria-valuemax="100">

                </div>
            </div>
        </div>



        <form action="{{ route('criterias.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre del criterio de evaluación</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" required>{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="percentage" class="form-label">Porcentaje</label>
                <input type="text" class="form-control" id="percentage" name="percentage" value="{{ old('percentage') }}"
                    required>
            </div>

            <input type="text" class="form-control" id="stage" name="stage" value="{{ $stage->id }}" hidden>

            <div class="mb-3">
                <label for="stage" class="form-label">Etapa evaluativa</label>
                <select class="form-control" id="stage" name="stage" disabled>
                    <option value="{{ $stage->id }}"> {{ $stage->name }}</option>
                </select>
            </div>


            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
