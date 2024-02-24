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
        <div class="contenedor">
            <a href="{{ route('withdrawals.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Registrar retiro</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('withdrawals.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- <div class="mb-3">
                <label for="project_id" class="form-label">Grupo: {{$group->number}} </label>
               <input type="hidden" name="project_id" value="{{$project->id}}">
            </div> --}}

            <div class="mb-3">
                <label for="type_withdrawals_id" class="form-label">Tipo de retiro</label>
                <select class="form-control" id="type_withdrawals_id" name="type_withdrawals_id">
                    <option value=""> Seleccione un tipo de retiro</option>
                    @foreach ($type_withdrawals as $type_withdrawal)
                        <option value="{{ $type_withdrawal->id }}"> {{ $type_withdrawal->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" name="description" id="description" rows="2" cols="1">{{ old('description') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="withdrawal_request_path" class="form-label">Solicitud de retiro</label>
                <input type="file" class="form-control" accept=".pdf,.PDF" id="withdrawal_request_path"
                    name="withdrawal_request_path" required>
            </div>
            <br />
            <div class="contenedor">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('withdrawals.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
