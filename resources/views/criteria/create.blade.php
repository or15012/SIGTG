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
        @if (session('protocol') !== null)
            @switch(session('protocol')['id'])
                @case(1)
                    <h1>Registrar criterio de evaluación</h1>
                @break

                @case(5)
                    <h1>Registrar subáreas</h1>
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

        <div class="m-4">

            @if (session('protocol') !== null)
                @switch(session('protocol')['id'])
                    @case(1)
                        <p>
                            Etapa evaluativa:
                            {{ $stage->name }}
                            <br>
                            Porcentaje utilizado: {{ $sumatory }}%
                            <br>
                            Porcentaje máximo: 100%
                        </p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                                style="width: {{ $sumatory }}%" aria-valuenow="{{ $sumatory }}" aria-valuemin="0"
                                aria-valuemax="100">

                            </div>
                        </div>
                    @break

                    @case(5)
                        <p>Área:  {{ $stage->name }}</p>
                    @break

                    @default
                @endswitch
            @endif

        </div>



        <form action="{{ route('criterias.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" required>{{ old('description') }}</textarea>
            </div>
            @if (session('protocol') != null)
                @switch(session('protocol')['id'])
                    @case(1)
                    @case(2)

                    @case(3)
                    @case(4)
                        <div class="mb-3">
                            <label for="percentage" class="form-label">Porcentaje</label>
                            <input type="text" class="form-control" id="percentage" name="percentage"
                                value="{{ old('percentage') }}" required>
                        </div>
                    @break

                    @default
                @endswitch
            @endif



            <input type="text" class="form-control" id="stage" name="stage" value="{{ $stage->id }}" hidden>

            <div class="mb-3">
                <label for="stage" class="form-label">
                    @if (session('protocol') !== null)
                        @switch(session('protocol')['id'])
                            @case(1)
                                Etapa evaluativa:
                            @break

                            @case(5)
                                Área:
                            @break

                            @default
                        @endswitch
                    @endif
                </label>
                <select class="form-control" id="stage" name="stage" disabled>
                    <option value="{{ $stage->id }}"> {{ $stage->name }}</option>
                </select>
            </div>


            @if (session('protocol') != null)
                @switch(session('protocol')['id'])
                    @case(5)
                        {{-- <div class="mb-3">
                            <label for="type" class="form-label">Tipo</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="-1"> Seleccione un tipo </option>
                                <option value="1">Con entrega de documentos</option>
                                <option value="0">Sin entrega de documentos</option>
                            </select>
                        </div> --}}
                    @break

                    @default
                @endswitch
            @endif


            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
