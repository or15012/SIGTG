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
                <a href="{{ route('agreements.create.group',$id) }}" class="btn btn-primary mb-3">Registrar acuerdo de grupo</a>

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
    <script src="{{ URL::asset('assets/js/pages/fontawesome.init.js') }}"></script>
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="{{ URL::asset('js/group_edit.js') }}"></script>
@endsection
