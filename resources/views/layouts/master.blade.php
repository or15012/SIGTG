<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8" />
        <title> @yield('title') | SIGTG - FIA</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">
        @include('layouts.head-css')
    </head>

<body data-layout="vertical" data-sidebar="dark">
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        @include('layouts.horizontal')

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>
                <div class="loading">Loading&#8230;</div>
                @include('layouts.footer')
            </div>
        </div>
        <!-- Main Modal -->
    <div id="main_modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        {{-- <span aria-hidden="true">&times;</span> --}}
                    </button>
                </div>

                <div class="alert alert-danger d-none m-3"></div>
                <div class="alert alert-primary d-none m-3"></div>
                <div class="modal-body "></div>

            </div>
        </div>
    </div>
        @include('layouts.right-sidebar')
        @include('layouts.vendor-scripts')
        <script src="{{asset('js/scripts.js')}}"></script>
    </body>
</html>
