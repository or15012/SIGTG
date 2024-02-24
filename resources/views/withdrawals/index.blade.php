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
        <h1>Lista de retiros</h1>
        <a href="{{ route('withdrawals.create') }}" class="btn btn-primary mb-3">Registrar retiro</a>


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="table-danger">
                    <th>ID</th>
                    <th>Tipo de retiro</th>
                    <th>Grupo</th>
                    <th>Estado</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($withdrawals as $withdrawal)
                    <tr>
                        <td>{{ $withdrawal->id }}</td>
                        <td>{{ $withdrawal->type_withdrawal->name }}</td>
                        <td>{{ $withdrawal->group->number }}</td>
                        <td>{{ $withdrawal->status() }}</td>
                        <td>{{ $withdrawal->description }}</td>
                        <td>

                            <a href="{{ route('withdrawals.edit', $withdrawal->id) }}" class="btn btn-primary"><i
                                    class="fas fa-pen"></i></a>

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
