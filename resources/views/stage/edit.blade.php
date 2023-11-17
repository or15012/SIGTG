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
        <h1>Editar Etapa Evaluativa</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('stages.update', $stage->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nombre de la Etapa Evaluativa</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $stage->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="cycle" class="form-label">Ciclo</label>
                <select class="form-control" name="cycle" id="cycle">
                    @foreach($cycles as $cycle)
                        @if($stage->cycle_id==$cycle->id)
                        <option value="{{$cycle->id}}" selected> 
                            {{$cycle->number}}-{{$cycle->year}} --actual--
                        </option>
                        @else
                        <option value="{{$cycle->id}}">
                            {{$cycle->number}}-{{$cycle->year}}
                        </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="protocol" class="form-label">Protocolo</label>
                <select class="form-control" name="protocol" id="protocol">
                    @foreach($protocols as $protocol)
                        @if($stage->protocol_id==$protocol->id)
                        <option value="{{$protocol->id}}" selected> 
                            {{$protocol->name}} --actual--
                        </option>
                        @else
                        <option value="{{$protocol->id}}">
                            {{$protocol->name}}
                        </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="school" class="form-label">Escuela</label>
                <select class="form-control" name="school" id="school">
                    @foreach($schools as $school)
                        @if($stage->school_id==$school->id)
                        <option value="{{$school->id}}" selected> 
                            {{$school->name}} --actual--
                        </option>
                        @else
                        <option value="{{$school->id}}">
                            {{$school->name}}
                        </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection