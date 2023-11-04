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
        <form method="POST" class="form-horizontal" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="first_name">Primer nombre</label>
                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name"
                    name="first_name" value="{{ old('first_name') }}" placeholder="Ingrese primer nombre" autofocus>
                @error('first_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="middle_name">Segundo nombre</label>
                <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name"
                    name="middle_name" value="{{ old('middle_name') }}" placeholder="Ingrese segundo nombre" autofocus>
                @error('middle_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="last_name">Primer apellido</label>
                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name"
                    name="last_name" value="{{ old('last_name') }}" placeholder="Ingrese primer apellido" autofocus>
                @error('last_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label class "form-label" for="second_last_name">Secundo apellido</label>
                <input type="text" class="form-control @error('second_last_name') is-invalid @enderror"
                    id="second_last_name" name="second_last_name" value="{{ old('second_last_name') }}"
                    placeholder="Ingrese segundo apellido" autofocus>
                @error('second_last_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="carnet">Carnet (7 digits)</label>
                <input type="text" class="form-control @error('carnet') is-invalid @enderror" id="carnet"
                    name="carnet" value="{{ old('carnet') }}" placeholder="Ingrese Carnet" autofocus>
                @error('carnet')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div>
                <label class="form-label" for="useremail">Correo electrónico</label>
                <div class="input-group">
                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="useremail"
                        value="{{ old('email') }}" name="email" placeholder="Ingresa acá sólo tu nombre de usuario" autofocus>
                    <span class="input-group-text" id="basic-addon2">@ues.edu.sv</span>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="status" class="form-label">Escuela</label>
                    <select class="form-select" name="school" required>
                        <option value="" disabled selected>Seleccione escuela</option>
                        @forelse ($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                        @empty
                            <option value="" disabled>No hay escuelas disponibles</option>
                        @endforelse
                    </select>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-12">
                    <label for="status" class="form-label">Tipo</label>
                    <select class="form-select" name="type" required>
                        <option value="" disabled selected>Seleccione tipo</option>
                        @forelse ($userTypes as  $key => $type)
                            <option value="{{ $key }}">{{ $type }}</option>
                        @empty
                            <option value="" disabled>No hay tipos disponibles</option>
                        @endforelse
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="modality_id" class="form-label">Modalidad</label>
                    <select class="form-select" name="modality_id" required>
                        <option value="" disabled selected>Seleccione la modalidad</option>
                        @forelse ($modalities as  $key => $value)
                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @empty
                            <option value="" disabled>No hay modalidades disponibles</option>
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="status" class="form-label">Protocolo</label>
                    <select class="form-select" name="protocol_id" required>
                        <option value="" disabled selected>Seleccione protocolo</option>
                        @forelse ($protocols as  $key => $protocol)
                            <option value="{{ $protocol->id }}">{{ $protocol->name }}</option>
                        @empty
                            <option value="" disabled>No hay protocolos disponibles</option>
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <div><label class="form-label" for="userpassword">Contraseña</label><span class="float-end btn m-0 p-0 btn-outline-info" id="btnGeneratePassword">Generar contraseña</span></div>
                <div class="input-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="userpassword"
                    name="password" placeholder="Ingrese password" autofocus>
                    <span class="input-group-text">
    <i class="fa fa-eye show_hide_pwd" 
   style="cursor: pointer"></i>
   </span>
                </div>
                    
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="confirmpassword">Confirmar contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                    id="confirmpassword" name="password_confirmation" placeholder="Ingrese confirmación de contraseña" autofocus>
                <span class="input-group-text">
    <i class="fa fa-eye show_hide_pwd" 
   style="cursor: pointer"></i>
   </span>
                </div>
                @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mt-3 text-end">
                <button class="btn btn-primary w-sm waves-effect waves-light" type="submit">Guardar</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#btnGeneratePassword').on('click',function(e){
                let randomstring = Math.random().toString(36).slice(-8);
                $('#userpassword').val(randomstring);
                $('#confirmpassword').val(randomstring);
            });


            $('.show_hide_pwd').on('click', function(e){
                const togglePassword = $(this);
                const password = $(this).parent().parent().find('input');
                const type = password.attr("type") === "password" ? "text" : "password";
                password.attr("type", type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>
@endsection
