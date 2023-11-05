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
        @endslot
    @endcomponent
    <div class="container">
        <h1>Tribunal Evaluador</h1>

        <!-- Agregar el botón para abrir el modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchModal">
            Abrir Modal
        </button>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre completo</th>
                    <th>Rol</th>
                    <th>Contacto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupCommittees as $groupCommittee)
                    <tr>
                        <td>{{ $groupCommittee->first_name }} {{ $groupCommittee->middle_name }}
                            {{ $groupCommittee->last_name }} {{ $groupCommittee->second_last_name }}</td>
                        <td>
                            @if ($groupCommittee->type == 0)
                                Asesor
                            @else
                                Jurado
                            @endif
                        </td>
                        <td>{{ $groupCommittee->email }}</td>
                        <td>
                            <a href="{{ route('groups.evaluating.committee.destroy', $groupCommittee->id) }}"
                                class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    @include('groups.evaluationCommittees.search-teacher-modal')
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/evaluation_committee_search.js') }}"></script>
@endsection
