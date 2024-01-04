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
            Bienvenido
        @endslot
    @endcomponent
    <div class="container">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="">
            Bienvenido
             {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
        </div>
        @if(Auth::user()->type == 1 && $showLinkCourses)
            <div>
                <h4>¿Desea preinscribir para curso de especialización? <a href="{{route('courses.preregistrations.create')}}">¡De click aqui!</a></h4>
            </div>
        @endif
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
