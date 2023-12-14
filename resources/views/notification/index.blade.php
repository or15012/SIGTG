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

        <h1>Lista de notificicaciones</h1>
        <a href="{{ route('notifications.create') }}" class="btn btn-primary mb-3">Crear notificacion</a>

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

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="table-danger">
                    <th>ID</th>
                    <th>Título</th>
                    <th>Mensaje</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($notifications as $notification)
                    <tr>
                        <td>{{ $notification->id }}</td>
                        <td>{{ $notification->notification->title }}</td>
                        <td>{{ $notification->notification->message }}</td>
                        <td>
                            <a title="Marcar como leído" href="{{route('notifications.mark.as.read', ['usernoti_id'=>$notification->id])}}" class="btn btn-primary"><i
                                    class="fas fa-check"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="12" class="text-center">Sin notificaciones</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
