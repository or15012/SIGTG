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
        <h1>
            @if (session('protocol') != null)
                @switch(session('protocol')['id'])
                    @case(1)
                    @case(2)

                    @case(3)
                    @case(4)
                        Lista de etapas evaluativas
                    @break

                    @case(5)
                        Lista de áreas temáticas
                    @break

                    @default
                @endswitch
            @endif
        </h1>


        @if (session('protocol') != null)
            @switch(session('protocol')['id'])
                @case(1)
                @case(2)

                @case(3)
                @case(4)
                    <a href="{{ route('stages.create') }}" class="btn btn-primary mb-3">
                        Nueva etapa evaluativa

                    </a>
                @break

                @default
            @endswitch
        @endif
        <div class="float-end d-flex justify-content-end align-items-center">
            @if (session('protocol') != null)
                @switch(session('protocol')['id'])
                    @case(1)
                    @case(2)

                    @case(3)
                    @case(4)
                        <a href="{{ route('stages.download.template') }}" class="btn btn-primary">
                            Descargar plantilla para carga de criterios
                        </a>
                    @break

                    @case(5)
                        {{-- <a href="{{ route('stages.download.template.subareas') }}" class="btn btn-primary">
                            Descargar plantilla para carga de subáreas
                        </a> --}}
                    @break

                    @default
                @endswitch
            @endif

        </div>
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
                    <th>Nombre</th>
                    <th>Ciclo</th>
                    <th>Protocolo</th>
                    <th>Escuela</th>
                    <th style="width: 6%;word-break: break-all;">Porcentaje</th>
                    <th style="width: 6%;word-break: break-all;">
                        @if (session('protocol') != null)
                            @switch(session('protocol')['id'])
                                @case(1)
                                @case(2)

                                @case(3)
                                @case(4)
                                    Orden de etapa
                                @break

                                @case(5)
                                    Orden de área temática
                                @break

                                @default
                            @endswitch
                        @endif
                    </th>
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(1)
                            @case(2)

                            @case(3)
                            @case(4)
                                <th>Entrega de documento</th>
                            @break

                            @default
                        @endswitch
                    @endif
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(4)
                                <th>Curso</th>
                            @break

                            @default
                        @endswitch
                    @endif

                    <th style="width: 15%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stages as $stage)
                    <tr>
                        <td>{{ $stage->name }}</td>
                        <td>{{ $stage->cycle->number }}-{{ $stage->cycle->year }}</td>
                        <td>{{ $stage->protocol->name }}</td>
                        <td>{{ $stage->school->name }}</td>
                        <td>{{ $stage->percentage }}</td>
                        <td>{{ $stage->sort }}</td>
                        @if (session('protocol') != null)
                            @switch(session('protocol')['id'])
                                @case(1)
                                @case(2)

                                @case(3)
                                @case(4)
                                    <td>{{ $stage->type == 0 ? 'No' : 'Si' }}</td>
                                @break

                                @default
                            @endswitch
                        @endif
                        @if (session('protocol') != null)
                            @switch(session('protocol')['id'])
                                @case(4)
                                    <th>{{ $stage->course->name }}</th>
                                @break

                                @default
                            @endswitch
                        @endif
                        <td>




                            @if (session('protocol') != null)
                                @switch(session('protocol')['id'])
                                    @case(1)
                                    @case(2)

                                    @case(3)
                                    @case(4)
                                        <a href="{{ route('stages.edit', $stage->id) }}" class="btn btn-primary my-1"
                                            title="Editar etapa"><i class="fas fa-pen"></i></a>

                                        <button class="btn btn-danger buttonDelete my-1" title="Eliminar etapa"
                                            onclick="mostrarConfirmacion('{{ route('stages.destroy', $stage->id) }}', '{{ csrf_token() }}')">
                                            <i class="fas fa-trash-alt"></i> </button>

                                        <br>
                                        <a href="{{ route('criterias.index', $stage->id) }}" class="btn btn-primary my-1"
                                            title="Ver criterios"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('criterias.create', $stage->id) }}" class="btn btn-primary my-1"
                                            title="Registar criterio">
                                            <i class="fas fa-file-medical"></i>
                                        </a>
                                        <button class="btn btn-secondary ajax-modal my-1" data-title="Carga de criterios"
                                            title="Cargar criterios"
                                            href="{{ route('stages.modal.load.criterias', ['stage_id' => $stage->id]) }}">
                                            <i class="fas fa-file"></i>
                                        </button>
                                    @break

                                    @case(5)
                                        <a href="{{ route('stages.edit', $stage->id) }}" class="btn btn-primary my-1"
                                            title="Editar área temática"><i class="fas fa-pen"></i></a>

                                        <button class="btn btn-danger buttonDelete my-1" title="Eliminar área temática"
                                            onclick="mostrarConfirmacion('{{ route('stages.destroy', $stage->id) }}', '{{ csrf_token() }}')">
                                            <i class="fas fa-trash-alt"></i> </button>

                                        <br>
                                        {{-- <a href="{{ route('criterias.index', $stage->id) }}" class="btn btn-primary my-1"
                                            title="Ver subáreas"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('criterias.create', $stage->id) }}" class="btn btn-primary my-1"
                                            title="Registrar subarea">
                                            <i class="fas fa-file-medical"></i>
                                        </a>
                                        <button class="btn btn-secondary ajax-modal my-1" data-title="Cargar subáreas"
                                            title="Cargar subáreas" title="Cargar subáreas"
                                            href="{{ route('stages.modal.load.criterias', ['stage_id' => $stage->id]) }}">
                                            <i class="fas fa-file"></i>
                                        </button> --}}
                                        {{-- <br> --}}
                                        <a href="{{ route('stages.coordinator.evaluations.index', $stage->id) }}"
                                            class="btn btn-primary my-1" title="Ver evaluaciones">
                                            <i class="fas fa-file-signature"></i>
                                        </a>
                                        <a href="{{ route('stages.coordinator.evaluations.create', $stage->id) }}"
                                            class="btn btn-primary my-1" title="Registrar evaluación">
                                            <i class="fas fa-folder-plus"></i>
                                        </a>
                                    @break
                                @endswitch
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {!! $stages->withQueryString()->links('pagination::bootstrap-5') !!}
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
