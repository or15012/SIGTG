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
        <h1>Lista de tipos de acuerdos</h1>
        <a href="{{ route('type_agreements.create') }}" class="btn btn-primary mb-3">Registrar tipo de acuerdo</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="table-danger">
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Afecta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($TypeAgreements as $typeagreement)
                    <tr>
                        <td>{{ $typeagreement->id }}</td>
                        <td>{{ $typeagreement->name }}</td>
                        <td>
                            @switch($typeagreement->affect)
                                @case(1)
                                    {{ "Estudiante" }}
                                @break

                                @case(2)
                                    {{ "Grupo" }}
                                @break

                                @case(3)
                                    {{ "Protocolo" }}
                                @break

                                @case(4)
                                    {{ "Escuela" }}
                                @break

                                @default
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('type_agreements.edit', $typeagreement->id) }}" class="btn btn-primary"><i
                                    class="fas fa-pen"></i></a>
                            <form action="{{ route('type_agreements.destroy', $typeagreement->id) }}" method="POST"
                                style="display: inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger buttonDelete"><i
                                        class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {!! $TypeAgreements->withQueryString()->links('pagination::bootstrap-5') !!}

    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
