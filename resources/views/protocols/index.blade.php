@extends('layouts.master')
@section('title')
    @lang('translation.Dashboard')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            SIGTG-FIA
        @endslot
        @slot('title')
            Welcome !
        @endslot
    @endcomponent
    <div class="container">
        <h1>Lista de protocolos</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($protocols as $protocol)
                    <tr>
                        <td>{{ $protocol->id }}</td>
                        <td>{{ $protocol->name }}</td>
                        <td>
                            <a href="{{ route('protocols.show', $protocol->id) }}" class="btn btn-primary"><i
                                class="fas fa-eye"></i></a>
                            <a href="{{ route('protocols.edit', $protocol->id) }}" class="btn btn-primary"><i
                                class="fas fa-pen"></i></a>
                            <form action="{{ route('protocols.destroy', $protocol->id) }}" method="POST"
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
