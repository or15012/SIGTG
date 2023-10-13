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
        <h1>Detalles del Ciclo</h1>
        <p><strong>Número:</strong> {{ $cycle->number }}</p>
        <p><strong>Año:</strong> {{ $cycle->year }}</p>
        <p><strong>Estado:</strong> {{ $cycle->status ? 'Activo' : 'Inactivo' }}</p>

        <h2>Parámetros</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cycle->parameters as $parameter)
                    <tr>
                        <td>{{ $parameter->name }}</td>
                        <td>{{ $parameter->value }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
