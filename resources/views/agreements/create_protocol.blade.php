@extends('layouts.master')
@section('title')
    Acuerdo de grupo
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
        <div class="contenedor">
            {{-- <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-danger regresar-button"><i
                    class="fas fa-arrow-left"></i>
                Regresar</a> --}}
        </div>
        <h1>Registrar acuerdo de protocolo</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('agreements.store.protocol') }}" method="POST">
            @csrf
            @include('layouts.agreement_include')
            {{-- <input type="hidden" name="group_id" value="{{ $group->id }}"> --}}
            <div class="mb-3">
                <label for="stage" class="form-label">
                    Tipo de acuerdo:
                </label>

                <select class="form-control" id="type" name="type" required>
                    <option value=""> Seleccione un tipo de acuerdo</option>

                    @forelse ($agreementTypes as $type)
                    <option value="{{ $type->id }}"> {{ $type->name }}</option>
                    @empty

                    @endforelse

                </select>
            </div>
            <div class="contenedor">
                {{-- <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-danger regresar-button">Cancelar</a> --}}
                <button type="submit" class="btn btn-primary ">Guardar</button>
                <div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
