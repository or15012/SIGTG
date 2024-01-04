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
        <h1>
            @if (session('protocol') != null)
                @switch(session('protocol')['id'])
                    @case(1)
                        Registro de Notas
                    @break

                    @case(5)
                        Registro de notas de sub áreas
                    @break

                    @default
                @endswitch
            @endif
        </h1>
        <form action="{{ route('grades.subareas.store') }}" method="post">
            @csrf
            <input type="hidden" name="evaluation_stage_id" value="{{ $evaluationStages->id }}">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>
                            @if (session('protocol') != null)
                                @switch(session('protocol')['id'])
                                    @case(1)
                                        Nombre de Alumnos / Notas
                                    @break

                                    @case(5)
                                        Alumno / Sub áreas
                                    @break

                                    @default
                                @endswitch
                            @endif
                        </th>
                        @foreach ($criteria as $item)
                            <th>
                                {{ $item->name }}
                                <br>
                                {{ $item->percentage }}%
                            </th>
                        @endforeach
                        <td>Nota Final</td>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($users as $user)
                        <tr id="user-{{ $user->id }}" data-value="{{ $user->id }}">
                            <td>{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}
                                {{ $user->second_last_name }}</td>
                            @foreach ($criteria as $item)
                                <td>
                                    @php
                                        $existingGrade = $grades->first(function ($grade) use ($user, $item) {

                                            return $grade->user_id === $user->id && $grade->subarea_criteria_id === $item->id;
                                        });
                                    @endphp
                                    <input id="note-{{ $user->id }}-{{ $item->id }}"
                                        data-percentage="{{ $item->percentage }}" class="note" min="0"
                                        max="10" step="0.01" type="number"
                                        name="notes[{{ $user->id }}][{{ $item->id }}]"
                                        value="{{ $existingGrade ? $existingGrade->note : 0 }}" required>
                                </td>
                            @endforeach
                            <td class="final-grade">
                                <input class="final-note-{{ $user->id }}" min="0" max="10" step="0.01"
                                    type="number" name="finalnote[{{ $user->id }}]" value="" required readonly>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/evaluation_stage_create.js') }}"></script>
@endsection
