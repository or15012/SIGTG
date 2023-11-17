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
        <div class="d-flex justify-content-between align-items-center">
            <h1>Actualizar grupo</h1>


            <div class="d-flex justify-content-end align-items-center">
                <a class="btn btn-secondary" href="{{ route('document.authorization.letter', $id) }}"><i
                        class="fa fa-file"></i>&nbsp;&nbsp;Carta de autorización</a>

                @if ($group[0]->authorization_letter)
                    <a class="btn btn-info" style="margin-left: 5px"
                        href="{{ route('download', ['file' => $group[0]->authorization_letter]) }}"><i
                            class="fa fa-file"></i>&nbsp;&nbsp;Carta adjunta</a>
                @endif

                <div class="contenedor">
                    <a href="{{ route('groups.index') }}" class="btn btn-danger regresar-button"><i
                            class="fas fa-arrow-left"></i>
                        Regresar</a>
                </div>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('groups.update', $id) }}" method="POST" id="form-group-confirm">
            @csrf
            @method('PUT')
            <input type="hidden" name="group_id" value="{{ $id }}">
            <input type="hidden" name="decision" value="" id="decision">
            @forelse ($group as $user)
                @if ($loop->first)
                    <div class="row mb-2">
                        <label for="example-text-input" class="col-md-2 col-form-label">Protocolo:</label>
                        <label for="example-text-input" class="col-md-10 col-form-label">{{ $user->name }}</label>
                    </div>
                @endif
                <div class="row mb-3" id="list-group">

                    <div class="col-12 col-md-12 col-lg-12" id="user-{{ $user->id }}">
                        <div class="card mb-2">
                            <div class="card-header">
                                {{ $user->carnet }} - {{ $user->first_name }} {{ $user->middle_name }}
                                {{ $user->last_name }} {{ $user->second_last_name }}
                                @if ($user->is_leader === 1)
                                    <label style="background-color: #f2f2f2;" class="px-2 rounded">LIDER</label>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-12 col-md-12 col-lg-12 ">
                    Grupo sin usuarios
                </div>
            @endforelse
            @if ($group[0]->status == 0)
                <div class="d-flex justify-content-end">
                    <button type="button" id="accept-group" class="btn btn-primary me-2">Aceptar Grupo</button>
                    <button type="button" id="deny-group" class="btn btn-danger ms-2">Denegar grupo</button>
                </div>
            @endif
        </form>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/pages/fontawesome.init.js') }}"></script>
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/group_edit.js') }}"></script>
@endsection
