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
        <h1>Observaciones Perfil: {{ $profile->name }}</h1>
        <a href="{{ route('profiles.coordinator.observation.create', $profile->id) }}" class="btn btn-primary m-1">Generar observación</a>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 50%">Descripcion</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($profile->observations as $observation)
                    <tr>
                        <td  style="width: 40%">{{  Illuminate\Support\Str::limit($observation->description, 100, '...') }}</td>
                        <td>{{ $observation->created_at->format('d-m-Y') }}</td>

                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
     <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
