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
        <h1>Detalles del Rol</h1>
        <dl class="row">
            <dt class="col-sm-3">ID:</dt>
            <dd class="col-sm-9">{{ $role->id }}</dd>

            <dt class="col-sm-3">Nombre:</dt>
            <dd class="col-sm-9">{{ $role->name }}</dd>

            <dt class="col-sm-3">Descripción:</dt>
            <dd class="col-sm-9">{{ $role->description }}</dd>

            <dt class="col-sm-3">Permisos:</dt>
            <dd class="col-sm-9">
                @if ($role->permissions->count() > 0)
                    <ul>
                        @foreach ($role->permissions as $permission)
                            <li>{{ $permission->description }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>No tiene permisos asignados.</p>
                @endif
            </dd>
        </dl>
        <a href="{{ route('roles.index') }}" class="btn btn-primary">Volver a la Lista</a>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
