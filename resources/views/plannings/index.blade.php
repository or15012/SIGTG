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
        <h1>Lista de planificación</h1>
        <a href="{{ route('plannings.create') }}" class="btn btn-primary mb-3">Nueva planificación</a>


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="table-danger">
                    <th>Nombre</th>
                    <th style="width: 40%">Descripcion</th>
                    <th>Fecha subida</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plannings as $planning)
                    <tr>
                        <td>{{ $planning->name }} </td>
                        <td style="width: 40%">{{ Illuminate\Support\Str::limit($planning->description, 100, '...') }}</td>
                        <td>{{ $planning->created_at->format('d-m-Y') }}</td>
                        <td>
                            @switch($planning->status)
                                @case(0)
                                    Planificación presentada.
                                @break

                                @case(1)
                                    Planificación aprobada.
                                @break

                                @case(2)
                                    Planificación observada.
                                @break

                                @case(3)
                                    Planificación rechazada.
                                @break

                                @default
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('profiles.preprofile.show', $planning->id) }}" class="btn btn-primary"><i
                                    class="fas fa-eye"></i></a>
                            <a href="{{ route('profiles.preprofile.edit', $planning->id) }}" class="btn btn-primary"><i
                                    class="fas fa-pen"></i></a>
                            <form action="{{ route('profiles.preprofile.destroy', $planning->id) }}" method="POST"
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
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
