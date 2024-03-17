@extends('layouts.master')
@section('title')
    Tipos de acuerdos
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
        <h1>Lista de tipos de acuerdos</h1>
        <a href="{{ route('type_agreements.create') }}" class="btn btn-primary mb-3">Registrar tipo de acuerdo</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="table-danger">
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Afecta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($TypeAgreements as $typeagreement)
                    <tr>
                        <td>{{ $typeagreement->id }}</td>
                        <td>{{ $typeagreement->name }}</td>
                        <td>
                            @switch($typeagreement->affect)
                                @case(1)
                                    {{ 'Estudiante' }}
                                @break

                                @case(2)
                                    {{ 'Grupo' }}
                                @break

                                @case(3)
                                    {{ 'Protocolo' }}
                                @break

                                @case(4)
                                    {{ 'Escuela' }}
                                @break

                                @default
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('type_agreements.edit', $typeagreement->id) }}" class="btn btn-primary"><i
                                    class="fas fa-pen"></i></a>

                            @if ($typeagreement->id !== 1 && $typeagreement->id !== 2 && $typeagreement->id !== 3 && $typeagreement->id !== 4)
                                <button class="btn btn-danger buttonDelete my-1"
                                    onclick="mostrarConfirmacion('{{ route('type_agreements.destroy', $typeagreement->id) }}', '{{ csrf_token() }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {!! $TypeAgreements->withQueryString()->links('pagination::bootstrap-5') !!}

    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })



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
