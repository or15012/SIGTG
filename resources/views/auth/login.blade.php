@extends('layouts.master-without_nav')

@section('title')
    @lang('translation.Login')
@endsection

@section('content')
    <div class="navbar-header d-flex justify-content-end mr-5">

        {{-- <a href="{{ route('register') }}" class="text-dark" style="margin-right: 15px;">
            <i class="fas fa-user-plus"></i> Registrarse
        </a> --}}
        <a href="{{ route('login') }}" class="text-dark" style="margin-right: -40px;">
            <i class="fas fa-sign-in-alt"></i> Ingresar
        </a>

        <div class="position-absolute top-2 start-0 m-3">
            <a href="{{ route('register') }}" class="text-dark" style="margin-left: 60px;">
                <i class="fas fa-house-user"></i> Inicio
            </a>
        </div>
    </div>


    </div>
    <!--<div class="authentication-bg min-vh-100"> -->
    <div class="min-vh-100" style="background-color: #ffffff">

        <div class="container" style="background-color: #ffffff;">
            <div class="d-flex flex-column min-vh-100 px-3 pt-4">
                <div class="row justify-content-center my-auto">
                    <div class="col-md-8 col-lg-6 col-xl-5">

                        <div class="text-center mb-4">
                            <a href="index">
                                <img src="" alt="" height="22">
                                <strong><span class="text-dark "style="font-size: 30px;">SIGTG-FIA</span></strong>
                            </a>
                        </div>

                        <div class="card" style="background-color: #F2F2F2;">
                            <div class="card-body p-4">
                                <div class="text-center p-3 mt-2">
                                    <h1 class="ingresar text-muted"><i class="fas fa-user"></i> Ingreso</h1>
                                </div>
                                <div class="p-2 mt-4">

                                    @if (Session::has('success'))
                                        <div class="alert alert-success text-center">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif
                                    @if (Session::has('errors'))
                                        <div class="alert alert-danger text-center">
                                            <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{$error}}</li>
                                            @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <div class="mt-3 mb-3">
                                            <label class="form-label" for="username">Usuario</label>
                                            <input name="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" id="username"
                                                value="{{ old('email', 'admin@ues.edu.sv') }}" placeholder="Enter Email"
                                                autocomplete="email" autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <div class="float-end">
                                                @if (Route::has('password.request'))
                                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                                        {{ __('Forgot Password?') }}
                                                    </a>
                                                @endif
                                            </div>
                                            <label class="form-label" for="userpassword">Clave</label>
                                            <input type="password" name="password"
                                                class="form-control  @error('password') is-invalid @enderror"
                                                id="userpassword" value="123456" placeholder="Enter password"
                                                aria-label="Password" aria-describedby="password-addon">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember"> Recuerdame </label>
                                        </div>

                                        <div class="mt-3 text-end">
                                            <button class= "ingresar btn btn-primary w-sm waves-effect waves-light"
                                                type="submit">Ingresar</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>

                    </div><!-- end col -->
                </div><!-- end row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center text-muted p-4">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> SIGTG-FIA. </p>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- end container -->
    </div>
@endsection
