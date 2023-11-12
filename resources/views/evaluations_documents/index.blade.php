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
        <h1>Lista de documentos por etapas</h1>
        <a href="{{ route('evaluations_documents.create') }}" class="btn btn-primary mb-3">Agregar documento</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Fecha de subida</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($evaluations_documents as $evaluation_document)
                    <tr>
                        <td>{{ $evaluation_document->id }}</td>
                        <td>{{ $evaluation_document->name }}</td>
                        <td>{{ $evaluation_document->created_at->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('evaluations_documents.edit', $evaluation_document->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('evaluations_documents.destroy', $evaluation_document->id) }}" method="POST" style="display: inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
     <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
