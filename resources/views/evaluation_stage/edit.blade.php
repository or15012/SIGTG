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

    <div class="container">
        <h1>Registro de Notas</h1>
        
      
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre de alumno</th>
                @foreach ($criterios as $criterio) 
                    <th>
                        {{$criterio->name}}
                       <br>
                        {{$criterio->percentage}}%
                    </th>
                @endforeach
                <td>Acciones</td>
                </tr>
            </thead>
            <tbody>
                <form action="{{ route('grades.save')}}" method="post" >
                    @csrf
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user['user']['first_name'] }}</td>
                            @foreach ($criterios as $criterio)
                            <td>
                                <input type="number" name="notas[{{$user['user']['id']}}][{{$criterio->id}}]" 
                                    value="{{ isset($notas[$user['user']['id']][$criterio->id]) ? $notas[$user['user']['id']][$criterio->id] : '' }}" >
                            </td>
                            @endforeach
                            <td>
                            </td>
                        </tr>
                    @endforeach

                    <button type="submit" >GUARDAR</button>
                
                </form>
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection