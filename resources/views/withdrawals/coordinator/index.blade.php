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
                    <th>Estudiante</th>
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
                        <td>{{ $withdrawal->user->first_name }} {{ $withdrawal->user->second_last_name }}</td>
                        <td>{{ $withdrawal->status() }}</td>
                        <td>{{ $withdrawal->description }}</td>
                        <td>

                            <a href="{{ route('withdrawals.coordinator.show', $withdrawal->id) }}" class="btn btn-primary"><i
                                    class="fas fa-eye"></i></a>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- {!! $withdrawals->withQueryString()->links('pagination::bootstrap-5') !!} --}}
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
