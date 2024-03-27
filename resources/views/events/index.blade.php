@extends('layouts.master')
@section('title')
    @lang('translation.Dashboard')
@endsection

@extends('layouts.app')
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
        <h1>Listado de defensas</h1>
        <div id="calendar"></div>
    </div>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#evento">
          Evento
        </button>
        
        <!-- Modal -->
        <div class="modal fade" id="evento" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Defensa</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('events.store', ['project' => $project->id]) }}" id="form" method="POST" enctype="multipart/form-data">

                            <div class="form-group">
                              <label for="title">Nombre del evento:</label>
                              <input type="text" class="form-control" name="title" id="title" aria-describedby="helpId" placeholder="Escribe el nombre del evento">
                            </div>

                            <div class="form-group">
                              <label for="description">Descripción:</label>
                              <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                            </div>

                            <div class="form-group">
                              <label for="start">Fecha de Inicio:</label>
                              <input type="text" class="form-control" name="start" id="start" aria-describedby="helpId" placeholder="">
                            </div>

                            <div class="form-group">
                                <label for="end">Fecha Fin:</label>
                                <input type="text" class="form-control" name="end" id="end" aria-describedby="helpId" placeholder="">
                              </div>
  


                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="contenedor">
                            <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
                            <button type="button" class="btn btn-warning" id="btnModificar">Modificar</button>
                            <button type="button" class="btn btn-secondary" id="btnEliminar">Eliminar</button>
                            <a href="{{ route('events.index', $project->id) }}" class="btn btn-danger regresar-button">Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>

    <!-- CSS de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery (necesario para los plugins de JavaScript de Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <!-- Popper.js (necesario para los plugins de JavaScript de Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>

    <!-- JavaScript de Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection
