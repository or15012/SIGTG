@extends('layouts.master')
@section('title')
    @lang('translation.Group')
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
        <div class="d-flex justify-content-between align-items-center">
            <h1>Detalle de grupo</h1>


            <div class="d-flex justify-content-end align-items-center">
                <div class="contenedor">
                    <a href="{{ route('groups.index') }}" class="btn btn-danger regresar-button" style="margin-left:15px"><i
                            class="fas fa-arrow-left"></i>
                        Regresar</a>
                </div>
            </div>
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


        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="d-flex justify-content-end">
            <a class="btn btn-secondary mx-1" href="{{ route('document.authorization.letter', $id) }}">
                <i class="fa fa-file"></i>&nbsp;&nbsp;Plantilla carta de autorización
            </a>
            @if ($group[0]->authorization_letter)
                <a class=" btn btn-secondary mx-1"
                    href="{{ route('download', ['file' => $group[0]->authorization_letter]) }}"><i
                        class="fa fa-file"></i>&nbsp;&nbsp;Carta adjunta</a>
            @endif
            @if ($group[0]->authorization_letter_higher_members)
                <a class=" btn btn-secondary mx-1"
                    href="{{ route('download', ['file' => $group[0]->authorization_letter_higher_members]) }}"><i
                        class="fa fa-file"></i>&nbsp;&nbsp;Carta grupo mayor a 5 integrantes</a>
            @endif
        </div>
        <form action="{{ route('groups.update', $id) }}" method="POST" id="form-group-confirm">
            @csrf
            @method('PUT')
            <input type="hidden" name="group_id" value="{{ $id }}">
            <input type="hidden" name="decision" value="" id="decision">
            @forelse ($group as $user)
                @if ($loop->first)
                    <div class="row mb-2">
                        <label for="example-text-input" class="col-md-2 col-form-label">Protocolo:</label>
                        <label for="example-text-input" class="col-md-10 col-form-label">{{ $user->name }}</label>
                    </div>
                @endif
                <div class="row mb-3" id="list-group">
                    <div class="col-12 col-md-12 col-lg-12" id="user-{{ $user->id }}">
                        <div class="card mb-2">
                            <div class="card-header">
                                {{ $user->carnet }} - {{ $user->first_name }} {{ $user->middle_name }}
                                {{ $user->last_name }} {{ $user->second_last_name }}
                                @if ($user->is_leader === 1)
                                    <label class="px-2 rounded gray-project">LIDER</label>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 col-md-12 col-lg-12 ">
                    Grupo sin usuarios
                </div>
            @endforelse
            @if ($group[0]->status == 0)
                <div class="d-flex justify-content-end">
                    <button type="button" id="accept-group" class="btn btn-primary me-2">Aceptar Grupo</button>
                    <button type="button" id="deny-group" class="btn btn-danger buttonDelete ms-2">Denegar grupo</button>
                </div>
            @endif
        </form>


        <div class="agreements">
            <div>
                <h3>Acuerdos de grupo</h3>
                <a href="{{ route('agreements.create.group', $id) }}" class="btn btn-primary mb-3">Registrar acuerdo de
                    grupo</a>

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
    <script src="{{ URL::asset('assets/js/pages/fontawesome.init.js') }}"></script>
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/group_edit.js') }}"></script>


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
