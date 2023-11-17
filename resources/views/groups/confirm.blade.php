@extends('layouts.master')
@section('title')
    @lang('translation.Group')
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
        <h1>Conformar grupo</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('groups.confirm.store') }}" id="form-group-confirm" method="POST">
            @csrf
            @if (isset($group))
                <input type="hidden" name="group_id" value="{{ $group->id }}">
            @endif
            <input type="hidden" name="decision" id="decision" value="">
            <div class="row mb-3" id="list-group">
                @forelse ($groupUsers as $user)
                    <div class="col-12 col-md-6 col-lg-6 ">
                        <div class="card mb-4">
                            <div class="card-header">
                                {{ $user->carnet }} - {{ $user->first_name }} {{ $user->middle_name }}
                                {{ $user->last_name }} {{ $user->second_last_name }}
                                @if ($user->pivot->is_leader === 1)
                                    <label class="bg-primary text-white px-2 rounded">Lider</label>
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
                                    @if ($user->id === Auth::user()->id)
                                        <div>
                                            {{-- Verificare estado para ver que mensaje y botonees mostrar
                                                No ha decidido mostrare Sin respuesta y rechazar y aceptar
                                                Decidio si mostrare Aceptado y boton dar de baja
                                                Si decidio no mostrare denegada y el boton aceptar
                                                --}}

                                            @switch($user->pivot->status)
                                                @case(0)
                                                    <div id="agregado">
                                                        <label>Agregado al grupo</label>
                                                        <button type="button" id="accept-invitation"
                                                            class="btn btn-primary  waves-effect waves-light"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Aceptar invitación.">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" id="deny-invitation"
                                                            class="btn btn-danger  waves-effect waves-light"
                                                            title="Rechazar invitación">
                                                            <i class="fas fa-window-close"></i>
                                                        </button>
                                                    </div>
                                                @break

                                                @case(1)
                                                    <div id="confirmado">
                                                        <label>Confirmado</label>
                                                        <button type="button" id="deny-invitation"
                                                            class="btn btn-danger  waves-effect waves-light"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Rechazar invitación.">
                                                            <i class="fas fa-window-close"></i>
                                                        </button>
                                                    </div>
                                                @break

                                                @case(2)
                                                    <div id="rechazado">
                                                        <label>Rechazado</label>
                                                        <button type="button" id="accept-invitation"
                                                            class="btn btn-primary  waves-effect waves-light"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Aceptar invitación.">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </div>
                                                @break

                                                @default
                                            @endswitch


                                        </div>
                                    @else
                                        @switch($user->pivot->status)
                                            @case(0)
                                                <div>
                                                    <label>Agregado al grupo</label>
                                                </div>
                                            @break

                                            @case(1)
                                                <div>
                                                    <label>Confirmado</label>

                                                </div>
                                            @break

                                            @case(2)
                                                <div>
                                                    <label>Rechazado</label>
                                                </div>
                                            @break

                                            @default
                                        @endswitch
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="col-12 col-md-6 col-lg-6 ">
                            <div class="card mb-4">
                                <div class="card-header">{{ $user->carnet }} - {{ $user->first_name }}
                                    {{ $user->middle_name }}
                                    {{ $user->last_name }} {{ $user->second_last_name }}</div>
                                <div class="card-body">
                                    <input type="hidden" name="users[]" value="{{ $user->id }}">
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </form>
        </div>
    @endsection
    @section('script')
        <script src="{{ URL::asset('assets/js/pages/fontawesome.init.js') }}"></script>
        <script src="{{ URL::asset('assets/js/app.js') }}"></script>
        <script src="{{ URL::asset('js/group_confirm.js') }}"></script>
    @endsection
