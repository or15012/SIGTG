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
        <div class="contenedor">
            <a href="{{ route('courses.index') }}" class="btn btn-danger regresar-button"><i class="fas fa-arrow-left"></i>
                Regresar</a>
        </div>
        <h1>Detalles del curso</h1>
        <p><strong>Nombre:</strong> {{ $course->name }}</p>
        <p><strong>Descripción:</strong> {{ $course->description }}</p>
        <p><strong>Ciclo:</strong> {{ $course->cycle->number }} / {{ $course->cycle->year }}</p>
        <p><strong>Escuela:</strong> {{ $course->school->name }}</p>



<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="presuscriptions-tab" data-bs-toggle="tab" data-bs-target="#presuscriptions-tab-pane" type="button" role="tab" aria-controls="presuscriptions-tab-pane" aria-selected="true">Pre-inscripciones <small>({{count($course->preregistrations)}})</small></button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="suscriptions-tab" data-bs-toggle="tab" data-bs-target="#suscriptions-tab-pane" type="button" role="tab" aria-controls="suscriptions-tab-pane" aria-selected="false">Inscripciones <small>({{count($course->registrations)}})</small></button>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="presuscriptions-tab-pane" role="tabpanel" aria-labelledby="presuscriptions-tab" tabindex="0">
    <div class="mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Carnet</th>
                    <th>Correo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($course->preregistrations as $preregistration)
                    <tr>
                        <td>{{ $preregistration->full_name() }}</td>
                        <td>{{ $preregistration->carnet }}</td>
                        <td>{{ $preregistration->email }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center"><b>¡Aún no hay pre-inscripciones!</b></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
  </div>
  <div class="tab-pane fade" id="suscriptions-tab-pane" role="tabpanel" aria-labelledby="suscriptions-tab" tabindex="0">
    <div class="mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Carnet</th>
                    <th>Correo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($course->registrations as $registration)
                    <tr>
                        <td>{{ $registration->full_name() }}</td>
                        <td>{{ $registration->carnet }}</td>
                        <td>{{ $registration->email }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center"><b>¡Aún no hay inscripciones!</b></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
  </div>
</div>




        




    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
