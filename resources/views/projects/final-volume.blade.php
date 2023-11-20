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
        <h1>Agregar tomo final</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('projects.final.volume.store', $project->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="summary" class="form-label">Resumen</label>
                <textarea type="text" class="form-control" id="summary" name="summary" value="{{ old('summary') }}" rows="4"
                    cols="50">{{ $project->summary }}</textarea>
            </div>

            <div class="mb-3">
                <label for="path_final_volume" class="form-label">Tomo final</label>
                <input type="file" id="path_final_volume" class="form-control" name="path_final_volume">
            </div>


            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
