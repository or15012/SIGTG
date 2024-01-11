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
        @endslot
    @endcomponent
    <div class="container">

        <h1>
            @if (session('protocol') != null)
                @switch(session('protocol')['id'])
                    @case(1)
                        Lista de grupos
                    @break

                    @case(2)
                        Listado de estudiantes
                    @break

                    @case(3)
                        Listado de estudiantes
                    @break

                    @case(4)
                        Listado de estudiantes
                    @break

                    @case(5)
                        Listado de estudiantes
                    @break

                    @default
                @endswitch
            @endif
        </h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('protocol')['id'] == 5 ||
                session('protocol')['id'] == 4 ||
                session('protocol')['id'] == 3 ||
                session('protocol')['id'] == 2)
            <table class="table table-bordered">
                <thead>
                    <tr class="table-danger">
                        <th>Número</th>
                        <th>Lider</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groups as $group)
                        <tr>
                            <td>
                                @if (isset($group->number))
                                    {{ $group->number }}
                                @else
                                    No asignado
                                @endif

                            </td>
                            <td>{{ $group->first_name }} {{ $group->middle_name }} {{ $group->last_name }}
                                {{ $group->second_last_name }}</td>
                            <td>{{ $group->name }}</td>
                            <td>
                                <a href="{{ route('groups.evaluating.committee.index', $group->id) }}"
                                    title="Asignar docentes" class="btn btn-primary">
                                    <i class="fas fa-balance-scale"></i>
                                </a>
                                <a href="{{ route('groups.edit', $group->id) }}" title="Ajustes de grupo"
                                    class="btn btn-primary">
                                    <i class="fas fa-cog"></i>
                                </a>

                                <a href="{{ route('extensions.index', $group->id) }}" title="Extensiones de grupo"
                                    class="btn btn-primary">
                                    <i class="fas fa-project-diagram"></i>
                                </a>
                                <button class="btn btn-secondary ajax-modal" title="Cargar cartas de autorización"
                                    data-title="Carta de autorización"
                                    href="{{ route('groups.modal.autorization.letter', ['group_id' => $group->id]) }}">
                                    <i class="fas fa-file"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $groups->withQueryString()->links('pagination::bootstrap-5') !!}
        @else
            <table class="table table-bordered">
                <thead>
                    <tr class="table-danger">
                        <th>Número</th>
                        <th>Lider</th>
                        <th>Cantidad de estudiantes</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groups as $group)
                        <tr>
                            <td>
                                @if (isset($group->number))
                                    {{ $group->number }}
                                @else
                                    No asignado
                                @endif

                            </td>
                            <td>{{ $group->first_name }} {{ $group->middle_name }} {{ $group->last_name }}
                                {{ $group->second_last_name }}</td>
                            <td>{{ $group->user_count }}</td>
                            <td>{{ $group->name }}</td>
                            <td>
                                <a href="{{ route('groups.evaluating.committee.index', $group->id) }}"
                                    title="Asignar docentes" class="btn btn-primary">
                                    <i class="fas fa-balance-scale"></i>
                                </a>
                                <a href="{{ route('groups.edit', $group->id) }}" title="Ajustes de grupo"
                                    class="btn btn-primary">
                                    <i class="fas fa-cog"></i>
                                </a>

                                <a href="{{ route('extensions.index', $group->id) }}" title="Extensiones de grupo"
                                    class="btn btn-primary">
                                    <i class="fas fa-project-diagram"></i>
                                </a>
                                <button class="btn btn-secondary ajax-modal" title="Cargar cartas de autorización"
                                    data-title="Carta de autorización"
                                    href="{{ route('groups.modal.autorization.letter', ['group_id' => $group->id]) }}">
                                    <i class="fas fa-file"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $groups->withQueryString()->links('pagination::bootstrap-5') !!}
        @endif
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
