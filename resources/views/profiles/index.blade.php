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
        <h1>Lista de Perfiles</h1>

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
                @foreach ($profiles as $profile)
                    <tr>
                        <td>{{ $profile->name }} </td>
                        <td  style="width: 40%">{{  Illuminate\Support\Str::limit($profile->description, 100, '...') }}</td>
                        <td>{{ $profile->created_at->format('d-m-Y') }}</td>
                        <td>
                            @switch($profile->status)
                                @case(0)
                                        Perfil presentado.
                                @break

                                @case(1)
                                        Perfil aprobado.
                                @break

                                @case(2)
                                        Perfil observado.
                                @break

                                @case(3)
                                        Perfil rechazado.
                                @break

                                @default

                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('profiles.show', $profile->id) }}" class="btn btn-primary">Ver</a>
                            <a href="{{ route('profiles.edit', $profile->id) }}" class="btn btn-warning">Editar</a>
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
