@extends('layouts.master')
@section('title')
    @lang('translation.Stages')
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
        <h1>Lista de fases</h1>
        <a href="{{ route('phases.create') }}" class="btn btn-primary mb-3">Nueva fase</a>

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

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="table-danger">
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Ciclo</th>
                    <th>Escuela</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($phases as $phase)
                    <tr>
                        <td>{{ $stage->id }}</td>
                        <td>{{ $stage->name }}</td>
                        <td>{{ $stage->cycle->number }}-{{ $stage->cycle->year }}</td>
                        <td>{{ $stage->school->name }}</td>
                        <td>
                            {{-- <a href="{{ route('criterias.index', $stage->id) }}" class="btn btn-primary my-1"><i
                                    class="fas fa-eye"></i></a> --}}
                            <a href="{{ route('phases.edit', $stage->id) }}" class="btn btn-primary my-1"><i
                                    class="fas fa-pen"></i></a>

                            <button class="btn btn-danger buttonDelete my-1"
                                onclick="mostrarConfirmacion('{{ route('stages.destroy', $stage->id) }}', '{{ csrf_token() }}')">
                                <i class="fas fa-trash-alt"></i> </button>

                            {{-- <a href="{{ route('criterias.create', $stage->id) }}" class="btn btn-primary my-1"><i
                                    class="fas fa-file-medical"></i></a>

                            <button class="btn btn-secondary ajax-modal my-1" data-title="Carga de criterios" title="Cargar criterios"
                                href="{{ route('stages.modal.load.criterias', ['stage_id' => $stage->id]) }}">
                                <i class="fas fa-file"></i> --}}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>

    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>


    <script>
        function mostrarConfirmacion(url, token) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, realiza una petición POST para eliminar el elemento
                    eliminarElemento(url, token);
                }
            });
        }

        function eliminarElemento(url, token) {
            var form = document.createElement("form");
            form.setAttribute("method", "POST");
            form.setAttribute("action", url);
            form.style.display = "none";

            var csrfTokenInput = document.createElement("input");
            csrfTokenInput.setAttribute("type", "hidden");
            csrfTokenInput.setAttribute("name", "_token");
            csrfTokenInput.setAttribute("value", token);

            var methodField = document.createElement("input");
            methodField.setAttribute("type", "hidden");
            methodField.setAttribute("name", "_method");
            methodField.setAttribute("value", "DELETE");

            form.appendChild(csrfTokenInput);
            form.appendChild(methodField);

            document.body.appendChild(form);

            form.submit();
        }
    </script>
@endsection
