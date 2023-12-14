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
            <a href="{{ route('notifications.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Crear notificación</h1>

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

        <form action="{{ route('notifications.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Título</label>
                <input class="form-control" name="title" id="title" value="{{ old('title') }}"/>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Mensaje</label>
                <textarea class="form-control" name="message" id="message" rows="2" cols="1">{{ old('message') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Enviar a</label>
                <select class="form-control select2" data-placeholder="Seleccione los roles" multiple="multiple" name="role_ids[]" id="role_ids">
                    @foreach ($roles as $role)
                        <option value="{{$role->id}}">{{$role->name}} {{$role->description}}</option>
                    @endforeach
                </select>
            </div>
            <br />
            <div class="contenedor">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('notifications.index') }}" class="btn btn-danger regresar-button">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
