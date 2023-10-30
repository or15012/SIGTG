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
        <h1>Agregar Asesoria</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('consultings.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="topics" class="form-label">Tema</label>
                <input type="text" class="form-control" id="topics" name="topics" value="{{ old('topics') }}" required>
            </div>
            <div class="mb-3">
                <label for="number" class="form-label">Número</label>
                <input type="number" class="form-control" id="number" name="number" value="{{ old('number') }}" required>
            </div>
            <div class="mb-3">
                <label for="summary" class="form-label">Resumen</label>
                <input type="text" class="form-control" id="summary" name="summary" value="{{ old('summary') }}" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
