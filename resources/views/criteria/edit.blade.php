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
        @if (session('protocol') != null)
            @switch(session('protocol')['id'])
                @case(1)
                @case(2)

                @case(3)
                @case(4)
                    <h1>Editar criterio de evaluación</h1>
                @break

                @case(5)
                    <h1>Editar subárea</h1>
                @break

                @default
            @endswitch
        @endif


        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('criterias.update', $criteria->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $criteria->name }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" required>{{ $criteria->description }}</textarea>
            </div>

            <div class="mb-3">
                <label for="percentage" class="form-label">Porcentaje</label>
                <input type="text" class="form-control" id="percentage" name="percentage"
                    value="{{ $criteria->percentage }}" required>
            </div>

            <input type="text" class="form-control" id="stage" name="stage" value="{{ $stage->id }}" hidden>

            <div class="mb-3">
                @if (session('protocol') != null)
                    @switch(session('protocol')['id'])
                        @case(1)
                        @case(2)
                        @case(3)
                        @case(4)
                            <label for="stage" class="form-label">Etapa Evaluativa</label>
                        @break

                        @case(5)
                            <label for="stage" class="form-label">Área</label>
                        @break

                        @default
                    @endswitch
                @endif

                <select class="form-control" id="stage" name="stage" disabled>
                    <option value="{{ $stage->id }}"> {{ $stage->name }}</option>
                </select>
            </div>

            @if (session('protocol') != null)
                @switch(session('protocol')['id'])
                    @case(5)
                        <div class="mb-3">
                            <label for="type" class="form-label">Tipo</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="-1"> Seleccione un tipo </option>
                                <option value="1" @if ($criteria->type == 1) selected @endif>Con entrega de documentos
                                </option>
                                <option value="0" @if ($criteria->type == 0) selected @endif>Sin entrega de documentos
                                </option>
                            </select>
                        </div>
                    @break

                    @default
                @endswitch
            @endif

            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
