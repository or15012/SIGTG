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
        @endslot
    @endcomponent
    <div class="container">
        
        <h1>Lista de Propuestas</h1>
        <a href="{{ route('proposals.create') }}" class="btn btn-primary mb-3">Nueva propuesta</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="table-danger">
                    <th>Nombre</th>
                    <th style="width: 40%">Descripcion</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proposals as $proposal)
                <tr>
                    <td>{{$proposal->name}}</td>
                    <td>{{$proposal->description}}</td>
                    <td>{{$proposal->status()}}</td>
                    <td>
                        <a href="{{ route('proposals.show', $proposal->id) }}" class="btn btn-primary"><i
                            class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('proposals.destroy', $proposal->id) }}" method="POST"
                            style="display: inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger buttonDelete"><i
                                    class="fas fa-trash-alt"></i></button>
                        </form> 
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
     <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
