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
        <h1>Editar documento de etapa</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('evaluations_documents.update', $evaluation_document->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $evaluation_document->name) }}" required>
            </div>

            <div class="mb-2">
                <label for="path" class="form-label">Documento actual de etapa</label>
                <a href="{{ route('evaluations_documents.download', [$evaluation_document->id, 'path']) }}"
                    class="btn btn-link">Descargar archivo actual</a>
            </div>

            <div class="mb-3">
                <label for="path" class="form-label">Nuevo documento de etapa</label>
                <input type="file" class="form-control" id="path" name="path">
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
