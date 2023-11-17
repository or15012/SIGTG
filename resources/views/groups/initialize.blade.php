@extends('layouts.master')
@section('title')
    @lang('translation.Group')
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
        <h1>Conformar grupos</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('groups.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-12 col-md-10 col-lg-10">
                    <input type="text" placeholder="Ingrese carnet del estudiante" id="carnet" class="form-control">
                </div>
                <div class="col-12 col-md-2 col-lg-2">
                    <button type="button" id="add-student" class="btn btn-primary w-md"
                        @if (isset($group) && $group->status != 0) disabled @endif>Agregar integrante</button>
                </div>
            </div>
            @if (isset($group))
                <input type="hidden" name="group_id" value="{{ $group->id }}">
            @endif
            <div class="row mb-3" id="list-group">
                @forelse ($groupUsers as $user)
                    <div class="col-12 col-md-6 col-lg-6" id="user-{{ $user->id }}">
                        <div class="card mb-4">
                            <div style="background-color: #F2DEDE;" class="card-header">
                                {{ $user->carnet }} - {{ $user->first_name }} {{ $user->middle_name }}
                                {{ $user->last_name }} {{ $user->second_last_name }}
                                @if ($user->pivot->is_leader === 1)
                                    <label style="background-color: #f2f2f2;" class="px-2 rounded">LIDER</label>
                                @endif
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="users[]" value="{{ $user->id }}">
                                @if ($user->pivot->is_leader === 1)
                                    {{-- Aqui quiero mostrar algo si soy lider y estoy logueado como lider --}}

                                    <label class="bg-primary text-white p-2 rounded">
                                        <i class="fas fa-user-check"></i>
                                    </label>
                                @else
                                    <div>
                                        <label class="my-1 fw-bold">Estado de asignación</label>
                                    </div>
                                    <div>
                                        @switch($user->pivot->status)
                                            @case(1)
                                                <label class="my-1 bg-soft-success text-opacity-100 p-2 rounded">Aceptado
                                                </label>
                                            @break

                                            @case(2)
                                                <label class="my-1 bg-soft-danger text-opacity-100 p-2 rounded">Rechazado
                                                </label>
                                            @break

                                            @default
                                                <label style="background-color:#F2F2F2"
                                                    class="my-1 text-opacity-100 p-2 rounded">Enviado
                                                </label>
                                        @endswitch
                                    </div>
                                    <button type="button" data-user="{{ $user->id }}"
                                        class="delete-user btn btn-danger waves-effect waves-light">
                                        <i class="fas fa-window-close"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="col-12 col-md-6 col-lg-6 ">
                            <div class="card mb-4">
                                <div style="background-color: #F2DEDE;" class="card-header">{{ $user->carnet }} -
                                    {{ $user->first_name }}
                                    {{ $user->middle_name }}
                                    {{ $user->last_name }} {{ $user->second_last_name }}</div>
                                <div class="card-body">
                                    <input type="hidden" name="users[]" value="{{ $user->id }}">
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
                <button type="submit" class="btn btn-primary @if (isset($group) && $group->status != 0) d-none @endif">
                    Conformar grupo
                </button>
            </form>
        </div>
    @endsection
    @section('script')
        <script src="{{ URL::asset('assets/js/pages/fontawesome.init.js') }}"></script>
        <script src="{{ URL::asset('assets/js/app.js') }}"></script>
        <script src="{{ URL::asset('js/group_initialize.js') }}"></script>
    @endsection
