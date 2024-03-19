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
                <a href="{{ route('agreements.create.student', $user->id) }}" class="btn btn-primary mb-3">Registrar acuerdo de
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
                                <form action="{{ route('workshop.destroy', $agreement->id) }}" method="POST"
                                    style="display: inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger buttonDelete"><i
                                            class="fas fa-trash-alt"></i></button>
                                </form>
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
@endsection
