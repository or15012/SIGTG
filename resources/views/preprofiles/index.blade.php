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
        <a href="{{ route('profiles.preprofile.create') }}" class="btn btn-primary mb-3">Agregar Preprefil</a>


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Fecha subida</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($preprofiles as $preprofile)
                    <tr>
                        <td>{{ $preprofile->name }} </td>
                        <td>{{ str_limit($preprofile->description, 100, '...') }}</td>
                        <td>{{ $preprofile->created_at->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('profiles.preprofile.edit', $preprofile->id) }}" class="btn btn-warning">
                            <i class="fas fa-cog"></i>
                            </a>
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
