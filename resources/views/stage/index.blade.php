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
        <h1>Lista de Etapas Evaluativas</h1>
        <a href="{{ route('stages.create') }}" class="btn btn-primary mb-3">Agregar Etapa Evaluativa</a>

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
                    <th>Ciclo</th>
                    <th>Protocolo</th>
                    <th>Escuela</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stages as $stage)
                    <tr>
                        <td>{{ $stage->id }}</td>
                        <td>{{ $stage->name }}</td>
                        <td>{{$stage->cycle->number}}-{{$stage->cycle->year}}</td>
                        <td>{{$stage->protocol->name}}</td>
                        <td>{{$stage->school->name}}</td>
                        <td>
                            <a href="{{ route('stages.edit', $stage->id) }}" class="btn btn-warning">Editar</a>
                            <button class="btn btn-danger" onclick="mostrarConfirmacion('{{ route('stages.destroy', $stage->id) }}', '{{csrf_token()}}')">Eliminar</button>
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