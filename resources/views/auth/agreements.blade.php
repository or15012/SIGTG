@extends('layouts.master')

@section('title')
    @lang('translation.ShowPerfil')
@endsection

@section('content')
    <div class="container">

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


        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="contenedor">
            <a href="{{ route('users.index') }}" class="btn btn-danger regresar-button">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>
        <div class="agreements">
            <div>
                <h3>Acuerdos de estudiante</h3>
                <a href="{{ route('agreements.create.student', $user->id) }}" class="btn btn-primary mb-3">Registrar acuerdo
                    de
                    estudiante</a>

            </div>
            <table class="table table-bordered">
                <thead>
                    <tr class="table-danger">
                        <th>Nombre de acuerdo</th>
                        <th>Número de acuerdo</th>
                        <th>Descripción</th>
                        <th>Fecha de aprobación</th>
                        <th>Fecha de subida</th>
                        <th>Registrado por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($agreements as $agreement)
                        <tr>
                            <td>{{ $agreement->name }}</td>
                            <td>{{ $agreement->number }}</td>
                            <td>{{ $agreement->description }}</td>
                            <td>{{ \Carbon\Carbon::parse($agreement->approval_date)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($agreement->created_at)->format('d-m-Y') }}</td>
                            <td>{{ $agreement->first_name }} {{ $agreement->last_name }}</td>
                            <td>
                                <button class="btn btn-danger buttonDelete"
                                    onclick="mostrarConfirmacion('{{ route('agreements.destroy', $agreement->id) }}', '{{ csrf_token() }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>

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
