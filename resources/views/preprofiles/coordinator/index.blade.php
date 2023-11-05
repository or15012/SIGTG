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
        <h1>Lista de Preperfiles</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Número de grupo</th>
                    <th>Nombre</th>
                    <th style="width: 40%">Descripcion</th>
                    <th>Fecha subida</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($preprofiles as $preprofile)
                    <tr>
                        <td>{{ $preprofile->number }}</td>
                        <td>{{ $preprofile->name }} </td>
                        <td  style="width: 40%">{{  Illuminate\Support\Str::limit($preprofile->description, 100, '...') }}</td>
                        <td class="text-nowrap">{{ $preprofile->created_at->format('d-m-Y') }}</td>
                        <td>
                            @switch($preprofile->status)
                                @case(0)
                                        Pre perfil presentado.
                                @break

                                @case(1)
                                        Pre perfil aprobado.
                                @break

                                @case(2)
                                        Pre perfil observado.
                                @break

                                @case(3)
                                        Pre perfil rechazado.
                                @break

                                @default

                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('profiles.preprofile.coordinator.show', $preprofile->id) }}" class="btn btn-primary m-1">Ver</a>
                            <a href="{{ route('profiles.preprofile.coordinator.observation.list', $preprofile->id) }}" class="btn btn-primary m-1">Observaciones</a>
                            <a href="{{ route('profiles.preprofile.coordinator.observation.create', $preprofile->id) }}" class="btn btn-primary m-1">Generar observación</a>
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
