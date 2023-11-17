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
        <h1>Actualizar grupo</h1>
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
                                    <label class="bg-primary text-white px-2 rounded">Lider</label>
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
            <div class="d-flex justify-content-end" >
                <button type="button" id="accept-group" class="btn btn-primary me-2">Aceptar Grupo</button>
                <button type="button" id="deny-group" class="btn btn-danger ms-2">Denegar grupo</button>
            </div>

        </form>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/pages/fontawesome.init.js') }}"></script>
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/group_edit.js') }}"></script>
@endsection
