<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8" />
        <title> @yield('title') | SIGTG - FIA</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">
        <style>
            .text-center{
                text-align: center;
            }
            .fw-bolder{
                font-weight: bold;
            }
            .py-2{
                padding-top: 10px;
                padding-bottom: 10px;
            }
            *{
                font-family: Arial;
            }
        </style>
    </head>

    <body style="padding: 0px; margin: 0;">
        <div style="width: 100%; margin-top: 20px; padding: 0;">
            <div class="text-center">
                <a href=""><img style="width: auto; height: 120px;" class="" src="{{ asset('images/logo_transparent.png') }}" alt=""></a>
            </div>

            @yield('content')
            

        <div class="mt-10 text-center py-2" style="background-color: #dadada; margin-top: 20px;">
            <small class="text-muted">Correo generado automáticamente. <br>Universidad de El Salvador <br> Copyright &copy;
                2023</small>
            <p class="text-muted" style="font-size: 15px;">No responder este correo.</p>
        </div>
        </div>
    </body>
</html>
