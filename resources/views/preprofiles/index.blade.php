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
        <h1>Lista de preperfiles</h1>
        <a href="{{ route('profiles.preprofile.create') }}" class="btn btn-primary mb-3">Nuevo preprefil</a>


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
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($preprofiles as $preprofile)
                    <tr>
                        <td>{{ $preprofile->name }} </td>
                        <td  style="width: 40%">{{  Illuminate\Support\Str::limit($preprofile->description, 100, '...') }}</td>
                        <td>{{ $preprofile->created_at->format('d-m-Y') }}</td>
                        <td>{{ $preprofile->proposal_priority }}</td>
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
                            <a href="{{ route('profiles.preprofile.show', $preprofile->id) }}" class="btn btn-primary"><i
                                class="fas fa-eye"></i></a>
                            <a href="{{ route('profiles.preprofile.edit', $preprofile->id) }}" class="btn btn-primary"><i
                                class="fas fa-pen"></i></a>
                            <form action="{{ route('profiles.preprofile.destroy', $preprofile->id) }}" method="POST" style="display: inline">
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
